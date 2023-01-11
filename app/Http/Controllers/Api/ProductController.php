<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiBaseController as BaseController;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Validator;
use DB;
use App\Http\Resources\ProductResource;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::where('shop_id', auth()->user()->shop_id);
        if ($request->has('is_stock')) {
            $products = $products->where('is_stock', $request->get('is_stock'));
        }
        if($request->has('price') && $request->get('price') == 'asc'){
            $products = $products->orderBy('price', 'ASC');
        }
        if ($request->has('price') && $request->get('price') == 'desc') {
            $products = $products->orderBy('price', 'DESC');
        }

        $products = $products->get();

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|unique:product,name,' . $request->route('app_product') . ',id,deleted_at,NULL,shop_id,' . $request->get('shop_id'),
            'shop_id' => 'required',
            'price' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ],[
            'shop_id.required' => 'The shop name field is required.'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $fileName = time() . '.' . $request->file('image')->extension();
                $request->file('image')->move(public_path('uploads'), $fileName);
                $input['image'] = $fileName;
            }

            $product = Product::create($input);
            DB::commit();
            return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
        } catch (Exception $exp) {
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        $input = $request->all();
        $product = Product::find($product);
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        $validator = Validator::make($input, [
            'name' => 'required|unique:product,name,' . $request->route('app_product') . ',id,deleted_at,NULL,shop_id,' . $request->get('shop_id'),
            'shop_id' => 'required',
            'price' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:2048'
        ], [
            'shop_id.required' => 'The shop name field is required.'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->hasFile('image')) {
            $fileName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads'), $fileName);
            $input['image'] = $fileName;
        }

        $update = Product::find($product)->update($input);
        $detail = [];
        if($update){
            $detail = Product::find($product);
        }

        return $this->sendResponse(new ProductResource($detail), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product)
    {
        $product = Product::find($product);
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}