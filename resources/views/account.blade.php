@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>
                        {{$account->type}} Account {{$account->id}} Balance: {{$account->balance}}
                    </h3>
                    <div>
                        <a href="/home">accounts</a>
                        <a href="/deposit/{{$account->id}}">deposit</a>
                        <a href="/withdrawal/{{$account->id}}">withdrawal</a>
                        <a href="/transfer/{{$account->id}}">transfer</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($transactions) > 0)
                    <table class="table">
                        <tr>
                            <td>TransactionID</td>
                            <td>Type</td>
                            <td align="right">Amount</td>
                            <td align="right">Transfer Account ID</td>
                        </tr>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{$transaction->id}}</td>
                                <td>{{$transaction->type}}</td>
                                <td align="right">{{$transaction->amount}}</td>
                                <td align="right">{{$transaction->transfer_account_id}}</td>
                            </tr>
                        @endforeach
                        <!-- use ORM for pagination $transaction->links -->
                    </table>
                    @else
                        <p>account has no transactions</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
