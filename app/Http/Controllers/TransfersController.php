<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Account;
use App\Transact;

class TransfersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=0)
    {
        $user = new User();
        $user->id = auth()->user()->id;
        $accounts = array();
        foreach($user->accounts as $account) {
            $accounts[$account->id] = $account->id;
        }
        
        //return view('transfer')->with('accounts', $accounts);
        return view('transfer')->with(['accounts'=>$accounts, 'id'=>$id]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'to_account' => 'required',
            'from_account' => 'required|different:to_account'
        ]);

        $fromAccount = Account::find($request->from_account);

        $this->validate($request, [
            'amount' => "required|numeric|min:.01|max:$fromAccount->balance"
        ]);

        $fromAccount->balance = $fromAccount->balance - $request->amount;
        $fromAccount->save();

        $toAccount = Account::find($request->to_account);
        $toAccount->balance = $toAccount->balance + $request->amount;
        $toAccount->save();
        
        $deposit = new Transact();
        $deposit->amount = $request->amount;
        $deposit->account_id = $request->to_account;
        $deposit->from_account_id = $request->from_account;
        $deposit->user_id = auth()->user()->id;
        $deposit->save();

        return redirect("/account/$request->to_account")->with('success', 'Transfer Successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->create($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
