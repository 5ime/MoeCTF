<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Index as IndexModel;

class Index extends Controller
{
    protected $setting;

    function initialize()
    {
        parent::initialize();
        $info = new IndexModel();
        $arr = json_decode($info->getSettingsInfo()->getContent(),true);
        $this->setting = $arr['data'];
    }

    public function index()
    {
        $this->assign([
            'title' => $this->setting['title'],
            'index' => $this->setting['title'],
            'keywords' => $this->setting['keywords'],
            'description' => $this->setting['desc'],
        ]);
        return $this->fetch();
    }

    public function ranking()
    {
        $this->assign([
            'title' => 'Rankings - ' . $this->setting['title'],
            'index' => $this->setting['title'],
            'keywords' => $this->setting['keywords'],
            'description' => $this->setting['desc'],
        ]);
        return $this->fetch();
    }

    public function about()
    {
        $this->assign([
            'title' => 'About - ' . $this->setting['title'],
            'index' => $this->setting['title'],
            'keywords' => $this->setting['keywords'],
            'description' => $this->setting['desc'],
        ]);
        return $this->fetch();
    }

    public function notify()
    {
        $this->assign([
            'title' => 'Notify - ' . $this->setting['title'],
            'index' => $this->setting['title'],
            'keywords' => $this->setting['keywords'],
            'description' => $this->setting['desc'],
        ]);
        return $this->fetch();
    }

    public function verify()
    {
        $this->assign([
            'title' => '邮箱激活 - ' . $this->setting['title'],
            'index' => $this->setting['title'],
            'keywords' => $this->setting['keywords'],
            'description' => $this->setting['desc'],
            'subtitle' => '',
            'content' => ''
        ]);
        return $this->fetch();
    }

    public function clockin(){
        $index = new IndexModel();
        return $index->clockin();
    }

    public function verifyEmail(){
        $index = new IndexModel();
        return $index->verifyEmail();
    }
}
