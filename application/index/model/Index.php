<?php
namespace app\index\model;
use think\Db;
use think\Model;
use think\facade\Session;

class Index extends Model
{
    public function clockin()
    {
        $userid = Session::get('userid');
        if (empty($userid)) return returnJsonData(201, 'Please login first');
    
        $today = date('Y-m-d');
        $data = Db::table('users')->where('id', $userid)->field('sign_time, money, sign_days')->find();
        $sign_days = 1;
        if ($data['sign_time']) {
            if ($today == date('Y-m-d', $data['sign_time'])) return returnJsonData(201, '今天已经签到了');
            if ($today == date('Y-m-d', strtotime('+1 day', $data['sign_time']))) $sign_days = $data['sign_days'] + 1;
        }
        $coin = Db::name('setting')->value('money');
        $rate = [1, [1, 10], [10, 20], [20, 30], [30, 40]];
        $money = $coin * (isset($rate[$sign_days]) ? rand(...$rate[$sign_days]) : 1);
        $res = Db::table('users')->where('id', $userid)->update(['money' => $data['money'] + $money, 'sign_time' => time(), 'sign_days' => $sign_days]);
        if ($res) {
            $log = [
                'uid' => $userid,
                'money' => $money,
                'type' => 2,
                'first' => $data['money'],
                'last' => $data['money'] + $money,
                'time' => time()
            ];
            Db::table('money')->insert($log);
            return returnJsonData(200, 'success, +' . $money);
        } else {
            return returnJsonData(201, 'error');
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