@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>{{ isset($device) ? 'Chỉnh sửa thiết bị' : 'Thêm thiết bị mới' }}</h4>
                    <span class="ml-1">Quản lý thiết bị</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($device) ? 'Chỉnh sửa thiết bị' : 'Thêm thiết bị' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ isset($device) ? route('devices.update', $device->id) : route('devices.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($device))
                                    @method('PUT')
                                @endif
                            
                                <div class="form-group">
                                    <label class="text-label">Tên thiết bị *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Nhập tên thiết bị"
                                        value="{{ old('name', $device->name ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Danh mục *</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ (isset($device) && $device->category_id == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Mô tả</label>
                                    <textarea class="form-control" name="description" placeholder="Mô tả thiết bị">{{ old('description', $device->description ?? '') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Ảnh thiết bị</label>
                                    <input type="file" class="form-control" name="image">
                                </div>

                                <div class="form-group">
                                    <label for="borrower_type">Kiểu người mượn</label>
                                    <select name="borrower_type" class="form-control" id="borrower_type" required>
                                        <option value="both" {{ (isset($device) && $device->borrower_type == 'both') ? 'selected' : '' }}>Cả sinh viên và giảng viên</option>
                                        <option value="student" {{ (isset($device) && $device->borrower_type == 'student') ? 'selected' : '' }}>Sinh viên</option>
                                        <option value="teacher" {{ (isset($device) && $device->borrower_type == 'teacher') ? 'selected' : '' }}>Giảng viên</option>
                                    </select>
                                </div>

                                <!-- Thông tin chi tiết thiết bị -->
                                <div class="device-items-container">
                                    <h3>Chi tiết thiết bị</h3>
                                    <div class="device-item">
                                        <div class="form-group">
                                            <label for="device_items[0][code]">Mã thiết bị</label>
                                            <input type="text" class="form-control" id="device_items[0][code]" name="device_items[0][code]" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="device_items[0][status]">Trạng thái</label>
                                            <select class="form-control" id="device_items[0][status]" name="device_items[0][status]" required>
                                                <option value="available">Có sẵn</option>
                                                <option value="borrowed">Đã mượn</option>
                                                <option value="damaged">Hỏng</option>
                                                <option value="maintenance">Bảo trì</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Nút thêm chi tiết thiết bị -->
                                    <button type="button" class="btn btn-info add-device-item">Thêm chi tiết thiết bị</button>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ isset($device) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script>
    let deviceItemIndex = 1;

    // Thêm chi tiết thiết bị
    document.querySelector('.add-device-item').addEventListener('click', function() {
        const container = document.querySelector('.device-items-container');
        const newItem = document.createElement('div');
        newItem.classList.add('device-item');
        newItem.innerHTML = `
            <div class="form-group">
                <label for="device_items[${deviceItemIndex}][code]">Mã thiết bị</label>
                <input type="text" class="form-control" id="device_items[${deviceItemIndex}][code]" name="device_items[${deviceItemIndex}][code]" required>
            </div>
            <div class="form-group">
                <label for="device_items[${deviceItemIndex}][status]">Trạng thái</label>
                <select class="form-control" id="device_items[${deviceItemIndex}][status]" name="device_items[${deviceItemIndex}][status]" required>
                    <option value="available">Có sẵn</option>
                    <option value="borrowed">Đã mượn</option>
                    <option value="damaged">Hỏng</option>
                    <option value="maintenance">Bảo trì</option>
                </select>
            </div>
        `;
        container.appendChild(newItem);
        deviceItemIndex++;
    });
</script>
@endsection
