<?php
namespace app\index\model;
use think\Db;
use think\Model;
use think\facade\Session;
use think\captcha\Captcha;

class User extends Model
{
    /* 获取用户状态*/

    public function getUserStatus(){
        $arr = Db::table('users')->where('id', Session::get('userid'))->field('avatar,type,money')->find();
        $data = [
            'userid' => Session::get('userid'),
            'username' => Session::get('username'),
            'avatar' => $arr['avatar'],
            'type' => $arr['type'] ? '管理员' : '普通用户',
            'door' => $arr['type'] ? '<a href="/admin">后台管理主页</a>' : ''
        ];
        return returnJsonData(200, 'success', array_filter($data));
    }

    /* 登录 */

    public function login()
    {
        $postData = input('post.');
        $username = $postData['username'] ?? '';
        $password = $postData['password'] ?? '';
        $checkcode = $postData['checkcode'] ?? '';
    
        if (empty($username) || empty($password) || empty($checkcode)) {
            return returnJsonData(201, 'Incomplete information');
        }
    
        $captcha = new Captcha();
        if (!$captcha->check($checkcode)) {
            return returnJsonData(201, 'Captcha error');
        }
    
        $data = Db::name('users')->where(['username' => $username, 'password' => hashPwd($password)])->find();
    
        if (!$data) {
            return returnJsonData(201, 'Login failed');
        }
    
        $isVerify = Db::name('setting')->value('verify');
        if ($isVerify && !$data['verify']) {
            return returnJsonData(201, 'No Verify Email');
        }
    
        Session::set('userid', $data['id']);
        Session::set('username', $data['username']);
        return returnJsonData(200, 'Login success');
    }
    

    /* 注册 */

    public function register(){
        $POST = input('post.');
        $username = $POST['username'];
        $password = $POST['password'];
        $email = $POST['email'];
        $checkcode = $POST['checkcode'];
        
        if (empty($username) || empty($password) || empty($email) || empty($checkcode)) {
            return returnJsonData(201, '信息不完整');
        }
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return returnJsonData(201, 'Please enter the correct email address');
        }
    
        $captcha = new Captcha();
        if (!$captcha->check($checkcode)) {
            return returnJsonData(201, 'Captcha error');
        }
    
        if (Db::name('users')->where('username', $username)->find()) {
            return returnJsonData(201, 'Username already exists');
        }
        if (Db::name('users')->where('email', $email)->find()) {
            return returnJsonData(201, 'Email already exists');
        }
    
        $randToken = md5(rand(100000,999999) . time());
        $coin = Db::name('setting')->value('coin');
    
        $set = Db::name('setting')->field('state,verify')->find();
        if ($set['verify']) {
            $sendEmail = sendEmail($email, $username, $randToken);
            if (json_decode($sendEmail->getContent(), true)['code'] != 200) {
                return returnJsonData(201, '发信失败，请检查发信配置');
            }
            $mail_time = time();
        } else {
            $mail_time = 0;
        }
    
        if ($set['state']) {
            return returnJsonData(201, '平台关闭注册');
        }
    
