@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        @extends('admin.components.message')

        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Phiếu mượn thiết bị</h4>
                    <span class="ml-1">Quản lý phiếu mượn & trả thiết bị</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Danh sách phiếu mượn</h4>
                <a href="{{ route('borrows.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tạo phiếu mượn
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Người mượn</th>
                                <th>Ngày mượn</th>
                                <th>Ngày trả</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($borrows as $key => $borrow)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $borrow->user->name }}</td>
                                    <td>{{ $borrow->borrow_date }}</td>
                                    <td>{{ $borrow->return_date ?? 'Chưa trả' }}</td>
                                    <td>
                                        @php
                                            $color = [
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'borrowed' => 'primary',
                                                'returned' => 'success',
                                            ][$borrow->status];
                                        @endphp
                                        <span class="badge badge-{{ $color }}">{{ ucfirst($borrow->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('borrows.show', $borrow->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> Chi tiết
                                        </a>
                                        @if ($borrow->status === 'pending')
                                            <form method="POST" action="{{ route('borrows.approve', $borrow->id) }}" style="display:inline;">
                                                @csrf
                                                <button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Duyệt</button>
                                            </form>
                                        @elseif ($borrow->status === 'approved')
                                            <form method="POST" action="{{ route('borrows.return', $borrow->id) }}" style="display:inline;">
                                                @csrf
                                                <button class="btn btn-sm btn-secondary"><i class="fa fa-undo"></i> Trả</button>
                                            </form>
                                        @endif
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
@endsection
