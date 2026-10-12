<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slider;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ── بازبینی اعتبار ورودی [امنیت: بارگذاری فایل] ──────────────
        //
        // پیش‌تر این متد هیچ اعتبارسنجی‌ای نداشت: هر فایلی با هر محتوایی
        // پذیرفته می‌شد و در `public/uploads/sliders/` نوشته می‌شد.
        // قاعده `image` نوع **واقعی** فایل را بررسی می‌کند (نه فقط پسوند)
        // و `max:4096` سقف ۴ مگابایت می‌گذارد.
        //
        // چرا مهم است؟ پوشه `public/` با وب‌سرور سرو می‌شود. فایل PHP
        // بارگذاری‌شده در آن پوشه، در سرورهایی که اجرای PHP در پوشه
        // بارگذاری را نبسته‌اند، به اجرای کد می‌رسد.
        //
        // ── تله‌ای که آزمون لو داد: قاعده `image` در Laravel 5.8 ─────
        //
        // ابتدا از قاعده `image` استفاده کردم؛ آزمون نشان داد **هر فایل
        // JPEG را رد می‌کند**. علت در فریم‌ورک است: `image` به
        // `mimes` با فهرست `['jpeg','png','gif','bmp','svg','webp']`
        // نگاشت می‌شود، در حالی که `guessExtension()` برای JPEG مقدار
        // **`jpg`** برمی‌گرداند. یعنی `image` عملاً همه عکس‌های jpg را
        // بلاک می‌کرد و مدیر پنل می‌گفت «دکمه کار نمی‌کند».
        //
        // پس دو قاعده صریح به‌کار می‌رود:
        //   • `mimes:jpg,jpeg,...` → نوع فایل از **محتوا** حدس زده می‌شود
        //     (فایل PHP با نام .jpg رد می‌شود؛ آزمون این را می‌سنجد).
        //   • `dimensions:max_width/max_height` → فایل باید واقعاً با
        //     `getimagesize` قابل خواندن باشد و ابعادش در محدوده بماند.
        //     این قاعده فایل‌های تصادفی و HTML با نام .jpg را هم می‌گیرد.
        $validated = $request->validate([
            'url'      => 'nullable|string|max:255',
            'photos'   => 'required|array|min:1|max:10',
            'photos.*' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:4096'
                          . '|dimensions:max_width=4000,max_height=4000',
        ], [
            'photos.required'  => __('حداقل یک تصویر اسلایدر لازم است.'),
            'photos.max'       => __('حداکثر ۱۰ تصویر در هر بار بارگذاری مجاز است.'),
            'photos.*.mimes'   => __('تنها تصویر jpg، png، webp یا gif پذیرفته می‌شود.'),
            'photos.*.max'     => __('حجم هر تصویر باید کمتر از ۴ مگابایت باشد.'),
            'photos.*.dimensions' => __('ابعاد تصویر باید حداکثر ۴۰۰۰×۴۰۰۰ پیکسل باشد.'),
        ]);

        foreach ($request->file('photos') as $photo) {
            $slider = new Slider;

            // `published` در دیتابیس پیش‌فرض ۱ دارد؛ صریح نوشتن آن،
            // رفتار را به پیش‌فرض ستون وابسته نمی‌کند.
            $slider->link      = $validated['url'];
            $slider->photo     = $photo->store('uploads/sliders');
            $slider->published = 1;
            $slider->save();
        }

        flash(__('Slider has been inserted successfully'))->success();

        return redirect()->route('home_settings.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * فعال/غیرفعال کردن اسلاید (فراخوانی AJAX از پنل).
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return string '1' موفق، '0' ناموفق
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);
        $slider->published = $request->status;
        if($slider->save()){
            return '1';
        }
        else {
            return '0';
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
        $slider = Slider::findOrFail($id);
        if(Slider::destroy($id)){
            //unlink($slider->photo);
            flash(__('Slider has been deleted successfully'))->success();
        }
        else{
            flash(__('Something went wrong'))->error();
        }
        return redirect()->route('home_settings.index');
    }
}


