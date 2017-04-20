## MyPersimmon

![macbook](https://raw.githubusercontent.com/cong5/myPersimmon/master/screen.jpg)


本项目使用 PHP 框架 [Laravel 5.4](https://doc.laravel-china.org/docs/5.4/) 进行开发。 
项目依赖了部分云服务，如图片使用了七牛云储存。（目前必须，暂时没有加入本地存储图片的功能）   
文章别名自动生成，使用了百度翻译功能。（必须）
接入了baidu ping服务，当发布文章的时候，自动向百度搜索引擎提交地址。（可以不使用） 
系统后台使用了Vuejs + Element-UI实现前后端分离 


### 博客功能具有以下功能

- 分类管理
- 文章管理
- 标签管理
- 评论管理
- 导航管理
- Redis 缓存
- 好用的 Simplemde Markdown 编辑器
- myPersimmon博客分类、文章都支持自定义URL
- 支持Metaweblog API，接口地址：http://example.com/xmlrpc ，可以方便的使用离线发布工具写博客，比如我就喜欢使用Mweb写博客，然后通过Metaweblog API发布。

更多功能欢迎大家自己挖掘，或者有好的意见和建议欢迎拍砖。


## 项目概述

* 项目名称：MyPersimmon
* 项目运行地址：https://cong5.net/

[MyPersimmon](https://github.com/Cong5/myPersimmon) 基于Laravel 5.4 版本开发。

## 目前运行环境

- Nginx 1.8+
- PHP 5.6+
- MySQL 5.5+
- Redis 3.0+

## 部署/安装

需要在系统上安装了基本的PHP运行环境、PHP包管理工具composer、Nodejs进行前端资源打包

### 基础安装

#### 1. 克隆源代码

克隆源代码到本地：

    > git clone https://github.com/Cong5/myPersimmon.git

#### 2. 安装扩展包依赖

    > composer install

#### 4. 生成配置文件

    > cp .env.example .env

然后在`.env`的配置文件里面新增如下配置项：

```shell
#七牛云储存
QINIU_ACCESSKEY=
QINIU_SECRETKEY=
QINIU_BUCKET=
#百度翻译
BAIDU_TRANSLATE_AK=
BAIDU_TRANSLATE_SK=
#百度ping
BAIDU_PING_SITE=
BAIDU_PING_TOKEN=
#错误信息推送到微信
SERVER_CHAN=
```

云服务AppKey和SecretKey申请地址：   
[七牛](http://www.qiniu.com/)   
[百度翻译](http://api.fanyi.baidu.com/api/trans/product/index)   
[百度ping](http://zhanzhang.baidu.com/linksubmit/index)    
[Server酱](http://sc.ftqq.com/)    
    
#### 5. 执行数据库迁移

```shell
php artisan migrate
```

#### 6. 填充初始数据

```shell
php artisan db:seed
```


### 前端工具集安装

> 代码里自带编译后的前端代码，如果你不想开发前端样式的话，你是不需要配置前端工具集的，可本部分，看`前后台入口`部分

1). 安装 node.js

直接去官网 [https://nodejs.org/en/](https://nodejs.org/en/) 下载安装最新版本。

2). 安装 Laravel Mix

```shell
npm install
```

如果嫌弃国内npm下载慢的话，可以使用[淘宝NPM镜像:http://npm.taobao.org/](http://npm.taobao.org/)

4). 直接 Mix 编译前端内容

开发环境使用：
```shell
npm run dev
```

生产环境请使用
```shell
npm run production
```

5). 监控修改并自动编译

```shell
npm run watch
```

### 前后台入口

> 如果要开启调试模式，请修改 `.env` 文件， `APP_ENV=local` 和 `APP_DEBUG=true` 。

* 首页地址：http://example.com/
* 管理后台：http://example.com/myp

默认用户名：mypersimmon@cong5.net
密码：mypersimmon

至此, 安装完成。


## License

> 使用 MyPersimmon 构建，或者基于 MyPersimmon 源代码修改的站点 **必须** 在页脚加上 `Powered by Mr柿子` 字样，并且必须链接到 `https://cong5.net` 上。

在遵守以上规则的情况下，你可以享受等同于 MIT 协议的授权。

