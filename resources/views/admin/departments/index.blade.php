@extends('layouts.app')
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Xin chào trở lại</h4>
                        <span class="ml-1">Danh sách khoa</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Khoa</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <!-- Danh sách khoa -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách khoa</h4>

                            <!-- Nút mở modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#createdepartmentModal">
                                <i class="fa fa-plus"></i> Thêm khoa
                            </button>

                            <!-- Modal Thêm Khoa -->
                            <div class="modal fade" id="createdepartmentModal" tabindex="-1" role="dialog"
                                aria-labelledby="modalTitle" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form id="create-department-form">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Thêm Khoa Mới</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name">Tên Khoa</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Mô Tả</label>
                                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Trạng Thái</label>
                                                    <select class="form-control" name="status" id="status">
                                                        <option value="1" selected>Hoạt động</option>
                                                        <option value="0">Không hoạt động</option>
                                                    </select>
                                                </div>
                                                <div id="form-error" class="text-danger small d-none"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Lưu</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class=" display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên Khoa</th>
                                            <th>Mô tả</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($departments as $key => $department)
                                            <tr>
                                                <td><strong>{{ $key + 1 }}</strong></td>
                                                <td><span
                                                        class="text-primary font-weight-bold">{{ $department->name }}</span>
                                                </td>
                                                <td>{{ $department->description }}</td>
                                                <td>
                                                    @if ($department->status === '1')
                                                        <span class="badge badge-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge badge-danger">Không hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-success">{{ $department->created_at->format('d/m/Y') }}</span>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-sm btn-warning btn-edit-department"
                                                        data-id="{{ $department->id }}">
                                                        <i class="fa fa-edit"></i> Sửa
                                                    </button>
                                                    <!-- Modal Sửa Khoa -->
                                                    <div class="modal fade" id="editDeparmentModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form id="edit-deparment-form">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="id" id="edit_id">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Cập nhật Khoa</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal"><span>&times;</span></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="edit_name">Tên Khoa</label>
                                                                            <input type="text" class="form-control"
                                                                                id="edit_name" name="name" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="edit_description">Mô Tả</label>
                                                                            <textarea class="form-control" id="edit_description" name="description"></textarea>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="edit_status">Trạng Thái</label>
                                                                            <select class="form-control" name="status"
                                                                                id="edit_status">
                                                                                <option value="1">Hoạt động</option>
                                                                                <option value="0">Không hoạt động
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div id="edit-form-error"
                                                                            class="text-danger small d-none"></div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Đóng</button>
                                                                        <button type="submit" class="btn btn-primary">Cập
                                                                            nhật</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <button class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $department->id }}">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên Khoa</th>
                                            <th>Mô tả</th>
                                            <th>Trạng thái</th>
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
        $(document).ready(function() {
            $('#create-department-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('departments.store') }}',
                    data: formData,
                    success: function(response) {
                        $('#createdepartmentModal').modal('hide');
                        $('#create-department-form')[0].reset();
                        $('#form-error').addClass('d-none');
                        toastr.success('Thêm khoa thành công!');
                        location.reload(); // hoặc gọi hàm load lại bảng danh sách
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        $('#form-error').removeClass('d-none').text(errorText);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Bấm nút Sửa
            $('.btn-edit-department').on('click', function() {
                const id = $(this).data('id');
                const url = `/admin/departments/${id}/edit`;

                $.get(url, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_description').val(data.description);
                    $('#edit_status').val(data.status);
                    $('#editDeparmentModal').modal('show');
                }).fail(() => {
                    toastr.error('Không lấy được dữ liệu khoa');
                });
            });

            // Gửi form cập nhật
            $('#edit-deparment-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const url = `/admin/departments/${id}`;
                const formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editDeparmentModal').modal('hide');
                        toastr.success('Cập nhật thành công!');
                        location.reload(); // hoặc update lại row đó
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        $('#edit-form-error').removeClass('d-none').text(errorText);
                    }
                });
            });
        });



        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // SweetAlert2 confirmation with loading
            Swal.fire({
                title: "Bạn có chắc chắn muốn xóa?",
                text: "Hành động này không thể hoàn tác!",
                icon: "warning", // Correct icon type for SweetAlert2
                showCancelButton: true,
                confirmButtonColor: "#dc3545", // Optional: red button for delete
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy",
                showLoaderOnConfirm: true, // Show loader during AJAX
                preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: "/admin/departments/" + id,
                            type: "POST",
                            data: {
                                _method: "DELETE",
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                resolve(
                                response); // Resolve with the response data when delete is successful
                            },
                            error: function(xhr) {
                                reject(
                                "Có lỗi xảy ra khi xóa khoa!"); // Reject in case of error
                            }
                        });
                    });
                },
                allowOutsideClick: () => !Swal.isLoading() // Prevent closing the modal while loading
            }).then((result) => {
                console.log(result); // Debugging result to check its structure
                if (result.value.success) {
                    // Show success message after successful delete
                    Swal.fire("Đã xóa!", result.value.message, "success");

                    // Remove the row from the table
                    var row = $('button[data-id="' + id + '"]').closest('tr');
                    row.fadeOut(500, function() {
                        $(this).remove();
                    });
                } else {
                    // Handle cancel if user cancels deletion
                    Swal.fire("Đã hủy", "Khoa không bị xóa", "info");
                }

            });
        });
    </script>
@endsection
