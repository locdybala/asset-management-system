<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Device;
use App\Models\DeviceItem;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        // Hiển thị danh sách thiết bị
        $devices = Device::with('category')->get(); // Nếu có quan hệ với danh mục
        return view('admin.devices.index', compact('devices'));
    }

    public function show($id)
    {
        $device = Device::findOrFail($id);
        $device_parts = $device->parts;  // Lấy tất cả các thiết bị con của thiết bị này

        return view('admin.devices.show', compact('device', 'device_parts'));
    }

     // Hiển thị form thêm thiết bị và chi tiết thiết bị
     public function create()
     {
         $categories = Category::all(); // Lấy danh mục thiết bị
         return view('admin.devices.create', compact('categories'));
     }
 
     // Lưu thiết bị và chi tiết thiết bị
     public function store(Request $request)
     {
         $request->validate([
             'name' => 'required',
             'category_id' => 'required',
             'borrower_type' => 'required',
             'device_items' => 'required|array',
             'device_items.*.code' => 'required|unique:device_items,code', // Kiểm tra mã thiết bị duy nhất
             'device_items.*.status' => 'required', // Kiểm tra trạng thái
         ]);
 
         // Lưu thiết bị
         $device = Device::create([
             'name' => $request->name,
             'category_id' => $request->category_id,
             'description' => $request->description,
             'image' => $request->file('image') ? $request->file('image')->store('devices') : null,
             'borrower_type' => $request->borrower_type,
         ]);
 
         // Lưu các chi tiết thiết bị
         foreach ($request->device_items as $item) {
             DeviceItem::create([
                 'device_id' => $device->id,
                 'code' => $item['code'],
                 'status' => $item['status'],
             ]);
         }
 
         return redirect()->route('devices.index')->with('success', 'Thiết bị và chi tiết thiết bị đã được thêm mới thành công!');
     }
}
