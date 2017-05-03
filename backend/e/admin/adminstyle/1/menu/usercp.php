<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>菜单</title>
<link href="../../../data/menu/menu.css" rel="stylesheet" type="text/css">
<script src="../../../data/menu/menu.js" type="text/javascript"></script>
<script src="/js/jquery.js"></script>
<script src="/js/ecmsshop_fanyi.js"></script>
<SCRIPT lanuage="JScript">
function tourl(url){
	parent.main.location.href=url;
}
</SCRIPT>
</head>
<body onLoad="initialize()">
<div class="leftside">
<h2><span>用户面板</span></h2>

<span id="pruser" onClick="chengstate('user')" class="htyhgl">后台用户管理</span>
<ul id="itemuser" style="display:none">
        <li><a href="../../user/EditPassword.php<?=$ecms_hashur[whehref]?>" target="main" class="xggrzl">修改个人资料</a></li>
		<?
		if($r[dogroup])
		{
		?>
        <li><a href="../../user/ListGroup.php<?=$ecms_hashur[whehref]?>" target="main" class="glyhz">管理用户组</a></li>
		<?
		}
		if($r[douserclass])
		{
		?>
        <li><a href="../../user/UserClass.php<?=$ecms_hashur[whehref]?>" target="main" class="glbm">管理部门</a></li>
		<?
		}
		if($r[douser])
		{
		?>
        <li><a href="../../user/ListUser.php<?=$ecms_hashur[whehref]?>" target="main" class="glyh">管理用户</a></li>
		<?
		}
		if($r[dolog])
		{
		?>
        <li><a href="../../user/ListLog.php<?=$ecms_hashur[whehref]?>" target="main" class="gldlrz">管理登陆日志</a></li>
        <li><a href="../../user/ListDolog.php<?=$ecms_hashur[whehref]?>" target="main" class="glczrz">管理操作日志</a></li>
        <?
		}
		if($r[doadminstyle])
		{
		?>
        <li><a href="../../template/AdminStyle.php<?=$ecms_hashur[whehref]?>" target="main" class="glhtfg">管理后台风格</a></li>
		<?
		}
		?>
</ul>

<?
if($r[domember]||$r[domemberf])
{
?>
<span id="prmember" class="qthygl" onClick="chengstate('member')">前台会员管理</span>
<ul id="itemmember" style="display:none">
		<?
		if($r[domember])
		{
		?>
        <li><a href="../../member/ListMemberGroup.php<?=$ecms_hashur[whehref]?>" target="main" class="glhyz">管理会员组</a></li>
        <li><a href="../../member/ListMember.php<?=$ecms_hashur[whehref]?>" target="main" class="glhy">管理会员</a></li>
        <li><a href="../../member/ClearMember.php<?=$ecms_hashur[whehref]?>" target="main" class="plqlhy">批量清理会员</a></li>
        <?
		}
		if($r[domemberf])
		{
		?>
        <li><a href="../../member/ListMemberF.php<?=$ecms_hashur[whehref]?>" target="main" class="glhyzd">管理会员字段</a></li>
        <li><a href="../../member/ListMemberForm.php<?=$ecms_hashur[whehref]?>" target="main" class="glhybd">管理会员表单</a></li>
		<?
		}
		?>
</ul>
<?
}
?>

<?
if($r[dospacestyle]||$r[dospacedata])
{
?>
<span id="prmemberspace" class="hykjgl" onClick="chengstate('memberspace')">会员空间管理</span>
<ul id="itemmemberspace" style="display:none">
		<?
		if($r[dospacestyle])
		{
		?>
        <li><a href="../../member/ListSpaceStyle.php<?=$ecms_hashur[whehref]?>" target="main" class="glkjmb">管理空间模板</a></li>
        <?
		}
		if($r[dospacedata])
		{
		?>
        <li><a href="../../member/MemberGbook.php<?=$ecms_hashur[whehref]?>" target="main" class="glkjly">管理空间留言</a></li>
        <li><a href="../../member/MemberFeedback.php<?=$ecms_hashur[whehref]?>" target="main" class="glkjfk">管理空间反馈</a></li>
		<?
		}
		?>
</ul>
<?
}
?>

<?
if($r[domemberconnect])
{
?>
<span id="prmemberconnect" class="wbjk" onClick="chengstate('memberconnect')">外部接口</span>
<ul id="itemmemberconnect" style="display:none">
        <li><a href="../../member/MemberConnect.php<?=$ecms_hashur[whehref]?>" target="main" class="glwbdljk">管理外部登录接口</a></li>
</ul>
<?
}
?>

<?
if($r[docard]||$r[dosendemail]||$r[domsg]||$r[dobuygroup])
{
?>
<span id="prmother" class="qtgn" onClick="chengstate('mother')">其他功能</span>
<ul id="itemmother" style="display:none">
		<?
		if($r[dobuygroup])
		{
		?>
        <li><a href="../../member/ListBuyGroup.php<?=$ecms_hashur[whehref]?>" target="main" class="glczlx">管理充值类型</a></li>
        <?
		}
		if($r[docard])
		{
		?>
        <li><a href="../../member/ListCard.php<?=$ecms_hashur[whehref]?>" target="main" class="gldk">管理点卡</a></li>
        <li><a href="../../member/GetFen.php<?=$ecms_hashur[whehref]?>" target="main" class="plzsds">批量赠送点数</a></li>
        <?
		}
		if($r[dosendemail])
		{
		?>
        <li><a href="../../member/SendEmail.php<?=$ecms_hashur[whehref]?>" target="main" class="plfsyj">批量发送邮件</a></li>
        <?
		}
		if($r[domsg])
		{
		?>
        <li><a href="../../member/SendMsg.php<?=$ecms_hashur[whehref]?>" target="main" class="plfsdxx">批量发送短消息</a></li>
        <li><a href="../../member/DelMoreMsg.php<?=$ecms_hashur[whehref]?>" target="main" class="plscdxx">批量删除短消息</a></li>
        <?
		}
		?>
</ul>
<?
}
?>
</div>
</body>
</html>