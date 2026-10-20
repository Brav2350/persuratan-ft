<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@ft.ac.id', 'role' => 'Super Admin', 'nip' => '199001012020011001'],
            ['name' => 'Admin Fakultas', 'email' => 'admin@ft.ac.id', 'role' => 'Admin Fakultas', 'nip' => '199202022021012002'],
            ['name' => 'Staff BAAK', 'email' => 'baak@ft.ac.id', 'role' => 'BAAK', 'nip' => '199303032022012003'],
            ['name' => 'Dekan FT', 'email' => 'dekan@ft.ac.id', 'role' => 'Penandatangan', 'nip' => '197505052000031005'],
        ];

        foreach ($users as $u) {
            // updateOrCreate mencegah error Duplicate Entry
            $user = User::updateOrCreate(
                ['email' => $u['email']], 
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                    'nip' => $u['nip'],
                    'is_active' => true,
                ]
            );
            
            // Sinkronkan role jika role tersebut sudah dibuat oleh RoleAndPermissionSeeder
            if (Role::where('name', $u['role'])->exists()) {
                $user->syncRoles([$u['role']]);
            }
        }
    }
}