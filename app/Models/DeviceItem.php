<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'code',
        'name',
        'status',
        'is_damaged',
        'description'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function borrowDetails() {
        return $this->hasMany(BorrowDetail::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
