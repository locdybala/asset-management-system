<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BorrowDetail;
class Borrow extends Model
{
    protected $fillable = ['user_id', 'staff_id', 'borrow_date', 'return_date', 'status'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff() {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function details() {
        return $this->hasMany(BorrowDetail::class);
    }
}
