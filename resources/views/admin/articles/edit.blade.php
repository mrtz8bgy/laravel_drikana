@extends('layouts.app')

{{-- استایل ماژول به فایل مستقل منتقل شده است (مرحله ۳ بازسازی) --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/drikana/articles.css') }}">
@endpush

@section('content')

{{-- ویرایش مقاله — فرم در admin/articles/_form.blade.php --}}

<div class="row">
    <div class="col-sm-12">
        <h3 class="pull-right">{{ __('ویرایش مقاله') }}: {{ \Illuminate\Support\Str::limit($article->title, 50) }}</h3>
        <a href="{{ route('admin.articles.index') }}" class="btn btn-default pull-left">
            <i class="demo-pli-arrow-right"></i> {{ __('بازگشت') }}
        </a>
    </div>
</div>

<br>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('error') }}
    </div>
@endif

@if($article->status === 'published')
    <div class="alert alert-info">
        <i class="demo-pli-information"></i>
        {{ __('این مقاله منتشر شده است و تغییرات فوراً در سایت اعمال می‌شود.') }}
    </div>
@endif

<form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    @include('admin.articles._form', ['article' => $article, 'categories' => $categories])

    <div class="row">
        <div class="col-sm-12 text-left">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-default">{{ __('انصراف') }}</a>
            <button type="submit" class="btn btn-primary">
                <i class="demo-pli-check"></i> {{ __('ذخیره تغییرات') }}
            </button>
        </div>
    </div>
</form>

@endsection

@push('script')
<script>
    /**
     * حذف یک تصویر از مقاله.
     *
     * عملیات حذف با متد GET و از مسیر کنترل‌شده انجام می‌شود؛ مجوز
     * کاربر در سرور بررسی می‌گردد (نه فقط با پنهان‌کردن دکمه).
     *
     * @param {number} imageId
     */
    function deleteArticleImage(imageId) {
        if (!confirm('{{ __('آیا از حذف این تصویر اطمینان دارید؟') }}')) {
            return;
        }

        var url = '{{ route('admin.articles.images.delete', ':id') }}'.replace(':id', imageId);

        window.location.href = url;
    }

    /**
     * آپلود تصویر مقاله با AJAX.
     */
    (function () {
        var input = document.getElementById('article_image');
        if (!input) return;

        input.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;

            var status = document.getElementById('image_upload_status');
            var formData = new FormData();
            formData.append('image', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            status.innerHTML = '{{ __('در حال بارگذاری...') }}';

            fetch('{{ route('admin.articles.images.upload', $article->id) }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.success) {
                    status.innerHTML = '<span class="text-success">{{ __('تصویر با موفقیت بارگذاری شد.') }}</span>';
                    setTimeout(function () { window.location.reload(); }, 800);
                } else {
                    status.innerHTML = '<span class="text-danger">' + (data.message || '{{ __('خطا در بارگذاری') }}') + '</span>';
                }
            })
            .catch(function () {
                status.innerHTML = '<span class="text-danger">{{ __('خطا در ارتباط با سرور') }}</span>';
            });
        });
    })();
</script>
@endpush
