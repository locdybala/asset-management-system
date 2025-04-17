<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\Device;
use App\Models\DeviceItem;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class BorrowController extends Controller
{
    public function index()
    {
        $borrows = Borrow::with('user', 'staff')->latest()->get();
        return view('admin.borrows.index', compact('borrows'));
    }

    public function create()
    {
        $devices = Device::all();
        $deviceItems = DeviceItem::where('status', 'available')->get();
        return view('admin.borrows.create', compact('deviceItems', 'devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrow_date' => 'required|date',
            'device_items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $borrow = Borrow::create([
                'user_id' => auth()->id(),
                'borrow_date' => $request->borrow_date,
                'status' => 'pending',
            ]);

            foreach ($request->device_items as $itemId) {
                BorrowDetail::create([
                    'borrow_id' => $borrow->id,
                    'device_item_id' => $itemId,
                ]);

                DeviceItem::where('id', $itemId)->update(['status' => 'borrowed']);
            }

            DB::commit();
            return redirect()->route('borrows.index')->with('success', 'Phiếu mượn đã được tạo.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrow->update([
            'status' => 'approved',
            'staff_id' => auth()->id(),
        ]);
        return back()->with('success', 'Phiếu mượn đã được duyệt.');
    }

    public function markReturned($id)
    {
        $borrow = Borrow::with('details')->findOrFail($id);
        $borrow->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        foreach ($borrow->details as $detail) {
            $detail->deviceItem->update(['status' => 'available']);
        }

        return back()->with('success', 'Phiếu mượn đã được đánh dấu là đã trả.');
    }

    public function getDeviceItems($device_id)
{
    $deviceItems = DeviceItem::where('device_id', $device_id)->where('status', 'available')->get();

    return response()->json([
        'device_items' => $deviceItems
    ]);
}

}

