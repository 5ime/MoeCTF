<?php
namespace app\index\model;
use think\Db;
use think\Model;
use think\facade\Session;

class Post extends Model
{
    public function postFlag()
    {
        $POST = input('post.');
        $id = $POST['id'];
        $data = Db::table('challenges')->where('id', $id)->find();
        if (!$data) {
            return returnJsonData(201, '题目不存在');
        }
        $flag = htmlspecialchars($POST['flag']);
        if ($data && $data['flag'] == $POST['flag']) {
            $submit = Db::table('submit')->where(['uid' => Session::get('userid'), 'cid' => $id, 'verify' => 1])->find();
            if ($submit) {
                return returnJsonData(201, '你已提交过该题目的flag');
            }
            $arr = ['uid' => Session::get('userid'), 'cid' => $id, 'value' => $flag, 'ip' => getUserIP(), 'time' => time(), 'verify' => 1];
            Db::table('submit')->insert($arr);
            $time = time();
            $user = Db::table('users')->where('id', Session::get('userid'))->find();
            $money = $user['money'] + $data['money'];
            $score = $user['scores'] + $data['score'];
            Db::table('users')->where('id', Session::get('userid'))->update(['money' => $money, 'scores' => $score]);
            $arr = ['uid' => Session::get('userid'), 'money' => $data['money'], 'type' => 1, 'last' => $user['money'], 'first' => $user['money'] + $data['money'], 'time' => $time];
            Db::table('money')->insert($arr);
            $arr = ['uid' => Session::get('userid'), 'cid' => $data['id'], 'time' => $time];
            Db::table('challenges')->where('id', $id)->setInc('solve');
            return returnJsonData(200, 'flag 正确');
        } else {
            $arr = ['uid' => Session::get('userid'), 'cid' => $id, 'value' => $flag, 'ip' => getUserIP(), 'time' => time()];
            Db::table('submit')->insert($arr);
            $time = time() - 60;
            $count = Db::table('submit')->where([['uid', '=', Session::get('userid')], ['cid', '=', $id], ['time', '>', $time]])->count();
            if ($count >= 10) {
                return returnJsonData(201, '频繁提交错误flag');
            }
            return returnJsonData(201, 'flag 错误');
        }
    }
    
    public function buyChallenge()
    {
        $id = input('post.id');
        $challenges = Db::name('challenges')->where('id', $id)->value('money');
        if (!$challenges) {
            return returnJsonData(201, '题目不存在');
        }
        $money = Db::name('users')->where('id', Session::get('userid'))->value('money');
        if ($money < $challenges) {
            return returnJsonData(201, '余额不足');
        }
        $data = Db::name('buys')->where('uid', Session::get('userid'))->where('cid', $id)->find();
        if ($data) {
            return returnJsonData(201, '请勿重复购买');
        }
        $first = $money;
        $money -= $challenges;
        Db::name('users')->where('id', Session::get('userid'))->update(['money' => $money]);
        $time = time();
        $arr = ['uid' => Session::get('userid'), 'money' => $challenges, 'type' => 0, 'first' => $first, 'last' => $money, 'time' => $time];
        Db::table('money')->insert($arr);
        $arr = ['uid' => Session::get('userid'), 'cid' => $id, 'time' => $time];
        Db::table('buys')->insert($arr);
        return returnJsonData(200, '购买成功');
    }

}