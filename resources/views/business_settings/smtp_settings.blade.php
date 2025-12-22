@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title text-center">{{__('SMTP Settings')}}</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="{{ route('env_key_update.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_DRIVER">
                        <div class="col-lg-3">
                            <label class="control-label">{{__('MAIL DRIVER')}}</label>
                        </div>
                        <div class="col-lg-6">
                            <select class="demo-select2" name="MAIL_DRIVER" onchange="checkMailDriver()">
                                <option value="sendmail" @if (env('MAIL_DRIVER') == "sendmail") selected @endif>Sendmail</option>
                                <option value="smtp" @if (env('MAIL_DRIVER') == "smtp") selected @endif>SMTP</option>
                                <option value="mailgun" @if (env('MAIL_DRIVER') == "mailgun") selected @endif>Mailgun</option>
                            </select>
                        </div>
                    </div>
                    <div id="smtp">
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_HOST">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAIL HOST')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAIL_HOST" value="{{  env('MAIL_HOST') }}" placeholder="MAIL HOST">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_PORT">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAIL PORT')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAIL_PORT" value="{{  env('MAIL_PORT') }}" placeholder="MAIL PORT">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_USERNAME">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAIL USERNAME')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAIL_USERNAME" value="{{  env('MAIL_USERNAME') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_PASSWORD">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAIL PASSWORD')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAIL_PASSWORD" value="{{  env('MAIL_PASSWORD') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_ENCRYPTION">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAIL ENCRYPTION')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAIL_ENCRYPTION" value="{{  env('MAIL_ENCRYPTION') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_FROM_ADDRESS">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAIL FROM ADDRESS')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAIL_FROM_ADDRESS" value="{{  env('MAIL_FROM_ADDRESS') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_FROM_NAME">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAIL FROM NAME')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAIL_FROM_NAME" value="{{  env('MAIL_FROM_NAME') }}">
                            </div>
                        </div>
                    </div>
                    <div id="mailgun">
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAILGUN_DOMAIN">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAILGUN DOMAIN')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAILGUN_DOMAIN" value="{{  env('MAILGUN_DOMAIN') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAILGUN_SECRET">
                            <div class="col-lg-3">
                                <label class="control-label">{{__('MAILGUN SECRET')}}</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="MAILGUN_SECRET" value="{{  env('MAILGUN_SECRET') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-purple" type="submit">{{__('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel bg-gray-light">
            <div class="panel-body">
                <h4>{{__('دستورالعمل های لازم')}}</h4>
                <p class="text-danger">{{ __('لطفا هنگام تنظیم SMTP مراقب باشید. برای پیکربندی نادرست در زمان سفارش ، ثبت نام جدید ، ارسال خبرنامه ، خطایی دریافت خواهید کرد.') }}</p>
                <hr>
                <p>{{ __('Non-SSL') }}</p>
                <ul class="list-group">
                    <li class="list-group-item text-dark">اگر بعد از پیکربندی "smtp" به عنوان درایور ایمیل ، با هر مشکلی روبرو هستید ، "sendmail" را برای درایور پست الکترونیکی انتخاب کنید </li>
                    <li class="list-group-item text-dark">پورت ایمیل را 587 تنظیم کنید</li>
                    <li class="list-group-item text-dark">اگر با مشکل "tls" روبرو شدید ، رمزنگاری نامه را به عنوان ssl تنظیم کنید</li>
                </ul>
                <p>{{ __('SSL') }}</p>
                <ul class="list-group mar-no">
                <li class="list-group-item text-dark">اگر بعد از پیکربندی "smtp" به عنوان درایور ایمیل ، با هر مشکلی روبرو هستید ، "sendmail" را برای درایور پست الکترونیکی انتخاب کنید </li>
                    <li class="list-group-item text-dark">پورت ایمیل را 465 تنظیم کنید</li>
                    <li class="list-group-item text-dark">رمزنگاری نامه را به عنوان ssl تنظیم کنید</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

    <script type="text/javascript">
        $(document).ready(function(){
            checkMailDriver();
        });
        function checkMailDriver(){
            if($('select[name=MAIL_DRIVER]').val() == 'mailgun'){
                $('#mailgun').show();
                $('#smtp').hide();
            }
            else{
                $('#mailgun').hide();
                $('#smtp').show();
            }
        }
    </script>

@endsection
