<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerPayment;
use App\Payment;
use Auth;

class PaymentController extends Controller
{
    use \App\Http\Controllers\Concerns\HandlesEncryptedIds;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // تاریخچهٔ پرداخت فروشنده از جدول اختصاصی `seller_payments` خوانده
        // می‌شود؛ پیش‌تر از جدول `payments` (پرداخت سفارش‌ها) خوانده می‌شد
        // که ستون `seller_id` ندارد و صفحه با خطای ۵۰۰ از کار می‌افتاد.
        $payments = SellerPayment::where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('frontend.seller.payment_history', compact('payments'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment_histories(Request $request)
    {
        // پنل مدیریت همهٔ پرداخت‌های انجام‌شده به فروشندگان را می‌بیند.
        $payments = SellerPayment::with('seller')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.sellers.payment_histories', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payments = SellerPayment::where('seller_id', $this->decryptId($id))->orderBy('created_at', 'desc')->get();
        if($payments->count() > 0){
            return view('admin.sellers.payment', compact('payments'));
        }
        flash('آرشیو پرداخت برای این فروشنده یافت نشد')->warning();
        return back();
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}


