<?php
namespace app\admin\model;
use think\Model;
use think\Db;

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
        $POST = input('post.');
        if ($POST['id']) {
            if(empty($POST['username'])){
                unset($POST['username']);
            }
            if(empty($POST['password'])){
                unset($POST['password']);
            }
            if (@$POST['password']) {
                $POST['password'] = hashPwd($POST['password']);
            }
            $data = Db::name('users')->where('id',$POST['id'])->update($POST);
            if ($data) {
                return returnJsonData(200,'success', $data);
            } else {
                return returnJsonData(201,'error');
            }
        } else {
            if(empty($POST['username'])){
                unset($POST['username']);
            }
            if(empty($POST['password'])){
                unset($POST['password']);
            }
            if (@$POST['password']) {
                $POST['password'] = hashPwd($POST['password']);
            }
            $data = Db::name('users')->insert($POST);
            if ($data) {
                return returnJsonData(200,'success', $data);
            } else {
                return returnJsonData(201,'error');
            }
        }
    }

    public function uploadAvatar(){
        $id = input('post.id');
        $file = request()->file('file');
        $filePath = 'uploads/avatar/';
        $info = $file->move($filePath);
        if($info){
            $avatar = Db::name('users')->where('id',$id)->value('avatar');
            if($avatar) {
                $filename = $filePath . $avatar;
                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
            $data = Db::name('users')->where('id',$id)->update(['avatar' => '/' . $filePath .$info->getSaveName()]);
            $data = [
                'url' => $filePath . $info->getSaveName(),
            ];
            return returnJsonData(200, 'success', $data);
        }else{
            return returnJsonData(201,'error');
        }
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