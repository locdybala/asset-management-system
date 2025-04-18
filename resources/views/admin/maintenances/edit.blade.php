@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chỉnh sửa yêu cầu bảo trì</h4>
                    <span class="ml-1">Cập nhật thông tin bảo trì</span>
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

                        <form action="{{ route('maintenances.update', $maintenance->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-label">Ngày bắt đầu *</label>
                                        <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $maintenance->start_date->format('Y-m-d')) }}" required>
                                        @if($errors->has('start_date'))
                                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-label">Loại bảo trì *</label>
                                        <select class="form-control" name="type" required>
                                            <option value="">-- Chọn loại bảo trì --</option>
                                            <option value="periodic" {{ old('type', $maintenance->type) == 'periodic' ? 'selected' : '' }}>Bảo trì định kỳ</option>
                                            <option value="repair" {{ old('type', $maintenance->type) == 'repair' ? 'selected' : '' }}>Sửa chữa</option>
                                        </select>
                                        @if($errors->has('type'))
                                            <span class="text-danger">{{ $errors->first('type') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Thiết bị *</label>
                                <select class="form-control select2" name="device_id" id="device_id" required>
                                    <option value="">Chọn thiết bị</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}" {{ old('device_id', $maintenance->deviceItem->device_id) == $device->id ? 'selected' : '' }}>
                                            {{ $device->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('device_id'))
                                    <span class="text-danger">{{ $errors->first('device_id') }}</span>
                                @endif
                            </div>

                            <div class="form-group" id="device_items_container" style="display: none;">
                                <label class="text-label">Chi tiết thiết bị *</label>
                                <div id="device_items_list" class="row">
                                    <!-- Chi tiết thiết bị sẽ được tải qua AJAX -->
                                </div>
                                @if($errors->has('device_item_id'))
                                    <span class="text-danger">{{ $errors->first('device_item_id') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="text-label">Mô tả *</label>
                                <textarea class="form-control" name="description" rows="3" required>{{ old('description', $maintenance->description) }}</textarea>
                                @if($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="text-label">Chi phí dự kiến</label>
                                <input type="number" class="form-control" name="cost" value="{{ old('cost', $maintenance->cost) }}" min="0">
                                @if($errors->has('cost'))
                                    <span class="text-danger">{{ $errors->first('cost') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="text-label">Trạng thái *</label>
                                <select class="form-control" name="status" required>
                                    <option value="pending" {{ old('status', $maintenance->status) == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="in_progress" {{ old('status', $maintenance->status) == 'in_progress' ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="completed" {{ old('status', $maintenance->status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ old('status', $maintenance->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                @if($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="text-label">Kết quả</label>
                                <textarea class="form-control" name="result" rows="3">{{ old('result', $maintenance->result) }}</textarea>
                                @if($errors->has('result'))
                                    <span class="text-danger">{{ $errors->first('result') }}</span>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Cập nhật yêu cầu bảo trì</button>
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
        // Initialize select2
        $('.select2').select2();

        // Load device items when device is selected
        $('#device_id').on('change', function() {
            loadDeviceItems($(this).val());
        });

        // If there's a previously selected device (after validation failure), load its items
        const selectedDeviceId = $('#device_id').val();
        if (selectedDeviceId) {
            loadDeviceItems(selectedDeviceId);
        }

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

                    // Re-check previously selected items
                    @if(old('device_item_id', $maintenance->device_item_id))
                        $('#device_items_list input[value="{{ old('device_item_id', $maintenance->device_item_id) }}"]').prop('checked', true);
                    @endif
                },
                error: function(xhr, status, error) {
                    $('#device_items_list').html('<div class="alert alert-danger">Không thể tải danh sách thiết bị. Vui lòng thử lại sau.</div>');
                }
            });
        }
    });
</script>
@endsection
