<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function category_products($category_id){

        $category = Category::find($category_id);
        $products = Product::where('category_id',$category_id)->paginate(12);
        $categories = Category::all();

        $categoryName = is_null($category) ? "None" : $category->category_name;

//        $productsCount = Product::where('category_id',$category_id)->count();

        return view('category_products')->with([
            'products' => $products,
            'productsCount' => $products->count(),
            'categories' => $categories,
            'categoryName' => $categoryName
        ]);
    }
    public function all_products(){

        $products = Product::orderBy('id','desc')->paginate(12);
        $featuredProducts = Product::where('is_featured',true)->get();
        $categories = Category::all();


//        $productsCount = Product::where('category_id',$category_id)->count();

        return view('all_products')->with([
            'products' => $products,
            'productsCount' => $products->count(),
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }

    public function categories(){

        $categories = Category::orderBy('id','desc')->get();

        return view('admin/categories')->with([
            'categories' => $categories,
        ]);
    }

    public function add_category(Request $request)
    {

        $this->validate($request, [
            'category_name' => 'required|unique:categories',
        ]);

        $category = new Category();
        $category->category_name = $request->category_name;
        $category->save();

        request()->session()->flash('success', 'Category has been created successfully!');

        return redirect()->back();
    }

    public function delete_category(Request $request)
    {

        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
        ]);

        $category = Category::find($request->category_id);
        $category->delete();

        request()->session()->flash('success', 'Category has been deleted successfully!');

        return redirect()->back();
    }


    public function edit_category($id)
    {
        $category = Category::find($id);
        return $category;
    }

    public function update_category(Request $request)
    {
        $data = request()->validate([
            'category_name' => 'required|unique:categories,category_name,'.$request->id,
            'id' => 'required',
        ]);

        Category::where('id',$request->id)->update($data);

        request()->session()->flash('success', 'Category has been updated.');

        return redirect()->back();
    }




    //Added
    public function products()
    {
        $products = Product::orderBy('id', 'desc')->get();
        $categories = Category::all();
        return view('admin.products')->with([
            'products' => $products,
            'categories' => $categories

        ]);
    }


    public function add_products(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|unique:products',
            'price' => 'required|numeric',
            'image' => 'required|file',
        ]);

        $uploadedFile = $request->file('image');
        $filename = time().$uploadedFile->getClientOriginalName();

        //upload the file
        $request->file('image')->storeAs("public/uploads", $filename);


        $product = new Product();
        $product->category_id = $request->category_id;
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->image_url = "uploads/".$filename;
        $product->is_featured = $request->is_featured == "on" ? true : false;
        $product->save();

        request()->session()->flash('success', 'Product has been created successfully!');

        return redirect()->back();
    }



    public function edit_product($id)
    {
        $product = Product::find($id);
        return $product;
    }

    public function update_product(Request $request)
    {


        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|unique:products,product_name,'.$request->id,
            'price' => 'required|numeric',
            'image' => 'file',
            'id' => 'required',
        ]);

        $product = Product::find($request->id);
        $product->category_id = $request->category_id;
        $product->product_name = $request->product_name;
        $product->price = $request->price;

        if ($request->hasFile('image')){
            $uploadedFile = $request->file('image');
            $filename = time().$uploadedFile->getClientOriginalName();

            //upload the file
            $request->file('image')->storeAs("public/uploads", $filename);

            $product->image_url = "uploads/".$filename;
        }

        $product->is_featured = $request->is_featured == "on" ? true : false;
        $product->update();

        request()->session()->flash('success', 'Product has been updated.');

        return redirect()->back();
    }



    public function delete_product(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:products,id',
        ]);

        $product = Product::find($request->id);
        $product->delete();

        request()->session()->flash('success', 'Product has been deleted successfully!');

        return redirect()->back();
    }



   public function blogs()
   {
    return view('blogs');
   }

   public function contact()
   {
       return view('contact');
   }



   public function product_details($id)
   {

       $product = Product::find($id);

       $categoryId = $product->category->id;
       $categoryProducts = Product::where('category_id', $categoryId)->get();
    //    $category = Category::where('id', $categoryId)->get();
       return view('product_details')->with([
           'product'=>$product,
           'categoryId' => $categoryId,
        //    'category'=>$category,
           'categoryProducts' => $categoryProducts
       ]);
   }


}
