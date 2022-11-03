<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Notify extends Model
{
    public function getNotifyList()
    {
        $data = Db::name('notify')->paginate(10)->toArray();
        if ($data) {
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }

    public function editNotify()
    {
        $data = input('post.');
        $data['time'] = time();
        if (empty($data['title']) && empty($data['content'])) {
            return returnJsonData(201,'error');
        }
        if ($data['id']) {
            $res = Db::name('notify')->where('id', $data['id'])->update(['title' => $data['title'], 'content' => $data['content'], 'time' => $data['time']]);
            if ($res) {
                return returnJsonData(200,'success');
            } else {
                return returnJsonData(201,'error');
            }
        } else {
            $res = Db::name('notify')->insert(['title' => $data['title'], 'content' => $data['content'], 'time' => $data['time']]);
            if ($res) {
                return returnJsonData(200,'success');
            } else {
                return returnJsonData(201,'error');
            }
        }
    }

    public function deleteNotify()
    {   
        $id = input('post.id');
        $data = Db::name('notify')->where('id',$id)->delete();
        if ($data) {
            return returnJsonData(200,'success');
        }else{
            return returnJsonData(201,'error');
        }
    }
}
