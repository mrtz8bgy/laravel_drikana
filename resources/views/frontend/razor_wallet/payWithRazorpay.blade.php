@extends('frontend.layouts.app')

@section('content')

    <form class="u-display-none" action="{!!route('payment.rozer')!!}" method="POST" id='rozer-pay'>
        <!-- Note that the amount is in paise = 50 INR -->
        <!--amount need to be in paisa-->
        <script src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="{{ config('drikana.payments.razorpay.key') }}"
                data-amount={{Session::get('payment_data')['amount']*100}}
                data-buttontext=""
                data-name="{{ config('app.name') }}"
                data-description="Wallet Payment"
                data-image="{{ asset(\App\GeneralSetting::first()->logo) }}"
                data-prefill.name= {{ Auth::user()->name}}
                data-prefill.email= {{ Auth::user()->email}}
                data-theme.color="#ff7529">
        </script>
        <input type="hidden" name="_token" value="{!!csrf_token()!!}">
    </form>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#rozer-pay').submit()
        });
    </script>
@endsection
