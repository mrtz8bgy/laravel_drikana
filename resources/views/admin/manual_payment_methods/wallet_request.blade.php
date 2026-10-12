@extends('layouts.app')

@section('content')

{{--
    درخواست‌های شارژ کیف پول (پرداخت آفلاین)
    ─────────────────────────────────────────────────────────────
    [رفع باگ — Stage 2] این ویو وجود نداشت در حالی که
    WalletController@offline_recharge_request آن را برمی‌گرداند.

    نکات امنیتی:
      • تصمیم تأیید/رد با متد POST و توکن CSRF ارسال می‌شود، نه با
        تصویر یا پرس‌وجوی GET که قابل جعل است.
      • مسیر فایل رسید از دیتابیس خوانده می‌شود، نه از ورودی کاربر.
--}}

<div class="row">
    <div class="col-sm-12">
        <h3>{{ __('Wallet Recharge Requests') }}</h3>
    </div>
</div>

<br>

@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button class="close" data-dismiss="alert" type="button">&times;</button>
        {{ session('success') }}
    </div>
@endif

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{ __('Offline Recharge Requests') }}</h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="18%">{{ __('User') }}</th>
                        <th width="12%">{{ __('Amount') }}</th>
                        <th width="15%">{{ __('Payment Method') }}</th>
                        <th width="15%">{{ __('Transaction ID') }}</th>
                        <th width="12%">{{ __('Receipt') }}</th>
                        <th width="10%">{{ __('Status') }}</th>
                        <th width="13%">{{ __('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wallets as $key => $wallet)
                        <tr>
                            <td>{{ $wallets->firstItem() + $key }}</td>
                            <td>
                                @php $user = \App\User::find($wallet->user_id); @endphp
                                {{ $user ? $user->name : '—' }}
                                @if($user)
                                    <br><small class="text-muted">{{ $user->email }}</small>
                                @endif
                            </td>
                            <td>{{ single_price($wallet->amount) }}</td>
                            <td><small>{{ $wallet->payment_method }}</small></td>
                            <td><small>{{ $wallet->payment_details }}</small></td>
                            <td>
                                @if($wallet->reciept)
                                    <a href="{{ asset($wallet->reciept) }}" target="_blank" rel="noopener">
                                        {{ __('View') }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($wallet->approval == 1)
                                    <span class="label label-success">{{ __('Approved') }}</span>
                                @elseif($wallet->approval == -1)
                                    <span class="label label-danger">{{ __('Rejected') }}</span>
                                @else
                                    <span class="label label-warning">{{ __('Pending') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($wallet->approval == 0)
                                    <form class="u-display-inline" action="{{ (Route::has('wallet_request.update_approved') ? route('wallet_request.update_approved') : '#') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $wallet->id }}">
                                        <input type="hidden" name="status" value="1">
                                        <button type="submit" class="btn btn-xs btn-success">
                                            {{ __('Approve') }}
                                        </button>
                                    </form>

                                    <form class="u-display-inline" action="{{ (Route::has('wallet_request.update_approved') ? route('wallet_request.update_approved') : '#') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $wallet->id }}">
                                        <input type="hidden" name="status" value="-1">
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            {{ __('Reject') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">{{ __('No data found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="text-center">
            {{ $wallets->links() }}
        </div>
    </div>
</div>

@endsection
