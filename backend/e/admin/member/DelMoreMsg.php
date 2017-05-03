<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
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
if($enews=="DelMoreMsg")
{
	include("../../class/com_functions.php");
	DelMoreMsg($_POST,$logininid,$loginin);
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量删除站内短消息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<script src="../ecmseditor/fieldfile/setday.js"></script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="DelMoreMsg.php<?=$ecms_hashur['whehref']?>">批量删除站内短消息</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>批量删除站内短消息</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="DelMoreMsg.php" onSubmit="return confirm('确认要删除?');">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="DelMoreMsg">
<table class="comm-table" cellspacing="0">
	<tbody>
    <tr> 
      <td>消息类型</td>
      <td style="text-align:left;"><select name="msgtype" id="msgtype">
          <option value="0">前台全部消息</option>
		  <option value="2">只删除前台系统消息</option>
		  <option value="1">后台全部消息</option>
		  <option value="3">只删除后台系统消息</option>
        </select></td>
    </tr>
    <tr>
      <td>发件人</td>
      <td style="text-align:left;"><input name="from_username" type="text" id="from_username">
        <input name="fromlike" type="checkbox" id="fromlike" value="1" checked>
        模糊匹配 (不填为不限)</td>
    </tr>
    <tr>
      <td>收件人</td>
      <td style="text-align:left;"><input name="to_username" type="text" id="to_username">
        <input name="tolike" type="checkbox" id="tolike" value="1" checked>
        模糊匹配(不填为不限)</td>
    </tr>
    <tr> 
      <td>包含关键字</td>
      <td style="text-align:left;"><input name="keyboard" type="text" id="keyboard"> 
        <select name="keyfield" id="keyfield">
          <option value="0">检索标题和内容</option>
          <option value="1">检索信息标题</option>
          <option value="2">检索信息内容</option>
        </select>
        (多个请用&quot;,&quot;格开)</td>
    </tr>
    <tr> 
      <td>时间</td>
      <td style="text-align:left;">删除从 
        <input name="starttime" type="text" id="starttime" onClick="setday(this)" size="12">
        到 
        <input name="endtime" type="text" id="endtime" onClick="setday(this)" size="12">
        之间的短消息</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td style="text-align:left;"><input type="submit" name="Submit" value="批量删除"></td>
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
