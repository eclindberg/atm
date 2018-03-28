<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();
        try {

            $new_balance = $fromAccount->balance - $request->amount;
            $this->updateAccountBalance($fromAccount, $new_balance);
            
            $toAccount = Account::find($request->to_account);
            $this->updateAccountBalance($toAccount, $toAccount->balance + $request->amount);            
            
            $deposit = new Transact();
            $deposit->amount = $request->amount;
            $deposit->account_id = $request->to_account;
            $deposit->from_account_id = $request->from_account;
            $deposit->user_id = auth()->user()->id;
            $deposit->save();
            
            DB::commit();
        
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect("/account/$request->account")->with('error', 'Transfer Failed. ' . $e->getMessage());
        }
        return redirect("/account/$request->to_account")->with('success', 'Transfer Successful');
    }

    function updateAccountBalance($account, $balance) {
        
        $affected = DB::update(
            "update accounts set balance = $balance where id = ? and balance = ?", [$account->id, $account->balance]);

        if ($affected == 0) {
            throw new \Exception('Could not update account balance for account ' . $account->id . '.');    
        }
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
