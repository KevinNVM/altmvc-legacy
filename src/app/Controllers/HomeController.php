<?php


class HomeController extends Controller
{
    public function index()
    {
        return View::make('index')
            ->withLayout('main')
            ->title('AltMVC');
    }

    function newUser()
    {
        $user = new User;

        $user->create(
            array(
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => md5($_POST['password']),
                'created_at' => now()
            )
        );

        redirect(url('/'));
    }

    function deleteUser($id)
    {
        $user = new User;

        $user->delete($id);

        redirect(url('/'));
    }

    public function about()
    {
        return View::make(
            'about',
            array(
                'name' => 'Kevin',
                'email' => 'kevindarmawan022@gmail.com',
                'github' => 'https://github.com/kevinnvm'
            )
        )->withLayout('main')->title('About Page');
    }

    public function testParam()
    {
        dump(func_get_args());
    }
}