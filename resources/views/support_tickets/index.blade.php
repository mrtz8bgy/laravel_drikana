@extends('layouts.app')

@section('content')

<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{__('Support Desk')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_support" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 300px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="شماره تیکت را وارد کرده و اینتر بزنید">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>{{ __('شناسه تیکت') }}</th>
                    <th>{{ __('Sending Date') }}</th>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Last reply') }}</th>
                    <th>{{ __('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($tickets as $key => $ticket)
                    @if ($ticket->user != null)
                        <tr>
                            <td>#{{ $ticket->code }}</td>
                            <td>
                                <input type="text" class="disabled-persian-date" id="order-date" value="{{ $ticket->created_at }}">
							    <input type="hidden" id="order-date-gregorian" value="{{ $ticket->created_at }}"> 
                                @if($ticket->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif
                            </td>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>
                                @if ($ticket->status == 'pending')
                                    <span class="badge badge-pill badge-danger">در انتظار پاسخ</span>
                                @elseif ($ticket->status == 'open')
                                    <span class="badge badge-pill badge-secondary">باز</span>
                                @else
                                    <span class="badge badge-pill badge-success">بسته</span>
                                @endif
                            </td>
                            <td>
                                @if (count($ticket->ticketreplies) > 0)
                                    <input type="text" class="disabled-persian-date order-date" value="{{ $ticket->ticketreplies->last()->created_at }}">
							        <input type="hidden" class="order-date-gregorian" value="{{ $ticket->ticketreplies->last()->created_at }}">
                                @else
                                    <input type="text" class="disabled-persian-date order-date" value="{{ $ticket->created_at }}">
							        <input type="hidden" class="order-date-gregorian" value="{{ $ticket->created_at }}">
                                @endif
                            </td>
                            <td>
                                <a href="{{route('support_ticket.admin_show', encrypt($ticket->id))}}" class="btn-link">{{__('مشاهده تیکت')}}</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $tickets->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
