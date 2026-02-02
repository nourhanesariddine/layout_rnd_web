<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyToken extends Model
{
    protected $fillable = [
        'token',
        'tokenable_id',
        'tokenable_type',
        'expires_at',
        'used',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Get the parent tokenable model (polymorphic relation).
     */
    public function tokenable()
    {
        return $this->morphTo();
    }

    /**
     * Check if token is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if token is valid (not used and not expired).
     */
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    /**
     * Mark token as used.
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
        ]);
    }
}
