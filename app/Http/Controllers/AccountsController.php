<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Account;
use App\Transact;
use App\UserAccount;

class AccountsController extends Controller
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


    public function index($id) {

        $user_id = auth()->user()->id;
        $account = Account::find($id);
        if ($account == null) {
            return redirect("/home")->with('error', "Account $id not found.");
        }
        
        $transactions = $account->getTransactions();
        return view('account', ['account' => $account, 'transactions' => $transactions]);
        //return view('account')->with('transactions', $account->transactions);
    }


    public function store(Request $request) {
        
        $this->validate($request, ['type' => 'required']);

        DB::beginTransaction();
        try {

            $account = new Account();
            $account->type = $request->type;
            $account->balance = 0;
            $account->save();

            $user_account = new UserAccount();
            $user_account->user_id = auth()->user()->id;
            $user_account->account_id = $account->id;
            $user_account->save();

            DB::commit();
        
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect('/home')->with('error', 'Account creation failed. ' . $e->getMessage());
        }

        return redirect('/home')->with('success', 'Account creation successful.');
    }


}
