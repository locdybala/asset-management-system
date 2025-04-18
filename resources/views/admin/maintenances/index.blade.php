@extends('layouts.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            @extends('admin.components.message')

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Quản lý bảo trì thiết bị</h4>
                        <span class="ml-1">Quản lý yêu cầu bảo trì & sửa chữa thiết bị</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách yêu cầu bảo trì</h4>
                    <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Tạo yêu cầu bảo trì
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover" style="min-width: 845px">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th width="10%">Mã thiết bị</th>
                                    <th width="15%">Tên thiết bị</th>
                                    <th width="10%">Loại bảo trì</th>
                                    <th width="12%">Ngày bắt đầu</th>
                                    <th width="12%">Ngày kết thúc</th>
                                    <th width="10%">Chi phí</th>
                                    <th width="12%">Trạng thái</th>
                                    <th width="10%">Người tạo</th>
                                    <th class="text-center" width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenances as $key => $maintenance)
                                    <tr class="border-bottom">
                                        <td class="text-center align-middle">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="mr-2">{{ $key + 1 }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $maintenance->deviceItem->code }}</td>
                                        <td class="align-middle">{{ $maintenance->deviceItem->device->name }}</td>
                                        <td class="align-middle">
                                            @if($maintenance->type === 'periodic')
                                                <span class="badge badge-info">Định kỳ</span>
                                            @else
                                                <span class="badge badge-warning">Sửa chữa</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $maintenance->start_date->format('d/m/Y') }}</td>
                                        <td class="align-middle">{{ $maintenance->end_date ? $maintenance->end_date->format('d/m/Y') : '-' }}</td>
                                        <td class="align-middle">{{ number_format($maintenance->cost) }} đ</td>
                                        <td class="align-middle">
                                            @switch($maintenance->status)
                                                @case('pending')
                                                    <span class="badge badge-warning badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Chờ xử lý
                                                    </span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge badge-info badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Đang thực hiện
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge badge-success badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Hoàn thành
                                                    </span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge badge-danger badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Đã hủy
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <div class="avatar-title rounded-circle bg-primary">
                                                        {{ substr($maintenance->creator->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                {{ $maintenance->creator->name }}
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#statusModal{{ $maintenance->id }}" data-toggle="tooltip" title="Cập nhật trạng thái">
                                                    <i class="fa fa-sync"></i>
                                                </button>
                                                <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" data-toggle="tooltip" title="Xóa">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Status Update Modal -->
                                            <div class="modal fade" id="statusModal{{ $maintenance->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('maintenances.update-status', $maintenance) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Cập nhật trạng thái</h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Trạng thái</label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="pending" {{ $maintenance->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                                        <option value="in_progress" {{ $maintenance->status === 'in_progress' ? 'selected' : '' }}>Đang thực hiện</option>
                                                                        <option value="completed" {{ $maintenance->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                                        <option value="cancelled" {{ $maintenance->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Kết quả</label>
                                                                    <textarea name="result" class="form-control" rows="3">{{ $maintenance->result }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th width="10%">Mã thiết bị</th>
                                    <th width="15%">Tên thiết bị</th>
                                    <th width="10%">Loại bảo trì</th>
                                    <th width="12%">Ngày bắt đầu</th>
                                    <th width="12%">Ngày kết thúc</th>
                                    <th width="10%">Chi phí</th>
                                    <th width="12%">Trạng thái</th>
                                    <th width="10%">Người tạo</th>
                                    <th class="text-center" width="15%">Thao tác</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm {
            width: 24px;
            height: 24px;
            font-size: 12px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-pill {
            font-size: 12px;
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
        }

        .table td {
            vertical-align: middle;
        }

        .fa-circle {
            font-size: 8px;
        }
    </style>
@endsection

