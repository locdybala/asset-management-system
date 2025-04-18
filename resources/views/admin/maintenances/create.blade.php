@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tạo yêu cầu bảo trì</h4>
                    <span class="ml-1">Chọn thiết bị cần bảo trì</span>
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

                        <form action="{{ route('maintenances.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="device_id">Thiết bị</label>
                                <select name="device_id" id="device_id" class="form-control select2 @error('device_id') is-invalid @enderror">
                                    <option value="">Chọn thiết bị</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}" {{ old('device_id') == $device->id ? 'selected' : '' }}>
                                            {{ $device->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('device_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="device_items_container" style="display: none;">
                                <div class="form-group">
                                    <label>Thiết bị chi tiết</label>
                                    <div id="device_items_list" class="border rounded p-3">
                                        <div class="text-center text-muted">Vui lòng chọn thiết bị trước</div>
                                    </div>
                                    @error('device_items')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type">Loại bảo trì</label>
                                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                    <option value="periodic" {{ old('type') == 'periodic' ? 'selected' : '' }}>Định kỳ</option>
                                    <option value="repair" {{ old('type') == 'repair' ? 'selected' : '' }}>Sửa chữa</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="start_date">Ngày bắt đầu</label>
                                <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Tạo yêu cầu</button>
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
                url: `/api/device-items/${deviceId}/available-for-maintenance`,
                method: 'GET',
                beforeSend: function() {
                    $('#device_items_list').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>');
                    $('#device_items_container').show();
                },
                success: function(response) {
                    if (response.length === 0) {
                        $('#device_items_list').html('<div class="alert alert-warning">Không có thiết bị chi tiết nào có thể bảo trì.</div>');
                        return;
                    }

                    let html = '<div class="row">';
                    response.forEach(function(item) {
                        html += `
                            <div class="col-md-4 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                           id="device_item_${item.id}"
                                           name="device_items[]"
                                           value="${item.id}"
                                           ${old('device_items') && old('device_items').includes(item.id.toString()) ? 'checked' : ''}>
                                    <label class="custom-control-label" for="device_item_${item.id}">
                                        ${item.code} - ${item.name}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    $('#device_items_list').html(html);
                },
                error: function(xhr, status, error) {
                    $('#device_items_list').html('<div class="alert alert-danger">Không thể tải danh sách thiết bị. Vui lòng thử lại sau.</div>');
                }
            });
        }
    });
</script>
@endsection
