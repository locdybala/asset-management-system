<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\DeviceItem;
use App\Models\Device;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with(['deviceItem.device', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.maintenances.index', compact('maintenances'));
    }

    public function create()
    {
        // Lấy danh sách thiết bị có ít nhất một thiết bị chi tiết có thể bảo trì
        $devices = Device::whereHas('deviceItems', function($query) {
            $query->where('status', 'available')
                ->whereDoesntHave('maintenances', function($q) {
                    $q->whereIn('status', ['pending', 'in_progress']);
                })
                ->whereDoesntHave('borrowDetails', function($q) {
                    $q->whereHas('borrow', function($q) {
                        $q->whereIn('status', ['pending', 'approved']);
                    });
                });
        })->get();

        return view('admin.maintenances.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_item_id' => 'required|exists:device_items,id',
            'type' => 'required|in:periodic,repair',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'required|string',
        ]);

        Maintenance::create([
            'device_item_id' => $request->device_item_id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'cost' => $request->cost,
            'description' => $request->description,
            'status' => 'pending',
            'created_by' => auth()->id()
        ]);

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã tạo yêu cầu bảo trì thành công!');
    }

    public function edit(Maintenance $maintenance)
    {
        $devices = Device::all();
        return view('admin.maintenances.edit', compact('maintenance', 'devices'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'device_item_id' => 'required|exists:device_items,id',
            'type' => 'required|in:periodic,repair',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'result' => 'nullable|string'
        ]);

        $maintenance->update($request->all());

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã cập nhật yêu cầu bảo trì thành công!');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã xóa yêu cầu bảo trì thành công!');
    }

    public function updateStatus(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'result' => 'nullable|string'
        ]);

        $maintenance->update([
            'status' => $request->status,
            'result' => $request->result,
            'end_date' => $request->status === 'completed' ? Carbon::now() : null
        ]);

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã cập nhật trạng thái bảo trì thành công!');
    }
}
