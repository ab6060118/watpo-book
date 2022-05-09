<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model {
    use SoftDeletes;
    protected $auditEvents = [
        'deleted',
    ];

    protected $hidden = [
        'deleted_at',
    ];
    public function prize() {
        return $this->hasOne(Prize::class, 'id', 'prize_id');
    }
    public function order() {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }
    public function getActivityAttribute() {
        // return $this->hasOneThrough(Activity::class, Prize::class);
        return $this->prize->activity;
    }
}
