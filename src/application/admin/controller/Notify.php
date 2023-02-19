<?php
namespace app\admin\controller;

use app\admin\model\Notify as NotifyModel;

class Notify extends Base
{
    protected $NotifyModel;

    public function __construct()
    {
        parent::__construct();
        $this->NotifyModel = new NotifyModel();
    }

    public function index()
    {
        $title = '公告管理';
        return $this->fetch('Index', ['title' => $title]);
    }
    
    public function getNotifyList()
    {
        return $this->NotifyModel->getNotifyList();
    }

    public function editNotify()
    {
        return $this->NotifyModel->editNotify();
    }

    public function deleteNotify()
    {
        return $this->NotifyModel->deleteNotify();
    }

}
