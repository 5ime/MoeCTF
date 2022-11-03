<?php
namespace app\index\controller;

use app\index\model\Index as IndexModel;
use app\index\model\User as UserModel;

class User extends Base
{
    protected $setting;
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $setInfo = new IndexModel();
        $setInfo = $setInfo->getSettingsInfo();
        $setInfo = json_decode($setInfo->getContent(),true);
        $this->setting = $setInfo['data'];
        $this->userModel = new UserModel();
    }
    
    public function home(){
        $info = json_decode($this -> getUserInfo()->getContent(),true);
        if ($info['data']['state'] == 0) {
            $title = $this->setting['title'];
        }else {
            $title = $info['data']['username'] . ' - ' . $this->setting['title'];
        }
        $this->assign([
            'title' => $title,
            'index' => $this->setting['title'],
            'keywords' => $this->setting['keywords'],
            'description' => $this->setting['desc'],
        ]);
        return $this->fetch();
    }

    public function getUserStatus(){
        return $this->userModel->getUserStatus();
    }

    public function login(){
        if(!request()->isPost())
        {
            return $this->fetch();
        }
        return $this->userModel->login(); 
    }

    public function register(){
        if(!request()->isPost())
        {
            if($this->setting['state'] == 'Hidden'){
                $this->assign([
                    'content' => '平台关闭注册'
                ]);
                return $this->fetch('application/error.html');
            }
            return $this->fetch();
        }
        return $this->userModel->register(); 
    }

    public function logout()
    {
        return $this->userModel->logout();
    }

   public function editUserInfo()
    {
        return $this->userModel->editUserInfo(); 
    }

    public function getUserInfo()
    {
        return $this->userModel->getUserInfo(); 
    }

    public function uploadAvatar(){
        return $this->userModel->uploadAvatar(); 
    }

    public function getHomeInfo(){
        return $this->userModel->getHomeInfo(); 
    }

}
