<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class Index extends Model
{
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

    public function editSettings()
    {
        $data = input('post.');
        $data['state'] = $data['state'] == 'Hidden' ? 0 : 1;
        $data['verify'] = $data['verify'] == 'False' ? 0 : 1;
        $res = Db::name('setting')->where('id', 1)->update($data);
        if ($res) {
            return returnJsonData(200,'success');
        } else {
            return returnJsonData(201,'error');
        }
    }
    
    public function getSubmitList()
    {
        $id = input('get.id');
        $data = Db::name('submit')->order('id', 'desc')->when($id, function($query) use ($id) {return $query->where('id', $id);})->paginate(10)->toArray();

        $userIds = array_column($data['data'], 'uid');
        $challengeIds = array_column($data['data'], 'cid');

        $usernames = Db::name('users')->where('id', 'in', $userIds)->column('username', 'id');
        $challengeTitles = Db::name('challenges')->where('id', 'in', $challengeIds)->column('title', 'id');

        foreach ($data['data'] as &$item) {
            $item['uid'] = $usernames[$item['uid']] ?? '';
            $item['cid'] = $challengeTitles[$item['cid']] ?? '';
        }

        if ($data['data']) {
            return returnJsonData(200,'success', $data);
        } else {
            return returnJsonData(201,'error');
        }
    }
}