        $data = ['username' => $username, 'password' => hashPwd($password), 'email' => $email, 'token' => $randToken, 'money' => $coin, 'time' => time(), 'mail_time' => $mail_time, 'verify' => $set['verify'] ? 0 : 1];
        $res = Db::name('users')->insert($data);
        if ($res) {
            $userid = Db::name('users')->where('username', $username)->value('id');
            $data = ['uid' => $userid, 'money' => $coin, 'type' => 4, 'first' => '0', 'last' => $coin, 'time' => time()];
            Db::table('money')->insert($data);
            return returnJsonData(200, 'Register success');
        } else {
            return returnJsonData(201, 'Register failed');
        }
    }
    

    /* 退出登录 */

    public function logout(){
        Session::clear();
        cookie('islogin', 0);
        return returnJsonData(200,'Logout success');
    }

   /* 修改用户信息 */

   public function editUserInfo()
   {
        $arr = [
            'username' => input('post.username'),
            'email' => input('post.email'),
            'website' => input('post.website'),
            'content' => input('post.content'),
        ];
        if (!filter_var($arr['email'], FILTER_VALIDATE_EMAIL)) {
            return returnJsonData(201,'Please enter the correct email address');
        }

        $password = input('post.password');
        $new_password = input('post.new_password');

        if (!empty($arr['username'])) {
            $data = Db::name('users')->where('username',$arr['username'])->find();
            if ($data) {
                return returnJsonData(201,'Username already exists');
            }
        }

        $isadmin = Db::name('users')->where('id',Session::get('userid'))->value('type');

        if ($isadmin == 1 && !empty($new_password)) {
            $arr['password'] = hashPwd($password);
        } else {
            if (!empty($password) && !empty($new_password)) {
                $data = Db::name('users')->where('username',Session::get('username'))->where('password',hashPwd($password))->find();
                if (!$data) {
                    return returnJsonData(201,'Password error');
                }
                if (input('password') == $new_password) {
                    return returnJsonData(201,'New password cannot be the same as the old password');
                }
                $arr['password'] = hashPwd($new_password);
            }
        }
        $arr = array_filter($arr);
        if ($arr['email']) {
            $arr['token'] = md5(rand(100000,999999) . time());
            $arr['verify'] = 0;
        }

        $data = Db::name('users')->where('id',Session::get('userid'))->update($arr);
        if ($arr['password']) {
            Session::clear();
            cookie('islogin', 0);
        }
        return returnJsonData(200,'success');
   }

   /* 获取用户信息 */

    public function getUserInfo()
    {
        $data = Db::name('users')->where('username',Session::get('username'))->field('username,email,avatar,state,content,website,verify')->find();
        if ($data) {
            return returnJsonData(200,'success',$data);
        }else{
            return returnJsonData(201,'failed');
        }
    }

   /* 修改头像 */

   public function uploadAvatar()
   {
       $file = request()->file('file');
       $filePath = 'uploads/avatar/';
       $info = $file->validate(['ext' => 'jpg,png,gif', 'size' => 2048000])->move($filePath);
   
       if (!$info) {
           return returnJsonData(201, 'error');
       }
   
       $oldAvatar = Db::name('users')->where('username', Session::get('username'))->value('avatar');
       if ($oldAvatar && file_exists($filePath . $oldAvatar)) {
           unlink($filePath . $oldAvatar);
       }
   
       Db::name('users')->where('username', Session::get('username'))
           ->update(['avatar' => '/' . $filePath . $info->getSaveName()]);
   
       $data = ['url' => '/' . $filePath . $info->getSaveName()];
       return returnJsonData(200, 'success', $data);
   }   

    /* 获取用户主页信息 */

    public function getHomeInfo($id = null)
    {
        $id = input('id') ? input('id') : $id;

        if (!is_numeric($id)) {
            return returnJsonData(201, 'Please enter the correct user id');
        }

        $isSelf = Session::get('userid') == $id || Session::get('userid') == 1;

        $userData = Db::name('users')->where('id', $id)->where($isSelf ? '' : 'state', 1)->field('username, avatar, content, scores, money, website')->find();

        if (!$userData) {
            return returnJsonData(201, 'User does not exist');
        }

        $solves = Db::name('submit')->where('uid', $id)->where('verify', 1)->join('challenges c', 'submit.cid = c.id')->field('c.id, c.title, c.category, c.score, submit.time')->order('submit.time desc')->select();

        $category = array_count_values(array_column($solves, 'category'));

        $maxCategory = $category ? array_search(max($category), $category) : 'Null';

        $rank = Db::name('users')->where('scores', '>', 0)->order('scores desc')->field('id')->select();

        $rank = array_search($id, array_column($rank, 'id')) + 1;

        $time = time() - 1209600;

        $solvelog = [];
        foreach ($solves as $key => $value) {
            if ($value['time'] > $time) {
                $day = date('m-d', $value['time']);

                if (isset($solvelog[$key - 1]) && $day == $solvelog[$key - 1]['time']) {
                    $solvelog[$key - 1]['score'] += $value['score'];
                } else {
                    $solvelog[] = ['time' => $day, 'score' => (int)$value['score']];
                }
            }
        }

        $data = [
            'username' => $userData['username'],
            'avatar' => $userData['avatar'],
            'content' => $userData['content'],
            'scores' => $userData['scores'],
            'money' => $userData['money'],
            'website' => $userData['website'],
            'total' => count($solves),
            'category' => $maxCategory,
            'count' => count($solves),
            'data' => $solves,
            'solvelog' => $solvelog,
            'rank' => $rank,
        ];

        if ($isSelf) {
            $moneyData = Db::name('money')->where('uid',Session::get('userid'))
                ->order('id desc')->limit(10)->select();
            foreach ($moneyData as &$item) {
                $item['type'] = ['购买附件', '解题奖励', '签到奖励', '系统奖励'][$item['type']];
            }
            unset($item);
            array_multisort(array_column($moneyData, 'time'), SORT_DESC, $moneyData);
            $data['moneys'] = $moneyData;
        }
        return returnJsonData(200,'success', $data);
    }

    /* 发送激活邮件 */

    function sendEmail(){
        $username = Session::get('username');
        $data = Db::name('users')->where('username', $username)->find();
        if (!$data || $data['verify']) {
            return returnJsonData(201, 'error');
        }
        $time = date('Y-m-d', $data['mail_time']);
        if ($time == date('Y-m-d')) {
            return returnJsonData(201, '每个用户每天只能发送1封邮件');
        }
        $sendEmail = sendEmail($data['email'], $username, $data['token']);
        if ($sendEmail->json('code') != 200) {
            return returnJsonData(201, '发信失败，请检查发信配置');
        }
        Db::name('users')->where('username', $username)->update(['mail_time' => time()]);
        return returnJsonData(200, 'success');
    }
}