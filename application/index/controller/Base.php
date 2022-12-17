<?php
namespace app\index\controller;
use think\facade\Session;
use think\Controller;

class Base extends controller
{
    public function initialize()
    {
        parent::initialize();
        if (!Session::has('userid') && strtolower($this->request->action()) != 'login' && strtolower($this->request->action()) != 'register') {
            if (strtolower($this->request->action()) == 'home') {
                $this->redirect('user/login');
            } else {
                returnJsonData(201, 'Please login first')->send();
                exit;
            }
        }
    }
}
