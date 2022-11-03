<?php
namespace app\index\model;
use GatewayWorker\Lib\Gateway;
use think\facade\Session;
use think\Model;
use think\Db;

class Push extends Model
{
    public function index($data){
        Gateway::sendToGroup('All', json_encode($data));
        return returnJsonData(200,'success');
    }
    public function notify()
    {
        Gateway::$registerAddress = '127.0.0.1:1236';
        $client_id = $_POST['client_id'];
        Gateway::joinGroup($client_id, 'All');
    }
    
}
