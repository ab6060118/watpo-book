<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prize extends Model {
    use SoftDeletes;
    protected $auditEvents = [
        'deleted',
    ];

    protected $hidden = [
        'deleted_at',
    ];
    public function activity() {
        return $this->hasOne(Activity::class, 'id', 'activity_id');
    }
}
