<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowDetail extends Model
{
    protected $fillable = ['borrow_id', 'device_item_id'];

    public function borrow() {
        return $this->belongsTo(Borrow::class);
    }

    public function deviceItem() {
        return $this->belongsTo(DeviceItem::class);
    }
}
