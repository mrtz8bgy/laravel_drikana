@extends('layouts.app')

@section('content')

    <h3 class="text-center">{{__('Activate OTP')}}</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">
                        ملی پیامک
                    </h3>
                </div>
                <div class="panel-body text-center">
                    <label class="switch">
                        <input type="checkbox" onchange="updateSettings(this, 'melipayamak')" @if(\App\OtpConfiguration::where('type', 'melipayamak')->first()->value == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">
                        فراز اس ام اس
                    </h3>
                </div>
                <div class="panel-body text-center">
                    <label class="switch">
                        <input type="checkbox" onchange="updateSettings(this, 'farazsms')" @if(\App\OtpConfiguration::where('type', 'farazsms')->first()->value == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function updateSettings(el, type){
            if($(el).is(':checked')){
                var value = 1;
            }
            else{
                var value = 0;
            }
            $.post('{{ route('otp_configurations.update.activation') }}', {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
                if(data == '1'){
                    showAlert('success', 'تنظیمات با موفقیت بروزرسانی شد.');
                }
                else{
                    showAlert('danger', 'خطای سیستمی رخ داده است.');
                }
            });
        }
    </script>
@endsection
