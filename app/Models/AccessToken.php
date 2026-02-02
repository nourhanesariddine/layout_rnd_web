<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AccessToken extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'token',
        'tokenable_id',
        'tokenable_type',
     
    
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
     
    ];

    /**
     * Get the parent tokenable model (polymorphic relation).
     */
    public function tokenable()
    {
        return $this->morphTo();
    }

  
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

 
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

   
  
}
