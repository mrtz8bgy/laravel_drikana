{{--
    ─────────────────────────────────────────────────────────────
    فرم مشترک ایجاد و ویرایش مقاله
    ─────────────────────────────────────────────────────────────

    متغیرها:
      $article    : مدل مقاله یا null (حالت ایجاد)
      $categories : دسته‌بندی‌های فعال

    نکات امنیتی:
      • تمام فیلدها با maxlength و type مناسب محدود شده‌اند.
      • فیلد «متن کامل» یک textarea است؛ محتوای آن در سرور با
        HtmlSanitizer پاک‌سازی می‌شود و هرگز به‌صورت خام ذخیره
        یا نمایش داده نمی‌شود.
      • آپلود تصویر در سرور با SecureImageUploader بررسی می‌شود
        (نوع واقعی فایل، ابعاد، حجم و مسیر ذخیره‌سازی).
--}}

@php
    $isEdit = isset($article) && $article !== null;
@endphp

<div class="row">
    <div class="col-md-8">

        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('محتوای مقاله') }}</h3>
            </div>
            <div class="panel-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="u-margin-0-padding-right-20px">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="title">{{ __('عنوان مقاله') }} <span class="text-danger">*</span></label>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-control"
                           maxlength="191"
                           value="{{ old('title', $isEdit ? $article->title : '') }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="slug">{{ __('نامک (slug)') }}</label>
                    <input type="text"
                           id="slug"
                           name="slug"
                           class="form-control"
                           maxlength="191"
                           value="{{ old('slug', $isEdit ? $article->slug : '') }}"
                           placeholder="{{ __('خالی بگذارید تا خودکار ساخته شود') }}">
                    <small class="text-muted">
                        {{ __('آدرس یکتای مقاله. تنها حروف کوچک لاتین، رقم و خط تیره مجاز است.') }}
                    </small>
                </div>

                <div class="form-group">
                    <label for="excerpt">{{ __('خلاصه مقاله') }}</label>
                    <textarea id="excerpt"
                              name="excerpt"
                              class="form-control"
                              rows="3"
                              maxlength="500">{{ old('excerpt', $isEdit ? $article->excerpt : '') }}</textarea>
                    <small class="text-muted">{{ __('حداکثر ۵۰۰ کاراکتر — در فهرست مقالات نمایش داده می‌شود.') }}</small>
                </div>

                <div class="form-group">
                    <label for="content">{{ __('متن کامل مقاله') }} <span class="text-danger">*</span></label>
                    <textarea id="content"
                              name="content"
                              class="form-control"
                              rows="18"
                              required>{{ old('content', $isEdit ? $article->content : '') }}</textarea>
                    <small class="text-muted">
                        {{ __('تگ‌های اسکریپت، iframe و رویدادهای جاوااسکریپت در هنگام ذخیره حذف می‌شوند.') }}
                    </small>
                </div>

            </div>
        </div>

        {{-- ============================================= --}}
        {{-- تصاویر مقاله --}}
        {{-- ============================================= --}}
        @if($isEdit)
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('تصاویر مقاله') }}</h3>
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        <label>{{ __('افزودن تصویر جدید') }}</label>
                        <input type="file" id="article_image" accept="image/jpeg,image/png,image/webp,image/gif">
                        <small class="text-muted">
                            {{ __('فرمت‌های مجاز: JPG، PNG، WebP، GIF — حداکثر ۳ مگابایت') }}
                        </small>
                        <div id="image_upload_status" class="text-muted u-margin-top-6px"></div>
                    </div>

                    <hr>

                    @if($article->images->count() > 0)
                        <div class="row">
                            @foreach($article->images as $image)
                                <div class="col-sm-3 u-margin-bottom-12px">
                                    <div class="thumbnail">
                                        <img class="u-height-120px-object-fit-cover" src="{{ $image->url }}" alt="{{ $image->alt }}">
                                        <div class="caption">
                                            <small class="text-muted u-word-break-break-all">
                                                {{ \Illuminate\Support\Str::limit($image->original_name, 20) }}
                                            </small>
                                            <a href="#"
 onclick="deleteArticleImage({{ $image->id }}); return false;"
 class="btn btn-xs btn-danger btn-block u-margin-top-6px">
                                                <i class="demo-pli-recycling"></i> {{ __('حذف') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">{{ __('هنوز تصویری به این مقاله افزوده نشده است.') }}</p>
                    @endif

                </div>
            </div>
        @endif

    </div>

    <div class="col-md-4">

        {{-- ============================================= --}}
        {{-- تنظیمات انتشار --}}
        {{-- ============================================= --}}
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('تنظیمات انتشار') }}</h3>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label>{{ __('وضعیت فعلی') }}</label>
                    <div>
                        @if($isEdit)
                            @php
                                $statusLabels = [
                                    'draft'          => ['default', 'پیش‌نویس'],
                                    'pending_review' => ['warning', 'در انتظار بررسی'],
                                    'published'      => ['success', 'منتشرشده'],
                                    'archived'       => ['danger', 'بایگانی‌شده'],
                                ];
                                $st = isset($statusLabels[$article->status]) ? $statusLabels[$article->status] : ['default', $article->status];
                            @endphp
                            <span class="label label-{{ $st[0] }} u-font-size-13px">{{ $st[1] }}</span>
                        @else
                            <span class="label label-default u-font-size-13px">{{ __('پیش‌نویس جدید') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="category_id">{{ __('دسته‌بندی') }}</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">{{ __('بدون دسته‌بندی') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                @if(old('category_id', $isEdit ? $article->category_id : null) == $category->id) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="author_name">{{ __('نام نویسنده / ثبت‌کننده') }}</label>
                    <input type="text"
                           id="author_name"
                           name="author_name"
                           class="form-control"
                           maxlength="191"
                           value="{{ old('author_name', $isEdit ? $article->author_name : Auth::user()->name) }}">
                </div>

                <div class="form-group">
                    <label class="switch">
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured', $isEdit ? $article->is_featured : false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <label class="u-margin-right-10px">{{ __('مقاله ویژه') }}</label>
                </div>

                <div class="form-group">
                    <label for="sort_order">{{ __('ترتیب نمایش') }}</label>
                    <input type="number" id="sort_order" name="sort_order" class="form-control"
                           min="0" max="9999"
                           value="{{ old('sort_order', $isEdit ? $article->sort_order : 0) }}">
                </div>

            </div>
        </div>

        {{-- ============================================= --}}
        {{-- تصویر شاخص --}}
        {{-- ============================================= --}}
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('تصویر شاخص') }}</h3>
            </div>
            <div class="panel-body">

                @if($isEdit && $article->featured_image)
                    <img src="{{ $article->featured_image_url }}"
 alt="{{ $article->featured_image_alt }}"
 class="img-responsive img-thumbnail u-max-height-160px-margin-bottom-10px">
                @endif

                <input type="file"
                       name="featured_image"
                       accept="image/jpeg,image/png,image/webp,image/gif"
                       class="form-control">

                <input type="text"
 name="featured_image_alt"
 class="form-control u-margin-top-8px"
 
 maxlength="191"
 placeholder="{{ __('متن جایگزین تصویر (alt)') }}"
 value="{{ old('featured_image_alt', $isEdit ? $article->featured_image_alt : '') }}">

                <small class="text-muted">
                    {{ __('متن جایگزین برای دسترس‌پذیری و سئو اهمیت دارد.') }}
                </small>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- سئو --}}
        {{-- ============================================= --}}
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('تنظیمات سئو') }}</h3>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <label for="keywords">{{ __('کلمات کلیدی') }}</label>
                    <input type="text" id="keywords" name="keywords" class="form-control"
                           maxlength="500"
                           placeholder="{{ __('با کاما جدا کنید') }}"
                           value="{{ old('keywords', $isEdit ? $article->keywords : '') }}">
                </div>

                <div class="form-group">
                    <label for="meta_title">{{ __('عنوان متا') }}</label>
                    <input type="text" id="meta_title" name="meta_title" class="form-control"
                           maxlength="191"
                           value="{{ old('meta_title', $isEdit ? $article->meta_title : '') }}">
                </div>

                <div class="form-group">
                    <label for="meta_description">{{ __('توضیحات متا') }}</label>
                    <textarea id="meta_description" name="meta_description" class="form-control"
                              rows="3" maxlength="300">{{ old('meta_description', $isEdit ? $article->meta_description : '') }}</textarea>
                </div>

            </div>
        </div>

    </div>
</div>
