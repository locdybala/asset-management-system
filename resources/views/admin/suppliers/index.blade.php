@extends('layouts.app')
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Xin chào trở lại</h4>
                        <span class="ml-1">Danh sách nhà cung cấp</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Nhà cung cấp</a></li>
                        <li class="breadcrumb-item active"><a href="#">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <!-- Danh sách nhà cung cấp -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách nhà cung cấp</h4>

                            <!-- Nút mở modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#createSupplierModal">
                                <i class="fa fa-plus"></i> Thêm nhà cung cấp
                            </button>

                            <!-- Modal Thêm -->
                            <div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form id="create-supplier-form">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Thêm nhà cung cấp</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="name">Tên nhà cung cấp</label>
                                                    <input type="text" class="form-control" name="name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Địa chỉ</label>
                                                    <input type="text" class="form-control" name="address">
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Số điện thoại</label>
                                                    <input type="text" class="form-control" name="phone">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" name="email">
                                                </div>
                                                <div class="form-group">
                                                    <label for="note">Ghi chú</label>
                                                    <textarea class="form-control" name="note" rows="2"></textarea>
                                                </div>
                                                <div id="form-error" class="text-danger small d-none"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Lưu</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên nhà cung cấp</th>
                                            <th>Địa chỉ</th>
                                            <th>SĐT</th>
                                            <th>Email</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suppliers as $key => $supplier)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $supplier->name }}</td>
                                                <td>{{ $supplier->address }}</td>
                                                <td>{{ $supplier->phone }}</td>
                                                <td>{{ $supplier->email }}</td>
                                                <td>{{ $supplier->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning btn-edit-supplier" data-id="{{ $supplier->id }}"> <i class="fa fa-edit"></i> Sửa</button>
                                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $supplier->id }}"><i class="fa fa-trash"></i> Xóa</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên nhà cung cấp</th>
                                            <th>Địa chỉ</th>
                                            <th>SĐT</th>
                                            <th>Email</th>
                                            <th>Ngày tạo</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="modal fade" id="editsupplierModal" tabindex="-1" role="dialog"
                                    aria-labelledby="modalEditTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form id="edit-supplier-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" id="edit_id">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cập nhật nhà cung cấp</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="edit_name">Tên nhà cung cấp</label>
                                                        <input type="text" class="form-control" id="edit_name" name="name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_address">Địa chỉ</label>
                                                        <input type="text" class="form-control" id="edit_address" name="address">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_phone">Số điện thoại</label>
                                                        <input type="text" class="form-control" id="edit_phone" name="phone">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_email">Email</label>
                                                        <input type="email" class="form-control" id="edit_email" name="email">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="edit_note">Ghi chú</label>
                                                        <textarea class="form-control" name="note" id="edit_note" rows="2"></textarea>
                                                    </div>
                                                    <div id="edit-form-error" class="text-danger small d-none"></div>
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
                            </div>
                        </div> <!-- card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#create-supplier-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('suppliers.store') }}',
                    data: formData,
                    success: function(response) {
                        $('#createdsupplierModal').modal('hide');
                        $('#create-supplier-form')[0].reset();
                        $('#form-error').addClass('d-none');
                        toastr.success('Thêm nhà cung cấp thành công!');
                        location.reload();
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
            $('.btn-edit-supplier').on('click', function() {
                const id = $(this).data('id');
                const url = `/admin/suppliers/${id}/edit`;

                $.get(url, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_address').val(data.address);
                    $('#edit_phone').val(data.phone);
                    $('#edit_email').val(data.email);
                    $('#edit_note').val(data.note);
                    $('#editsupplierModal').modal('show');
                }).fail(() => {
                    toastr.error('Không lấy được dữ liệu nhà cung cấp');
                });
            });

            // Gửi form cập nhật
            $('#edit-supplier-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const url = `/admin/suppliers/${id}`;
                const formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editsupplierModal').modal('hide');
                        toastr.success('Cập nhật thành công!');
                        location.reload();
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
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            url: "/admin/suppliers/" + id,
                            type: "POST",
                            data: {
                                _method: "DELETE",
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                resolve(response);
                            },
                            error: function(xhr) {
                                reject("Có lỗi xảy ra khi xóa nhà cung cấp!");
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
                    Swal.fire("Đã hủy", "nhà cung cấp không bị xóa", "info");
                }
            });
        });
    </script>
@endsection
