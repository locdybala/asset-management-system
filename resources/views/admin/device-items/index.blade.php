<td>
    <div class="d-flex">
        <a href="{{ route('qrcode.show', $item->id) }}" class="btn btn-info btn-sm mr-1" title="QR Code">
            <i class="fa fa-qrcode"></i>
        </a>
        <a href="{{ route('device-items.edit', $item->id) }}" class="btn btn-warning btn-sm mr-1">
            <i class="fa fa-edit"></i>
        </a>
        <form action="{{ route('device-items.destroy', $item->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                <i class="fa fa-trash"></i>
            </button>
        </form>
    </div>
</td> 