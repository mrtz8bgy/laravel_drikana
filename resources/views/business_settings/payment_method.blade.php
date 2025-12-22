@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title text-center">{{__('تنظیمات درگاه پرداخت زرین پال')}}</h3>
            </div>
            <div class="panel-body text-center">
                <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="ZARINPAL_MERCHANT_CODE">
                        <div class="col-lg-3">
                            <label class="control-label">کد درگاه پرداخت</label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="ZARINPAL_MERCHANT_CODE" value="{{  env('ZARINPAL_MERCHANT_CODE') }}" placeholder="کد درگاه پرداخت" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-center">
                            <button class="btn btn-purple" type="submit">ذخیره</button>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div>
</div>    

@endsection
