<?php

/**
 * Display variable(s) with enhanced formatting and immediately terminate script execution.
 *
 * @param mixed $data,... Variable(s) to be displayed.
 * @return void
 */
function dd()
{
    ob_start();
    foreach (func_get_args() as $data) {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
    echo ob_get_clean();
}

/**
 * Display variable(s) with enhanced formatting.
 *
 * @param mixed $data,... Variable(s) to be displayed.
 * @return void
 */
function dump()
{
    ob_start();
    foreach (func_get_args() as $data) {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
    echo ob_get_clean();
}


/**
 * Create url relative to the app url
 *
 * @return string
 */
function url($path = '')
{

    $baseUrl = $_ENV['BASE_URL'];
    $prefix = $_ENV['URL_PREFIX'];
    $path = substr($path, 1) !== '/' ? $path : "/{$path}";
    $path = substr($path, -1) !== '/' ? $path : "{$path}/";

    $url = "{$baseUrl}{$prefix}{$path}";

    return $url;
}

/**
 * Load and instantiate a model class.
 *
 * @param string $filename Name of the model file (without the .php extension).
 * @return object Instantiated model object.
 * @throws Exception If the model file is not found.
 */
function useModel($filename)
{
    $path = dirname(__FILE__) . "/../app/Models/{$filename}.php";
    if (file_exists($path)) {
        require $path;

        if (!class_exists($filename)) {
            throw new Exception("Model class not found: {$filename}");
        }

    } else {
        throw new Exception('Model Not Found: ' . $path);
    }

    return new $filename;
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function curl($url, $options = array())
{
    $data = isset($options['data']) ? $options['data'] : null;
    $method = isset($options['method']) ? $options['method'] : 'GET';

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    curl_close($ch);

    // Process the response
    if ($response !== false) {
        // Request successful
        return $response;
    } else {
        // Request failed
        return false;
    }

}

function now()
{
    return date('F j, Y H:i:s', time());
}

function pluralize($word)
{
    $exceptions = array(
        'man' => 'men',
        'woman' => 'women',
        'roof' => 'rooves'
        // Add any other exceptions here
    );

    $irregulars = array(
        'child' => 'children',
        'person' => 'people',
        // Add any other irregular plurals here
    );

    $suffixes = array(
        's',
        'es',
    );

    // Check if the word is an exception or an irregular plural
    if (array_key_exists($word, $exceptions)) {
        return $exceptions[$word];
    } elseif (array_key_exists($word, $irregulars)) {
        return $irregulars[$word];
    }

    // Apply general pluralization rules
    foreach ($suffixes as $suffix) {
        if (substr($word, -1 * strlen($suffix)) === $suffix) {
            return $word;
        }
    }

    return $word . 's'; // Default plural suffix
}