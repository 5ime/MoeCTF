# MoeCTF

> 🧀 A Simple, Free CTF Platform 一个简单、免费的CTF平台

MoeCTF 正在开发中...

## 部署

### 安装 MoeCTF

导入 `moectf.sql` ，修改 `config/database.php` 文件中的数据库配置

```
// 服务器地址
'hostname'        => '127.0.0.1',
// 数据库名
'database'        => 'moectf',
// 用户名
'username'        => '',
// 密码
'password'        => '',
```

### 启动 Worker

```
php think worker:server
```

## 访问 MoeCTF

直接访问你的网站即可

### 默认账号

```
默认用户名：admin
默认密码：123456
```

### 预览 MoeCTF

![image](https://user-images.githubusercontent.com/31686695/199656842-d4d4140b-1e50-4a5b-884c-0c15592458e1.png)