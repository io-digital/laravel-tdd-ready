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

    public function testUserCreateModel()
    {
        $newUser = factory($this->repo->modelName())->make([
            'password' => $this->password,
            'password_confirmation' => $this->password
        ])->toArray();

        $savedUser = $this->repo->create($newUser);

        $this->assertNotNull($savedUser->id);
        $this->assertTrue(password_verify($this->password, $savedUser->password));
    }

    public function testUserEdit()
    {
        $user = factory($this->repo->modelName())->create();
        $newName = 'New Name';

        $user = $this->repo->edit($user->id, ['name' => $newName]);
        $this->assertEquals($user->name, $newName);
    }

    public function testUserDelete()
    {
        $user = factory($this->repo->modelName())->create();
        $this->repo->delete($user->id);
        $this->assertNull($user->find($user->id));
    }

}
