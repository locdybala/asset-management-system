<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DeviceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'code',
        'name',
        'status',
        'is_damaged',
        'description',
        'qr_code',
        'qr_token',
        'last_scanned_at'
    ];

    protected $casts = [
        'last_scanned_at' => 'datetime'
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

    public function qrScans()
    {
        return $this->hasMany(QrScan::class);
    }

    // Tạo QR code cho thiết bị
    public function generateQrCode()
    {
        // Tạo token ngẫu nhiên nếu chưa có
        if (!$this->qr_token) {
            $this->qr_token = Str::random(32);
            $this->save();
        }

        // Tạo URL cho QR code
        $url = route('device-items.scan', ['token' => $this->qr_token]);

        // Tạo QR code với Google Charts API
        $qrCodeUrl = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($url);
        
        // Tải QR code từ Google Charts API
        $response = Http::get($qrCodeUrl);
        if ($response->successful()) {
            // Lưu QR code vào storage
            $filename = 'qrcodes/' . $this->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $response->body());

            // Cập nhật đường dẫn QR code
            $this->qr_code = $filename;
            $this->save();

            return $this->qr_code;
        }

        return null;
    }

    // Ghi lại lịch sử quét
    public function logScan($action, $userId = null, $oldStatus = null, $newStatus = null, $notes = null)
    {
        $this->qrScans()->create([
            'user_id' => $userId,
            'action' => $action,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'notes' => $notes
        ]);

        $this->last_scanned_at = now();
        $this->save();
    }

    // Cập nhật trạng thái qua QR code
    public function updateStatusViaQr($newStatus, $userId = null, $notes = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        $this->logScan('update_status', $userId, $oldStatus, $newStatus, $notes);
    }

    // Kiểm tra token QR code có hợp lệ
    public function validateQrToken($token)
    {
        return $this->qr_token === $token;
    }
}
