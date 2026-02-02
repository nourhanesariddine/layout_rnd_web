<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'birthdate',
        'city',
        'company',
        'job_title',
        'address',
        'state',
        'zip_code',
        'country',
        'notes',
    ];

    /**
     * The departments that belong to the contact.
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'contact_department')
            ->withTimestamps();
    }

    protected $casts = [
        'birthdate' => 'date',
    ];
}
