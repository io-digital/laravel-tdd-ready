<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Contracts\Repositories\UserRepository;

class UserModelTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(UserRepository::class);
        $this->password = 'test_pass';
    }

    private function _getValidUserArray(){

        $newUser = factory($this->repo->modelName())->make([])->toArray();
        $newUser['password'] = $this->password;
        $newUser['password_confirmation'] = $newUser['password'];
        return $newUser;
    }

    public function testUserCreateModel()
    {
        $newUser = factory($this->repo->modelName())->make([])->toArray();
        $newUser['password'] = $this->password;
        $savedUser = $this->repo->create($newUser);

        $this->assertNotNull($savedUser->id);
        $this->assertTrue(password_verify($this->password, $savedUser->password));
    }

    public function testUserDelete()
    {
        $user = factory($this->repo->modelName())->create();
        $user->destroy();
        $this->assertNull($user->find($user->id));
    }

}
