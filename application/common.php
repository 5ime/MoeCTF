<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function returnJsonData($code,$msg,$data=array())
{
    $json_data['code'] = $code;
    $json_data['msg'] = $msg;
    if($data) {
        $json_data['data'] = $data;
    }
    return json($json_data);
}

function explodeTags($data, $count = 3)
{
    foreach ($data['data'] as $key => $value) {
        $tags = explode(',', $value['tags']);
        if (count($tags) == 1 && $tags[0] == '') {
            $tags = array('No Tags');
        }
        $data['data'][$key]['tags'] = array_slice($tags, 0, $count);
    }
    return $data;
}

function hashPwd($string)
{
	return "###".md5(hash_hmac("sha256", $string, '!dJ&S6@GliG3'));
}

function sendEmail($tomail, $name, $randCode, $attachment = null) {
    $config = Db::name('setting')->field('title, smtp, smtp_name, smtp_pass')->find();
    $mail = new PHPMailer\PHPMailer\PHPMailer();           // 实例化PHPMailer对象
    $mail->CharSet = 'UTF-8';                              // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                                       // 设定使用SMTP服务
    $mail->SMTPDebug = 0;                                  // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
    $mail->Host = $config['smtp'];                         // SMTP服务器的名称
    $mail->SMTPAuth = true;                                // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl';                             // 使用安全协议
    $mail->Port = 465;                                     // SMTP服务器的端口号
    $mail->Username = $config['smtp_name'];                // SMTP服务器用户名
    $mail->Password = $config['smtp_pass'];                // SMTP服务器密码
    $mail->SetFrom($config['smtp_name'], $config['title']);
    $replyEmail = '';                                      // 留空则为发件人EMAIL
    $replyName = '';                                       // 回复名称（留空则为发件人名称）
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $config['title'] . '用户邮箱验证';
    $url = request()->domain();
    $body = "亲爱的" . $name . "您好！<br>请点击此链接激活您的邮箱：" . $url . "/verify/" . base64_encode($randCode);
    $mail->MsgHTML($body);
    $mail->AddAddress($tomail, $name);
    if (is_array($attachment)) {                           // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? returnJsonData(200,'success') : returnJsonData(201,'error'); //$mail->ErrorInfo;
}