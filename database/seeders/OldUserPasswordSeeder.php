<?php

namespace Database\Seeders;

use App\Users\Models\OldUserPassword;
use App\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OldUserPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $recordCount = 50;

        for ($i = 0; $i < $recordCount; $i++) {
            $oldPassword = Hash::make(Str::random(25));
            $data[] = [
                'user_id' => User::get('user_id')->random()->user_id,
                'old_password' => $oldPassword,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ];
        }

        foreach (array_chunk($data, 1000) as $chunk) {
            OldUserPassword::insert($chunk);
        }
    }
}
