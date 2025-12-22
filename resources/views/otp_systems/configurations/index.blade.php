@extends('layouts.app')

@section('content')

    <div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title text-center">
                    اطلاعات ملی پیامک
                </h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="{{ route('update_credentials') }}" method="POST">
                    <input type="hidden" name="otp_method" value="melipayamak">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MELIPAYAMAK_USERNAME">
                        <div class="col-lg-3">
                            <label class="control-label">
                                نام کاربری
                            </label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MELIPAYAMAK_USERNAME" value="{{  env('MELIPAYAMAK_USERNAME') }}" placeholder="نام کاربری ملی پیامک" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MELIPAYAMAK_PASSWORD">
                        <div class="col-lg-3">
                            <label class="control-label">
                                رمز عبور
                            </label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MELIPAYAMAK_PASSWORD" value="{{  env('MELIPAYAMAK_PASSWORD') }}" placeholder="رمز عبور ملی پیامک" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MELIPAYAMAK_PATTERN_ID">
                        <div class="col-lg-3">
                            <label class="control-label">
                                شناسه پترن
                            </label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MELIPAYAMAK_PATTERN_ID" value="{{  env('MELIPAYAMAK_PATTERN_ID') }}" placeholder="شناسه پترن ملی پیامک" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-purple" type="submit">
                                ذخیره
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">
                        اطلاعات فراز اس ام اس
                    </h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ route('update_credentials') }}" method="POST">
                        <input type="hidden" name="otp_method" value="farazsms">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="FARAZSMS_USERNAME">
                            <div class="col-lg-3">
                                <label class="control-label">نام کاربری</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="FARAZSMS_USERNAME" value="{{  env('FARAZSMS_USERNAME') }}" placeholder="نام کاربری فراز اس‌ام‌اس" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="FARAZSMS_PASSWORD">
                            <div class="col-lg-3">
                                <label class="control-label">رمز عبور</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="FARAZSMS_PASSWORD" value="{{  env('FARAZSMS_PASSWORD') }}" placeholder="رمز عبور فراز اس‌ام‌اس" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="FARAZSMS_FROM">
                            <div class="col-lg-3">
                                <label class="control-label">شماره فرستنده</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="FARAZSMS_FROM" value="{{  env('FARAZSMS_FROM') }}" placeholder="شماره فرستنده فراز اس‌ام‌اس" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="FARAZSMS_PATTERN_ID">
                            <div class="col-lg-3">
                                <label class="control-label">شناسه پترن</label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="FARAZSMS_PATTERN_ID" value="{{  env('FARAZSMS_PATTERN_ID') }}" placeholder="شناسه پترن فراز اس‌ام‌اس" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-purple" type="submit">ذخیره</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

    </script>
@endsection
