<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"totaldata");
$totaltype=(int)$_POST['totaltype'];
$classid=(int)$_POST['classid'];
$tbname=RepPostVar($_POST['tbname']);
$startday=RepPostVar($_POST['startday']);
$endday=RepPostVar($_POST['endday']);
$userid=(int)$_POST['userid'];
$onclick=0;
$allnum=0;
$nochecknum=0;
$checknum=0;
$bfb=0;
$and=' where ';
//按类别统计
if($totaltype==0)
{
	$tbname='';
	if($classid&&!$class_r[$classid][tbname])
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	//未审核
	$query="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]."_check";
	//已审核
	$query1="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname];
	//点击
	$onclickquery="select avg(onclick) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname];
	if($classid)
	{
		//中级类别
		if(empty($class_r[$classid][islast]))
		{
			$where=ReturnClass($class_r[$classid][sonclass]);
		}
		//终极类别
		else
		{
			$where="classid='$classid'";
		}
		$query.=" where ".$where;
		$query1.=" where ".$where;
		$onclickquery.=" where ".$where;
		$and=' and ';
	}
}
//按表统计
elseif($totaltype==1)
{
	$classid=0;
	if(!$tbname)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	//未审核
	$query="select count(*) as total from {$dbtbpre}ecms_".$tbname."_check";
	//已审核
	$query1="select count(*) as total from {$dbtbpre}ecms_".$tbname;
	//点击
	$onclickquery="select avg(onclick) as total from {$dbtbpre}ecms_".$tbname;
}
else
{
	printerror("ErrorUrl","history.go(-1)");
}
//时间
if($startday&&$endday)
{
	$start=$startday." 00:00:00";
	$end=$endday." 23:59:59";
	$timeadd=$and."(newstime>='".to_time($start)."' and newstime<='".to_time($end)."')";
	$query.=$timeadd;
	$query1.=$timeadd;
	$onclickquery.=$timeadd;
	$and=' and ';
}
//用户
if($userid)
{
	$useradd=$and."userid='$userid'";
	$query.=$useradd;
	$query1.=$useradd;
	$onclickquery.=$useradd;
	$and=' and ';
}
//数据表
$htb=0;
$tbsql=$empire->query("select tbname,tname from {$dbtbpre}enewstable order by tid");
while($tbr=$empire->fetch($tbsql))
{
	$select="";
	if($tbr[tbname]==$tbname)
	{
		$htb=1;
		$select=" selected";
	}
	$tbstr.="<option value='".$tbr[tbname]."'".$select.">".$tbr[tname]."</option>";
}
if($totaltype==1&&$htb==0)
{
	printerror('ErrorUrl','');
}
if($classid||$tbname)
{
	//审核
	$checknum=$empire->gettotal($query1);
	//未审核
	$nochecknum=$empire->gettotal($query);
	//总信息数
	$allnum=$checknum+$nochecknum;
	//点击率
	$onclick=$empire->gettotal($onclickquery);
}
//栏目
$fcfile="../data/fc/ListEnews.php";
$class="<script src=../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",$classid,0,"|-",0,0);}
//用户
$usersql=$empire->query("select userid,username from {$dbtbpre}enewsuser order by userid");
while($userr=$empire->fetch($usersql))
{
	if($userr[userid]==$userid)
	{$select=" selected";}
	else
	{$select="";}
	$user.="<option value='".$userr[userid]."'".$select.">".$userr[username]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>统计数据</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script src="ecmseditor/fieldfile/setday.js"></script>
<style>
.comm-table td { text-align:left;}
.comm-table td p{ padding:5px;}
.comm-table td table{ border-right:1px solid #EFEFEF; border-top:1px solid #EFEFEF;}
</style>

</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php<?=$ecms_hashur[whehref]?>">后台首页</a> > <a href="TotalData.php<?=$ecms_hashur[whehref]?>">统计数据</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>统计数据</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form name="form1" method="post" action="TotalData.php">
  <?=$ecms_hashur['eform']?>
<input name="enews" type="hidden" id="enews" value="TotalData">
		<tr>
			<th style="width:200px;">设置TAGS</th>
			<th></th>
		</tr>
        <tr>
        	<td><input name="totaltype" type="radio" value="0"<?=$totaltype==0?' checked':''?>>
        按栏目统计</td>
            <td style="text-align:left;"><select name="classid" id="classid">
          <?=$class?>
        </select>
        （如选择父栏目，将统计于所有子栏目）</td>
        </tr>
        <tr>
        	<td><input name="totaltype" type="radio" value="1"<?=$totaltype==1?' checked':''?>>
        按数据表统计</td>
            <td style="text-align:left;"><select name="tbname" id="tbname">
          <?=$tbstr?>
        </select></td>
        </tr>
        <tr>
        	<td>录入者：</td>
            <td style="text-align:left;"><select name="userid" id="userid">
          <option value="0">所有录入者</option>
          <?=$user?>
        </select></td>
        </tr>
        <tr>
        	<td>时间范围：</td>
            <td style="text-align:left;">从 
        <input name="startday" type="text" value="<?=$startday?>" size="12" onClick="setday(this)">
        到 
        <input name="endday" type="text" value="<?=$endday?>" size="12" onClick="setday(this)">
        之间的数据(两边为空则为不限制日期)</td>
        </tr>
        <tr>
        	<td></td>
            <td style="text-align:left;"><input type="submit" name="Submit" value="开始统计"> <input type="reset" name="Submit2" value="重置"></td>
        </tr>
        <tr>
        	<td colspan="2">
            <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="4"> <div align="center">统计时间： 
        <?=date("Y-m-d H:i:s")?>
      </div></td>
  </tr>
  <tr> 
    <td width="23%" height="25"><div align="center">总信息数</div></td>
    <td width="23%" height="25"> <p align="center">未审核数</p></td>
    <td width="23%" height="25"> <div align="center">已审核数</div></td>
    <td width="15%"><div align="center">平均点击数</div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25"><div align="center"><font color=red> 
        <?=$allnum?>
        </font></div></td>
    <td height="25"><div align="center"> 
        <?=$nochecknum?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$checknum?>
      </div></td>
    <td><div align="center">
        <?=$onclick?>
      </div></td>
  </tr>
</table>
            </td>
        </tr>
        </form>
	</tbody>
</table>
</div>
<div class="line"></div>
      </div>
    </div>
</div>
</div>
</body>
</html>
