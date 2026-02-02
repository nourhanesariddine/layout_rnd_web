<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];

    /**
     * The contacts that belong to the department.
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_department')
            ->withTimestamps();
    }
}
