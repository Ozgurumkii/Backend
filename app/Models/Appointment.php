<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userid',
        'customerid',
        'apartmentid',
        'startdate',
        'enddate',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerid');
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class, 'apartmentid');
    }
}
