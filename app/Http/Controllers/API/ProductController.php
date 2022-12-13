<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'amount' => 'required',
        ]);

        return Product::create([
            'name' => $request->name,
            'title' => $request->title,
            'slug' => Str::slug($request->name),
            'img' => $request->img,
            'description' => $request->description,
            'amount' => $request->amount,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'amount' => 'required',
        ]);

        $product = Product::find($id);
        $product->update($request->all());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

    // API Specific Search or Filter
    public function search(Request $request)
    {
        $productQuery = Product::query();

        if (!empty($request->name)){
            $productQuery->where('name', 'like', '%'.$request->name.'%');
        };
        if (!empty($request->title)){
            $productQuery->where('title', 'like', '%'.$request->title.'%');
        };

        $productQuery->orderBy('id', 'desc');
        $product = $productQuery->get();

        return $product;
    }
}
