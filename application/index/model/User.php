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

    public function login(){
        $POST = input('post.');
        $username = @$POST['username'];
        $password = @$POST['password'];
        $checkcode = @$POST['checkcode'];

        if (empty($username) || empty($password) || empty($checkcode)) {
            return returnJsonData(201,'信息不完整');
        }

        $captcha = new Captcha();
        if(!$captcha->check($checkcode))
        {
            return returnJsonData(201,'Captcha error');
        }

        $data = Db::name('users')->where('username',$username)->where('password',hashPwd($password))->find();
        if ($data) {
            $isVerify = Db::name('setting')->value('verify');
            if($isVerify){
                if ($data['verify'] == 0) {
                    return returnJsonData(201,'No Verify Email');
                }
            }
            Session::set('userid', $data['id']);
            Session::set('username', $data['username']);
            return returnJsonData(200,'Login success');
        }else{
            return returnJsonData(201,'Login failed');
        }
    }

    /* 注册 */

    public function register(){
        $POST = input('post.');
        $username = @$POST['username'];
        $password = @$POST['password'];
        $email = @$POST['email'];
        $checkcode = @$POST['checkcode'];
        if (empty($username) || empty($password) || empty($email) || empty($checkcode)) {
            return returnJsonData(201,'信息不完整');
        }
        $captcha = new Captcha();
        if(!$captcha->check($checkcode))
        {
            return returnJsonData(201,'Captcha error');
        }
        $data = Db::name('users')->where('username',$username)->find();
        if ($data) {
            return returnJsonData(201,'Username already exists');
        }
        $data = Db::name('users')->where('email',$email)->find();
        if ($data) {
            return returnJsonData(201,'Email already exists');
        }
        $randToken = md5(rand(100000,999999) . time());
        $coin = Db::name('setting')->value('coin');
        $data = [
            'username' => $username,
            'password' => hashPwd($password),
            'email' => $email,
            'token' => $randToken,
            'money' => $coin,
            'time' => time()
        ];
        $set = Db::name('setting')->filed('state,verify')->find();
        if($set['verify']){
            $sendEmail = sendEmail($data['email'], $data['username'], $randToken);
            if(json_decode($sendEmail->getContent(),true)['code'] != 200){
                return returnJsonData(201,'发信失败，请检查发信配置');
            }
            $data['mail_time'] = time();
        }
        if($set['state']){
            return returnJsonData(201,'平台关闭注册');
        }
        $res = Db::name('users')->insert($data);
        if ($res) {
            $userid = Db::name('users')->where('username',$username)->value('id');
            $data = [
                'uid' => $userid,
                'money' => $coin,
                'type' => 4,
                'first' => '0',
                'last' => $coin,
                'time' => time()
            ];
            Db::table('money')->insert($data);
            return returnJsonData(200,'Register success');
        }else{
            return returnJsonData(201,'Register failed');
        }
    }

    /* 退出登录 */

    public function logout(){
        Session::clear();
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
        $password = input('post.password');
        $new_password = input('post.new_password');

        if (!empty(@$arr['username'])) {
            $data = Db::name('users')->where('username',$arr['username'])->find();
            if ($data) {
                return returnJsonData(201,'Username already exists');
            }
        }

        if (!empty(@$password) && !empty(@$new_password)) {
            $data = Db::name('users')->where('username',Session::get('username'))->where('password',hashPwd($password))->find();
            if (!$data) {
                return returnJsonData(201,'Password error');
            }
            if (input('password') == $new_password) {
                return returnJsonData(201,'New password cannot be the same as the old password');
            }
            $arr['password'] = hashPwd($new_password);
        }

        $data = Db::name('users')->where('id',Session::get('userid'))->update(array_filter($arr));
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

    public function uploadAvatar(){
        $file = request()->file('file');
        $filePath = 'uploads/avatar/';
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move($filePath);
        if($info){
            $avatar = Db::name('users')->where('username',Session::get('username'))->value('avatar');
            if($avatar) {
                $filename = $filePath . $avatar;
                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
            Db::name('users')->where('username',Session::get('username'))->update(['avatar' => '/' . $filePath . $info->getSaveName()]);
            $data = [
                'url' => '/' . $filePath . $info->getSaveName(),
            ];
            return returnJsonData(200, 'success', $data);
        }else{
            return returnJsonData(201,'error');
        }
    }

    /* 获取用户主页信息 */

    public function getHomeInfo(){
        $id = input('id');
        if (!is_numeric($id)) {
            return returnJsonData(201,'error');
        }
        if (Session::get('userid') == $id || Session::get('userid') == 1) {
            $userData = Db::name('users')->where('id',$id)->field('username,avatar,content,scores,money,website')->find();
        }else{
            $userData = Db::name('users')->where('id',$id)->where('state',1)->field('username,avatar,content,scores,money,website')->find();
        }
        if (!$userData) {
            return returnJsonData(201,'error');
        }
        $getSolvs = Db::name('submit')->where('uid',$id)->where('verify',1)->paginate(10)->toArray();
        $solves = [];
        $category = [];
        
        foreach ($getSolvs['data'] as $key => $value) {
            $getChallenge = Db::name('challenges')->where('id',$value['cid'])->field('title,category,score,id')->find();
            $solves[$key]['id'] = $getChallenge['id'];
            $solves[$key]['title'] = $getChallenge['title'];
            $solves[$key]['category'] = $getChallenge['category'];
            $solves[$key]['score'] = $getChallenge['score'];
            $solves[$key]['time'] = $value['time'];
        }
        array_multisort(array_column($solves, 'time'), SORT_DESC, $solves);
        
        foreach ($solves as $key => $value) {
            $category[$value['category']] = isset($category[$value['category']]) ? $category[$value['category']] + 1 : 1;
        }
        if ($category) {
            $maxCategory = array_search(max($category), $category);
        } else {
            $maxCategory = 'Null';
        }
        $rank = Db::name('users')->order('scores desc')->select();
        $rank = array_search($id, array_column($rank, 'id'));

        // 获取最近14天的提交记录
        $time = time() - 1209600;
        $solvelog = [];
        foreach ($solves as $key => $value) {
            if ($value['time'] > $time) {
                $solvelog[$key]['time'] = date('m-d',$value['time']);
                $solvelog[$key]['score'] = (int)$value['score'];
                if (isset($solvelog[$key - 1]) && date('m-d',$value['time']) == date('m-d',$solves[$key - 1]['time'])) {
                    $solvelog[$key]['score'] = $solvelog[$key - 1]['score'] + $value['score'];
                    unset($solvelog[$key - 1]);
                }
            }
        }
        $solvelog = array_values($solvelog);
        
        $data = [
            'username' => $userData['username'],
            'avatar' => $userData['avatar'],
            'content' => $userData['content'],
            'scores' => $userData['scores'],
            'money' => $userData['money'],
            'website' => $userData['website'],
            'total' => $getSolvs['total'],
            'per_page' => $getSolvs['per_page'],
            'current_page' => $getSolvs['current_page'],
            'last_page' => $getSolvs['last_page'],
            'category' => $maxCategory,
            'count' => count($solves),
            'data' => $solves,
            'solvelog' => $solvelog,
            'rank' => $rank + 1,
        ];

        if($id == Session::get('userid')){
            $moneyData = Db::name('money')->where('uid',Session::get('userid'))->order('id desc')->limit(10)->select();
            if ($moneyData) {
                foreach ($moneyData as $key => $value) {
                    if ($value['type'] == 0) {
                        $moneyData[$key]['type'] = '购买附件';
                    }elseif ($value['type'] == 1) {
                        $moneyData[$key]['type'] = '解题奖励';
                    }elseif ($value['type'] == 2) {
                        $moneyData[$key]['type'] = '签到奖励';
                    }else {
                        $moneyData[$key]['type'] = '系统奖励';
                    }
                }
                array_multisort(array_column($moneyData, 'time'), SORT_DESC, $moneyData);
            }
            $data['moneys'] = $moneyData;
        }
        return returnJsonData(200,'success', $data);
    }

    /* 发送激活邮件 */

    function sendEmail(){
        $username = Session::get('username');
        $data = Db::name('users')->where('username',$username)->field('email,token,verify,mail_time')->find();
        if (!$data || $data['verify']) {
            return returnJsonData(201,'error');
        }
        $time = date('Y-m-d',$data['mail_time']);
        if ($time == date('Y-m-d')) {
            return returnJsonData(201,'每个用户每天只能发送1封邮件');
        }
        $sendEmail = sendEmail($data['email'], $username, $data['token']);
        if(json_decode($sendEmail->getContent(),true)['code'] != 200){
            return returnJsonData(201,'发信失败，请检查发信配置');
        }
        Db::name('users')->where('username',$username)->update(['mail_time' => time()]);
        return returnJsonData(200,'success');
    }
}