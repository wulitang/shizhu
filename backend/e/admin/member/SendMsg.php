<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../member/class/user.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
//验证权限
CheckLevel($logininid,$loginin,$classid,"msg");
$enews=$_POST['enews'];
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="SendMsg")
{
	include("../../class/com_functions.php");
	include "../".LoadLang("pub/fun.php");
	DoSendMsg($_POST,0,$logininid,$loginin);
}
$groupid=(int)$_GET['groupid'];
//----------会员组
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($level_r=$empire->fetch($sql))
{
	if($groupid==$level_r[groupid])
	{$select=" selected";}
	else
	{$select="";}
	$membergroup.="<option value=".$level_r[groupid].$select.">".$level_r[groupname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>发送站内短消息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="SendMsg.php<?=$ecms_hashur['whehref']?>">发送站内短消息</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>发送站内短消息</span></h3>
<div class="jqui anniuqun">
<form name="sendform" method="post" action="SendMsg.php" onSubmit="return confirm('确认要发送?');">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="SendMsg">
<table class="comm-table" cellspacing="0">
	<tbody>
    <tr style="display:none;"> 
      <td>接收会员组</td>
      <td style="text-align:left;"> <select name="groupid[]" size="5" multiple id="groupid[]">
          <?=$membergroup?>
        </select> <font color="#666666">(全选用&quot;CTRL+A&quot;,选择多个用CTRL/SHIFT+点击选择)</font></td>
    </tr>
     <tr>
      <td >接收会员用户名</td>
      <td style="text-align:left;"><input name="username" type="text" id="username" size="60">
        <font color="#666666">(多个用户名"|"隔开)</font></td>
    </tr>
    <tr> 
      <td >每组发送个数</td>
      <td style="text-align:left;"><input name="line" type="text" id="line" value="300" size="8"> 
      </td>
    </tr>
    <tr> 
      <td>标题</td>
      <td style="text-align:left;"><input name="title" type="text" id="title" size="60"></td>
    </tr>
    <tr> 
      <td height="25" valign="top">内容<br>
          (支持html代码)</td>
      <td style="text-align:left;"><textarea name="msgtext" cols="60" rows="14" id="msgtext"></textarea></td>
    </tr>
    <tr> 
      <td ><div align="left"></div></td>
      <td style="text-align:left;"><input type="submit" name="Submit" value="发送"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
    <tr> 
      <td >&nbsp;</td>
      <td>可以在标题与内容中使用：[!--username--]代表用户名</td>
    </tr>
	</tbody>
</table>
        </form>
</div>
<div class="line"></div>
        </div>
    </div>
</div>
</div>
</body>
</html>
