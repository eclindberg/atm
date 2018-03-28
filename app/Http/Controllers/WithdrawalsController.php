<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\Account;
use App\Transact;


class WithdrawalsController extends Controller
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
        
        return view('withdrawal')->with(['accounts'=>$accounts, 'id'=>$id]);
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
            'account' => 'required'
        ]);

        $account = Account::find($request->account);

        $this->validate($request, [
            'amount' => "required|numeric|min:.01|max:$account->balance"
        ]);

        DB::beginTransaction();
        try {

            $new_balance = $account->balance - $request->amount;
            $affected = DB::update(
                "update accounts set balance = $new_balance where id = ? and balance = ?", [$account->id, $account->balance]);

            if ($affected == 0) {
                throw new \Exception('Could not update account balance.');
            }

            $withdrawal = new Transact();
            $withdrawal->amount = $request->amount *-1;
            $withdrawal->account_id = $request->account;
            $withdrawal->user_id = auth()->user()->id;
            $withdrawal->save();

            DB::commit();
        
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect("/account/$request->account")->with('error', 'Withdrawal Failed. ' . $e->getMessage());
        }

        return redirect("/account/$request->account")->with('success', 'Withdrawal Successful');
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
