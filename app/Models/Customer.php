<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phonenumber',
        'userid',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'customerid', 'id');
    }
}
