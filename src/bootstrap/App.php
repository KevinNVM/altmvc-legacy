<?php

class App
{

    public $uri, $method;

    public function __construct()
    {
        $this->uri = strtolower($_SERVER['REQUEST_URI']);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Main application's entry point
     *
     * @return void
     */
    public function run()
    {
        $this->loadClasses();

        $env = new Env('env');
        $env->load();

        $router = new Router();
        $routerPath = 'Routes.php'; # Select router to use

        require_once dirname(__FILE__) . "/../app/$routerPath";

        // use router on running app
        $page = $router->useRouter(
            $this->uri,
            $this->method
        );

        $this->render($page);
    }

    /**
     * Render the page
     *
     * @param string $page
     * @return void
     */
    private function render($page)
    {
        echo $page;
    }

    /**
     * Load classes located on Core's folder and other Utility class folder
     *
     * @return void
     */
    private function loadClasses()
    {
        require 'autoload.php';
    }
}

return new App();