@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="widget-stat card bg-primary">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="fa fa-laptop"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1">Tổng số thiết bị</p>
                                <h3 class="text-white">{{ $totalDevices }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="widget-stat card bg-success">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="fa fa-exchange-alt"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1">Tổng số phiếu mượn</p>
                                <h3 class="text-white">{{ $totalBorrows }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="widget-stat card bg-warning">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="fa fa-exclamation-triangle"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1">Thiết bị hư hỏng</p>
                                <h3 class="text-white">{{ $totalDamaged }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-xxl-3 col-sm-6">
                <div class="widget-stat card bg-info">
                    <div class="card-body">
                        <div class="media">
                            <span class="mr-3">
                                <i class="fa fa-clock"></i>
                            </span>
                            <div class="media-body text-white">
                                <p class="mb-1">Thiết bị đang mượn</p>
                                <h3 class="text-white">{{ $totalBorrowed }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thống kê thiết bị theo trạng thái</h4>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="deviceStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thống kê mượn trả theo tháng</h4>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="borrowChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách thiết bị hư hỏng</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã thiết bị</th>
                                        <th>Tên thiết bị</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày cập nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($damagedDevices as $device)
                                    <tr>
                                        <td>{{ $device->code }}</td>
                                        <td>{{ $device->device->name }}</td>
                                        <td><span class="badge badge-warning">Hư hỏng</span></td>
                                        <td>{{ $device->updated_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-xxl-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách thiết bị đang mượn</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã thiết bị</th>
                                        <th>Tên thiết bị</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày cập nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowedDevices as $device)
                                    <tr>
                                        <td>{{ $device->code }}</td>
                                        <td>{{ $device->device->name }}</td>
                                        <td><span class="badge badge-info">Đang mượn</span></td>
                                        <td>{{ $device->updated_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ trạng thái thiết bị
    const deviceStatusCtx = document.getElementById('deviceStatusChart').getContext('2d');
    const deviceStatusChart = new Chart(deviceStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Sẵn sàng', 'Đang mượn', 'Hư hỏng'],
            datasets: [{
                data: [
                    {{ $deviceStatusStats['available'] ?? 0 }},
                    {{ $deviceStatusStats['borrowed'] ?? 0 }},
                    {{ $deviceStatusStats['damaged'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(40, 199, 111, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(255, 193, 7, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Biểu đồ mượn trả theo tháng
    const borrowCtx = document.getElementById('borrowChart').getContext('2d');
    const borrowChart = new Chart(borrowCtx, {
        type: 'line',
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [{
                label: 'Số phiếu mượn',
                data: [
                    {{ $borrowStats[1] ?? 0 }},
                    {{ $borrowStats[2] ?? 0 }},
                    {{ $borrowStats[3] ?? 0 }},
                    {{ $borrowStats[4] ?? 0 }},
                    {{ $borrowStats[5] ?? 0 }},
                    {{ $borrowStats[6] ?? 0 }},
                    {{ $borrowStats[7] ?? 0 }},
                    {{ $borrowStats[8] ?? 0 }},
                    {{ $borrowStats[9] ?? 0 }},
                    {{ $borrowStats[10] ?? 0 }},
                    {{ $borrowStats[11] ?? 0 }},
                    {{ $borrowStats[12] ?? 0 }}
                ],
                borderColor: 'rgba(0, 123, 255, 1)',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
