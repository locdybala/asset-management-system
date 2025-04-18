<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceItem;

class DeviceItemController extends Controller
{
    public function index(Request $request, $device_id)
    {
        $deviceItems = DeviceItem::where('device_id', $device_id)
            ->where('status', 'available')
            ->get();

        return view('admin.device-items.list', compact('deviceItems'));
    }

    public function store(Request $request)
    {
        foreach ($request->items as $item) {
            DeviceItem::create([
                'device_id' => $request->device_id,
                'code' => $item['code'],
                'status' => $item['status'],
            ]);
        }

        return redirect()->back()->with('success', 'Thêm thiết bị con thành công!');
    }

    public function update(Request $request, $id)
    {
        $item = DeviceItem::findOrFail($id);
        $item->update([
            'code' => $request->code,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thiết bị con thành công!');
    }

    public function destroy($id)
    {
        $item = DeviceItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Xoá thiết bị con thành công!');
    }

    public function getDeviceItems($device_id)
    {
        $deviceItems = DeviceItem::where('device_id', $device_id)
            ->where('status', '!=', 'damaged')
            ->get();

        return response()->json([
            'device_items' => $deviceItems
        ]);
    }
}
