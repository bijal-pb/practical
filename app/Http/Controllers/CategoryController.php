<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\str;
use Illuminate\support\validator;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->paginate(5);
      
        return view('categories.index',compact('categories'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
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
            'image' => 'required',
        ]);
      
        $input = $request->all();

        if($image = $request->file('image')){
            $destinationPath = $request->file('image')->store('image', 'public');
            $catImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath,$catImage);
            $input['image'] = $catImage;
        }
        Category::create($request->all());
       
        return redirect()->route('categories.index')
                        ->with('success','Category created successfully.');
    }
  
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit',compact('category'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable',
        ]);
      
        $input = $request->all();

        if($image = $request->file('image')){
            $destinationPath = 'image/';
            $catImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath,$catImage);
            $input['image'] = $catImage;
        }else{
            unset($input['image']);
        }
        $category->update($request->all());
      
        return redirect()->route('categories.index')
                        ->with('success','Category updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
       
        return redirect()->route('categories.index')
                        ->with('success','category deleted successfully');
    }
}
