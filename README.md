# 🧀 MoeCTF

A Simple, Free CTF Platform 一个简单、免费的CTF平台

## 安装

1. 下载源码：

```
git clone https://github.com/5ime/moectf.git
```

2. 运行服务：

```
docke-compose up -d
```

3. 查看容器ID：

```
docker ps
```

4. 进入PHP容器：

```
docker exec -it {id} bash
```

5. 启动Worker：

```
php think worker:gateway -d
```

6. 访问网站

浏览器访问localhost即可访问MoeCTF系统，管理员账号密码为admin/123456。

![image](https://user-images.githubusercontent.com/31686695/199656842-d4d4140b-1e50-4a5b-884c-0c15592458e1.png)

## 贡献

如果您有任何好的想法或建议，欢迎提交Issue或Pull Request。

## 许可

MoeCTF采用GPL许可，详情请参考[LICENSE](https://github.com/5ime/MoeCTF/blob/master/LICENSE)文件。