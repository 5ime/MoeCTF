<?php
namespace app\admin\controller;

use app\admin\model\Index as settingModel;

class Index extends Base
{
    protected $settingModel;

    public function __construct()
    {
        parent::__construct();
        $this->settingModel = new settingModel();
    }

    public function index()
    {
        $title = '后台首页';
        return $this->fetch('Index', ['title' => $title]);
    }

    public function setting()
    {
        $title = '站点设置';
        return $this->fetch('Setting', ['title' => $title]);
    }

    public function Submit()
    {
        $title = '提交记录';
        return $this->fetch('Submit', ['title' => $title]);
    }

    public function getSettingsInfo()
    {
        return $this->settingModel->getSettingsInfo();
    }

    public function editSettings()
    {
        return $this->settingModel->editSettings();
    }

    public function getSubmitList()
    {
        return $this->settingModel->getSubmitList();
    }
}
