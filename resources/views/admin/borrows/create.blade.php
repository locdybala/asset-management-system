@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tạo phiếu mượn thiết bị</h4>
                    <span class="ml-1">Chọn thiết bị muốn mượn</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin phiếu mượn</h4>
                    </div>
                    <div class="card-body">

                        {{-- Hiển thị lỗi nếu có --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Đã xảy ra lỗi:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('borrows.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label class="text-label">Ngày mượn *</label>
                                <input type="date" class="form-control" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Chọn thiết bị *</label>
                                <select id="device_select" class="form-control" name="device_id" required>
                                    <option value="">-- Chọn thiết bị --</option>
                                    @foreach ($devices as $device)
                                        <option value="{{ $device->id }}">{{ $device->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="device_items_container" style="display: none;">
                                <label class="text-label">Chi tiết thiết bị *</label>
                                <div id="device_items_list" class="row">
                                    <!-- Chi tiết thiết bị sẽ được tải vào đây qua AJAX -->
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Tạo phiếu mượn</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script>
    // Khi chọn thiết bị, gửi AJAX để lấy chi tiết thiết bị
    $('#device_select').on('change', function() {
        var device_id = $(this).val();
        if (device_id) {
            // Gửi AJAX để lấy chi tiết thiết bị của thiết bị đã chọn
            $.ajax({
                url: '/admin/borrows/device-items/' + device_id,  // Đường dẫn lấy chi tiết thiết bị
                type: 'GET',
                success: function(response) {
                    if (response.device_items.length > 0) {
                        var html = '';
                        response.device_items.forEach(function(item) {
                            html += `
                                <div class="col-md-4 mb-2">
                                    <div class="border p-2 rounded d-flex align-items-center">
                                        <input type="checkbox" name="device_items[]" value="${item.id}" class="mr-2">
                                        <div>
                                            <strong>${item.code}</strong><br>
                                            <small>Serial: ${item.serial_number}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $('#device_items_list').html(html);
                        $('#device_items_container').show();
                    } else {
                        $('#device_items_list').html('<p class="text-muted">Không có chi tiết thiết bị khả dụng.</p>');
                        $('#device_items_container').show();
                    }
                }
            });
        } else {
            $('#device_items_container').hide();
        }
    });
</script>
@endsection
