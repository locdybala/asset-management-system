@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Danh sách phiếu mượn phòng</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('room-borrows.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Tạo phiếu mượn mới
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Phòng</th>
                                        <th>Người mượn</th>
                                        <th>Ngày mượn</th>
                                        <th>Ngày trả</th>
                                        <th>Lý do</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roomBorrows as $borrow)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $borrow->room->name }}</td>
                                        <td>{{ $borrow->user->name }}</td>
                                        <td>{{ $borrow->borrow_date->format('d/m/Y H:i') }}</td>
                                        <td>{{ $borrow->return_date->format('d/m/Y H:i') }}</td>
                                        <td>{{ Str::limit($borrow->reason, 50) }}</td>
                                        <td>
                                            @if($borrow->status == 'pending')
                                                <span class="badge badge-warning">Chờ duyệt</span>
                                            @elseif($borrow->status == 'approved')
                                                <span class="badge badge-success">Đã duyệt</span>
                                            @elseif($borrow->status == 'returned')
                                                <span class="badge badge-info">Đã trả</span>
                                            @else
                                                <span class="badge badge-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('room-borrows.show', $borrow->id) }}" class="btn btn-info btn-sm mr-1">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if($borrow->status == 'pending')
                                                    <form action="{{ route('room-borrows.approve', $borrow->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm mr-1" onclick="return confirm('Bạn có chắc chắn muốn duyệt phiếu mượn này?')">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('room-borrows.cancel', $borrow->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn hủy phiếu mượn này?')">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @elseif($borrow->status == 'approved')
                                                    <form action="{{ route('room-borrows.return', $borrow->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Bạn có chắc chắn muốn đánh dấu đã trả?')">
                                                            <i class="fa fa-undo"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Chưa có phiếu mượn nào</td>
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
@endsection
