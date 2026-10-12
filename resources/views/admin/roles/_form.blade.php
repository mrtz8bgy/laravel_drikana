{{--
    ─────────────────────────────────────────────────────────────
    فرم مشترک ایجاد و ویرایش نقش
    ─────────────────────────────────────────────────────────────

    متغیرهای مورد انتظار:
      $role            : مدل نقش (در حالت ایجاد، null)
      $permissionGroups: گروه‌بندی مجوزها
      $actorPermissions: مجوزهای کاربر جاری
      $isSuperAdmin    : آیا کاربر جاری مدیر کل است؟
--}}

@php
    $isEdit = isset($role) && $role !== null;
@endphp

<div class="col-lg-10 col-lg-offset-1">

    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">
                {{ $isEdit ? __('ویرایش نقش') : __('ایجاد نقش جدید') }}
            </h3>
        </div>

        <form class="form-horizontal"
              action="{{ $isEdit ? route('roles.update', $role->id) : route('roles.store') }}"
              method="POST">
            @csrf

            @if($isEdit)
                <input name="_method" type="hidden" value="PATCH">
            @endif

            <div class="panel-body">

                {{-- نمایش خطاهای اعتبارسنجی --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="u-margin-0-padding-right-20px">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ============================================= --}}
                {{-- نام نقش --}}
                {{-- ============================================= --}}
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label class="col-sm-3 control-label" for="name">{{ __('نام نقش') }} <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control"
                               maxlength="100"
                               value="{{ old('name', $isEdit ? $role->name : '') }}"
                               placeholder="{{ __('مثال: مدیر فروشگاه') }}"
                               required>
                        <small class="text-muted">{{ __('نامی که در پنل نمایش داده می‌شود.') }}</small>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- شناسه نقش (slug) --}}
                {{-- ============================================= --}}
                <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                    <label class="col-sm-3 control-label" for="slug">
                        {{ __('شناسه یکتا (slug)') }}
                        @if(!$isEdit) <span class="text-danger">*</span> @endif
                    </label>
                    <div class="col-sm-9">
                        @if($isEdit && $role->is_system)
                            {{-- شناسه نقش سیستمی قابل تغییر نیست: کد برنامه به آن وابسته است --}}
                            <input type="text" class="form-control" value="{{ $role->slug }}" disabled>
                            <input type="hidden" name="slug" value="{{ $role->slug }}">
                            <small class="text-danger">
                                {{ __('شناسه نقش سیستمی قابل تغییر نیست، چون کد برنامه به آن وابسته است.') }}
                            </small>
                        @else
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-control"
                                   maxlength="60"
                                   pattern="[a-z0-9_\-]+"
                                   value="{{ old('slug', $isEdit ? $role->slug : '') }}"
                                   placeholder="{{ __('مثال: store_manager') }}">
                            <small class="text-muted">
                                {{ __('فقط حروف کوچک لاتین، رقم، خط تیره و زیرخط. اگر خالی بماند از نام ساخته می‌شود.') }}
                            </small>
                        @endif
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- توضیحات --}}
                {{-- ============================================= --}}
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="description">{{ __('توضیحات') }}</label>
                    <div class="col-sm-9">
                        <textarea id="description"
                                  name="description"
                                  class="form-control"
                                  rows="2"
                                  maxlength="500"
                                  placeholder="{{ __('این نقش برای چه کسانی است؟') }}">{{ old('description', $isEdit ? $role->description : '') }}</textarea>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- سطح نقش --}}
                {{-- ============================================= --}}
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="level">{{ __('سطح دسترسی') }}</label>
                    <div class="col-sm-9">
                        @if($isEdit && $role->is_system)
                            <input type="text" class="form-control" value="{{ $role->level }}" disabled>
                            <small class="text-danger">{{ __('سطح نقش سیستمی قابل تغییر نیست.') }}</small>
                        @else
                            <input type="number"
                                   id="level"
                                   name="level"
                                   class="form-control"
                                   min="1"
                                   max="{{ $isSuperAdmin ? 100 : 89 }}"
                                   value="{{ old('level', $isEdit ? $role->level : 50) }}">
                            <small class="text-muted">
                                {{ __('عدد بزرگ‌تر یعنی دسترسی بیشتر. حداکثر مجاز برای شما:') }}
                                <strong>{{ $isSuperAdmin ? 100 : 89 }}</strong>
                            </small>
                        @endif
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- وضعیت فعال/غیرفعال --}}
                {{-- ============================================= --}}
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{ __('وضعیت') }}</label>
                    <div class="col-sm-9">
                        <label class="switch">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $isEdit ? $role->is_active : true) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <small class="text-muted">{{ __('نقش غیرفعال هیچ مجوزی به کاربرانش نمی‌دهد.') }}</small>
                    </div>
                </div>

            </div>

            {{-- ============================================= --}}
            {{-- مجوزها --}}
            {{-- ============================================= --}}
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('مجوزها') }}</h3>
            </div>

            <div class="panel-body">

                <div class="alert alert-info">
                    <i class="demo-pli-information"></i>
                    {{ __('شما تنها می‌توانید مجوزهایی را اعطا کنید که خودتان دارید. مجوزهایی که ندارید غیرفعال نمایش داده می‌شوند.') }}
                </div>

                @php
                    $selected = old('permissions', $isEdit ? $role->permissionSlugs() : []);
                @endphp

                @include('admin.partials.permission_matrix', [
                    'permissionGroups' => $permissionGroups,
                    'selected'         => $selected,
                    'actorPermissions' => $actorPermissions,
                    'isSuperAdmin'     => $isSuperAdmin,
                ])

            </div>

            <div class="panel-footer text-left">
                <a href="{{ route('roles.index') }}" class="btn btn-default">
                    {{ __('انصراف') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    {{ $isEdit ? __('ذخیره تغییرات') : __('ایجاد نقش') }}
                </button>
            </div>

        </form>
    </div>
</div>
