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
//验证权限
CheckLevel($logininid,$loginin,$classid,"pay");

//设置接口
function EditPayApi($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[payid]=(int)$add[payid];
	if(empty($add[payname])||!$add[payid])
	{
		printerror("EmptyPayApi","history.go(-1)");
    }
	$add[isclose]=(int)$add[isclose];
	$add[myorder]=(int)$add[myorder];
	$add[paymethod]=(int)$add[paymethod];
	$sql=$empire->query("update {$dbtbpre}enewspayapi set isclose='$add[isclose]',payname='$add[payname]',paysay='$add[paysay]',payuser='$add[payuser]',paykey='$add[paykey]',payfee='$add[payfee]',payemail='$add[payemail]',myorder='$add[myorder]',paymethod='$add[paymethod]' where payid='$add[payid]'");
	if($sql)
	{
		//操作日志
		insert_dolog("payid=".$add[payid]."<br>payname=".$add[payname]);
		printerror("EditPayApiSuccess","PayApi.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//支付参数设置
function SetPayFen($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[paymoneytofen]=(int)$add[paymoneytofen];
	$add[payminmoney]=(int)$add[payminmoney];
	if(empty($add[paymoneytofen]))
	{
		printerror("EmptySetPayFen","history.go(-1)");
    }
	$sql=$empire->query("update {$dbtbpre}enewspublic set paymoneytofen='$add[paymoneytofen]',payminmoney='$add[payminmoney]'");
	if($sql)
	{
		//操作日志
		insert_dolog("moneytofen=$add[paymoneytofen]&minmoney=$add[payminmoney]");
		printerror("SetPayFenSuccess","SetPayFen.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
//增加用户
if($enews=="EditPayApi")
{
	EditPayApi($_POST,$logininid,$loginin);
}
elseif($enews=="SetPayFen")
{
	SetPayFen($_POST,$logininid,$loginin);
}

$sql=$empire->query("select payid,paytype,payfee,paylogo,paysay,payname,isclose from {$dbtbpre}enewspayapi order by myorder,payid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>支付接口</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<style>
.comm-table td{ padding:4px 10px; line-height:16px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > 在线支付&gt; <a href="PayApi.php">管理支付接口</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理支付接口 <a href="ListPayRecord.php" class="gl">管理支付记录</a> <a href="SetPayFen.php" class="gl">支付参数设置</a></span></h3>
        <div class="line"></div>

<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>接口名称</th>
			<th>接口描述</th>
			<th>状态</th>
			<th>接口类型</th>
            <th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
	  if($r[paytype]=='alipay')
	  {
		  $r[payname]="<font color='red'><b>".$r[payname]."</b></font>";
	  }
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="38" align="center" nowrap="nowrap"> 
      <?=$r[payname]?>
    </td>
    <td>
      <?=$r[paysay]?>
    </td>
    <td nowrap="nowrap"><div align="center">
        <?=$r[isclose]==0?'开启':'关闭'?>
      </div></td>
    <td height="25" nowrap="nowrap"> <div align="center">
        <?=$r[paytype]?>
      </div></td>
    <td height="25" nowrap="nowrap"> <div align="center"><a href="SetPayApi.php?enews=EditPayApi&payid=<?=$r[payid]?>">配置接口</a></div></td>
  </tr>
  <?
  }
  db_close();
  $empire=null;
  ?>
	</tbody>
</table>
      </div>
    </div>
</div>
</div>
</body>
</html>
