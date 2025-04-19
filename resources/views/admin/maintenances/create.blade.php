@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tạo yêu cầu bảo trì mới</h4>
                    <span class="ml-1">Thêm yêu cầu bảo trì mới</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin yêu cầu bảo trì</h4>
                    </div>
                    <div class="card-body">
                        {{-- Hiển thị thông báo lỗi --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Lỗi!</strong> {{ session('error') }}
                            </div>
                        @endif

                        {{-- Hiển thị thông báo thành công --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Thành công!</strong> {{ session('success') }}
                            </div>
                        @endif

                        {{-- Hiển thị lỗi validation --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Đã xảy ra lỗi:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('maintenances.store') }}" method="POST" id="createMaintenanceForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loại thiết bị</label>
                                        <select name="device_id" id="device_id" class="form-control select2" required>
                                            <option value="">Chọn loại thiết bị</option>
                                            @foreach($devices as $device)
                                                <option value="{{ $device->id }}">{{ $device->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loại bảo trì</label>
                                        <select name="type" class="form-control" required>
                                            <option value="repair">Sửa chữa</option>
                                            <option value="periodic">Bảo trì định kỳ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="device_items_container" style="display: none;">
                                <label>Thiết bị cần bảo trì</label>
                                <div id="device_items_list" class="row">
                                    <!-- Chi tiết thiết bị sẽ được tải qua AJAX -->
                                </div>
                                <input type="hidden" name="device_item_id" id="selected_device_item_id" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày bắt đầu</label>
                                        <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group maintenance-interval-group" style="display: none;">
                                        <label>Khoảng thời gian bảo trì (tháng) <span class="text-danger">*</span></label>
                                        <input type="number" name="maintenance_interval" class="form-control" min="1" value="{{ old('maintenance_interval') }}" id="maintenanceInterval">
                                        @error('maintenance_interval')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Chi phí dự kiến</label>
                                <input type="number" name="cost" class="form-control" min="0">
                            </div>

                            <button type="submit" class="btn btn-primary">Tạo yêu cầu</button>
                            <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">Quay lại</a>
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
    $(document).ready(function() {
        // Khởi tạo select2
        $('.select2').select2();

        // Hiển thị/ẩn trường khoảng thời gian bảo trì
        function toggleMaintenanceInterval(type) {
            const intervalGroup = $('.maintenance-interval-group');
            const intervalInput = $('#maintenanceInterval');
            
            if (type === 'periodic') {
                intervalGroup.show();
                intervalInput.prop('required', true);
            } else {
                intervalGroup.hide();
                intervalInput.prop('required', false);
                intervalInput.val(''); // Xóa giá trị khi chuyển sang loại khác
            }
        }

        // Xử lý khi thay đổi loại bảo trì
        $('select[name="type"]').change(function() {
            toggleMaintenanceInterval($(this).val());
        });

        // Khởi tạo trạng thái ban đầu
        toggleMaintenanceInterval($('select[name="type"]').val());

        // Load device items khi chọn loại thiết bị
        $('#device_id').on('change', function() {
            loadDeviceItems($(this).val());
        });

        // Function to load device items
        function loadDeviceItems(deviceId) {
            if (!deviceId) {
                $('#device_items_container').hide();
                return;
            }

            $.ajax({
                url: `/admin/device-items/${deviceId}`,
                method: 'GET',
                beforeSend: function() {
                    $('#device_items_list').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>');
                    $('#device_items_container').show();
                },
                success: function(response) {
                    $('#device_items_list').html(response);
                },
                error: function(xhr, status, error) {
                    $('#device_items_list').html('<div class="alert alert-danger">Không thể tải danh sách thiết bị. Vui lòng thử lại sau.</div>');
                }
            });
        }

        // Xử lý khi chọn thiết bị
        $(document).on('change', '.device-item-radio', function() {
            const selectedId = $(this).val();
            const status = $(this).data('status');
            
            // Kiểm tra trạng thái thiết bị
            if (status === 'in_use' || status === 'in_maintenance') {
                toastr.error('Không thể tạo yêu cầu bảo trì cho thiết bị đang được sử dụng hoặc đang trong quá trình bảo trì');
                $(this).prop('checked', false);
                $('#selected_device_item_id').val('');
                return;
            }
            
            $('#selected_device_item_id').val(selectedId);
        });

        // Validate form trước khi submit
        $('#createMaintenanceForm').on('submit', function(e) {
            const selectedDeviceId = $('#selected_device_item_id').val();
            const maintenanceType = $('select[name="type"]').val();
            const maintenanceInterval = $('#maintenanceInterval').val();

            if (!selectedDeviceId) {
                e.preventDefault();
                toastr.error('Vui lòng chọn thiết bị cần bảo trì');
                return false;
            }

            if (maintenanceType === 'periodic') {
                if (!maintenanceInterval || maintenanceInterval < 1) {
                    e.preventDefault();
                    toastr.error('Vui lòng nhập khoảng thời gian bảo trì (tối thiểu 1 tháng)');
                    return false;
                }
            }
        });
    });
</script>
@endsection
