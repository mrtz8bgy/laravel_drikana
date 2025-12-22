@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{ \App\Seller::find($payments->first()->seller_id)->user->name }} ({{ \App\Seller::find($payments->first()->seller_id)->user->shop->name }})</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{ __('Payment Method') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $key => $payment)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>
                            <input type="text" class="disabled-persian-date" id="order-date" value="{{ $payment->created_at }}">
							<input type="hidden" id="order-date-gregorian" value="{{ $payment->created_at }}">
                        </td>
                        <td>
                            {{ single_price($payment->amount) }}
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }} @if ($payment->txn_code != null) (TRX ID : {{ $payment->txn_code }}) @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
