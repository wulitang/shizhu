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
//验证权限
CheckLevel($logininid,$loginin,$classid,"pay");
$r=$empire->fetch1("select paymoneytofen,payminmoney from {$dbtbpre}enewspublic limit 1");
$url="在线支付&gt; <a href=PayApi.php>管理支付接口</a>&nbsp;>&nbsp;支付参数配置";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>支付参数配置</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td{ padding:4px 4px; height:16px;}
.comm-table td table{ border-top:1px solid #EFEFEF; border-right:1px solid #EFEFEF;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>支付参数配置 <a href="ListPayRecord.php" class="gl">管理支付记录</a> <a href="PayApi.php" class="gl">管理支付接口</a></span></h3>
<div class="jqui anniuqun">
<form name="setpayform" method="post" action="PayApi.php" enctype="multipart/form-data">
<input name="enews" type="hidden" id="enews" value="SetPayFen"> 
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr> 
      <td>一元可购买：</td>
      <td style="text-align:left;"><input name="paymoneytofen" type="text" id="paymoneytofen" value="<?=$r[paymoneytofen]?>" size="35">
        点数</td>
    </tr>
    <tr> 
      <td>最小支付金额：</td>
      <td style="text-align:left;"><input name="payminmoney" type="text" id="payminmoney" value="<?=$r[payminmoney]?>" size="35">
        元</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td style="text-align:left;"><input type="submit" name="Submit" value=" 设 置 "> &nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="重置"></td>
    </tr>
	</tbody>
</table>
        </form>
</div>
        </div>
    </div>
</div>
</div>
</body>
</html>
