<?php

define('VIEWS_PATH', dirname(__FILE__) . '/../app/Views/');
define('VIEWS_LAYOUT_PATH', dirname(__FILE__) . '/../app/Views/layouts/');
define('DEFAULT_LAYOUT_PATH', 'main');

class View
{
    protected $view;
    protected $params;
    protected $layout;
    protected $title;

    public function __construct($view, $params = array(), $layout = '', $title = '')
    {
        $this->view = $view;
        $this->params = $params;
        $this->layout = $layout;
        $this->title = $title;

        if (empty($this->title)) {
            $this->title = isset($_ENV["APP_NAME"]) ? (string) $_ENV["APP_NAME"] : '';
        }
    }

    public static function make($view, $params = array(), $title = '')
    {
        return new self($view, $params, $title);
    }

    public function title($title)
    {
        return new self($this->view, $this->params, $this->layout, $title);
    }

    public function render()
    {
        $viewPath = VIEWS_PATH . $this->view . '.php';
        $layoutPath = VIEWS_LAYOUT_PATH . $this->layout . '.php';

        $viewString = '';
        $layoutString = '';

        if (!file_exists($viewPath)) {
            throw new Exception('ViewNotFoundException');
        }

        if (!empty($this->layout)) {
            if (!file_exists($layoutPath)) {
                throw new Exception('LayoutNotFoundException');
            } else {
                $layoutString = $this->getLayout($layoutPath);
            }
        }

        $viewString = $this->getView($viewPath);

        $output = '';
        if (!empty($this->layout)) {
            $output = str_replace(
                array('%%BODY%%', '%%TITLE%%'),
                array($viewString, $this->title),
                $layoutString
            );
        } else {
            $output = $viewString;
        }

        return $output;
    }

    public function withLayout($layout = DEFAULT_LAYOUT_PATH)
    {
        $this->layout = $layout;

        return new self($this->view, $this->params, $this->layout);
    }

    private function getLayout($layoutPath)
    {
        ob_start();
        require($layoutPath);
        return ob_get_clean();
    }

    private function getView($viewPath)
    {
        extract($this->params);
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    public function __toString()
    {
        try {
            return $this->render();
        } catch (Exception $e) {
            return 'View\'s File Not Found';
        }
    }
}