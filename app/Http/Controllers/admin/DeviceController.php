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
        $devices = Device::with([
            'category.unit',
            'deviceItems.borrowDetails.borrow',
            'deviceItems.maintenances'
        ])->get();
        return view('admin.devices.index', compact('devices'));
    }

    public function show($id)
    {
        $device = Device::with(['category.unit', 'deviceItems.borrowDetails.borrow', 'deviceItems.maintenances'])->findOrFail($id);
        $device_parts = $device->deviceItems;  // Sửa từ items thành deviceItems

        return view('admin.devices.show', compact('device', 'device_parts'));
    }

    public function edit($id)
    {
        $device = Device::findOrFail($id);
        $categories = Category::all(); // nếu có dùng danh mục
        return view('admin.devices.edit', compact('device', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'borrower_type' => 'in:both,student,teacher',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Tên thiết bị là bắt buộc.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận ảnh định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        $device->name = $request->name;
        $device->category_id = $request->category_id;
        $device->borrower_type = $request->borrower_type;
        $device->description = $request->description;


        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($device->image && file_exists(public_path('storage/' . $device->image))) {
                unlink(public_path('storage/' . $device->image));
            }

            $imagePath = $request->file('image')->store('devices', 'public');
            $device->image = $imagePath;
        }

        $device->save();

        return redirect()->route('devices.index')->with('success', 'Cập nhật thiết bị thành công.');
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
            'name' => 'required|string|max:255',
            'borrower_type' => 'in:both,student,teacher',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Tên thiết bị là bắt buộc.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận ảnh định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        $device = new Device($request->except('image'));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('devices', 'public');
            $device->image = $path;
        }

        $device->save();

        return redirect()->route('devices.index')->with('success', 'Thêm thiết bị thành công!');
    }

    public function destroy($id)
    {
        try {
            $device = Device::findOrFail($id);

            // Nếu có thiết bị con liên quan thì bạn có thể xử lý xóa kèm ở đây nếu cần
            $device->delete();

            return response()->json([
                'success' => true,
                'message' => 'Thiết bị đã được xóa thành công.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi xóa thiết bị.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
