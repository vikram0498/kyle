<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $table = 'reports';

    protected $fillable = ['conversation_id', 'reported_by', 'reason'];

    protected static function boot () 
    {
        parent::boot();
        static::creating(function(Report $model) {
            $model->reported_by = auth()->user()->id;
        });               
    }
    
    
    /**
     * Get the conversation that this report belongs to.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

   
    /**
     * Get the user who created the report.
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
