@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{$account->type}} Account #{{$account->id}}  Balance: {{$account->balance}}</h4>
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
                            <td>Type</td>
                            <td>Date</td>
                            <td align="right">Amount</td>
                            <td align="right">Transfer Account ID</td>
                        </tr>
                        @foreach($transactions as $transaction)
                            <?php

                            $transaction->type = 'Deposit';
                                                                    
                            if ($transaction->account_id == $account->id) {
                                $transaction->transfer_account_id = $transaction->from_account_id;
                                if ($transaction->amount < 0) {
                                    $transaction->type = 'Withdrawal';
                                    $transaction->amount = number_format($transaction->amount, 2);
                                }
                            } else {
                                $transaction->transfer_account_id = $transaction->account_id;
                                if ($transaction->amount > 0) {
                                    $transaction->type = 'Withdrawal';
                                    $transaction->amount = number_format($transaction->amount *-1, 2);
                                }
                            }

                            $createdDate = new DateTime($transaction->created_at);

                            ?>
                            <tr>
                                <td>{{$transaction->type}}</td>
                                <td>{{$createdDate->format("M-d-Y g:i a")}}</td>
                                <td align="right">{{$transaction->amount}}</td>
                                <td align="right">{{$transaction->transfer_account_id}}</td>
                            </tr>
                        @endforeach
                    </table>
                    {{$transactions->links()}}
                    @else
                        <p>account has no transactions</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
