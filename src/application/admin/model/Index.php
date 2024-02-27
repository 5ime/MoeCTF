<?php
namespace app\admin\model;
use GatewayWorker\Lib\Gateway;
use think\Model;
use think\Db;

class Index extends Model
{
    public function getSettingsInfo()
    {
        $data = Db::name('setting')->find();
        $data['smtp_pass'] = '********';
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
        if (empty($data['data'])) {
            return returnJsonData(202,'无提交记录');
        }
        
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

    public function getSystemInfo()
    {
        $challenges = Db::name('challenges')->field('id, title, solve')->order('solve', 'desc')->paginate(10)->toArray();
        $userCount = Db::name('users')->count();
        foreach ($challenges['data'] as &$challenge) {
            $challenge['pass_rate'] = round($challenge['solve'] / $userCount * 100, 2);
        }
        usort($challenges['data'], function ($a, $b) {
            return $b['pass_rate'] <=> $a['pass_rate'];
        });
        $data = [
            'count' => [
                'users' => $userCount,
                'challenges' => count($challenges),
            ],
            'data' => $challenges,
        ];
        return returnJsonData(200, 'success', $data);
    }

    public function getServerInfo()
    {
        // 获取系统负载信息
        $loadavg = sys_getloadavg();
        $load_percentage = round($loadavg[0] / $this->getProcessorCount() * 100, 2);
    
        // 获取内存使用情况
        $meminfo = file('/proc/meminfo');
        $mem_total = filter_var($meminfo[0], FILTER_SANITIZE_NUMBER_INT);
        $mem_free = filter_var($meminfo[1], FILTER_SANITIZE_NUMBER_INT);
        $mem_buffers = filter_var($meminfo[2], FILTER_SANITIZE_NUMBER_INT);
        $mem_cached = filter_var($meminfo[3], FILTER_SANITIZE_NUMBER_INT);
        $mem_usage = round(100 - (($mem_free + $mem_buffers + $mem_cached) / $mem_total) * 100, 2);
    
        // 获取当前连接数
        $count = Gateway::getAllClientCount();
    
        // 获取CPU使用率
        $stat = file_get_contents('/proc/stat');
        preg_match('/^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $stat, $matches);
        list($user1, $nice1, $system1, $idle1) = array_slice($matches, 1);
        sleep(1);
        $stat = file_get_contents('/proc/stat');
        preg_match('/^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/', $stat, $matches);
        list($user2, $nice2, $system2, $idle2) = array_slice($matches, 1);
        $cpu_percentage = round((($user2 + $nice2 + $system2) - ($user1 + $nice1 + $system1)) / (($user2 + $nice2 + $system2 + $idle2) - ($user1 + $nice1 + $system1 + $idle1)) * 100, 2);
    
        return json([
            'load_percentage' => $load_percentage,
            'mem_usage' => $mem_usage,
            'cpu_percentage' => $cpu_percentage,
            'online_count' => $count,
        ]);
    }
    
    private function getProcessorCount()
    {
        // 获取CPU核心数
        if (file_exists('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            return count($matches[0]);
        }
        return 1;
    }
}
