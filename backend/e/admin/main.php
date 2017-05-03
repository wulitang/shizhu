<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../member/class/user.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
//我的状态
$user_r=$empire->fetch1("select pretime,preip,loginnum,preipport from {$dbtbpre}enewsuser where userid='$logininid'");
$gr=$empire->fetch1("select groupname from {$dbtbpre}enewsgroup where groupid='$loginlevel'");
//管理员统计
$adminnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser");
$date=date("Y-m-d");
$noplnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$public_r['pldeftb']." where checked=1");
//未审核会员
$nomembernum=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('checked')."=0");
$yesmembernum=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('checked')."=1");
//过期广告
$outtimeadnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsad where endtime<'$date' and endtime<>'0000-00-00'");
//系统信息
	if(function_exists('ini_get')){
        $onoff = ini_get('register_globals');
    } else {
        $onoff = get_cfg_var('register_globals');
    }
    if($onoff){
        $onoff="打开";
    }else{
        $onoff="关闭";
    }
    if(function_exists('ini_get')){
        $upload = ini_get('file_uploads');
    } else {
        $upload = get_cfg_var('file_uploads');
    }
    if ($upload){
        $upload="可以";
    }else{
        $upload="不可以";
    }
	if(function_exists('ini_get')){
        $uploadsize = ini_get('upload_max_filesize');
    } else {
        $uploadsize = get_cfg_var('upload_max_filesize');
    }
	if(function_exists('ini_get')){
        $uploadpostsize = ini_get('post_max_size');
    } else {
        $uploadpostsize = get_cfg_var('post_max_size');
    }
//开启
$register_ok="开启";
if($public_r[register_ok])
{$register_ok="关闭";}
$addnews_ok="开启";
if($public_r[addnews_ok])
{$addnews_ok="关闭";}
//版本
@include("../class/EmpireCMS_version.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国CMS网站管理系统</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link href="/skins/opera.css" rel="stylesheet" type="text/css" />
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
});	
function member(){
art.dialog.open('member/ListMember.php<?=$ecms_hashur['whehref']?>',
    {title: '会员管理信息',lock: true,opacity: 0.5, width: 800, height: 520});
}
function ziliao(){
art.dialog.open('user/EditPassword.php<?=$ecms_hashur['whehref']?>',
    {title: '个人资料信息',lock: true,opacity: 0.5, width: 800, height: 520});
}
//修改网站标题
function peizhi(){
art.dialog.open('SetEnews.php<?=$ecms_hashur[whehref]?>',
    {title: '系统参数设置',lock: true,opacity: 0.2, width: 1000, height: 450});
}
//增加信息
function addnews(){
art.dialog.open('AddInfoChClass.php<?=$ecms_hashur[whehref]?>',
    {title: '增加信息',lock: true,opacity: 0.5, width: 800, height: 540});
}
//单页管理
function danye(){
art.dialog.open('template/ListPage.php<?=$ecms_hashur[whehref]?>',
    {title: '修改单页面',lock: true,opacity: 0.5, width: 800, height: 540});
}
//留言管理
function lygl(){
art.dialog.open('tool/gbook.php<?=$ecms_hashur[whehref]?>',
    {title: '管理留言信息',lock: true,opacity: 0.5, width: 800, height: 540});
}
//反馈管理
function fkgl(){
art.dialog.open('tool/feedback.php<?=$ecms_hashur[whehref]?>',
    {title: '管理反馈信息',lock: true,opacity: 0.5, width: 800, height: 540});
}
//友情链接管理
function flinkgl(){
art.dialog.open('tool/ListLink.php<?=$ecms_hashur[whehref]?>',
    {title: '管理友情链接',lock: true,opacity: 0.5, width: 800, height: 540});
}
</script>
</head>

<body>
	<div class="container">
    	<div class="leftside">
        	<div id="menu">
<div class="ui-tab-container">
	<ul class="clearfix ui-tab-list">
		<li><span class="haiisnews"></span></li>
		<li class="ui-tab-active"><span class="user"></span></li>
		<li><span class="system"></span></li>
	</ul>
	<div class="ui-tab-bd">
		<div class="ui-tab-content" style="display:none">
        	<div class="line"></div>
            <h3>淮安互联最新动态</h3>
            <div class="line"></div>
           	<ul>
