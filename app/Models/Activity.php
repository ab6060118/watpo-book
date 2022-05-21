<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model {
    use SoftDeletes;
    protected $auditEvents = [
        'deleted',
    ];

    protected $hidden = [
        'deleted_at',
    ];
    public function prizes() {
        return $this->hasMany(Prize::class, 'activity_id', 'id');
    }

}
