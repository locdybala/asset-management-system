@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <!-- Tiêu đề -->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết thiết bị</h4>
                    <span class="ml-1">{{ $device->name }}</span>
                </div>
            </div>
        </div>

        <!-- Thông tin thiết bị chính -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    @if($device->image)
                        <img class="card-img-top img-fluid" src="{{ asset('uploads/device_images/' . $device->image) }}" alt="Ảnh thiết bị">
                    @else
                        <img class="card-img-top img-fluid" src="{{ asset('images/no-image.png') }}" alt="Không có ảnh">
                    @endif
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card p-4">
                    <h5><strong>Tên thiết bị:</strong> {{ $device->name }}</h5>
                    <p><strong>Danh mục:</strong> {{ $device->category->name ?? 'Không xác định' }}</p>
                    <p><strong>Mô tả:</strong> {!! nl2br(e($device->description)) ?? 'Không có mô tả' !!}</p>
                </div>
            </div>
        </div>

        <!-- Danh sách thiết bị con -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách các thiết bị con</h4>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDeviceItemModal">
                            Thêm thiết bị con
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Mã thiết bị</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($device_parts as $key => $part)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $part->code }}</td>
                                            <td>
                                                @switch($part->status)
                                                    @case('available')
                                                        <span class="badge badge-success">Có sẵn</span>
                                                        @break
                                                    @case('borrowed')
                                                        <span class="badge badge-warning">Đang mượn</span>
                                                        @break
                                                    @case('damaged')
                                                        <span class="badge badge-danger">Hỏng</span>
                                                        @break
                                                    @case('maintenance')
                                                        <span class="badge badge-info">Bảo trì</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $part->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <!-- Nút sửa -->
                                                <button class="btn btn-sm btn-primary edit-btn"
                                                        data-id="{{ $part->id }}"
                                                        data-code="{{ $part->code }}"
                                                        data-status="{{ $part->status }}"
                                                        data-toggle="modal"
                                                        data-target="#editDeviceItemModal">
                                                    Sửa
                                                </button>
                                    
                                                <!-- Nút xoá -->
                                                <form action="{{ route('device-items.destroy', $part->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xoá?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Xoá</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm thiết bị con -->
        <div class="modal fade" id="addDeviceItemModal" tabindex="-1" role="dialog" aria-labelledby="addDeviceItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('device-items.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="device_id" value="{{ $device->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm thiết bị con</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered" id="device-item-table">
                                <thead>
                                    <tr>
                                        <th>Mã thiết bị</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="items[0][code]" class="form-control" required></td>
                                        <td>
                                            <select name="items[0][status]" class="form-control">
                                                <option value="available">Có sẵn</option>
                                                <option value="borrowed">Đang mượn</option>
                                                <option value="damaged">Hỏng</option>
                                                <option value="maintenance">Bảo trì</option>
                                            </select>
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">+ Thêm dòng</button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Lưu thiết bị con</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Sửa thiết bị con -->
<div class="modal fade" id="editDeviceItemModal" tabindex="-1" role="dialog" aria-labelledby="editDeviceItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editDeviceItemForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật thiết bị con</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mã thiết bị</label>
                        <input type="text" name="code" class="form-control" id="edit-code" required>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control" id="edit-status">
                            <option value="available">Có sẵn</option>
                            <option value="borrowed">Đang mượn</option>
                            <option value="damaged">Hỏng</option>
                            <option value="maintenance">Bảo trì</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>


    </div> <!-- end container-fluid -->
</div>
@endsection

@section('js')
<script>
    let index = 1;

    document.getElementById('addRow').addEventListener('click', function () {
        const tableBody = document.querySelector('#device-item-table tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td><input type="text" name="items[${index}][code]" class="form-control" required></td>
            <td>
                <select name="items[${index}][status]" class="form-control">
                    <option value="available">Có sẵn</option>
                    <option value="borrowed">Đang mượn</option>
                    <option value="damaged">Hỏng</option>
                    <option value="maintenance">Bảo trì</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
        `;
        tableBody.appendChild(newRow);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>

<script>
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const code = this.dataset.code;
            const status = this.dataset.status;

            document.getElementById('edit-code').value = code;
            document.getElementById('edit-status').value = status;
            document.getElementById('editDeviceItemForm').action = '/admin/device-items/' + id;
        });
    });
</script>
@endsection
