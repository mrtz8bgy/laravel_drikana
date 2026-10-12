<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;

class CurrencyController extends Controller
{

    public function changeCurrency(Request $request)
    {
    	$request->session()->put('currency_code', $request->currency_code);
        $currency = Currency::where('code', $request->currency_code)->first();
    	flash(__('Currency changed to ').$currency->name)->success();
    }

    public function currency(Request $request)
    {
        $currencies = Currency::all();
        $active_currencies = Currency::where('status', 1)->get();
        return view('admin.business_settings.currency', compact('currencies', 'active_currencies'));
    }

    /**
     * به‌روزرسانی نرخ تبدیل و وضعیت یک ارز.
     *
     * [رفع باگ — Stage 2]
     * این متد پیش‌تر کامنت شده بود، در حالی که روت
     * `POST /admin/currency/update` (نام: currency.update) به آن
     * اشاره می‌کرد. نتیجه این بود که هر بار تغییر نرخ ارز در پنل
     * مدیریت با خطای 500 مواجه می‌شد.
     *
     * همچنین متن خطای فارسی داخل متد کامنت‌شده با کدگذاری نادرست
     * (mojibake) ذخیره شده بود که در بازنویسی اصلاح شد.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string '1' در صورت موفقیت، '0' در صورت شکست
     */
    public function updateCurrency(Request $request)
    {
        // اعتبارسنجی ورودی — پیش از این هیچ بررسی‌ای انجام نمی‌شد
        $request->validate([
            'id'            => 'required|integer',
            'exchange_rate' => 'required|numeric|min:0',
            'status'        => 'nullable|integer|in:0,1',
        ]);

        $currency = Currency::findOrFail($request->id);

        $currency->exchange_rate = $request->exchange_rate;
        $currency->status        = $request->status;

        if ($currency->save()) {
            flash(__('نرخ ارز با موفقیت به‌روزرسانی شد'))->success();

            return '1';
        }

        flash(__('مشکلی پیش آمد! لطفاً مجدداً امتحان کنید'))->error();

        return '0';
    }

    public function updateYourCurrency(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = $currency->status;
        if($currency->save()){
            flash('واحد پول با موفقیت به روز رسانی شد')->success();
            return redirect()->route('currency.index');
        }
        else {
            flash('مشکل پیش آمده! لطفاً مجدداً امتحان کنید')->error();
            return redirect()->route('currency.index');
        }
    }

    public function create()
    {
        return view('partials.currency_create');
    }

    public function edit(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        return view('partials.currency_edit', compact('currency'));
    }

    public function store(Request $request)
    {
        $currency = new Currency;
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = '0';
        if($currency->save()){
            flash('واحد پول با موفقیت به روز رسانی شد')->success();
            return redirect()->route('currency.index');
        }
        else {
            flash('مشکل پیش آمده! لطفاً مجدداً امتحان کنید')->error();
            return redirect()->route('currency.index');
        }
    }

    public function update_status(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->status = $request->status;
        if($currency->save()){
            return 1;
        }
        return 0;
    }
}


