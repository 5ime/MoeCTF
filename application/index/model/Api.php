<?php
namespace app\index\model;
use think\Db;
use think\Model;
use think\facade\Session;

class Api extends Model
{
    protected function getSolved($userid)
    {
        $solved = Db::table('submit')->where('uid',$userid)->where('verify',1)->select();
        $solved = array_column($solved,'cid');
        return $solved;
    }

    public function getChallenges()
    {
        $postData = input('post.');
        
        // 构建查询条件
        $where = [];
        if ($postData['sort'] != 'all') {
            $where[] = ['category', '=', ucfirst($postData['sort'])];
        }
        if ($postData['state'] != 'all') {
            $solved = $this->getSolved(Session::get('userid'));
            if ($postData['state'] == 'resolved') {
                $where[] = ['id', 'in', $solved];
            } else {
                $where[] = ['id', 'not in', $solved];
            }
        }
    
        // 查询数据并分页
        $data = Db::name('challenges')->where($where)->where('state', 1)->order('id asc')->field('id,title,money,solve,score,tags,category')->paginate(12)->toArray();
    
        // 处理翻页冲突
        $pageNum = input('page') ? input('page') : 1;
        if ($pageNum > $data['last_page'] || !is_numeric($pageNum)) {
            return returnJsonData(201, 'No results');
        }
        $data['current_page'] = (int)$pageNum;
    
        // 标记已解决的题目
        $solved = $this->getSolved(Session::get('userid'));
        foreach ($data['data'] as $key => $value) {
            if (in_array($value['id'], $solved)) {
                $data['data'][$key]['solved'] = 1;
            } else {
                $data['data'][$key]['solved'] = 0;
            }
        }
    
        return returnJsonData(200, 'success', explodeTags($data));
    }

    public function getCategory()
    {
        $data = Db::name('categorys')->field('name')->select();
        if($data){
            return returnJsonData(200,'success',$data);
        }else{
            return returnJsonData(201,'error');
        }
    }

    public function getChallengeInfo()
    {
        $userId = Session::get('userid');
        if (empty($userId)) {
            return returnJsonData(201, 'Not Login');
        }
    
        $id = input('id');
        if (empty($id)) {
            return returnJsonData(201, 'error');
        }
    
        $solved = $userId ? $this->getSolved($userId) : [];
    
        $data = Db::name('challenges')->where('state', 1)->where('id', $id)->field('id, title, money, solve, score, hint, tags, category, content')->find();
        
        if (!$data) {
            return returnJsonData(201, 'Please check the challenge id');
        }
    
        $data['solved'] = in_array($data['id'], $solved) ? 1 : 0;
    
        $tags = array_filter(explode(',', $data['tags']));
        $data['tags'] = count($tags) > 0 ? $tags : ['No Tags'];
    
        $downInfo = json_decode($this->downloadFile($data['id'])->getContent(), true);
        if ($downInfo['code'] == 200) {
            $data['download'] = '/downloadFile/' . $data['id'] . '&type=down';
        } elseif ($downInfo['msg'] == 'error') {
            $data['download'] = 'Not purchased';
        } else {
            $data['download'] = 'Not a file';
        }
    
        return returnJsonData(200, 'success', $data);
    }    
    
    public function downloadFile($id) {
        if (empty(Session::get('userid'))) {
            return returnJsonData(201, 'Not Login');
        }
    
        $file = Db::name('buys')->alias('b')->join('challenges c', 'b.cid = c.id')->where('b.uid', Session::get('userid'))->where('b.cid', $id)->where('c.state', 1)->value('c.download');
    
        if (!$file) {
            return returnJsonData(201, 'Not a file');
        }
    
        if (input('type') === 'down') {
            $filename = basename($file);
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
    
        return returnJsonData(200, 'success');
    }

    public function getSolveRank() {
        $id = input('post.id');
    
        if (empty($id)) {
            return returnJsonData(201, 'error');
        }
    
        $data = Db::name('submit')->alias('s')->join('users u', 'u.id=s.uid')->where('s.cid', $id)->where('s.verify', 1)->where('u.state', 1)->order('s.time asc')->limit(20)->field('s.id,s.uid,s.time,u.username')->select();
    
        $data = array_values(array_filter($data, function($value) {
            return $value['username'] != null;
        }));
    
        if ($data) {
            return returnJsonData(200, 'success', $data);
        } else {
            return returnJsonData(201, 'error');
        }
    }
    

    public function getAllRank(){
        $data = Db::name('users')->where('state', 1)->where('scores', '<>', '')->order('scores desc')->field('id, username, scores')->paginate(10)->toArray();
    
        $userIds = array_column($data['data'], 'id');
        $submitTimes = Db::name('submit')->where('uid', 'in', $userIds)->where('verify', 1)->field('uid, MAX(time) as time')->group('uid')->select();
    
        foreach ($data['data'] as $key => $value) {
            foreach ($submitTimes as $submit) {
                if ($value['id'] == $submit['uid']) {
                    $data['data'][$key]['time'] = $submit['time'];
                    break;
                }
            }
        }
    
        return returnJsonData(200, 'success', $data);
    }
    

    public function getAllNotify(){
        $data = Db::name('notify')->paginate(5)->toArray();

        if ($data) {
            return returnJsonData(200,'success',$data);
        }else{
            return returnJsonData(201,'error');
        }
    }

    public function getSearchInfo(){
        $keyword = input('keyword');
        if (empty($keyword)) {
            return returnJsonData(201,'Please enter the search content');
        }
        $solved = Session::get('userid') ? $this->getSolved(Session::get('userid')) : [];
        $data = Db::name('challenges')->where('state',1)->where('title','like','%'.$keyword.'%')->field('id,title,money,solve,score,hint,download,tags,category')->paginate(12)->toArray();
        $category = Db::name('categorys')->select();
        foreach($data['data'] as $key => $value){
            foreach($category as $v){
                if($value['category'] == $v['id']){
                    $data['data'][$key]['category'] = $v['name'];
                    break;
                }
            }
            $data['data'][$key]['solved'] = in_array($value['id'], $solved) ? 1 : 0;
        }
        if ($data['data']) {
            return returnJsonData(200,'success',explodeTags($data));
        } else {
            return returnJsonData(201,'No results');
        }
    }
}
