@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết phòng</h4>
                    <span class="ml-1">{{ $room->name }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning mr-2">
                    <i class="fa fa-edit"></i> Sửa
                </a>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-primary mb-4">Thông tin cơ bản</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td><strong>Tên phòng</strong></td>
                                        <td>{{ $room->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mã phòng</strong></td>
                                        <td>{{ $room->code }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Khoa</strong></td>
                                        <td>{{ $room->department->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Trạng thái</strong></td>
                                        <td>
                                            @if($room->status == 'available')
                                                <span class="badge badge-success">Có sẵn</span>
                                            @else
                                                <span class="badge badge-danger">Đang sử dụng</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mô tả</strong></td>
                                        <td>{{ $room->description ?? 'Không có mô tả' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thiết bị trong phòng</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#fixed-devices">
                                    Thiết bị cố định ({{ $room->fixedDeviceItems->count() }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mobile-devices">
                                    Thiết bị di động ({{ $room->mobileDeviceItems->count() }})
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="fixed-devices">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mã thiết bị</th>
                                                <th>Tên thiết bị</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($room->fixedDeviceItems as $device)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $device->code }}</td>
                                                <td>{{ $device->device->name }}</td>
                                                <td>
                                                    @if($device->status == 'available')
                                                        <span class="badge badge-success">Có sẵn</span>
                                                    @elseif($device->status == 'borrowed')
                                                        <span class="badge badge-info">Đang mượn</span>
                                                    @elseif($device->status == 'maintenance')
                                                        <span class="badge badge-warning">Đang bảo trì</span>
                                                    @else
                                                        <span class="badge badge-danger">Hỏng</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Không có thiết bị cố định</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="mobile-devices">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mã thiết bị</th>
                                                <th>Tên thiết bị</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($room->mobileDeviceItems as $device)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $device->code }}</td>
                                                <td>{{ $device->device->name }}</td>
                                                <td>
                                                    @if($device->status == 'available')
                                                        <span class="badge badge-success">Có sẵn</span>
                                                    @elseif($device->status == 'borrowed')
                                                        <span class="badge badge-info">Đang mượn</span>
                                                    @elseif($device->status == 'maintenance')
                                                        <span class="badge badge-warning">Đang bảo trì</span>
                                                    @else
                                                        <span class="badge badge-danger">Hỏng</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Không có thiết bị di động</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
