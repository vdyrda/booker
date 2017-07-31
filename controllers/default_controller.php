<?php
class DefaultController
{
    public function run($action = 'index')
    {
        if (!method_exists($this, $action)) {
            $action = 'index';
        }
        return $this->$action();
    }

    public function index()
    {
        include VIEW_PATH.'view_default.php';
    }
}

