<?php
namespace app\admin\controller;

use app\admin\model\User as UserModel;

class User extends Base
{
    protected $UserModel;

    public function __construct()
    {
        parent::__construct();
        $this->UserModel = new UserModel();
    }

    public function index()
    {   
        $title = '后台首页';
        return $this->fetch('Index', ['title' => $title]);
    }

    public function add()
    {   
        return $this->fetch('Add');
    }

    public function getUserList()
    {
        return $this->UserModel->getUserList();
    }
    
    public function getUserAllInfo()
    {
        return $this->UserModel->getUserAllInfo();
    }

    public function editUserState()
    {
        return $this->UserModel->editUserState();
    }

    public function editUser()
    {
        return $this->UserModel->editUser();
    }

    public function uploadAvatar(){
        return $this->UserModel->uploadAvatar();
    }

    public function deleteUser()
    {   
        return $this->UserModel->deleteUser();
    }
}
