<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    public $table = 'campaigns';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'budget',
        'status',
        'created_by',
        'updated_by',
    ];

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }
}
