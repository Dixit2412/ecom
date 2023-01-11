<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\DataTables\ProductDataTable;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Models\Product;
use App\Models\Shop;
use DB;
use Illuminate\Support\Facades\Hash;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Middleware
        $this->middleware('auth');
        view()->share('title', 'Product');
        view()->share('module_title', 'Product');
        view()->share('shop', Shop::pluck('name', 'id')->toArray());
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ProductDataTable $dataTable)
    {
        $action_nav = [
            'add_new' => ['title' => 'Add Product', 'url' => route('product.create'), 'attributes' => ['class' => 'btn btn-sm btn-primary heading-btn', 'title' => 'Add New']],
            'export' => ['title' => 'Excel Export', 'url' => route('product.export'), 'attributes' => ['class' => 'btn btn-sm btn-warning heading-btn', 'title' => 'Export CSV']],
            'import' => ['title' => 'Excel Import', 'url' => route('product.import'), 'attributes' => ['class' => 'btn btn-sm btn-success heading-btn', 'title' => 'Import CSV']]
        ];
        view()->share('module_title', 'All Product');
        view()->share('action_data', $action_nav);
        return $dataTable->render('product.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        view()->share('module_action',array('back' => [
            'title' => 'Back', 'url' => route('product.index'),'attributes' => ['class' => 'btn btn-block btn-warning btn-sm', 'title' => 'Back']]
        ));

        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ProductRequest $request)
    {
        $input = $request->except(['_token', 'save', 'save_exit']);
        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $fileName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->move(public_path('uploads'), $fileName);
                $input['image'] = $fileName;
            }

            Product::create($input)->getKey();
            DB::commit();
            session()->flash('success', 'New Product Created.');
            if ($request->get('save_exit')) {
                return redirect()->route('product.index');
            }
            return redirect()->route('product.create');
        } catch (Exception $exp) {
            DB::rollback();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return '';
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Product $product)
    {
        view()->share('title', 'Edit Product');
        view()->share('module_action', array('back' => [
            'title' => 'Back', 'url' => route('product.index'), 'attributes' => ['class' => 'btn btn-block btn-warning btn-sm', 'title' => 'Back']
        ]));
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ProductRequest $request, $product)
    {
        $input = $request->except(['_method','_token']);
        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $fileName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->move(public_path('uploads'), $fileName);
                $input['image'] = $fileName;
            }
            $update = Product::find($product)->update($input);
            if ($update) {
                DB::commit();
                session()->flash('success', $input['name'] . ' Record update.');
                if ($request->get('update_exit')) {
                    return redirect()->route('product.index');
                }
            } else {
                session()->flash('error', $input['name'] . ' Record updating error.');
                redirect()->route('product.edit', $product);
            }
        } catch (Exception $exp) {
            DB::rollback();
        }
        return redirect()->route('product.edit', $product);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Product $product)
    {
        if ($product) {
            $dependency = $product->deleteValidate(array($product->id));
            if (!$dependency) {
                $product->delete();
                session()->flash('success', 'Product deleted!');
            } else {
                session()->flash('error', 'This Product is used in '. implode(",", $dependency));
                return redirect('product');
            }
        }
        return redirect('product');
    }

    /**
     * function used for export product data in excel
     * @return void
     * @date 10-01-2023
     * @author Dixit
     */
    public function getExcelData()
    {
        $fileName = 'product_'.date('dmYHis').'.csv';
        $products = Product::with(['shop'])->get();
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Shop', 'Name', 'Price', 'Is Stock'];
        $handle = fopen('php://output', 'w');
        $callback = function () use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $p_detail) {
                fputcsv($file, [
                    !empty($p_detail->shop->name) ? $p_detail->shop->name : null,
                    $p_detail->name,
                    $p_detail->price,
                    $p_detail->is_stock
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function ImportView()
    {
        view()->share('module_title', 'Import Product Detail');
        view()->share('module_action', array(
            'back' => [
                'title' => 'Back', 'url' => route('product.index'), 'attributes' => ['class' => 'btn btn-block btn-warning btn-sm', 'title' => 'Back']
            ]
        ));
        return view('product.import_create');
    }

    public function ImportStore(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
        if ($request->hasFile('file')) {
            $fileName = time() . '.' . $request->file('file')->extension();
            $request->file('file')->move(public_path('uploads/product'), $fileName);
            $input['file'] = $fileName;
        }
        $filename = public_path('uploads/product/' . $input['file']);
        $fileD = fopen($filename, 'r');
        $column = fgetcsv($fileD);
        // dump($column);exit;
        while (!feof($fileD)) {
            $data = $final = [];
            $data = fgetcsv($fileD);
            foreach ($column as $col_key => $col_value) {
                if (is_array($data)) {
                    if (array_key_exists($col_key, $data)) {
                        $final[str_replace(' ', '_', strtolower($col_value))] = $data[$col_key];
                    }
                }
            }
            $rowData[] = $final;
        }
        // dump($rowData); exit();
        foreach ($rowData as $key => $value) {
            if (!empty($value)) {
                $name = null;
                $name = trim($value['shop_name']);
                $find_shop = Shop::where('name', $name)->first();
                if (!is_null($find_shop) && isset($find_shop->id)) {
                    $value['shop_id'] = $find_shop->id;
                }
                Product::create($value);
            }
        }
        session()->flash('success', 'Product Import Successfully.');
        return redirect()->back();
    }
}