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
CheckLevel($logininid,$loginin,$classid,"totaldata");
$userid=(int)$_GET['userid'];
$username=RepPostVar($_GET['username']);
$tbname=RepPostVar($_GET['tbname']);
if(empty($tbname))
{
	$tbname=$public_r['tbname'];
}
if(!$userid||!$username||!$tbname)
{
	printerror('ErrorUrl','');
}
//数据表
$b=0;
$htb=0;
$tbsql=$empire->query("select tbname,tname from {$dbtbpre}enewstable order by tid");
while($tbr=$empire->fetch($tbsql))
{
	$b=1;
	$select="";
	if($tbr[tbname]==$tbname)
	{
		$htb=1;
		$select=" selected";
	}
	$tbstr.="<option value='".$tbr[tbname]."'".$select.">".$tbr[tname]."</option>";
}
if($b==0)
{
	printerror('ErrorUrl','');
}
if($htb==0)
{
	printerror('ErrorUrl','');
}
//日期
$year=date("Y");
$yyear=$year-1;
$date=RepPostVar($_GET['date']);
if(empty($date))
{
	$date=date("Y-m");
}
$selectdate='';
$yselectdate='';
for($i=1;$i<=12;$i++)
{
	$m=$i;
	if($i<10)
	{
		$m='0'.$i;
	}
	//今年
	$sdate=$year.'-'.$m;
	$select='';
	if($sdate==$date)
	{
		$select=' selected';
	}
	$selectdate.="<option value='".$sdate."'".$select.">".$sdate."</option>";
	//去年
	$ysdate=$yyear.'-'.$m;
	$yselect='';
	if($ysdate==$date)
	{
		$yselect=' selected';
	}
	$yselectdate.="<option value='".$ysdate."'".$yselect.">".$ysdate."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title><?=$username?> 的发布统计</title>
</head>

<body>

<div class="container" style="overflow-x:hidden;">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="MoreUserTotal.php?tbname=<?=$tbname?>&userid=<?=$userid?>&username=<?=$username?><?=$ecms_hashur['ehref']?>"><?=$username?> 的发布统计</a></div></div>
<div class="kongbai"></div>
<form action="../ecmsfile.php" method="post" enctype="multipart/form-data" name="form1">
  <?=$ecms_hashur['form']?>
    <div id="tab" style="padding-bottom:0px;_margin-bottom:50px;overflow:hidden;">
	<div class="ui-tab-container">
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
            <div class="newscon anniuqun">
<div class="ui-tab-content">
        	<h3><span><?=$username?> 的发布统计</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label></label><div align="center">请选择：<select name="date" id="date">
		<?=$yselectdate.$selectdate?>
        </select>
        <select name="tbname" id="tbname">
          <?=$tbstr?>
        </select>
        <input type="submit" name="Submit" value="显示"></div></li>
        </ul>
        <table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
	<th>日期</th>
    <th>发布数</th>
    <th>未审核数</th>
		</tr>
<?php
  $dr=explode('-',$date);
  $dr[0]=(int)$dr[0];
  $dr[1]=(int)$dr[1];
  for($j=1;$j<=31;$j++)
  {
  	//检测日期合法性
	if(!checkdate($dr[1],$j,$dr[0]))
	{
		continue;
	}
 	$d=$j;
	if($j<10)
	{
		$d='0'.$j;
	}
  	$thisday=$date.'-'.$d;
	//发布数
	$totalnum=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$tbname." where userid='$userid' and ismember=0 and truetime>=".to_time($thisday." 00:00:00")." and truetime<=".to_time($thisday." 23:59:59"));
	//未审核数
	$totalchecknum=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$tbname."_check where userid='$userid' and ismember=0 and truetime>=".to_time($thisday." 00:00:00")." and truetime<=".to_time($thisday." 23:59:59"));
  ?>
  <tr> 
    <td><div align="center">
        <?=$thisday?>
      </div></td>
    <td><div align="center">
        <?=($totalnum+$totalchecknum)?>
      </div></td>
    <td><div align="center"><?=$totalchecknum?></div></td>
  </tr>
  <?php
  }
  ?>

  </tbody>
</table>
            </div>
        </div>
        	</div>
        </div>
 <div class="line"></div>
  </div>
 </div>
</div>
 </form>
 <div class="clear"></div>
</div>
</body>
</html>
