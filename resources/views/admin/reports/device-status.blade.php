@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Báo cáo tình trạng thiết bị</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <div class="btn-group">
                    <a href="{{ route('reports.device-status-pdf') }}" class="btn btn-primary">
                        <i class="fas fa-file-pdf"></i> Xuất PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart-container" style="position: relative; height:400px; width:100%;">
                                    <canvas id="deviceStatusChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>Tình trạng</th>
                                                <th>Số lượng</th>
                                                <th>Tỷ lệ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($statusPercentages as $status => $percentage)
                                            <tr>
                                                <td>
                                                    @switch($status)
                                                        @case('available')
                                                            <span class="badge light badge-success">Sẵn sàng</span>
                                                            @break
                                                        @case('borrowed')
                                                            <span class="badge light badge-warning">Đang mượn</span>
                                                            @break
                                                        @case('maintenance')
                                                            <span class="badge light badge-info">Bảo trì</span>
                                                            @break
                                                        @case('damaged')
                                                            <span class="badge light badge-danger">Hỏng</span>
                                                            @break
                                                        @default
                                                            <span class="badge light badge-secondary">{{ $status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $deviceCounts[$status] }}</td>
                                                <td>{{ number_format($percentage, 2) }}%</td>
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
    </div>
</div>
@endsection

@section('js')
<!-- Thêm các script cần thiết -->

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script>
$(document).ready(function() {
    // Đảm bảo Chart.js đã được load
    if (typeof Chart !== 'undefined') {
        var ctx = document.getElementById('deviceStatusChart').getContext('2d');
        var data = {
            labels: {!! json_encode($statusPercentages->keys()->map(function($key) {
                switch($key) {
                    case 'available': return 'Sẵn sàng';
                    case 'borrowed': return 'Đang mượn';
                    case 'maintenance': return 'Bảo trì';
                    case 'damaged': return 'Hỏng';
                    default: return $key;
                }
            })->toArray()) !!},
            datasets: [{
                data: {!! json_encode($statusPercentages->values()->toArray()) !!},
                backgroundColor: [
                    '#2bc155', // Xanh lá - Sẵn sàng
                    '#ffb800', // Vàng - Đang mượn
                    '#1890ff', // Xanh dương - Bảo trì
                    '#ff4d4f'  // Đỏ - Hỏng
                ],
                borderWidth: 0
            }]
        };
        var options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        boxWidth: 20,
                        font: {
                            size: 14
                        },
                        padding: 15
                    }
                }
            }
        };

        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: options
        });
    } else {
        console.error('Chart.js is not loaded');
    }
});
</script>
@endsection