@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Danh sách thiết bị</h4>
                    <span class="ml-1">Quản lý thiết bị trường học</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Thiết bị</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                </ol>
            </div>
        </div>

        <!-- Danh sách thiết bị -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách thiết bị</h4>
                        <a href="{{ route('devices.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Thêm thiết bị
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên thiết bị</th>
                                        <th>Danh mục</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devices as $key => $device)
                                        <tr>
                                            <td><strong>{{ $key + 1 }}</strong></td>
                                            <td>{{ $device->name }}</td>
                                            <td>{{ $device->category->name }}</td>
                                            <td>
                                                @if($device->status == 'available')
                                                    <span class="badge badge-success">Có sẵn</span>
                                                @elseif($device->status == 'maintenance')
                                                    <span class="badge badge-info">Bảo trì</span>
                                                @elseif($device->status == 'broken')
                                                    <span class="badge badge-danger">Hỏng</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-success">{{ $device->created_at->format('d/m/Y') }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('devices.show', $device->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i> Xem chi tiết
                                                </a>
                                                <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </a>
                                                <form action="{{ route('devices.destroy', $device->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa?')">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên thiết bị</th>
                                        <th>Danh mục</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- end .container-fluid -->
</div>
@endsection
