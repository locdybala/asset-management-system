@foreach($deviceItems as $item)
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="device_item_{{ $item->id }}"
                           name="device_items[]" value="{{ $item->id }}">
                    <label class="custom-control-label" for="device_item_{{ $item->id }}">
                        <strong>Mã thiết bị:</strong> {{ $item->code }}<br>
                        <strong>Trạng thái:</strong>
                        <span class="badge badge-{{ $item->status == 'available' ? 'success' : 'warning' }}">
                            {{ $item->status == 'available' ? 'Sẵn sàng' : 'Đang mượn' }}
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
@endforeach
