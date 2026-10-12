<?php

/*
 * تنظیمات بستهٔ Paystack.
 *
 * نکته: به‌جای getenv() از تابع کمکی env() استفاده شده است تا مقدارها
 * از کش تنظیمات و .env لاراول (با در نظر گرفتن APP_ENV و .env.testing)
 * درست خوانده شوند. مقدارهای پیش‌فرض هیچ کلید واقعی‌ای ندارند و باید
 * در .env تنظیم شوند.
 */

return [

    /*
     * کلید عمومی Paystack (از داشبورد درگاه).
     */
    'publicKey' => env('PAYSTACK_PUBLIC_KEY'),

    /*
     * کلید مخفی Paystack. این مقدار هرگز نباید در مخزن گیت قرار بگیرد.
     */
    'secretKey' => env('PAYSTACK_SECRET_KEY'),

    /*
     * نشانی پایهٔ API پرداخت.
     */
    'paymentUrl' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),

    /*
     * واحد پول پیش‌فرض تراکنش.
     */
    'currency' => env('PAYSTACK_CURRENCY', 'NGN'),

    /*
     * ایمیل اختیاری فروشنده.
     */
    'merchantEmail' => env('MERCHANT_EMAIL'),

];
