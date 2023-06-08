<?php

class Controller
{
    /**
     * This method is responsible for loading a model based on the provided model name.
     *
     * @param mixed $modelName,... Model Name(s) to be loaded.
     * @return void An instance of the loaded model.
     * @throws Exception If the model class or the model file is not found.
     */
    protected function loadModel()
    {
        foreach (func_get_args() as $modelName) {
            $modelPath = dirname(__FILE__) . "/../app/Models/{$modelName}.php";

            if (file_exists($modelPath)) {
                require_once $modelPath;

                if (!class_exists($modelName)) {
                    throw new Exception("Model class not found: {$modelName}");
                }
            } else {
                throw new Exception("Model file not found: {$modelPath}");
            }
        }
    }

    /**
     * Parse data sent to the body as a json
     *
     * @return object|array
     */
    public function parseBody()
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true); // Assuming the body contains JSON data

        return $data;
    }
}