<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\Users', 'user_accounts');
    }

    public function transactions()
    {
        $transactions = $this->hasMany('App\Transact', 'transacts');      
        return $transactions;
    }

    // TODO: use ORM Pagination

    public function getTransactions() {
        $transactionRecords = DB::table('transacts')
                    ->where('account_id', $this->id)
                    ->orWhere('from_account_id', $this->id)
                    ->get();
        return $this->getWritableTransactions($transactionRecords);
    }

    private function getWritableTransactions($transactions) {
        $writableTransactions = array();
        
        foreach($transactions as $transaction) {
         
            $transaction->type = 'Deposit';
            
            if ($transaction->account_id == $this->id) {
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

            $writableTransactions[] = $transaction;    
        } 
        return $writableTransactions;
    }

}
