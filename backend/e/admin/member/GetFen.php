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
CheckLevel($logininid,$loginin,$classid,"card");
$enews=$_POST['enews'];
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="GetFen")
{
	include('../../member/class/member_adminfun.php');
	$cardfen=$_POST['cardfen'];
	GetFen_all($cardfen,$logininid,$loginin);
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量赠送点数</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
</head>

<body style="min-height:100%; height:100%;">
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="GetFen.php<?=$ecms_hashur['whehref']?>">批量赠送点数</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>给会员批量增加点数</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="GetFen.php">
 <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="GetFen">
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr bgcolor="#FFFFFF"> 
      <td>请输入点数： 
          <input name="cardfen" type="text" id="cardfen" value="0" size="6">
          点 
          <input type="submit" name="Submit" value="批量增加"></td>
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