<li><a href="#">此处你可以自定义新闻内容</a></li>
<li><a href="#">修改在 e/admin/main.php</a></li>
<li><a href="#">请支持作者 购买使用</a></li>
<li><a href="#">作者会一直更新下去~ 感谢大家</a></li>
<li><a href="http://wpa.qq.com/msgrd?v=3&uin=372009617&site=qq&menu=yes">购买及售后QQ:372009617</a></li>
<li><a href="http://www.phome.net/service/price/ecms.html" target="_blank">正版帝国软件购买</a></li>
            </ul>
            <div class="clear"></div>
        </div>
		<div class="ui-tab-content">
        <div class="line"></div>
        <h3>我的状态</h3>
        <div class="line"></div>
        <div class="userinfo">
        	<span>登录者: <?=$loginin?></span>
            <span>用户组: <?=$gr[groupname]?></span>
            <span>这是您第 <?=$user_r[loginnum]?> 次登录</span>
            <span>上次登录IP: <?=$user_r[preip]?$user_r[preip].':'.$user_r[preipport]:'---'?></span>
            <span>上次登录: <?=$user_r[pretime]?date('Y-m-d H:i:s',$user_r[pretime]):'---'?></span>
            <span><a href="javascript:void(0)" onclick="ziliao()" class="xgmm">修改密码</a></span>
        </div>
        <div class="line"></div>
        <h3>特别感谢</h3>
        <div class="line"></div>
		<div class="userinfo">
        	<span>后台界面制作: <a href="http://wpa.qq.com/msgrd?v=3&uin=372009617&site=qq&menu=yes" target="_blank" title="点击在线联系我">yecha</a></span>
            <span>开发与支持团队: wm_chief、amt、帝兴、小游、zeedy</span>
            <span>禾火木风、yingnt、hicode、sooden、老鬼、小林、天浪歌、TryLife、5starsgeneral</span>
            <span>版权所有: <a href="http://www.digod.com" target="_blank">漳州市芗城帝兴软件开发有限公司</a></span>
        </div>
        </div>
		<div class="ui-tab-content" style="display:none">
        <div class="line"></div>
        <h3>系统信息</h3>
        <div class="line"></div>
        	<div class="userinfo">
            	<span>会员注册: <?=$register_ok?></span>
                <span>会员投稿: <?=$addnews_ok?></span>
                <span>管理员个数: <a href="user/ListUser.php"><?=$adminnum?></a> 人</span>
                <span>未审核评论: <a href="openpage/AdminPage.php?leftfile=<?=urlencode('../pl/PlNav.php')?>&mainfile=<?=urlencode('../pl/ListAllPl.php?checked=2')?>&title=<?=urlencode('管理评论')?>"><?=$noplnum?></a> 条</span>
                <span>未审核会员: <a href="member/ListMember.php?sear=1&schecked=1"><?=$nomembernum?></a> 人</span>
                <span>过期广告: <a href="tool/ListAd.php?time=1"><?=$outtimeadnum?></a> 个</span>
                <span>登陆者IP: <? echo egetip();?></span>
                <span>程序编码: <?=EmpireCMS_CHARVER?></span>
                <span>服务器软件: <?=$_SERVER['SERVER_SOFTWARE']?></span>
                <span>操作系统: <? echo defined('PHP_OS')?PHP_OS:'未知';?></span>
                <span>PHP版本: <? echo @phpversion();?></span>
                <span>MYSQL版本: <? echo @mysql_get_server_info();?></span>
                <span>全局变量: <?=$onoff?> <font color="#666666">(建议关闭)</font></span>
                <span>魔术引用: <?=MAGIC_QUOTES_GPC?'开启':'关闭'?> <font color="#666666">(建议开启)</font></span>
                <span>上传文件: <?=$upload?> <font color="#666666">(最大文件：<?=$uploadsize?>，表单：<?=$uploadpostsize?>)</font></span>
                <span>当前时间: <? echo date("Y-m-d H:i:s");?></span>
                <span>使用域名: <?=$_SERVER['HTTP_HOST']?></span>
            </div>
        </div>
	</div>
 </div>
            </div>
        </div>
        <div class="rightmain">
        	<div class="kjmenu">
            	<span>快捷菜单</span>
                <div class="webtj">
                </div>
            </div>
            <!--编辑人员-->
            <div class="qmenu">
            	<h3>快速操作菜单</h3>
                <ul class="qmenunav">
                  <li><a href="javascript:void(0)" onclick="ziliao()"><img src="adminstyle/1/yecha/qlink/ziliao.png" alt=""><span>个人资料</span></a></li>
                  <li><a href="javascript:void(0)" onclick="addnews()"><img src="adminstyle/1/yecha/qlink/addnews.png" alt=""><span>增加新闻</span></a></li>
                  <li style="position:relative;"><a href="javascript:void(0)" onclick="member()" title="共有<?=$yesmembernum?>个会员"><img src="adminstyle/1/yecha/qlink/hire-me.png" alt=""><span>会员管理</span></a><strong title="<?=$nomembernum?>人未审核"><?=$nomembernum?></strong></li>
                  <li><a href="javascript:void(0)" onclick="danye()" title="查看单页面"><img src="adminstyle/1/yecha/qlink/maps.png" alt=""><span>联系我们</span></a></li>
                  <li><a href="javascript:void(0)" onclick="peizhi()"><img src="adminstyle/1/yecha/qlink/config1.png" alt=""><span>参数设置</span></a></li>
        		</ul>
                <span class="line"></span>
            </div>
            <!--管理人员-->
            <div class="qmenu">
                <ul class="qmenunav">
                  <li><a href="javascript:void(0)" onclick="lygl()"><img src="adminstyle/1/yecha/qlink/ly.png" alt=""><span>留言管理</span></a></li>
                  <li><a href="javascript:void(0)" onclick="fkgl()"><img src="adminstyle/1/yecha/qlink/fk.png" alt=""><span>反馈管理</span></a></li>
                  <li><a href="javascript:void(0)" onclick="flinkgl()"><img src="adminstyle/1/yecha/qlink/link.gif" alt=""><span>友情链接</span></a></li>
        		</ul>
            </div>
            <div class="qmenu" style="border-bottom:none;">
            	<h3>系统提醒</h3>
                <div class="tixing">
                	<span class="tip bluetip">欢迎您,<?=$loginin?> 上次登陆时间: <?=$user_r[pretime]?date('Y-m-d H:i:s',$user_r[pretime]):'---'?></span>
                	<span class="tip greentip">您当前的帝国CMS版本为: Ecms <?=EmpireCMS_VERSION?> (<?=EmpireCMS_LASTTIME?>) 暂时无更新</span>
                    <span class="tip redtip"><a href="javascript:void(0)" onclick="ziliao()" class="xgmm">友情提醒: 请设置安全的管理员密码,建议字母+数字!</a></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
db_close();
$empire=null;
?>
