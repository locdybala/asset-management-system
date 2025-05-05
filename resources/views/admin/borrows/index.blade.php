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
                    <a href="{{ route('device-borrows.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Tạo phiếu mượn
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="example" class="table table-hover" style="min-width: 845px">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th width="15%">Người mượn</th>
                                    <th width="12%">Ngày mượn</th>
                                    <th width="12%">Ngày trả</th>
                                    <th>Lý do mượn</th>
                                    <th width="12%">Trạng thái</th>
                                    <th class="text-center" width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($borrows as $key => $borrow)
                                    <tr class="border-bottom">
                                        <td class="text-center align-middle">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="mr-2">{{ $key + 1 }}</span>
                                                <button class="btn btn-link p-0 toggle-details"
                                                    data-borrow-id="{{ $borrow->id }}">
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <div class="avatar-title rounded-circle bg-primary">
                                                        {{ substr($borrow->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                {{ $borrow->user->name }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</td>
                                        <td class="align-middle">
                                            {{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') : 'Chưa trả' }}
                                        </td>
                                        <td class="align-middle">{{ Str::limit($borrow->reason, 50) }}</td>
                                        <td class="align-middle">
                                            @php
                                                $color = match ($borrow->status) {
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'borrowed' => 'primary',
                                                    'returned' => 'success',
                                                    'cancelled' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $color }} badge-pill px-3 py-2">
                                                <i class="fa fa-circle mr-1 small"></i>
                                                {{ $borrow->status_text }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                @if ($borrow->status === 'pending')
                                                    <form method="POST"
                                                        action="{{ route('device-borrows.approve', $borrow->id) }}"
                                                        class="mr-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            data-toggle="tooltip" title="Duyệt">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('device-borrows.cancel', $borrow->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            data-toggle="tooltip" title="Hủy">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @elseif ($borrow->status === 'approved')
                                                    <a href="{{ route('device-borrows.return', $borrow->id) }}" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" title="Trả">
                                                        <i class="fa fa-undo"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Chi tiết phiếu mượn sẽ được chèn ngay sau mỗi dòng -->
                                    <div class="borrow-details-row" data-parent-row-id="{{ $borrow->id }}">
                                        <div class="borrow-details-wrapper" data-borrow-id="{{ $borrow->id }}" style="display: none;">
                                            <div class="borrow-details">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th width="15%">Người mượn</th>
                                    <th width="12%">Ngày mượn</th>
                                    <th width="12%">Ngày trả</th>
                                    <th>Lý do mượn</th>
                                    <th width="12%">Trạng thái</th>
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

        .fa-chevron-down {
            font-size: 12px;
            color: #666;
            transition: transform 0.2s;
        }

        .rotate-icon {
            transform: rotate(180deg);
        }

        .details-row {
            background: none !important;
        }

        .details-row:hover {
            background: none !important;
        }

        .borrow-details-row {
            position: relative;
            width: 100%;
            background: none !important;
        }

        .borrow-details-wrapper {
            margin: 0;
            width: 100%;
            background: white;
            border-bottom: 1px solid #e5e5e5;
        }

        .borrow-details {
            padding: 15px 30px;
            background-color: #f8f9fa;
            border-left: 1px solid #e5e5e5;
            border-right: 1px solid #e5e5e5;
            margin: 0 -1px; /* Để border khớp với table */
        }

        /* Đảm bảo chi tiết nằm đúng vị trí sau mỗi dòng */
        #example tbody tr {
            position: relative;
        }

        /* Fix DataTable styling conflicts */
        .dataTables_wrapper .borrow-details-row {
            background: none !important;
        }

        .dataTables_wrapper .borrow-details-row:hover {
            background: none !important;
        }
    </style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Xử lý sự kiện click vào nút toggle-details
        $('.toggle-details').on('click', function() {
            const borrowId = $(this).data('borrow-id');
            const icon = $(this).find('i');
            const detailsRow = $(`.borrow-details-row[data-parent-row-id="${borrowId}"]`);
            const detailsWrapper = detailsRow.find('.borrow-details-wrapper');
            const detailsContainer = detailsWrapper.find('.borrow-details');

            // Toggle icon
            icon.toggleClass('rotate-icon');

            // Nếu đã có nội dung, chỉ cần toggle hiển thị
            if (detailsContainer.children().length > 0) {
                detailsWrapper.slideToggle();
                return;
            }

            // Hiển thị loading
            detailsContainer.html(`
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);
            detailsWrapper.slideDown();

            // Gọi API lấy chi tiết
            $.ajax({
                url: "{{ route('device-borrows.details', ['id' => ':id']) }}".replace(':id', borrowId),
                method: 'GET',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success) {
                        detailsContainer.html(response.html);
                    } else {
                        detailsContainer.html(`
                            <div class="alert alert-danger m-3">
                                ${response.message || 'Không thể tải chi tiết phiếu mượn'}
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);

                    detailsContainer.html(`
                        <div class="alert alert-danger m-3">
                            Có lỗi xảy ra khi tải chi tiết phiếu mượn
                        </div>
                    `);
                }
            });
        });
    });
</script>
@endsection
