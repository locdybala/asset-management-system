@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Báo cáo tài sản theo phòng ban</h3>
                    <div class="card-tools">
                        <a href="{{ route('reports.export-department-assets-pdf') }}" class="btn btn-secondary">Xuất PDF</a>
                        <a href="{{ route('reports.export-department-assets-excel') }}" class="btn btn-success">Xuất Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($departments as $department)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>{{ $department->name }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mã thiết bị</th>
                                            <th>Tên thiết bị</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày mua</th>
                                            <th>Giá trị</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($department->deviceItems as $item)
                                        <tr>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->device->name }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>{{ $item->purchase_date }}</td>
                                            <td>{{ number_format($item->value) }} VNĐ</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Không có thiết bị nào</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 