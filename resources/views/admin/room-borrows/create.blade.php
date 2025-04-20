@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tạo phiếu mượn phòng mới</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('room-borrows.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Phòng <span class="text-danger">*</span></label>
                                <select name="room_id" class="form-control @error('room_id') is-invalid @enderror" required>
                                    <option value="">Chọn phòng</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }} ({{ $room->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Ngày mượn <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="borrow_date" class="form-control @error('borrow_date') is-invalid @enderror"
                                    value="{{ old('borrow_date') }}" required>
                                @error('borrow_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Ngày trả <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="return_date" class="form-control @error('return_date') is-invalid @enderror"
                                    value="{{ old('return_date') }}" required>
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Lý do mượn <span class="text-danger">*</span></label>
                                <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Thiết bị di động muốn mượn</label>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Mã thiết bị</th>
                                                <th>Tên thiết bị</th>
                                                <th>Trạng thái</th>
                                                <th>Chọn</th>
                                            </tr>
                                        </thead>
                                        <tbody id="mobile-devices-table">
                                            <!-- Dữ liệu sẽ được thêm bằng JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Tạo phiếu mượn</button>
                                <a href="{{ route('room-borrows.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
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
        // Lấy danh sách thiết bị di động khi chọn phòng
        $('select[name="room_id"]').change(function() {
            const roomId = $(this).val();
            if (roomId) {
                $.get(`/admin/rooms/${roomId}/mobile-devices`, function(devices) {
                    const tbody = $('#mobile-devices-table');
                    tbody.empty();

                    devices.forEach((device, index) => {
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${device.code}</td>
                                <td>${device.device.name}</td>
                                <td>
                                    ${device.status === 'available' ?
                                        '<span class="badge badge-success">Có sẵn</span>' :
                                        '<span class="badge badge-danger">Không khả dụng</span>'
                                    }
                                </td>
                                <td>
                                    ${device.status === 'available' ?
                                        `<input type="checkbox" name="device_items[]" value="${device.id}">` :
                                        ''
                                    }
                                </td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                });
            } else {
                $('#mobile-devices-table').empty();
            }
        });
    });
</script>
@endsection
