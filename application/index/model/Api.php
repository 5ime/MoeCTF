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
        if(Session::get('userid')){
            $solved = $this->getSolved(Session::get('userid'));
        }else{
            $solved = [];
        }
        $data = Db::name('challenges')->order('id asc')->where('state',1)->field('id,title,money,solve,score,hint,download,tags,category')->paginate(12)->toArray();
        $category = Db::name('categorys')->select();
        foreach($data['data'] as $key => $value){
            foreach($category as $k => $v){
                if($value['category'] == $v['id']){
                    $data['data'][$key]['category'] = $v['name'];
                }
            }
        }

        if(input('get.page') > $data['last_page'] || !is_numeric(input('get.page'))){
            return returnJsonData(201,'error');
        }
        
        foreach($data['data'] as $key => $value){
            if(in_array($value['id'],$solved)){
                $data['data'][$key]['solved'] = 1;
            }else{
                $data['data'][$key]['solved'] = 0;
            }
        }
        if($data){
            return returnJsonData(200,'success',explodeTags($data));
        }else{
            return returnJsonData(201,'error');
        }
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

    public function getSearchSort()
    {
        $POST = input('post.');
        $solved = [];

        if ($POST['sort'] == 'all') {
            $data = Db::name('challenges')->where('state',1)->order('id asc')->field('id,title,money,solve,score,hint,download,tags,category')->paginate(10)->toArray();
        }else {
            $data = Db::name('challenges')->where('state',1)->where('category',ucfirst($POST['sort']))->order('id asc')->field('id,title,money,solve,score,hint,download,tags,category')->paginate(10)->toArray();
        }

        if(input('get.page') > $data['last_page'] || !is_numeric(input('get.page'))){
            return returnJsonData(201,'error');
        }
        
        if(Session::get('userid')){
            $solved = $this->getSolved(Session::get('userid'));
        }

        foreach($data['data'] as $key => $value){
            if(in_array($value['id'],$solved)){
                $data['data'][$key]['solved'] = 1;
            }else{
                $data['data'][$key]['solved'] = 0;
            }
        }

        if($POST['state'] == 'resolved'){
            foreach($data['data'] as $key => $value){
                if($value['solved'] == 0){
                    unset($data['data'][$key]);
                }
            }
        }
        
        if($POST['state'] == 'unsolved'){
            foreach($data['data'] as $key => $value){
                if($value['solved'] == 1){
                    unset($data['data'][$key]);
                }
            }
        }
        
        $data['data'] = array_values($data['data']);
        if($data && $data['data']){
            return returnJsonData(200,'success',explodeTags($data));
        }else{
            return returnJsonData(201,'error');
        }
    }

    public function getChallengeInfo()
    {
        if (empty(Session::get('userid'))) {
            return returnJsonData(201, 'Not Login');
        }
     
        $id = input('id');
        if (empty($id)) {
            return returnJsonData(201,'error');
        }

        if(Session::get('userid')){
            $solved = $this->getSolved(Session::get('userid'));
        }else{
            $solved = [];
        }

        $data = Db::name('challenges')->where('state',1)->where('id',$id)->field('id,title,money,solve,score,hint,tags,category,content')->find();

        if(in_array($data['id'],$solved)){
            $data['solved'] = 1;
        }
        $tags = explode(',', $data['tags']);
        if (count($tags) == 1 && $tags[0] == '') {
            $tags = array('No Tags');
        }
        $data['tags'] = $tags;

        $downInfo = json_decode($this->downloadFile($data['id'])->getContent(),true);

        if ($downInfo['code'] == 200){
            $data['download'] = '/downloadFile/' . $data['id'] . '&type=down';
        }else if($downInfo['msg'] == 'error'){
            $data['download'] = 'Not purchased';
        }else{
            $data['download'] = 'Not a file';
        }
        
        if ($data) {
            return returnJsonData(200,'success',$data);
        }else{
            return returnJsonData(201,'error');
        }
    }
    
    public function downloadFile($id){
        if (empty(Session::get('userid'))) {
            return returnJsonData(201, 'Not Login');
        }
        $download = Db::name('challenges')->where('state',1)->where('id',$id)->value('download');
        $data = Db::name('buys')->where('uid', Session::get('userid'))->where('cid', $id)->find();
        if($data) {
            $file = $download;
            if (file_exists($file)) {
                if(input('type') == 'down' )
                {
                    $filename = basename($file);
                    header("Content-type: application/octet-stream");
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header("Content-Length: " . filesize($file));
                    readfile($file);
                }
                return returnJsonData(200,'success');
            }else{
                return returnJsonData(201,'Not a file');
            }
        }else{
            return returnJsonData(201,'error');
        }
    }

    public function getSolveRank(){
        $id = input('post.id');
        if (empty($id)) {
            return returnJsonData(201,'error');
        }
        $data = Db::name('submit')->where('cid',$id)->where('verify',1)->order('time asc')->field('id,uid,time')->select();
        if ($data) {
            $data = array_slice($data, 0, 20);
            foreach ($data as $key => $value) {
                $data[$key]['username'] = Db::name('users')->where('id',$value['uid'])->where('state',1)->value('username');
            }
            $data = array_filter($data, function($value) {
                return $value['username'] != null;
            });
            $data = array_slice($data, 0, 10);
            $data = array_values($data);
            if ($data) {
                return returnJsonData(200,'success',$data);
            }else{
                return returnJsonData(201,'error');
            }
        }else{
            return returnJsonData(201,'error');
        }
    }

    public function getAllRank(){
        $data = Db::name('users')->where('state',1)->where('scores','neq','')->order('scores desc')->field('id,username,scores')->paginate(10)->toArray();
        foreach ($data['data'] as $key => $value) {
            $data['data'][$key]['time'] = Db::name('submit')->where('uid',$value['id'])->where('verify',1)->order('time desc')->value('time');
        }
        if ($data) {
            return returnJsonData(200,'success',$data);
        }else{
            return returnJsonData(201,'error');
        }
    }
    public function getAllNotify(){
        $data = Db::name('notify')->paginate(5)->toArray();
        if ($data) {
            return returnJsonData(200,'success',$data);
        }else{
            return returnJsonData(201,'error');
        }
    }
}
