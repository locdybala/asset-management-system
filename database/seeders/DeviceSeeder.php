<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\Device;
use App\Models\Category;
use App\Models\Unit;
use App\Models\DeviceItem;
use Faker\Factory as Faker;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy danh sách danh mục và đơn vị tính
        $categories = Category::all();
        $units = Unit::all();

        // Tạo dữ liệu mẫu cho thiết bị
        $devices = [
            [
                'name' => 'Máy tính để bàn',
                'description' => 'Máy tính để bàn phục vụ học tập và nghiên cứu',
                'is_fixed' => true
            ],
            [
                'name' => 'Máy chiếu',
                'description' => 'Máy chiếu phục vụ giảng dạy',
                'is_fixed' => true
            ],
            [
                'name' => 'Laptop',
                'description' => 'Laptop phục vụ học tập và nghiên cứu',
                'is_fixed' => false
            ],
            [
                'name' => 'Máy in',
                'description' => 'Máy in phục vụ văn phòng',
                'is_fixed' => true
            ],
            [
                'name' => 'Máy quét',
                'description' => 'Máy quét tài liệu',
                'is_fixed' => false
            ],
            [
                'name' => 'Máy ảnh',
                'description' => 'Máy ảnh phục vụ ghi hình',
                'is_fixed' => false
            ],
            [
                'name' => 'Máy quay phim',
                'description' => 'Máy quay phim phục vụ ghi hình',
                'is_fixed' => false
            ],
            [
                'name' => 'Bàn ghế',
                'description' => 'Bàn ghế phục vụ học tập',
                'is_fixed' => true
            ],
            [
                'name' => 'Tủ đựng tài liệu',
                'description' => 'Tủ đựng tài liệu văn phòng',
                'is_fixed' => true
            ],
            [
                'name' => 'Điều hòa',
                'description' => 'Điều hòa không khí',
                'is_fixed' => true
            ]
        ];

        foreach ($devices as $deviceData) {
            // Chọn ngẫu nhiên một danh mục và đơn vị tính
            $category = $categories->random();
            $unit = $units->random();

            // Tạo thiết bị
            $device = Device::create([
                'name' => $deviceData['name'],
                'description' => $deviceData['description'],
                'category_id' => $category->id,
                'unit_id' => $unit->id
            ]);

            // Tạo số lượng thiết bị con ngẫu nhiên (từ 3 đến 10)
            $quantity = rand(3, 10);
            for ($i = 1; $i <= $quantity; $i++) {
                DeviceItem::create([
                    'device_id' => $device->id,
                    'code' => $device->code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'status' => $faker->randomElement(['available', 'in_use', 'maintenance', 'broken']),
                    'is_fixed' => $deviceData['is_fixed']
                ]);
            }
        }
    }
}
