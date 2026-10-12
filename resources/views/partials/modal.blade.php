<script>
    /**
     * نمایش مودال تأیید حذف.
     *
     * ── ارتقای امنیتی (مرحله ۷ — امنیت) ──────────────────────────────
     * نسخه قبلی، لینک تأیید حذف را با تنظیم خصیصه href به آدرس حذف می‌فرستاد
     * کاربر با کلیک روی آن، درخواست «GET» به مسیر حذف می‌فرستاد.
     * این الگو دو مشکل جدی داشت:
     *
     *   ۱) توکن CSRF فرستاده نمی‌شد؛ بنابراین هر صفحه خارجی می‌توانست
     *      با یک تصویر یا لینک، حذف رکوردها را در مرورگر مدیر فعال کند
     *      (حمله CSRF).
     *   ۲) درخواست GET برای عملیات تغییردهنده، از نظر معماری نادرست است
     *      و خزنده‌ها یا پیش‌بارگذاری مرورگر می‌توانند آن را اجرا کنند.
     *
     * اکنون به‌جای لینک، یک فرم با متد POST و فیلد پنهان _method=DELETE
     * استفاده می‌شود که توکن CSRF را هم می‌فرستد. امضای تابع
     * `confirm_modal(url)` تغییر نکرده، پس تمام صفحاتی که از آن
     * استفاده می‌کنند بدون هیچ تغییری کار می‌کنند.
     */
    function confirm_modal(delete_url)
    {
        jQuery('#confirm-delete').modal('show', {backdrop: 'static'});
        document.getElementById('delete_form').setAttribute('action', delete_url);
    }
</script>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">

        <form id="delete_form" method="POST" action="">
            @csrf
            <input type="hidden" name="_method" value="DELETE">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">{{__('Confirmation')}}</h4>
                </div>

                <div class="modal-body">
                    <p>{{__('Delete confirmation message')}}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
                    <button type="submit" class="btn btn-danger btn-ok">{{__('Delete')}}</button>
                </div>

            </div>
        </form>

    </div>
</div>
