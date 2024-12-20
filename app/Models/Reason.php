<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    public $table = 'reasons';

    protected $fillable = [
        'name', 'description',
    ];

    
    public function reports()
    {
        return $this->hasMany(Report::class);
    }    
  
}
