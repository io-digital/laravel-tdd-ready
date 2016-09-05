<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;

use App\Models\Contracts\Repositories\UserRepository;

class UserController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->model = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->model->paginate();
        return view('users.listing')->with(['items' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $this->model->create($request->all());
        return redirect()->action('UserController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->model->find($id);

        if(!$user){
            abort('404');
        }

        return view('users.detail')->with(['item' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->model->find($id);

        if(!$user){
            abort('404');
        }

        return view('users.form')->with(['item' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $id)
    {
        $this->model->edit($id, $request->all());
        return redirect()->action('UserController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->model->find($id);

        if(!$user){
            abort('404');
        }

        $user->destroy();

        return redirect()->action('UserController@index');
    }
}
