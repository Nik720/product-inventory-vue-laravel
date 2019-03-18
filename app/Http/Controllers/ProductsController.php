<?php

namespace App\Http\Controllers;

use App\Products;
use Illuminate\Http\Request;
use Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::paginate(5);
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'productName' => 'bail|required|max:50',
            'description' => 'bail|required',
            'stock' => 'bail|required|numeric',
            'price' => 'bail|required|numeric',
            'sku' => 'bail|required',
        ];
        $validatedData =  Validator::make($request->all(), $rules);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validatedData->messages()
            ]);
        }

        try {
            $name = "";
            if($request->hasFile('image')){
                $name = time()."_".$request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path('images'), $name);
            }
            $product = Products::create([
                'name' => $request->productName,
                'description' => $request->description,
                'stock' => $request->stock,
                'price' => $request->price,
                'image' => $name,
                'sku' => $request->sku,
                'new_arrival' => $request->new_arrival,
            ]);

            return response()->json([
                'status' => (bool) $product,
                'data'   => $product,
                'message' => $product ? 'Product Created!' : 'Error Creating Product'
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        try {
            if ($products) {
                return response()->json(['status' => 'success', 'data' => $products]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Requested Guest data not found.']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products)
    {
        $rules = [
            'productName' => 'bail|required|max:50',
            'description' => 'bail|required',
            'stock' => 'bail|required|numeric',
            'price' => 'bail|required|numeric',
            'sku' => 'bail|required'
        ];
        $validatedData =  Validator::make($request->all(), $rules);
        if ($validatedData->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validatedData->messages()
            ]);
        }

        try {

            if($request->hasFile('image')){
                $name = time()."_".$request->file('image')->getClientOriginalName();
                $request->file('image')->move(public_path('images'), $name);
                $products->image = $name;
            }
            $products->name = $request->productName;
            $products->description = $request->description;
            $products->stock = $request->stock;
            $products->price = $request->price;
            $products->sku = $request->sku;
            $products->new_arrival = $request->new_arrival;
            $products->save();
            if ($products) {
                return response()->json(['status' => true]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Products  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        $status = $products->delete();
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Product Deleted!' : 'Error Deleting Product'
        ]);
    }
}
