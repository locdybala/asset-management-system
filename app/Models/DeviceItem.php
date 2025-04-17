<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceItem extends Model
{
    protected $guarded = [];

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
}
