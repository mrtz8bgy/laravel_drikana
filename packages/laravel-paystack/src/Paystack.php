<?php

namespace Mehedi\Paystack;

use Illuminate\Support\Facades\Config;

/**
 * کلاینت سبک درگاه پرداخت Paystack.
 *
 * ── چرا این کلاس داخل مخزن است؟ ─────────────────────────────────────
 * بستهٔ اصلی `mehedi-iitdu/laravel-paystack` از Packagist و GitHub حذف
 * شده است (فایل dist و مخزن، هر دو ۴۰۴) و هیچ نسخهٔ عمومی دیگری از آن
 * باقی نمانده. این کلاس همان API‌ای را پیاده می‌کند که
 * `App\Http\Controllers\PaystackController` استفاده می‌کند:
 *
 *     $paystack = new Paystack($secretKey, $baseUrl);
 *     $request->reference = $paystack->genTranxRef();
 *     return $paystack->getAuthorizationUrl()->redirectNow();
 *     // و در بازگشت:
 *     $payment = $paystack->getPaymentData();
 *
 * ── نکتهٔ مهم ──────────────────────────────────────────────────────
 * هیچ تراکنش واقعی‌ای با این کد اجرا نشده است، چون کلیدهای واقعی
 * Paystack در دسترس نبود. جریان کار (ساخت ارجاع، درخواست init و verify)
 * مطابق مستندات عمومی Paystack پیاده شده و برای فعال‌سازی روی محیط
 * عملیاتی فقط باید کلیدها در .env تنظیم شوند.
 *
 * تمام درخواست‌ها با cURL و روی HTTPS انجام می‌شوند و کلید مخفی هرگز
 * در URL قرار نمی‌گیرد (فقط در هدر Authorization).
 */
class Paystack
{
    /**
     * کلید مخفی درگاه.
     *
     * @var string
     */
    protected $secretKey;

    /**
     * نشانی پایهٔ API (مثلاً https://api.paystack.co).
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * پاسخ آخرین فراخوانی API.
     *
     * @var array
     */
    protected $response = [];

    /**
     * دادهٔ تأییدشدهٔ تراکنش.
     *
     * @var array
     */
    protected $paymentData = [];

    /**
     * @param string|null $secretKey کلید مخفی (پیش‌فرض: PAYSTACK_SECRET_KEY)
     * @param string|null $baseUrl   نشانی پایهٔ API
     */
    public function __construct($secretKey = null, $baseUrl = null)
    {
        $this->secretKey = $secretKey ?: Config::get('paystack.secretKey');
        $this->baseUrl   = rtrim($baseUrl ?: Config::get('paystack.paymentUrl', 'https://api.paystack.co'), '/');
    }

    /**
     * تولید ارجاع یکتا برای تراکنش.
     *
     * @return string
     */
    public function genTranxRef()
    {
        return 'DRIKANA_' . strtoupper(uniqid('', true)) . '_' . time();
    }

    /**
     * نشانی بازگشت پس از پرداخت (Callback URL).
     *
     * این مسیر در `routes/web.php` ثبت شده است:
     *     GET /paystack/payment/callback
     *
     * @return string
     */
    public function getCallbackUrl()
    {
        return url('/paystack/payment/callback');
    }

    /**
     * درخواست آغاز تراکنش از Paystack.
     *
     * مقادیر email / amount / reference از شیء Request جاری خوانده
     * می‌شوند، چون کنترلر پروژه همان‌ها را روی Request تنظیم می‌کند
     * (`$request->email = ...`).
     *
     * @return $this
     */
    public function getAuthorizationUrl()
    {
        $request = request();

        $fields = [
            'email'        => $request->email,
            'amount'       => $request->amount,
            'reference'    => $request->reference ?: $this->genTranxRef(),
            'callback_url' => $this->getCallbackUrl(),
            'currency'     => $request->currency ?: Config::get('paystack.currency', 'NGN'),
        ];

        if ($request->metadata) {
            $fields['metadata'] = $request->metadata;
        }

        $this->response = $this->call('POST', '/transaction/initialize', $fields);

        return $this;
    }

    /**
     * هدایت کاربر به صفحهٔ پرداخت Paystack.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectNow()
    {
        if (empty($this->response['data']['authorization_url'])) {
            abort(500, $this->errorMessage());
        }

        return redirect($this->response['data']['authorization_url']);
    }

    /**
     * تأیید تراکنش و دریافت دادهٔ پرداخت (فراخوانی در Callback).
     *
     * @return array
     */
    public function getPaymentData()
    {
        $reference = request()->reference ?: request('reference');

        if (!$reference) {
            return ['status' => false, 'message' => 'ارجاع تراکنش (reference) در درخواست موجود نیست.'];
        }

        // اگر همین حالا init کرده‌ایم، همان ارجاع را دوباره استفاده می‌کنیم
        $this->paymentData = $this->call('GET', '/transaction/verify/' . rawurlencode($reference));

        return $this->paymentData;
    }

    /**
     * پیام خطای آخرین فراخوانی (اگر وجود داشته باشد).
     *
     * @return string
     */
    public function errorMessage()
    {
        if (isset($this->response['message']) && $this->response['status'] === false) {
            return (string) $this->response['message'];
        }

        if (isset($this->paymentData['message']) && $this->paymentData['status'] === false) {
            return (string) $this->paymentData['message'];
        }

        return 'خطای نامشخص در ارتباط با درگاه پرداخت Paystack.';
    }

    /**
     * فراخوانی API با cURL.
     *
     * نکته امنیتی: کلید مخفی فقط در هدر Authorization فرستاده می‌شود؛
     * نه در URL و نه در بدنه. پاسخ در همین کلاس نگه داشته می‌شود و
     * در هیچ لاگی ثبت نمی‌گردد.
     *
     * @param  string $method
     * @param  string $path
     * @param  array  $payload
     * @return array
     */
    protected function call($method, $path, array $payload = [])
    {
        if (empty($this->secretKey)) {
            return [
                'status'  => false,
                'message' => 'کلید مخفی Paystack تنظیم نشده است (PAYSTACK_SECRET_KEY).',
            ];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->secretKey,
            'Content-Type: application/json',
            'Cache-Control: no-cache',
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $body  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false || $error !== '') {
            return ['status' => false, 'message' => 'ارتباط با Paystack برقرار نشد: ' . $error];
        }

        $decoded = json_decode($body, true);

        if (!is_array($decoded)) {
            return ['status' => false, 'message' => 'پاسخ نامعتبر از Paystack دریافت شد.'];
        }

        return $decoded;
    }
}
