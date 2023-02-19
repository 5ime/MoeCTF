<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Challenges extends Model
{
    public function getChallengeList()
    {
        $data = Db::name('challenges')->field('id,title,category,score,state,time')->paginate(10)->toArray();
        if ($data) {
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }
    
    public function addChallenge(){
        $POST = input('post.');
        $POST['time'] = time();
        $data = Db::name('challenges')->insert($POST);
        if ($data) {
            return returnJsonData(200,'success');
        } else {
            return returnJsonData(201,'error');
        }
    }

    public function uploadFile()
    {
        $id = input('post.id');
        $file = request()->file('file');
        $filePath = 'uploads/file/';
        
        if (!empty($id)) {
            $data = Db::name('challenges')->where('id', $id)->value('download');
            if ($data) {
                $filename = $filePath . $data;
                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
        }
        
        $info = $file->rule('sha1')->move($filePath);
        
        if ($info) {
            $data = [
                'name' => $info->getFilename(),
                'url' => $filePath . $info->getSaveName(),
            ];
            Db::name('challenges')->where('id', $id)->update(['download' => $data['url']]);
            return returnJsonData(200, 'success', $data);
        } else {
            return returnJsonData(201, 'error');
        }   
    }


    public function editChallengeState()
    {
        $value = input('value');
        $id = input('id');
        switch($value){
            case 'display':
                Db::name('challenges')->where('id', $id)->update(['state' => 0]);
                return returnJsonData(200, 'success');
            case 'hidden':
                Db::name('challenges')->where('id', $id)->update(['state' => 1]);
                return returnJsonData(200, 'success');
            default:
                return returnJsonData(201, 'error');
        }
    }

    public function editChallenge(){
        $POST = input('post.');
        if (!is_numeric($POST['id'])) {
            return returnJsonData(201,'error');
        }
        $POST['time'] = time();
        $data = Db::name('challenges')->update($POST);
        if ($data) {
            return returnJsonData(200,'success');
        } else {
            return returnJsonData(201,'error');
        }
    }

    public function deleteChallenge()
    {   
        $id = input('post.id');
        $data = Db::name('challenges')->where('id',$id)->delete();
        if ($data) {
            return returnJsonData(200,'success');
        }else{
            return returnJsonData(201,'error');
        }
    }

    public function getChallengeAllInfo()
    {
        $id = input('id');
        if (!is_numeric($id)) {
            return returnJsonData(201,'error');
        }
        $data = Db::name('challenges')->where('id', $id)->find();
        if ($data) {
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }

}
