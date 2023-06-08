<?php


class Router
{
    private $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array(),
    );

    public function get($path, $handler)
    {
        $path = trim($path, '/');
        $this->routes['GET'][$path] = $handler;
        return $this;
    }

    public function post($path, $handler)
    {
        $path = trim($path, '/');
        $this->routes['POST'][$path] = $handler;
        return $this;
    }

    public function put($path, $handler)
    {
        $path = trim($path, '/');
        $this->routes['PUT'][$path] = $handler;
        return $this;
    }

    public function delete($path, $handler)
    {
        $path = trim($path, '/');
        $this->routes['DELETE'][$path] = $handler;
        return $this;
    }

    public function useRouter($path, $method)
    {
        if (!in_array(strtoupper($method), array('GET', 'POST', 'PUT', 'DELETE')) && empty($path)) {
            throw new Exception('Invalid Parameters!');
        }

        // Get requested route from URL
        $route = preg_replace('/\?.*/', '', $path); // Strip query string from URL

        // Add URL prefix
        $prefix = $_ENV['URL_PREFIX'];



        // dd($_ENV['URL_PREFIX']);
        if (strpos($route, $prefix) === 0) {
            $route = substr($route, strlen($prefix));
        }
        $route = trim($route, '/'); // Strip trailing slashes from URL

        // Check if route exists in routes array for the requested method
        foreach ($this->routes[$method] as $pattern => $handler) {
            // Test if route matches pattern
            $regex = str_replace('/', '\/', $pattern);
            $regex = preg_replace('/\{[a-zA-Z]+\}/', '([a-zA-Z0-9_]+)', $regex);
            $regex = '/^' . $regex . '$/';
            if (preg_match($regex, $route, $matches)) {
                // Remove first element of $matches array, which contains the entire match
                array_shift($matches);

                // Call handler with parameter values as arguments

                // $output = call_user_func_array($handler, $matches);

                /**
                 * Changed how router call the controller and it's method.
                 * To add support for using models in controller using `__constructor()`
                 */

                // $controller = new $handler[0];
                // $output = $controller->$handler[1]($matches); # PHP 5.2 doesn't support spread operator 

                $controller = new $handler[0];
                $output = call_user_func_array(array($controller, $handler[1]), $matches);


                if (is_array($output)) {
                    header("Content-Type: application/json");
                    ob_start();
                    echo json_encode($output);
                    $response = ob_get_clean();
                    return $response;
                } else {
                    ob_start();
                    echo $output;
                    $response = ob_get_clean();
                    return $response;
                }

                return;
            }
        }

        // handle 404 Routes
        header("HTTP/1.1 404 Not Found");
        die('404 ' . "Route $method $path Not Found");
    }





    /**
     * Get all of the routes
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}