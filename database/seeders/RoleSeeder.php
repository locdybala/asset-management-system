<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Tạo các permission
        $permissions = [
            // Quản lý người dùng
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Quản lý khoa
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',

            // Quản lý danh mục
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Quản lý thiết bị
            'view devices',
            'create devices',
            'edit devices',
            'delete devices',

            // Quản lý thiết bị con
            'view device-items',
            'create device-items',
            'edit device-items',
            'delete device-items',

            // Quản lý phiếu mượn
            'view borrows',
            'create borrows',
            'edit borrows',
            'delete borrows',
            'approve borrows',
            'return borrows',
            'cancel borrows',

            // Quản lý bảo trì
            'view maintenances',
            'create maintenances',
            'edit maintenances',
            'delete maintenances',

            // Quản lý phòng
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',

            // Quản lý mượn phòng
            'view room-borrows',
            'create room-borrows',
            'edit room-borrows',
            'delete room-borrows',
            'approve room-borrows',
            'return room-borrows',
            'cancel room-borrows',

            // Báo cáo
            'view reports',
            'export reports'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Tạo role admin và gán tất cả permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Tạo role nhân viên và gán permissions
        $staff = Role::create(['name' => 'staff']);
        $staffPermissions = [
            // Quản lý người dùng
            'view users',

            // Quản lý khoa
            'view departments',

            // Quản lý danh mục
            'view categories',

            // Quản lý thiết bị
            'view devices',
            'create devices',
            'edit devices',

            // Quản lý thiết bị con
            'view device-items',
            'create device-items',
            'edit device-items',

            // Quản lý phiếu mượn
            'view borrows',
            'create borrows',
            'edit borrows',
            'approve borrows',
            'return borrows',
            'cancel borrows',

            // Quản lý bảo trì
            'view maintenances',
            'create maintenances',
            'edit maintenances',

            // Quản lý phòng
            'view rooms',
            'create rooms',
            'edit rooms',

            // Quản lý mượn phòng
            'view room-borrows',
            'create room-borrows',
            'edit room-borrows',
            'approve room-borrows',
            'return room-borrows',
            'cancel room-borrows',

            // Báo cáo
            'view reports',
            'export reports'
        ];
        $staff->givePermissionTo($staffPermissions);

        // Tạo role giảng viên và gán permissions
        $teacher = Role::create(['name' => 'teacher']);
        $teacherPermissions = [
            // Quản lý thiết bị
            'view devices',

            // Quản lý thiết bị con
            'view device-items',

            // Quản lý phiếu mượn
            'view borrows',
            'create borrows',
            'edit borrows',

            // Quản lý bảo trì
            'view maintenances',
            'create maintenances',

            // Quản lý phòng
            'view rooms',

            // Quản lý mượn phòng
            'view room-borrows',
            'create room-borrows',
            'edit room-borrows',

            // Báo cáo
            'view reports'
        ];
        $teacher->givePermissionTo($teacherPermissions);

        // Tạo role sinh viên và gán permissions
        $student = Role::create(['name' => 'student']);
        $studentPermissions = [
            // Quản lý thiết bị
            'view devices',

            // Quản lý thiết bị con
            'view device-items',

            // Quản lý phiếu mượn
            'view borrows',
            'create borrows',

            // Quản lý bảo trì
            'view maintenances',

            // Quản lý phòng
            'view rooms',

            // Quản lý mượn phòng
            'view room-borrows',
            'create room-borrows',

            // Báo cáo
            'view reports'
        ];
        $student->givePermissionTo($studentPermissions);
    }
}
