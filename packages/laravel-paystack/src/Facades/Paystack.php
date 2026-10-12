<?php

namespace Mehedi\Paystack\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * الیاس (Facade) برای دسترسی کوتاه به سرویس Paystack.
 *
 * استفاده:  \Mehedi\Paystack\Facades\Paystack::genTranxRef();
 * (الیاس سراسری `Paystack` در config/app.php ثبت شده است.)
 */
class Paystack extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paystack';
    }
}
