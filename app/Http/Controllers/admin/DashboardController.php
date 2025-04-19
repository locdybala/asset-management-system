<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceItem;
use App\Models\Borrow;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê cơ bản
        $totalDevices = Device::count();
        $totalBorrows = Borrow::count();
        $totalDamaged = DeviceItem::where('status', 'damaged')->count();
        $totalBorrowed = DeviceItem::where('status', 'borrowed')->count();

        // Thống kê trạng thái thiết bị
        $deviceStatusStats = [
            'available' => DeviceItem::where('status', 'available')->count(),
            'borrowed' => $totalBorrowed,
            'damaged' => $totalDamaged,
            'maintenance' => DeviceItem::where('status', 'maintenance')->count()
        ];

        // Thống kê mượn trả theo tháng
        $borrowStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $borrowStats[$i] = Borrow::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        // Danh sách thiết bị hư hỏng
        $damagedDevices = DeviceItem::with('device')
            ->where('status', 'damaged')
            ->latest()
            ->take(5)
            ->get();

        // Danh sách thiết bị đang mượn
        $borrowedDevices = DeviceItem::with('device')
            ->where('status', 'borrowed')
            ->latest()
            ->take(5)
            ->get();

        // Thống kê chi phí bảo trì
        $maintenanceCosts = Maintenance::selectRaw('YEAR(start_date) as year, MONTH(start_date) as month, SUM(cost) as total_cost')
            ->whereYear('start_date', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Thống kê thiết bị theo danh mục
        $deviceByCategory = Device::with('category')
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->get();

        // Thống kê mượn trả theo khoa/phòng
        $borrowsByDepartment = Borrow::with('user.department')
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->get()
            ->groupBy('user.department.name');

        return view('admin.dashboard', compact(
            'totalDevices',
            'totalBorrows',
            'totalDamaged',
            'totalBorrowed',
            'deviceStatusStats',
            'borrowStats',
            'damagedDevices',
            'borrowedDevices',
            'maintenanceCosts',
            'deviceByCategory',
            'borrowsByDepartment'
        ));
    }
}
