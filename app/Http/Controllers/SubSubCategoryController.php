<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\SubSubCategory;
use App\Brand;
use App\Product;
use App\Language;

class SubSubCategoryController extends Controller
{
    use \App\Http\Controllers\Concerns\HandlesEncryptedIds;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $subsubcategories = SubSubCategory::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $subsubcategories = $subsubcategories->where('name', 'like', '%'.$sort_search.'%');
        }
        $subsubcategories = $subsubcategories->paginate(15);
        return view('admin.subsubcategories.index', compact('subsubcategories', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.subsubcategories.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subsubcategory = new SubSubCategory;
        $subsubcategory->name = $request->name;
        $subsubcategory->sub_category_id = $request->sub_category_id;
        //$subsubcategory->attributes = json_encode($request->choice_attributes);
        //$subsubcategory->brands = json_encode($request->brands);
        $subsubcategory->meta_title = $request->meta_title;
        $subsubcategory->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        $data = openJSONFile('en');
        $data[$subsubcategory->name] = $subsubcategory->name;
        saveJSONFile('en', $data);

        if($subsubcategory->save()){
            flash(__('SubSubCategory has been inserted successfully'))->success();
            return redirect()->route('subsubcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subsubcategory = SubSubCategory::findOrFail($this->decryptId($id));
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.subsubcategories.edit', compact('subsubcategory', 'categories', 'brands'));
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
        $subsubcategory = SubSubCategory::findOrFail($id);

        foreach (Language::all() as $key => $language) {
            $data = openJSONFile($language->code);
            unset($data[$subsubcategory->name]);
            $data[$request->name] = "";
            saveJSONFile($language->code, $data);
        }

        $subsubcategory->name = $request->name;
        $subsubcategory->sub_category_id = $request->sub_category_id;
        //$subsubcategory->attributes = json_encode($request->choice_attributes);
        //$subsubcategory->brands = json_encode($request->brands);
        $subsubcategory->meta_title = $request->meta_title;
        $subsubcategory->meta_description = $request->meta_description;
        if ($request->slug != null) {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $subsubcategory->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);
        }

        if($subsubcategory->save()){
            flash(__('SubSubCategory has been updated successfully'))->success();
            return redirect()->route('subsubcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subsubcategory = SubSubCategory::findOrFail($id);
        Product::where('subsubcategory_id', $subsubcategory->id)->delete();
        if(SubSubCategory::destroy($id)){
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$subsubcategory->name]);
                saveJSONFile($language->code, $data);
            }
            flash(__('SubSubCategory has been deleted successfully'))->success();
            return redirect()->route('subsubcategories.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function get_subsubcategories_by_subcategory(Request $request)
    {
        $subsubcategories = SubSubCategory::where('sub_category_id', $request->subcategory_id)->get();
        return $subsubcategories;
    }

    /**
     * برندهای وابسته به یک زیر‌زیر‌دسته.
     *
     * [رفع باگ — Stage 2]
     * این متد کامنت شده بود در حالی که روت
     * `POST /subsubcategories/get_brands_by_subsubcategory` به آن
     * اشاره می‌کرد؛ بنابراین انتخاب زیر‌زیر‌دسته در فرم محصولات با
     * خطای 500 متوقف می‌شد.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_brands_by_subsubcategory(Request $request)
    {
        $request->validate(['subsubcategory_id' => 'required|integer']);

        $subsubcategory = SubSubCategory::findOrFail($request->subsubcategory_id);

        $brand_ids = json_decode($subsubcategory->brands, true);

        if (!is_array($brand_ids)) {
            $brand_ids = [];
        }

        // پاک‌سازی شناسه‌ها: تنها اعداد صحیح مجاز هستند
        $brand_ids = array_filter(array_map('intval', $brand_ids));

        $brands = Brand::whereIn('id', $brand_ids)->get();

        return response()->json($brands);
    }

    /**
     * ویژگی‌های وابسته به یک زیر‌زیر‌دسته.
     *
     * [رفع باگ — Stage 2]
     * این متد نیز کامنت شده بود و روت مربوطه خطای 500 می‌داد.
     * علاوه بر آن، پیاده‌سازی قبلی برای هر شناسه یک کوئری جدا می‌زد
     * (مسئله N+1)؛ در اینجا با یک کوئری گروهی جایگزین شد.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_attributes_by_subsubcategory(Request $request)
    {
        $request->validate(['subsubcategory_id' => 'required|integer']);

        $subsubcategory = SubSubCategory::findOrFail($request->subsubcategory_id);

        $attribute_ids = json_decode($subsubcategory->attributes, true);

        if (!is_array($attribute_ids)) {
            $attribute_ids = [];
        }

        $attribute_ids = array_filter(array_map('intval', $attribute_ids));

        $attributes = \App\Attribute::whereIn('id', $attribute_ids)->get();

        return response()->json($attributes);
    }
}


