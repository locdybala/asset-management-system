@extends('layouts.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            @extends('admin.components.message')
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Danh sách thiết bị</h4>
                        <span class="ml-1">Quản lý thiết bị trường học</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Thiết bị</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <!-- Danh sách thiết bị -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách thiết bị</h4>
                            <a href="{{ route('devices.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Thêm thiết bị
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ảnh</th>
                                            <th>Tên thiết bị</th>
                                            <th>Danh mục</th>
                                            <th>Số lượng</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($devices as $key => $device)
                                            <tr>
                                                <td><strong>{{ $key + 1 }}</strong></td>
                                                <td>
                                                    @if ($device->image)
                                                        <img src="{{ asset('storage/' . $device->image) }}"
                                                            alt="Ảnh thiết bị" width="50" height="50"
                                                            style="object-fit: cover; border-radius: 6px;">
                                                    @else
                                                        <span class="text-muted">Không có ảnh</span>
                                                    @endif
                                                </td>
                                                <td>{{ $device->name }}</td>
                                                <td>{{ $device->category->name ?? 'Chưa có danh mục' }}</td>
                                                <td>{{ $device->items->count() }}</td>
                                                <td><span
                                                        class="text-success">{{ $device->created_at->format('d/m/Y') }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('devices.show', $device->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Xem
                                                    </a>
                                                    <a href="{{ route('devices.edit', $device->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i> Sửa
                                                    </a>
                                                    <button class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $device->id }}">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Ảnh</th>
                                            <th>Tên thiết bị</th>
                                            <th>Danh mục</th>
                                            <th>Số lượng</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- end .container-fluid -->
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // SweetAlert2 confirmation with loading
            Swal.fire({
                title: "Bạn có chắc chắn muốn xóa?",
                text: "Hành động này không thể hoàn tác!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: "/admin/devices/" + id,
                            type: "POST",
                            data: {
                                _method: "DELETE",
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                resolve(response);
                            },
                            error: function(xhr) {
                                reject("Có lỗi xảy ra khi xóa danh mục!");
                            }
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.value.success) {
                    Swal.fire("Đã xóa!", result.value.message, "success");

                    var row = $('button[data-id="' + id + '"]').closest('tr');
                    row.fadeOut(500, function() {
                        $(this).remove();
                    });
                } else {
                    Swal.fire("Đã hủy", "Danh mục không bị xóa", "info");
                }
            });
        });
    </script>
@endsection
