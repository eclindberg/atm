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

}
