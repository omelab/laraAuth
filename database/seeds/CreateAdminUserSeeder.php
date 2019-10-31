<?php

use Illuminate\Database\Seeder; 
use App\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $admin = Admin::create([
        	'name' => 'Shamim Ashraf', 
        	'email' => 'shamim@gmail.com',
        	'password' => bcrypt('123456')
        ]);
    
        $role = Role::create(['guard_name' => 'admin', 'name' => 'Admin']);
   
        $permissions = Permission::pluck('id','id')->where('guard_name', 'admin')->all(); 
  
        $role->syncPermissions($permissions);
   
        $admin->assignRole([$role->id]);
    }
}
