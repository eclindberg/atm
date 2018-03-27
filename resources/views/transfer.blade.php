@extends('layouts.app')

@section('content')
    <p>Transfer</p>
    {!! Form::open(['action' => 'TransfersController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('to_account', 'To Account ID')}}
            {{Form::select('to_account', $accounts, $id)}}
        </div>
        <div class="form-group">
            {{Form::label('amount', 'Amount')}}
            {{Form::text('amount')}}
        </div>
        <div class="form-group">
            {{Form::label('from_account', 'From Account ID')}}
            {{Form::select('from_account', $accounts)}}
        </div>

        {{Form::submit('Submit', ['class'=>'btn'])}}
    {!! Form::close() !!}
@endsection