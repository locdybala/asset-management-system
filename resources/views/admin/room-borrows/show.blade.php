@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết phiếu mượn phòng</h4>
                    <span class="ml-1">#{{ $roomBorrow->id }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('room-borrows.index') }}" class="btn btn-secondary">
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
                                        <td><strong>Phòng</strong></td>
                                        <td>{{ $roomBorrow->room->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Người mượn</strong></td>
                                        <td>{{ $roomBorrow->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày mượn</strong></td>
                                        <td>{{ $roomBorrow->borrow_date->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày trả</strong></td>
                                        <td>{{ $roomBorrow->return_date->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lý do</strong></td>
                                        <td>{{ $roomBorrow->reason }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Trạng thái</strong></td>
                                        <td>
                                            @if($roomBorrow->status == 'pending')
                                                <span class="badge badge-warning">Chờ duyệt</span>
                                            @elseif($roomBorrow->status == 'approved')
                                                <span class="badge badge-success">Đã duyệt</span>
                                            @elseif($roomBorrow->status == 'returned')
                                                <span class="badge badge-info">Đã trả</span>
                                            @else
                                                <span class="badge badge-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($roomBorrow->staff)
                                    <tr>
                                        <td><strong>Người duyệt</strong></td>
                                        <td>{{ $roomBorrow->staff->name }}</td>
                                    </tr>
                                    @endif
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
                                    Thiết bị cố định ({{ $roomBorrow->room->fixedDeviceItems->count() }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mobile-devices">
                                    Thiết bị di động ({{ $roomBorrow->room->mobileDeviceItems->count() }})
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
                                            @forelse($roomBorrow->room->fixedDeviceItems as $device)
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
                                            @forelse($roomBorrow->room->mobileDeviceItems as $device)
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
