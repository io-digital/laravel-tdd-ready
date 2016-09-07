<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\Contracts\Repositories\UserRepository;

class UserControllerTest extends TestCase
{
    public function __construct(){

    }
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(UserRepository::class);
        $this->mock('App\Models\Contracts\Repositories\UserRepository');
    }

    public function testIndexUsers()
    {
        $this->mock->shouldReceive('paginate')->once();
        $response = $this->call('GET', 'users');
        $this->assertResponseOk();
        $this->assertViewHas('items');
    }

    public function testUserCreate()
    {
        $response = $this->call('GET', 'users/create');
        $this->assertResponseOk();
    }

    public function testUserStoreValidationFails()
    {
        $response = $this->call('POST', 'users', []);
        $this->assertSessionHasErrors('name');
        $this->assertSessionHasErrors('email');
        $this->assertSessionHasErrors('password');
    }

    public function testUserStoreValidationPass()
    {
        $newUser = factory($this->repo->modelName())->make([
            'password' => 'testpass',
            'password_confirmation' => 'testpass'
        ])->toArray();

        $storeUserRequestMock = Mockery::mock('App\Http\Requests\StoreUser');
        $storeUserRequestMock->shouldReceive('all')->once()->andReturn(true);

        App::instance('App\Http\Requests\StoreUser', $storeUserRequestMock);

        $this->mock->shouldReceive('create')->once();
        $response = $this->call('POST', 'users', $newUser);
        $this->assertRedirectedToAction('UserController@index');
    }

    public function testUserShowNotFound(){

        $this->mock->shouldReceive('find')->once()->andReturnNull();
        $response = $this->call('GET', 'users/1');
        $this->assertResponseStatus('404');
    }

    public function testUserShow(){

        $this->mock->shouldReceive('find')->once()->andReturnSelf();
        $response = $this->call('GET', 'users/1');
        $this->assertResponseOk();
        $this->assertViewHas('item');
    }

    public function testUserEditNotFound(){

        $this->mock->shouldReceive('find')->once()->andReturnNull();
        $response = $this->call('GET', 'users/1/edit');
        $this->assertResponseStatus('404');
    }

    public function testUserEdit(){

        $this->mock->shouldReceive('find')->once()->andReturnSelf();
        $response = $this->call('GET', 'users/1/edit');
        $this->assertResponseOk();
        $this->assertViewHas('item');
    }

    public function testUserUpdateValidationFails()
    {
        $response = $this->call('PUT', 'users/1', []);
        $this->assertSessionHasErrors('name');
        $this->assertSessionHasErrors('email');
        $this->assertSessionHasErrors('password');
    }

    public function testUserUpdateValidationPass()
    {
        $newUser = factory($this->repo->modelName())->make([
            'password' => 'testpass',
            'password_confirmation' => 'testpass'
        ])->toArray();

        $updateUserRequestMock = Mockery::mock('App\Http\Requests\UpdateUser');
        $updateUserRequestMock->shouldReceive('all')->once()->andReturn(true);

        App::instance('App\Http\Requests\UpdateUser', $updateUserRequestMock);

        $this->mock->shouldReceive('edit')->once();
        $response = $this->call('PUT', 'users/1', $newUser);
        $this->assertRedirectedToAction('UserController@index');
    }

    public function testUserDestroyNotFound(){

        $this->mock->shouldReceive('find')->once()->andReturnNull();
        $response = $this->call('DELETE', 'users/1');
        $this->assertResponseStatus('404');
    }

    public function testUserDestroy(){

        $this->mock->shouldReceive('find')->once()->andReturnSelf();
        $this->mock->shouldReceive('delete')->once();
        $response = $this->call('DELETE', 'users/1');
        $this->assertRedirectedToAction('UserController@index');
    }
}
