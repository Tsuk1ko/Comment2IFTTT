# Comment2IFTTT

该插件为 Typecho 插件，可将博客新评论推送至 IFTTT Webhooks，进而产生多种自由玩法，例如将新评论提醒推送至 Telegram 等

## 安装插件

### 方法一

进入 Typecho 插件目录`usr/plugins/`，直接克隆本项目

```bash
git clone https://github.com/Tsuk1ko/Comment2IFTTT.git
```

### 方法二

进入 Typecho 插件目录`usr/plugins/`，创建名为`Comment2IFTTT`的文件夹，然后将 [Plugin.php](https://github.com/Tsuk1ko/Comment2IFTTT/raw/master/Plugin.php) 文件下载到此文件夹内

## 配置插件

在 Typecho 后台启用插件后需要先进入该插件的设置页面设置 **Webhooks Key** 和 **Event Name** 后才能使用

### Webhooks Key

当然你得需要有一个 IFTTT 帐号……

首先到 IFTTT 的 [Webhooks](https://ifttt.com/maker_webhooks) 服务页面启用服务，然后点击右上角的“Documentation”即可得到

### Event Name

Webhooks 事件名，自己起一个名字，例如`typecho`什么的

## 在 IFTTT 中运用 Webhooks

如果你从未使用过 IFTTT，可以先试着自己玩一下熟悉一下（

该插件会向 Webhooks 推送3个 value：

- value1 - 收到评论的文章标题
- value2 - 评论人昵称
- value3 - 评论内容

于是你就可以自由构建收到通知的格式了

### 举个栗子

例如我自己的用法是将评论推送到 Telegram（需要先在 IFTTT 中关联 Telegram 帐号）

首先新建一个 Applet：if **Webhooks** then **Telegram**

Webhooks 选（也只能选）“Receive a web request”，然后填入 Event Name，就是你在插件里设置的那个

Telegram 选“Send message”，Message text 便是你可以自由利用 value1\~3 来构建的消息内容，以下是我的例子

```text
文章《{{Value1}}》有新评论啦<br>
<b>{{Value2}}</b><br>
{{Value3}}<br>
```
