<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceItem;
use App\Models\Borrow;
use App\Models\Maintenance;
use App\Models\Room;
use App\Models\RoomBorrow;
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

        // Thống kê phòng
        $totalRooms = Room::count();
        $totalRoomBorrows = RoomBorrow::where('status', 'approved')->count();
        $totalMaintenance = DeviceItem::where('status', 'maintenance')->count();

        // Tính hiệu suất sử dụng
        $totalAvailable = DeviceItem::where('status', 'available')->count();
        $totalInUse = DeviceItem::where('status', 'borrowed')->count();
        $usageEfficiency = $totalDevices > 0 ? round(($totalInUse / $totalDevices) * 100) : 0;

        // Thống kê trạng thái thiết bị
        $deviceStatusStats = [
            'available' => DeviceItem::where('status', 'available')->count(),
            'borrowed' => $totalBorrowed,
            'damaged' => $totalDamaged,
            'maintenance' => $totalMaintenance
        ];

        // Thống kê mượn trả theo tháng
        $borrowStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $borrowStats[$i] = Borrow::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        // Thống kê mượn phòng theo tháng
        $roomBorrowStats = [];
        for ($i = 1; $i <= 12; $i++) {
            $roomBorrowStats[$i] = RoomBorrow::whereMonth('created_at', $i)
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

        // Danh sách phòng đang mượn
        $borrowedRooms = RoomBorrow::with(['room', 'user'])
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get();

        // Danh sách thiết bị đang bảo trì
        $maintenanceDevices = DeviceItem::with(['device', 'maintenance'])
            ->where('status', 'maintenance')
            ->latest()
            ->take(5)
            ->get();

        // Thống kê chi phí bảo trì
        $maintenanceCosts = [];
        for ($i = 1; $i <= 12; $i++) {
            $maintenanceCosts[$i] = Maintenance::whereMonth('start_date', $i)
                ->whereYear('start_date', now()->year)
                ->sum('cost');
        }

        return view('admin.dashboard', compact(
            'totalDevices',
            'totalBorrows',
            'totalDamaged',
            'totalBorrowed',
            'totalRooms',
            'totalRoomBorrows',
            'totalMaintenance',
            'usageEfficiency',
            'deviceStatusStats',
            'borrowStats',
            'roomBorrowStats',
            'damagedDevices',
            'borrowedDevices',
            'borrowedRooms',
            'maintenanceDevices',
            'maintenanceCosts'
        ));
    }
}
