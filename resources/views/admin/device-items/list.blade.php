@foreach($deviceItems as $item)
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input device-item-radio" 
                           id="device_item_{{ $item->id }}"
                           name="device_item" 
                           value="{{ $item->id }}"
                           data-status="{{ $item->status }}"
                           {{ old('device_item') == $item->id ? 'checked' : '' }}>
                    <label class="custom-control-label" for="device_item_{{ $item->id }}">
                        <strong>Mã thiết bị:</strong> {{ $item->code }}<br>
                        <strong>Trạng thái:</strong>
                        <span class="badge badge-{{ $item->status == 'available' ? 'success' : 'warning' }}">
                            @switch($item->status)
                                @case('available')
                                    Sẵn sàng
                                    @break
                                @case('in_use')
                                    Đang sử dụng
                                    @break
                                @case('maintenance')
                                    Đang bảo trì
                                    @break
                                @default
                                    {{ $item->status }}
                            @endswitch
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
@endforeach
