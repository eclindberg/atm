@extends('layouts.app')

@section('content')
    <p>Withdrawal</p>
    {!! Form::open(['action' => 'WithdrawalsController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('account', 'Account ID')}}
            {{Form::select('account', $accounts, $id)}}
        </div>
        <div class="form-group">
            {{Form::label('amount', 'Amount')}}
            {{Form::text('amount')}}
        </div>

        {{Form::submit('Submit', ['class'=>'btn'])}}
    {!! Form::close() !!}
@endsection