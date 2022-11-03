<?php
namespace app\index\model;
use think\Db;
use think\Model;
use think\facade\Session;

class Post extends Model
{
    public function postFlag(){
        $POST = input('post.');
        $flag = $POST['flag'];
        $id = $POST['id'];
        $data = Db::table('challenges')->where('id',$id)->where('flag',$flag)->find();
        if ($data) {
            $time = time();
            $user = Db::table('users')->where('id',Session::get('userid'))->find();
            $money = $user['money'] + $data['money'];
            $score = $user['scores'] + $data['score'];
            Db::table('users')->where('id',Session::get('userid'))->update(['money' => $money, 'scores' => $score]);
            $arr = ['uid' => Session::get('userid'), 'money' => $data['money'], 'type' => 1, 'last' => $user['money'], 'first' => $user['money'] + $data['money'], 'time' => $time];
            Db::table('money')->insert($arr);
            $arr = ['uid' => Session::get('userid'), 'cid' => $data['id'], 'time' => $time];
            Db::table('solves')->insert($arr);
            Db::table('challenges')->where('id',$id)->setInc('solve');
            return returnJsonData(200,'Flag is correct');
        }
        return returnJsonData(201,'Flag is incorrect');
    }

    public function buyChallenge(){
        $POST = input('post.');
        $id = $POST['id'];
        $data = Db::name('buys')->where('uid',Session::get('userid'))->where('cid',$id)->find();
        if (!$data) {
            $challenges = Db::name('challenges')->where('id',$id)->value('money');

            $money = Db::name('users')->where('id',Session::get('userid'))->value('money');
            if ($money >= $challenges) {
                $money = $money - $challenges;
                Db::name('users')->where('id',Session::get('userid'))->update(['money' => $money]);
                $arr = ['uid' => Session::get('userid'), 'money' => $challenges, 'type' => 0,'last' => $money, 'time' => time()];
                Db::table('money')->insert($arr);
                $arr = ['uid' => Session::get('userid'), 'cid' => $id, 'time' => time()];
                Db::table('buys')->insert($arr);
                // $file = Db::name('file')->where('id',$data['file_id'])->find();
                // $file['url'] = 'http://'.$_SERVER['HTTP_HOST'].'/uploads/'.$file['url'];
                // return returnJsonData(200,'success',$file);
                return returnJsonData(200,'success');
            }else{
                return returnJsonData(201,'Insufficient balance');
            }
        }else{
            return returnJsonData(201,'Post not found');
        }
    }
}