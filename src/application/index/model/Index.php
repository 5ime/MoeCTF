<?php
namespace app\index\model;
use think\Db;
use think\Model;
use think\facade\Session;

class Index extends Model
{
    public function clockin()
    {
        if (empty(Session::get('userid'))) {
            return returnJsonData(201,'Please login first');
        }
        $data = Db::table('users')->where('id',Session::get('userid'))->field('sign_time,money,sign_days')->find();
        $sign_days = 1;
        if ($data['sign_time']) {
            if(date('Y-m-d',time()) == date('Y-m-d',$data['sign_time'])){

                return returnJsonData(201,'今天已经签到了');
            }
            if(date('Y-m-d',time()) == date('Y-m-d',strtotime('+1 day',$data['sign_time']))){
                $sign_days = $data['sign_days'] + 1;
            }
        }
        $firstMoney = $data['money'];
        $coin = Db::name('setting')->value('money');
        if ($sign_days < 7) {
            $money = $coin;
        }elseif ($sign_days == 7) {
            $money = $coin * rand(1,10);
        }elseif ($sign_days == 14) {
            $money = $coin * rand(10,20);
        }elseif ($sign_days == 21) {
            $money = $coin * rand(20,30);
        }elseif ($sign_days == 28) {
            $money = $coin * rand(30,40);
        }
        $data = ['uid' => Session::get('userid'), 'money' => $money, 'type' => 2, 'first' => $firstMoney, 'last' => $firstMoney + $money, 'time' => time()];
        Db::table('money')->insert($data);
        $res = Db::table('users')->where('id',Session::get('userid'))->update(['money' => $firstMoney + $money,'sign_time' => time(),'sign_days' => $sign_days]);
        if ($res) {
            return returnJsonData(200,'success, +'.$money);
        }else{
            return returnJsonData(201,'error');
        }
    }    

    public function getSettingsInfo()
    {
        $data = Db::name('setting')->find();
        if ($data) {
            $data['state'] = $data['state'] == 0 ? 'Hidden' : 'Display';
            $data['verify'] = $data['verify'] == 0 ? 'False' : 'True';
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }

    public function verifyEmail()
    {
        $token = base64_decode(input('id'));
        $updated = Db::name('users')->where('token', $token)->where('verify', 0)->update(['verify' => 1]);
        if ($updated) {
            return returnJsonData(200, 'Verify success');
        } else {
            return returnJsonData(201, 'Verify failed or already verified');
        }
    }
}