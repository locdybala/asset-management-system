<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\DeviceItem;
use App\Models\Borrow;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê thiết bị theo trạng thái
        $deviceStatusStats = DeviceItem::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Thống kê tần suất mượn trả theo tháng
        $borrowStats = Borrow::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Thống kê thiết bị hư hỏng
        $damagedDevices = DeviceItem::where('status', 'damaged')
            ->with('device')
            ->get();

        // Thống kê thiết bị đang mượn
        $borrowedDevices = DeviceItem::where('status', 'borrowed')
            ->with('device')
            ->get();

        // Tổng số thiết bị
        $totalDevices = DeviceItem::count();
        $totalBorrows = Borrow::count();
        $totalDamaged = count($damagedDevices);
        $totalBorrowed = count($borrowedDevices);

        return view('admin.dashboard', compact(
            'deviceStatusStats',
            'borrowStats',
            'damagedDevices',
            'borrowedDevices',
            'totalDevices',
            'totalBorrows',
            'totalDamaged',
            'totalBorrowed'
        ));
    }
}
