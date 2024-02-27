<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Sort extends Model
{
    public function getSortList()
    {
        $data = Db::name('categorys')->paginate(10)->toArray();
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]['count'] = Db::name('challenges')->where('category', $value['name'])->count();
        }
        if ($data) {
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }

    public function editSort()
    {
        $data = input('post.');
        if ($data['id']) {
            $res = Db::name('categorys')->where('id', $data['id'])->update(['name' => $data['name']]);
        } else {
            $res = Db::name('categorys')->insert(['name' => $data['name']]);
        }
        if ($res) {
            return returnJsonData(200,'success');
        } else {
            return returnJsonData(201,'error');
        }
    }

    public function deleteSort()
    {   
        $count = Db::name('categorys')->count();
        if ($count == 1) {
            return returnJsonData(201,'必须保留一个');
        }
        $id = input('post.id');
        $data = Db::name('categorys')->where('id',$id)->delete();
        if ($data) {
            return returnJsonData(200,'success');
        }else{
            return returnJsonData(201,'error');
        }
    }
}
