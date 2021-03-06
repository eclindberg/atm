@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>Accounts</h4></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($accounts) > 0)    
                    <table class="table">
                        <tr>
                            <td>AccountID</td>
                            <td>Type</td>
                            <td align="right">Balance</td>
                            <td></td>
                        </tr>
                    @foreach($accounts as $account)
                        <tr>
                            <td>{{$account->id}}</a></td>
                            <td>{{$account->type}}</td>
                            <td align="right">{{$account->balance}}</td>
                            <td><a href="/account/{{$account->id}}">view</a></td>
                        </tr>
                    @endforeach

                    </table>
                    @else
                        <p>user has no accounts</p>
                    @endif
                </div>
                <div class="card-body">
                {!! Form::open(['action' => 'AccountsController@store', 'method' => 'POST']) !!}
                <div class="form-group">
                    {{Form::label('type', 'Add New Account: Type')}}
                    {{Form::select('type', ['Checking'=>'Checking','Saving'=>'Saving'])}}
                    {{Form::submit('Submit')}}
                </div>         
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
