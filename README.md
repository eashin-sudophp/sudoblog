eashin的个人博客，持续完善丰富博客各项功能。

# sudoblog

## 相关链接
博客： https://blog.sudophp.com/  
介绍： [开源博客项目基于thinkphp5](http://blog.sudophp.com/sudoblog)

## 介绍
急于整出[博客](https://blog.sudophp.com/)并上线，所以将以前基于thinkphp开发的简易项目搬过来，进行前后端的初步优化。
当前博客暂时以文章模块为主，不过易于二次开发扩展；设置了路由简化前台文章和列表的访问链接，实现seo友好的链接结构；后台模板是hui和layui（未实现手机端自适应），前端是买的一个模板套上去。更多功能持续完善中
下一步实现免登陆评论和评论管理，pv/uv和文章浏览数，单页模板管理等（ps：其他如微信扫码登陆，公众号验证码，邮箱功能等）

![sudoblog前台](http://blog.sudophp.com/static/images/screenshot/breath_index.png)  
![sudoblog后台登陆](http://blog.sudophp.com/static/images/screenshot/breath_login.png)  
![sudoblog后台文章](http://blog.sudophp.com/static/images/screenshot/breath_article.png)  
![sudoblog后台文章分类](http://blog.sudophp.com/static/images/screenshot/breath_category.png)  


## 第一版的目录结构
~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─admin              管理后台模块目录
│  │  ├─controller      控制器目录
│  │  ├─lib             逻辑控制器目录
│  │  ├─model           模型目录
│  │  ├─volidate        验证器目录
│  │  ├─common.php      模块函数文件
│  │  └─config.php      模块配置文件
│  ├─api                接口模块目录
│  │  └─controller      控制器目录
│  ├─extra              其他配置目录
│  ├─index              前台模块目录
│  │  ├─controller      控制器目录
│  │  ├─common.php      模块函数文件
│  │  └─config.php      模块配置文件
│  ├─lang               语言包目录
│  ├─command.php        命令行工具配置文件
│  ├─common.php         公共函数文件
│  ├─config.php         公共配置文件
│  ├─route.php          路由配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─database.php       数据库配置文件
│
├─extend                扩展类库目录
│  ├─common             公共模块目录
│  │  ├─Cache.php       缓存实现文件
│  │  ├─Errcode.php     接口错误码文件
│  │  ├─Format.php      统一格式验证器
│  │  └─Redis.php       reids实现文件
│  └─Qiniu              七牛实现目录
│
├─public                WEB目录（对外访问目录）
│  ├─_mystorage         自定义的临时缓存目录
│  ├─lib                前端扩展存放目录
│  ├─static             静态资源目录
│  │  ├─admin           后台静态资源目录
│  │  ├─web             前台静态资源目录
│  │  │  └─breath       前台某个模板（eg:breath）资源目录
│  │  └─images          图片目录
│  ├─template           前台模板备份目录
│  ├─upload             上传目录
│  ├─index.php          入口文件
│  └─.htaccess          用于apache的重写
│
├─template              模板目录
│  ├─web                前台模板目录
│  │  ├─breath          前台某个模板（eg:breath）目录
│  │  │  ├─404.html     404模板文件
│  │  │  ├─detail.html  文章详情模板文件
│  │  │  ├─footer.html  公共底部模板文件
│  │  │  ├─header.html  公共头部模板文件
│  │  │  ├─index.html   首页模板文件
│  │  │  └─list.html    列表模板文件
│  │  └─index.html      前台模板布局文件
│  └─admin              后台模板目录
│
├─thinkphp              框架系统目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~


## 安装教程

1. git clone https://gitee.com/ashin1206293024_admin/sudoblog.git 把项目拉下来；
2. composer install
3. 后台登录默认为admin 123456；
4. 需要开启mod_rewrite；
5. 添加后台模板到/template/web/xxx/,静态资源放到/public/static/web/xxx/下（xxx为前端模板的名称，模板文件命名参考上述目录结构）

## 链接结构说明

1.后台  
隐藏路由文件，默认/admin即可完成访问，链接规则：http://blog.sudophp/admin/index/welcome.html  
为tp5的默认url规则，[学习-tp5的url访问](https://www.kancloud.cn/manual/thinkphp5/118012)  

2.前台  
首页: http://blog.sudophp.com  （第二页 http://blog.sudophp.com/2 ...）  
文章分类列表: http://blog.sudophp.com/php （第二页 http://blog.sudophp.com/php/2 ...）  
文章详情页 http://blog.sudophp.com/php/what-is-php.html  
（ps: 以上路由规则见：application/route.php）  

## 项目介绍

已完成部分：
- [x] 前台模板基于boostrap的响应式页面布局适配手机和平板
- [x] 容易添加和切换前台模板
- [x] seo友好的链接结构
- [x] ueditor 百度富文本编辑器

下一步待实现：
- [ ] 评论功能-免登陆评论和后台的评论管理，一键设置评论免审核等
- [ ] 文章浏览数，pv/uv的统计
- [ ] 友情链接及管理功能
- [ ] markdown文本编辑器
- [ ] 博客单页，暂定留言页、文章标签页、友链页、分类（无限极分类）页、时光轴倒叙的文章列表页、网站地图页等

可能实现：
- [ ] 微信登陆
- [ ] 公众号验证码
- [ ] 邮箱功能

## 致谢

thinkphp5: http://www.thinkphp.cn/  
jquery: https://jquery.com/  
layui: https://www.layui.com/  
h-ui: http://www.h-ui.net/  

本博客不足之处敬请指正，小弟抱紧各位大佬的大腿，不断去完善改进sudoblog。(by Eashin)  
偶会一直成长滴。(by Sudoblog)  


