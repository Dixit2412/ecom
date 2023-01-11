<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShopRequest;
use App\DataTables\ShopDataTable;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Models\Country;
use App\Models\Shop;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;

class ShopController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Middleware
        $this->middleware('auth');
        view()->share('title', 'Shop');
        view()->share('module_title', 'Shop');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(ShopDataTable $dataTable)
    {
        $action_nav = [
            'add_new' => ['title' => '<b><i class="fas fa-plus-square"></i></b> Add Shop', 'url' => route('shop.create'), 'attributes' => ['class' => 'btn btn-sm btn-primary heading-btn btn-add', 'title' => 'Add New']]
        ];
        view()->share('module_title', 'All Shop');
        view()->share('action_data', $action_nav);
        return $dataTable->render('shop.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        view()->share('module_action',array('back' => [
            'title' => '<b><i class="fas fa-arrow-alt-circle-left mr-1"></i></b> Back', 'url' => route('shop.index'),'attributes' => ['class' => 'btn btn-block btn-warning btn-sm', 'title' => 'Back']
        ]));

        return view('shop.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ShopRequest $request)
    {
        $input = $request->except(['_token', 'password', 'save', 'save_exit']);
        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $fileName = time() . '.' . $request->file->extension();

                $request->file->move(public_path('uploads'), $fileName);
                $input['image'] = $fileName;
            }

            $model = Shop::create($input)->getKey();
            if($model){
                User::create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make($request->get('password')),
                    'type' => 'shop',
                    'shop_id' => $model
                ]);
            }
            DB::commit();
            session()->flash('success', 'New Shop Created.');
            if ($request->get('save_exit')) {
                return redirect()->route('shop.index');
            }
            return redirect()->route('shop.create');
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
    public function edit(Shop $shop)
    {
        view()->share('title', 'Edit Shop');
        view()->share('module_action', array('back' => [
            'title' => '<b><i class="fas fa-arrow-alt-circle-left mr-1"></i></b> Back', 'url' => route('shop.index'), 'attributes' => ['class' => 'btn btn-block btn-warning btn-sm', 'title' => 'Back']
        ]));
        return view('shop.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ShopRequest $request, $shop)
    {
        $input = $request->except(['_method','_token']);
        DB::beginTransaction();
        try {
            $update = Shop::find($shop)->update($input);
            if ($update) {
                DB::commit();
                session()->flash('success', $input['name'] . ' Record update.');
                if ($request->get('update_exit')) {
                    return redirect()->route('shop.index');
                }
            } else {
                session()->flash('error', $input['name'] . ' Record updating error.');
                redirect()->route('shop.edit', $shop);
            }
        } catch (Exception $exp) {
            DB::rollback();
        }
        return redirect()->route('shop.edit', $shop);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Shop $shop)
    {
        if ($shop) {
            $dependency = $shop->deleteValidate(array($shop->id));
            if (!$dependency) {
                User::where('shop_id', $shop->id)->delete();
                $shop->delete();
                session()->flash('success', 'Shop deleted!');
            } else {
                session()->flash('error', 'This Shop is used in '. implode(",", $dependency));
                return redirect('shop');
            }
        }
        return redirect('shop');
    }
}