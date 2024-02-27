<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use think\facade\Session;

class User extends Model
{
    public function getUserList()
    {
        $data = Db::name('users')->field('id,username,email,website,state,verify')->paginate(10)->toArray();
        if ($data) {
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }
    
    public function getUserAllInfo()
    {
        $id = input('post.id');
        $data = Db::name('users')->where('id', $id)->field('id,username,email,avatar,content,website,scores,type,time')->find(); 
        if ($data) {
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }

    public function editUserState()
    {
        $value = input('value');
        $id = input('id');
        switch($value){
            case 'display':
                Db::name('users')->where('id', $id)->update(['state' => 0]);
                return returnJsonData(200, 'success');
            case 'hidden':
                Db::name('users')->where('id', $id)->update(['state' => 1]);
                return returnJsonData(200, 'success');
            case 'verify':
                Db::name('users')->where('id', $id)->update(['verify' => 0]);
                return returnJsonData(200, 'success');
            case 'unverify':
                Db::name('users')->where('id', $id)->update(['verify' => 1]);
                return returnJsonData(200, 'success');
            default:
                return returnJsonData(201, 'error');
        }
    }

    public function editUser()
    {
        $id = input('post.id');
        $arr = [
            'username' => input('post.username'),
            'email' => input('post.email'),
            'website' => input('post.website'),
            'content' => input('post.content'),
        ];
    
        if (!filter_var($arr['email'], FILTER_VALIDATE_EMAIL)) {
            return returnJsonData(201, 'Please enter the correct email address');
        }
    
        if (!empty(input('post.password'))) {
            $arr['password'] = hashPwd(input('post.password'));
        }
    
        if (!empty($arr['username']) && Db::name('users')->where('username', $arr['username'])->find()) {
            return returnJsonData(201, 'Username already exists');
        }
    
        if ($arr['email']) {
            $arr['token'] = md5(rand(100000, 999999) . time());
            $arr['verify'] = 0;
        }
    
        if (Db::name('users')->where('id', $id)->update(array_filter($arr))) {
            if ($id == Session::get('userid') && !empty($arr['password'])) {
                Session::clear();
                cookie('islogin', 0);
            }
            return returnJsonData(200, 'success');
        }
    
        return returnJsonData(201, 'error');
    }
    
    public function addUser()
    {   
        $arr = [
            'username' => input('post.username'),
            'email' => input('post.email'),
            'website' => input('post.website'),
            'content' => input('post.content'),
        ];
    
        if (!filter_var($arr['email'], FILTER_VALIDATE_EMAIL)) {
            return returnJsonData(201, 'Please enter the correct email address');
        }
    
        if (!empty(input('post.password'))) {
            $arr['password'] = hashPwd(input('post.password'));
        }
    
        if (!empty($arr['username']) && Db::name('users')->where('username', $arr['username'])->find()) {
            return returnJsonData(201, 'Username already exists');
        }
    
        if ($arr['email']) {
            $arr['token'] = md5(rand(100000, 999999) . time());
            $arr['verify'] = 0;
        }

        if (Db::name('users')->insert($arr)){
            return returnJsonData(200, 'success');
        }
        return returnJsonData(201, 'error');
    }


    public function uploadAvatar()
    {
        $id = input('post.id');
        $file = request()->file('file');
        $filePath = 'uploads/avatar/';
        $info = $file->move($filePath);
        if (!$info) {
            return returnJsonData(201, 'error');
        }
        $avatar = Db::name('users')->where('id', $id)->value('avatar');
        if ($avatar && file_exists($filePath . $avatar)) {
            unlink($filePath . $avatar);
        }
        Db::name('users')->where('id', $id)->update(['avatar' => '/' . $filePath . $info->getSaveName()]);
        return returnJsonData(200, 'success', ['url' => '/' . $filePath . $info->getSaveName()]);
    }    

    public function deleteUser()
    {   
        $id = input('post.id');
        $data = Db::name('users')->where('id',$id)->delete();
        if ($data) {
            return returnJsonData(200,'success');
        }else{
            return returnJsonData(201,'error');
        }
    }
}