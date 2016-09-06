<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Contracts\Repositories\UserRepository;

class RepositoryFunctionTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(UserRepository::class);
        $this->testUser = factory($this->repo->modelName())->create(); // tests modelName() already
    }

    public function testAll()
    {
        $this->assertEquals($this->repo->all()->count(), 1);
    }

    public function testFind()
    {
        $this->assertNotNull($this->repo->find($this->testUser->id));
    }

    public function testFindBy()
    {
        $this->assertNotNull($this->repo->findBy('id', $this->testUser->id));
    }

    public function testFindWhere()
    {
        $this->assertEquals($this->repo->findWhere(function($query){
            return $query->where('id', $this->testUser->id);
        })->count(), 1);

        $this->assertEquals($this->repo->findWhere(['id' => $this->testUser->id])->count(), 1);
        $this->assertEquals($this->repo->findWhere([['id', '=', $this->testUser->id]])->count(), 1);

        $this->assertEquals($this->repo->findWhere([
            'id' => $this->testUser->id, 
            'name' => ''],
            ['*'],
            true
        )->count(), 1);

        $this->assertEquals($this->repo->findWhere([
            'id' => $this->testUser->id, 
            'name' => '']
        )->count(), 0);
    }

    public function testFindWhereIn()
    {
        $this->assertEquals($this->repo->findWhereIn('id', [$this->testUser->id])->count(), 1);
    }

    public function testSimplePaginate()
    {
        $this->assertEquals($this->repo->simplePaginate()->count(), 1);
    }

    public function testPaginate()
    {
        $this->assertEquals($this->repo->paginate()->count(), 1);
    }
}
