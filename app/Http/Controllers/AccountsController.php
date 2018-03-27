<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Transact;

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
}
