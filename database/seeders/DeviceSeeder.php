<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('devices')->insert([
            // Thiết bị văn phòng - category_id = 1
            [
                'name' => 'Máy in Canon LBP2900',
                'borrower_type' => 'teacher',
                'category_id' => 1,
                'description' => 'Máy in laser đen trắng, phù hợp cho văn phòng nhỏ',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Máy chiếu Epson EB-X05',
                'borrower_type' => 'both',
                'category_id' => 1,
                'description' => 'Máy chiếu đa năng dùng trong giảng dạy và hội họp',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Máy photocopy Ricoh MP 2501',
                'borrower_type' => 'teacher',
                'category_id' => 1,
                'description' => 'Thiết bị photo đa chức năng, tốc độ cao',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Thiết bị phòng học - category_id = 2
            [
                'name' => 'Bảng trắng từ 1m2',
                'borrower_type' => 'teacher',
                'category_id' => 2,
                'description' => 'Bảng trắng từ tính dùng phấn và bút lông',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bàn học đơn gỗ MDF',
                'borrower_type' => 'student',
                'category_id' => 2,
                'description' => 'Bàn học sinh cấp đại học, khung sắt sơn tĩnh điện',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Thiết bị phòng lab - category_id = 3
            [
                'name' => 'Bộ mô phỏng mạch điện tử cơ bản',
                'borrower_type' => 'student',
                'category_id' => 3,
                'description' => 'Dành cho môn học vi điều khiển, mạch điện tử',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Máy hiện sóng DS1102Z-E',
                'borrower_type' => 'teacher',
                'category_id' => 3,
                'description' => 'Thiết bị đo tín hiệu sóng điện tử, 100MHz',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nguồn DC lập trình GW Instek',
                'borrower_type' => 'both',
                'category_id' => 3,
                'description' => 'Nguồn cấp điện đa năng cho phòng lab',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Thiết bị lưu trữ - category_id = 4
            [
                'name' => 'Ổ cứng SSD Samsung 1TB',
                'borrower_type' => 'teacher',
                'category_id' => 4,
                'description' => 'Ổ cứng tốc độ cao dùng lưu trữ dữ liệu giảng dạy',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'USB SanDisk 128GB',
                'borrower_type' => 'student',
                'category_id' => 4,
                'description' => 'USB tốc độ cao, chuẩn 3.0',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Thiết bị điện tử nhỏ - category_id = 5
            [
                'name' => 'Cảm biến nhiệt độ LM35',
                'borrower_type' => 'student',
                'category_id' => 5,
                'description' => 'Cảm biến nhiệt độ cho các dự án IoT',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Module WiFi ESP8266',
                'borrower_type' => 'student',
                'category_id' => 5,
                'description' => 'Dùng cho kết nối không dây trong các đồ án kỹ thuật',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Thiết bị phụ trợ - category_id = 6
            [
                'name' => 'Bộ tua vít đa năng',
                'borrower_type' => 'both',
                'category_id' => 6,
                'description' => 'Phụ kiện sửa chữa thiết bị điện tử',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cáp HDMI 5m',
                'borrower_type' => 'teacher',
                'category_id' => 6,
                'description' => 'Cáp truyền tín hiệu hình ảnh cho máy chiếu, màn hình',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
