<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Contracts\Repositories\UserRepository;

class ViewAllUsersTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(UserRepository::class);
        $this->password = 'test_pass';
    }

    public function testViewAllUsers()
    {
        $numberOfUsers = 10;

        for ($i = 0; $i < $numberOfUsers; $i ++ ){
            $newUser = factory($this->repo->modelName())->create([]);
        }

        $allUsers = $this->repo->all();

        $this->assertEquals($numberOfUsers, $allUsers->count());
    }
}
