<?php

class UserController extends Controller
{

    public function __construct()
    {
        $this->loadModel('User');
    }

    public function index()
    {
        $user = new User;

        $data = array(
            'users' => $user->getAll()
        );

        return View::make('users/index', $data)
            ->title('Users')
            ->withLayout();

    }

    public function show()
    {
        //...
    }

    public function create()
    {
        //...
    }

    public function store()
    {
        $data = $this->parseBody();

        $user = new User;

        $user->create(
            array(
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => md5($data['password']),
                'created_at' => now()
            )
        );

        redirect(url('/'));
    }

    public function edit()
    {
        //...
    }

    public function update($id)
    {
        $body = $this->parseBody();

        $user = new User;

        $user->update($id, $body);

        redirect(url());
    }

    public function destroy($id)
    {
        $user = new User;
        $user->delete($id);

        redirect(url());
    }

}