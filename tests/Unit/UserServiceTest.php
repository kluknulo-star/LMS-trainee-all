<?php

namespace Tests\Unit;

use App\Users\Services\UserService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $this->clearTestingData();
        $userData = [
            'surname' => 'TestSurname',
            'name' => 'TestName',
            'patronymic' => 'TestPatronymic',
            'email' => 'TestEmail@test.test',
            'password' => 'passWord12345!',
        ];

        $userService = app(UserService::class);
        $userService->store($userData);

        $dbUser = DB::table('users')->where('email', 'TestEmail@test.test')->first();
        $this->assertTrue($dbUser->email == $userData['email']);
    }

    public function testUpdateUser()
    {
        $userData = ['surname' => 'IvanovTestingBot'];
        $dbUser = DB::table('users')->select('user_id')->where('email', 'TestEmail@test.test')->first();

        $userService = app(UserService::class);
        $dbUser = $userService->update($userData, $dbUser->user_id);

        $this->assertTrue($dbUser->surname == $userData['surname']);
    }

    public function testSoftDeleteUser()
    {
        $dbUser = DB::table('users')->select('user_id')->where('email', 'TestEmail@test.test')->first();

        $userService = app(UserService::class);
        $result = $userService->destroy($dbUser->user_id);

        $this->assertTrue($result);
    }

    public function testRestoreUser()
    {
        $dbUser = DB::table('users')->select('user_id')->where('email', 'TestEmail@test.test')->first();

        $userService = app(UserService::class);
        $result = $userService->restore($dbUser->user_id);

        $this->assertTrue($result);
        $this->clearTestingData();
    }

    public function clearTestingData()
    {
        DB::table('users')->where('email', 'TestEmail@test.test')->delete();
    }
}
