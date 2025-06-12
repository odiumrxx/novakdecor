<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function brands(){
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }
    public function add_brand()
    {
        return view('admin.brand-add');
    }
    public function brand_store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048'
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $file_extension = $imageFile->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateBrandThumbailsImage($imageFile,$file_name);
            $brand->image = $file_name;
        } else {
            $brand->image = null;
        }

        $brand->save();
        return redirect()->route('admin.brands')->with('status','Бренд успешно добавлен!');
    }

    public function brand_edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand-edit',compact('brand'));
    }

    public function brand_update(Request $request){
        $brand = Brand::findOrFail($request->id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,'.$brand->id,
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048'
        ]);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)){
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $imageFile = $request->file('image');
            $file_extension = $imageFile->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateBrandThumbailsImage($imageFile,$file_name);
            $brand->image = $file_name;
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Бренд успешно обновлен!');
    }

    public function GenerateBrandThumbailsImage($imageFile, $imageName)
    {
        $destinationPath = public_path('uploads/brands');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $manager = new ImageManager(new Driver());
        $img = $manager->read($imageFile->path());
        $img->cover(124, 124)->save($destinationPath . '/' . $imageName);
    }

    public function brand_delete($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return redirect()->route('admin.brands')->with('error', 'Бренд не найден!');
        }
        if(File::exists(public_path('uploads/brands').'/'.$brand->image))
        {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Бренд успешно удален!');
    }

    public function categories()
    {
      $categories = Category::orderBy('id','DESC')->paginate(10);
      return view('admin.categories',compact('categories'));
    }

    public function category_add()
    {
        return view('admin.category-add');
    }
    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048'
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $file_extension = $imageFile->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateCategoryThumbailsImage($imageFile,$file_name);
            $category->image = $file_name;
        } else {
            $category->image = null;
        }

        $category->save();
        return redirect()->route('admin.categories')->with('status','Категория успешно добавлена!');
    }
    public function GenerateCategoryThumbailsImage($imageFile, $imageName)
    {
        $destinationPath = public_path('uploads/categories');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $manager = new ImageManager(new Driver());
        $img = $manager->read($imageFile->path());
        $img->cover(124, 124)->save($destinationPath . '/' . $imageName);
    }
    public function category_edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category-edit',compact('category'));
    }

    public function category_update(Request $request){
        $category = Category::findOrFail($request->id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,'.$category->id,
            'image' => 'nullable|mimes:png,jpg,jpeg,webp|max:2048'
        ]);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if($request->hasFile('image')){
            if(File::exists(public_path('uploads/categories').'/'.$category->image)){
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $imageFile = $request->file('image');
            $file_extension = $imageFile->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenerateCategoryThumbailsImage($imageFile,$file_name);
            $category->image = $file_name;
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status','Категория успешно обновлена!');
    }
    public function category_delete($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('admin.categories')->with('error', 'Категория не найдена!');
        }
        if(File::exists(public_path('uploads/categories').'/'.$category->image))
        {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Категория успешно удалена!');
    }
    public function products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }

    public function product_add()
    {
      $categories = Category::select('id','name')->orderBy('name')->get();
      $brands = Brand::select('id', 'name')->orderBy('name')->get();
      return view('admin.product-add',compact('categories', 'brands'));
    }

    public function product_store(Request $request)
    {
        // Генерируем слаг из имени до валидации и добавляем его в запрос
        $generatedSlug = Str::slug($request->name);
        $request->merge(['slug' => $generatedSlug]);

        $request->validate([
           'name' => 'required|string|max:100',
           'slug' => 'required|string|unique:products,slug',
           'category_id' => 'required|exists:categories,id',
           'brand_id' => 'required|exists:brands,id',
           'short_description' => 'required|string|max:255',
           'description' => 'required|string',
           'regular_price' => 'required|numeric|min:0',
           'sale_price' => 'nullable|numeric|min:0|lt:regular_price',
           'SKU' => 'required|string|unique:products,SKU',
           'quantity' => 'required|integer|min:0',
           'stock_status' => 'required|in:instock,outofstock',
           'featured' => 'required|boolean',
           'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
           'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $generatedSlug;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image'))
        {
               $image = $request->file('image');
               $imageName = $current_timestamp . '.' . $image->extension();
               $this->SaveProductImage($image, $imageName);
               $product->image = $imageName;
        }

        $gallery_arr = [];
        if($request->hasFile('images'))
        {
               $allowedfileExtention = ['jpg','png','jpeg','webp'];
               $files = $request->file('images');
               $counter = 1;
               foreach($files as $file)
               {
                 $gextension = $file->getClientOriginalExtension();
                 $gcheck = in_array($gextension,$allowedfileExtention);
                 if($gcheck)
                 {
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->SaveProductImage($file, $gfileName);
                    array_push($gallery_arr, $gfileName);
                    $counter++;
                 }
               }
               $product->images = implode(',',$gallery_arr);
        } else {
              $product->images = null;
        }

        $product->save();
        return redirect()->route('admin.products')->with('status',"Продукция была успешно добавлена!");
    }

    public function SaveProductImage($imageFile, $imageName)
    {
        $destinationPath = public_path('uploads/products');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $imageFile->move($destinationPath, $imageName);
    }

    public function product_edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

      public function product_update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Продукт не найден.');
        }

        try {
            // Генерируем слаг из имени до валидации и добавляем его в запрос
            $generatedSlug = Str::slug($request->name);
            $request->merge(['slug' => $generatedSlug]);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:products,slug,' . $product->id,
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'short_description' => 'nullable|string',
                'description' => 'nullable|string',
                'regular_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|lt:regular_price|min:0',
                'SKU' => 'required|string|max:255|unique:products,SKU,' . $product->id,
                'quantity' => 'required|integer|min:0',
                'stock_status' => 'required|in:instock,outofstock',
                'featured' => 'required|boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $product->fill($validatedData);

            if ($request->hasFile('image')) {
                if ($product->image && File::exists(public_path('uploads/products/' . $product->image))) {
                    File::delete(public_path('uploads/products/' . $product->image));
                    Log::info('Старое основное изображение удалено: ' . $product->image);
                }
                $imageName = time() . '.' . $request->image->extension();
                $this->SaveProductImage($request->file('image'), $imageName);
                $product->image = $imageName;
                Log::info('Новое основное изображение сохранено: ' . $imageName);
            }

            if ($request->hasFile('images')) {
                $newGalleryImages = [];
                $current_timestamp = Carbon::now()->timestamp;
                $counter = count(array_filter($product->images ? explode(',', $product->images) : [])) + 1;

                foreach ($request->file('images') as $file) {
                    $gextension = $file->getClientOriginalExtension();
                    $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                    $this->SaveProductImage($file, $gfileName);
                    $newGalleryImages[] = $gfileName;
                    $counter++;
                }
                $existingImages = $product->images ? explode(',', $product->images) : [];
                $allImages = array_merge($existingImages, $newGalleryImages);
                $product->images = implode(',', array_filter($allImages));
            }

            $product->save();
            return redirect()->route('admin.products')->with('success', 'Продукт успешно обновлен!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Ошибка при обновлении продукта: ' . $e->getMessage() . ' Трассировка: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Произошла ошибка при обновлении продукта.')->withInput();
        }
    }

    public function product_delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Продукт не найден!');
        }

        if (File::exists(public_path('uploads/products') . '/' . $product->image)) {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }

        if ($product->images) {
            $gallery_images = explode(',', $product->images);
            foreach ($gallery_images as $image_name) {
                if (File::exists(public_path('uploads/products') . '/' . $image_name)) {
                    File::delete(public_path('uploads/products') . '/' . $image_name);
                }
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Продукт успешно удален!');
    }

}