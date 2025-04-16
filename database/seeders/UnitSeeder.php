<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::insert([
            [
                'code' => 'C',
                'name' => 'Chiếc',
                'description' => 'Đơn vị dùng cho thiết bị đơn lẻ như máy in, máy chiếu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'B',
                'name' => 'Bộ',
                'description' => 'Dùng cho các thiết bị có nhiều thành phần như bộ máy tính, bộ thực hành',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'O',
                'name' => 'Ổ',
                'description' => 'Đơn vị cho thiết bị lưu trữ như ổ cứng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'H',
                'name' => 'Hộp',
                'description' => 'Dùng cho thiết bị nhỏ, đóng gói như hộp vi mạch',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'Cái',
                'name' => 'Cái',
                'description' => 'Đơn vị phổ biến, dùng chung cho nhiều loại thiết bị',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
