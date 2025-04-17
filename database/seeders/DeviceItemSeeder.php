<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = DB::table('devices')->get();
        $now = now();

        foreach ($devices as $device) {
            // Mỗi thiết bị có 3-7 bản sao ngẫu nhiên
            $quantity = rand(3, 7);

            for ($i = 1; $i <= $quantity; $i++) {
                // Lấy supplier ngẫu nhiên từ bảng suppliers
                $supplier = DB::table('suppliers')->inRandomOrder()->first(); // lấy supplier ngẫu nhiên

                DB::table('device_items')->insert([
                    'device_id'     => $device->id,
                    'code'          => 'DEV-' . $device->id . '-' . $i,
                    'serial_number' => 'SN-' . strtoupper(Str::random(8)),
                    'status'        => 'available',
                    'supplier_id'   => $supplier->id, // dùng ID của nhà cung cấp ngẫu nhiên
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }
    }
}
