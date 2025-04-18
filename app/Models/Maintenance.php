<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_item_id',
        'type',
        'start_date',
        'end_date',
        'cost',
        'description',
        'status',
        'result',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2'
    ];

    public function deviceItem()
    {
        return $this->belongsTo(DeviceItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'in_progress' => 'Đang bảo trì',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
            default => 'Không xác định'
        };
    }

    public function getTypeTextAttribute()
    {
        return match($this->type) {
            'periodic' => 'Bảo trì định kỳ',
            'repair' => 'Sửa chữa',
            default => 'Không xác định'
        };
    }
}
