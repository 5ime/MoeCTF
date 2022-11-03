<?php
namespace app\admin\controller;
use think\facade\Session;
use think\Controller;

class Base extends controller
{
    public function initialize()
    {
        parent::initialize();
        if (!Session::has('userid')) {
            $this->redirect('user/login');
        }elseif(Session::get('userid') != 1){
            $this->redirect('/404');
        }
    }
}