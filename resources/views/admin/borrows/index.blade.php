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
                    <a href="{{ route('borrows.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Tạo phiếu mượn
                    </a>
                </div>
                <div class="card-body">
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
                                                        action="{{ route('borrows.approve', $borrow->id) }}"
                                                        class="mr-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            data-toggle="tooltip" title="Duyệt">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                        action="{{ route('borrows.cancel', $borrow->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            data-toggle="tooltip" title="Hủy">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @elseif ($borrow->status === 'approved')
                                                    <form method="POST"
                                                        action="{{ route('borrows.return', $borrow->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                            data-toggle="tooltip" title="Trả">
                                                            <i class="fa fa-undo"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
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

            <!-- Container for borrow details -->
            <div id="borrow-details-container"></div>
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

        .borrow-details {
            display: none;
            margin: 10px 0;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Khởi tạo DataTable
            var table = $('#example').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                language: {
                    "sProcessing": "Đang xử lý...",
                    "sLengthMenu": "Xem _MENU_ mục",
                    "sZeroRecords": "Không tìm thấy dòng nào phù hợp",
                    "sInfo": "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    "sInfoEmpty": "Đang xem 0 đến 0 trong tổng số 0 mục",
                    "sInfoFiltered": "(được lọc từ _MAX_ mục)",
                    "sInfoPostFix": "",
                    "sSearch": "Tìm:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Đầu",
                        "sPrevious": "Trước",
                        "sNext": "Tiếp",
                        "sLast": "Cuối"
                    }
                }
            });

            // Xử lý sự kiện khi click vào nút toggle
            $(document).on('click', '.toggle-details', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var borrowId = $(this).data('borrow-id');
                var $icon = $(this).find('i');
                var $row = $(this).closest('tr');

                // Remove any existing details div for this borrow
                $('#borrow-details-' + borrowId).remove();

                // Toggle icon rotation
                $icon.toggleClass('rotate-icon');

                // If icon is rotated, load and show details
                if ($icon.hasClass('rotate-icon')) {
                    // Create and insert details div after the row
                    var $detailsDiv = $('<div/>', {
                        id: 'borrow-details-' + borrowId,
                        class: 'borrow-details p-3'
                    }).insertAfter($row);

                    // Show loading state
                    $detailsDiv.html(`
                        <div class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Đang tải...</span>
                            </div>
                            <div class="mt-2">Đang tải dữ liệu...</div>
                        </div>
                    `).slideDown();

                    // Load details via AJAX
                    $.ajax({
                        url: '{{ route("borrows.details", ["id" => ":id"]) }}'.replace(':id', borrowId),
                        method: 'GET',
                        success: function(response) {
                            console.log('Response:', response); // Debug log
                            if (response.html) {
                                $detailsDiv.html(response.html);
                            } else {
                                $detailsDiv.html(`
                                    <div class="text-center text-danger">
                                        <i class="fa fa-exclamation-circle"></i>
                                        Không thể tải dữ liệu chi tiết
                                    </div>
                                `);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error); // Debug log
                            console.error('Status:', status);
                            console.error('Response:', xhr.responseText);
                            
                            $detailsDiv.html(`
                                <div class="text-center text-danger">
                                    <i class="fa fa-exclamation-circle"></i>
                                    Có lỗi xảy ra khi tải dữ liệu: ${error}
                                </div>
                            `);
                        }
                    });
                } else {
                    // Hide and remove details
                    $('#borrow-details-' + borrowId).slideUp(function() {
                        $(this).remove();
                    });
                }
            });

            // Tooltip
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
