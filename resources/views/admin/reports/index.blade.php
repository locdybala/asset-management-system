@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Báo cáo và Thống kê</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Báo cáo tài sản theo phòng ban</h5>
                                    <p class="card-text">Xem danh sách tài sản được phân bổ cho từng phòng ban</p>
                                    <a href="{{ route('reports.department-assets') }}" class="btn btn-primary">Xem báo cáo</a>
                                    <a href="{{ route('reports.export-department-assets-pdf') }}" class="btn btn-secondary">Xuất PDF</a>
                                    <a href="{{ route('reports.export-department-assets-excel') }}" class="btn btn-success">Xuất Excel</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Thống kê tình trạng thiết bị</h5>
                                    <p class="card-text">Xem tỷ lệ thiết bị theo từng trạng thái</p>
                                    <a href="{{ route('reports.device-status') }}" class="btn btn-primary">Xem báo cáo</a>
                                    <a href="{{ route('reports.export-device-status-pdf') }}" class="btn btn-secondary">Xuất PDF</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Báo cáo chi phí bảo trì</h5>
                                    <p class="card-text">Xem chi tiết chi phí bảo trì thiết bị</p>
                                    <a href="{{ route('reports.maintenance-costs') }}" class="btn btn-primary">Xem báo cáo</a>
                                    <a href="{{ route('reports.export-maintenance-costs-pdf') }}" class="btn btn-secondary">Xuất PDF</a>
                                    <a href="{{ route('reports.export-maintenance-costs-excel') }}" class="btn btn-success">Xuất Excel</a>
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