<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Contracts\Repositories\UserRepository;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(UserRepository::class);
        $this->mock('App\Models\Contracts\Repositories\UserRepository');
        $this->password = 'test_pass';
    }

    public function testIndexUsers()
    {
        $this->mock->shouldReceive('paginate')->once();
        $response = $this->call('GET', 'users');
        $this->assertResponseOk();
        $this->assertViewHas('items');
    }

    public function testIndexCreate()
    {
        $response = $this->call('GET', 'users/create');
        $this->assertResponseOk();
        //$this->assertViewHas('items');
    }

    /*public function testCreateUser()
    {
        $newUser = factory($this->repo->modelName())->make([])->toArray();
        $newUser['password'] = $this->password;
        $savedUser = $this->repo->create($newUser);

        $this->assertNotNull($savedUser->id);
        $this->assertTrue(password_verify($this->password, $savedUser->password));
    }*/

    /*public function testCreateUserValidation()
    {
        $this->mock->shouldReceive('all')->once();

        $response = $this->call('GET', 'users');

        $this->assertResponseOk();
    }   
    */
}
