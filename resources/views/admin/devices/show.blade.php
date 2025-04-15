@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết thiết bị</h4>
                    <span class="ml-1">{{ $device->name }}</span>
                </div>
            </div>
        </div>

        <!-- Chi tiết thiết bị -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Danh sách các thiết bị con</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên thiết bị</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($device_parts as $key => $part)
                                        <tr>
                                            <td><strong>{{ $key + 1 }}</strong></td>
                                            <td>{{ $part->name }}</td>
                                            <td>
                                                @if($part->status == 'available')
                                                    <span class="badge badge-success">Có sẵn</span>
                                                @elseif($part->status == 'borrowed')
                                                    <span class="badge badge-warning">Đang mượn</span>
                                                @elseif($part->status == 'broken')
                                                    <span class="badge badge-danger">Hỏng</span>
                                                @elseif($part->status == 'maintenance')
                                                    <span class="badge badge-info">Bảo trì</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-success">{{ $part->created_at->format('d/m/Y') }}</span>
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

    </div> <!-- end .container-fluid -->
</div>
@endsection
