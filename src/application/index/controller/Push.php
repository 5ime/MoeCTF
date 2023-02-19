<?php	
namespace app\index\controller;	
use app\index\model\Push as PushModel;

class Push extends Base
{
    public function index()
    {
        
        $data = [
            'type' => 'notify',
            'msg'  => input('msg'),
        ];
        $pushModel = new PushModel();
        return $pushModel->index($data);
    }
    public function notify()
    {
        $pushModel = new PushModel();
        return $pushModel->notify();
    }
    
}
