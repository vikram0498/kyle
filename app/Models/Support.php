<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Support extends Model
{
    use SoftDeletes;

    public $table = 'support';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'contact_preferance',
        'message',
        'created_by',
    ];

    protected static function boot () 
    {
        parent::boot();
        // static::creating(function(support $model) {
        //     $model->created_by = auth()->user()->id;
        // });               
    } 
}
