{{--
    ─────────────────────────────────────────────────────────────
    نمایش گروهی مجوزها به صورت چک‌باکس
    ─────────────────────────────────────────────────────────────

    متغیرهای مورد انتظار:
      $permissionGroups : آرایه‌ای به شکل  [نام گروه => [slug => عنوان]]
      $selected         : آرایه‌ای از slugهای انتخاب‌شده (پیش‌فرض: خالی)
      $actorPermissions : آرایه‌ای از slugهای مجاز برای کاربر جاری (پیش‌فرض: null = بدون محدودیت)
      $isSuperAdmin     : آیا کاربر جاری مدیر کل است؟ (پیش‌فرض: false)
      $readOnly         : حالت فقط-خواندنی (پیش‌فرض: false)

    نکته امنیتی مهم:
      غیرفعال‌کردن چک‌باکس‌ها فقط یک لایه «تجربه کاربری» است. اعمال واقعی
      مجوزها در سرویس RbacService و میدل‌ور permission انجام می‌شود. حتی اگر
      کاربری این HTML را دستکاری کند، درخواستش در سرور رد می‌شود.
--}}
@php
    $selected         = isset($selected) ? $selected : [];
    $actorPermissions = isset($actorPermissions) ? $actorPermissions : null;
    $isSuperAdmin     = isset($isSuperAdmin) ? $isSuperAdmin : false;
    $readOnly         = isset($readOnly) ? $readOnly : false;
@endphp

@foreach($permissionGroups as $groupName => $permissions)

    <div class="panel panel-default u-margin-bottom-15px">
        <div class="panel-heading u-padding-8px-15px">
            <h4 class="panel-title u-font-size-14px">
                <i class="demo-pli-folder"></i> {{ $groupName }}
                <span class="badge badge-default pull-left">{{ count($permissions) }}</span>
            </h4>
        </div>
        <div class="panel-body u-padding-10px-15px">
            <div class="row">

                @foreach($permissions as $slug => $title)

                    @php
                        // آیا کاربر جاری اجازه دارد این مجوز را اعطا کند؟
                        $canGrant = $isSuperAdmin
                            || $actorPermissions === null
                            || in_array($slug, $actorPermissions, true);

                        $isChecked = in_array($slug, $selected, true);

                        // چک‌باکس غیرفعال — یا به دلیل نداشتن حق اعطا،
                        // یا به دلیل حالت فقط-خواندنی
                        $disabled = $readOnly || !$canGrant;
                    @endphp

                    <div class="col-sm-6 col-md-4 u-margin-bottom-6px">
                        <label class="control-label"
                               style="font-weight: 400; cursor: {{ $disabled ? 'not-allowed' : 'pointer' }};"
                               @if(!$canGrant) title="{{ __('شما این مجوز را ندارید و نمی‌توانید آن را اعطا کنید') }}" @endif>

                            <input type="checkbox"
 name="permissions[]"
 value="{{ $slug }}"
 class="demo-sw u-margin-left-6px"
 
 @if($isChecked) checked @endif
 @if($disabled) disabled @endif>

                            {{-- در حالت غیرفعال، مقدار انتخاب‌شده باید حفظ شود --}}
                            @if($disabled && $isChecked)
                                <input type="hidden" name="permissions[]" value="{{ $slug }}">
                            @endif

                            {{ $title }}

                            @if(!$canGrant)
                                <span class="label label-danger u-font-size-10px">
                                    {{ __('بدون دسترسی') }}
                                </span>
                            @endif
                        </label>
                        <br>
                        <small class="text-muted u-font-size-10px">{{ $slug }}</small>
                    </div>

                @endforeach

            </div>
        </div>
    </div>

@endforeach
