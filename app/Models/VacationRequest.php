<?php

namespace App\App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationRequest extends Model
{
    protected $fillable = [
        'user_id', 
        'start_date', 
        'end_date', 
        'days', 
        'reason', 
        'status', 
        'observations', 
        'action_by'
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}