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
CheckLevel($logininid,$loginin,$classid,"table");
$tid=(int)$_GET['tid'];
$tbname=RepPostVar($_GET['tbname']);
if(!$tid||!$tbname)
{
	printerror("ErrorUrl","history.go(-1)");
}
$r=$empire->fetch1("select tid,datatbs,deftb from {$dbtbpre}enewstable where tid='$tid'");
if(!$r[tid])
{
	printerror("ErrorUrl","history.go(-1)");
}
$tr=explode(',',$r[datatbs]);
$url="数据表:[".$dbtbpre."ecms_".$tbname."]&nbsp;>&nbsp;<a href=ListDataTable.php?tid=$tid&tbname=$tbname".$ecms_hashur['ehref'].">管理副表分表</a>";
$datatbname=$dbtbpre.'ecms_'.$tbname.'_data_';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理副表分表</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table2 td{ padding:8px 0; height:16px; background:none;}
.comm-table2 td table{ border-top:1px solid #CDCDCD; border-right:1px solid #CDCDCD;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>增加副表分表</span></h3>
			<div class="line"></div>
<div class="jqui anniuqun">
<form name="adddatatableform" method="post" action="../ecmsmod.php" onsubmit="return confirm('确认要增加?');">
<table class="comm-table" cellspacing="0">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="AddDataTable">
        <input name="tid" type="hidden" id="tid" value="<?=$tid?>"> <input name="tbname" type="hidden" id="tbname" value="<?=$tbname?>">
	<tbody>
       <tr> 
        <td><?=$datatbname?>
        <input name="datatb" type="text" id="datatb" value="0" size="6">
        <input type="submit" name="Submit" value="增加">
        <font color="#666666">(表名要用数字)</font></td>
    </tr>
	</tbody>
  </table>
</form>
<table class="comm-table" cellspacing="0">
<tbody>
<tr>
    <th>表名</th>
    <th>记录数</th>
    <th>操作</th>
		</tr>
    <?php
  $count=count($tr)-1;
  $maxtb=0;
  for($i=1;$i<$count;$i++)
  {
  	$total_r=$empire->fetch1("SHOW TABLE STATUS LIKE '".$datatbname.$tr[$i]."';");
	$bgcolor="#ffffff";
	$movejs=' onmouseout="this.style.backgroundColor=\'#ffffff\'" onmouseover="this.style.backgroundColor=\'#C3EFFF\'"';
	if($tr[$i]==$r['deftb'])
	{
		$bgcolor="#DBEAF5";
		$movejs='';
	}
	if($tr[$i]>$maxtb)
	{
		$maxtb=$tr[$i];
	}
	$dostr=$tr[$i]==1?"":"&nbsp;&nbsp;&nbsp;[<a href=\"../ecmsmod.php?tid=$tid&tbname=$tbname&enews=DelDataTable&datatb=".$tr[$i].$ecms_hashur['href']."\" onclick=\"return confirm('确认要删除，删除会删除表里的所有信息?');\">删除</a>]";
  ?>
  <tr bgcolor="<?=$bgcolor?>"<?=$movejs?>> 
    <td height="25"> 
      <?=$datatbname?><b><?=$tr[$i]?></b>
    </td>
    <td height="25"><div align="center"> 
        <?=$total_r['Rows']?>
      </div></td>
    <td height="25"><div align="center">[<a href="../ecmsmod.php?tid=<?=$tid?>&tbname=<?=$tbname?>&enews=DefDataTable&datatb=<?=$tr[$i]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要将这个表设为当前存放表?');">设为当前存放表</a>]<?=$dostr?></div></td>
  </tr>
  <?php
	}
	?>
	</tbody>
</table>
        </form>
</div>
<div class="line"></div>
        </div>
    </div>
</div>
</div>
<script>
document.adddatatableform.datatb.value="<?=$maxtb+1?>";
</script>
</body>
</html>
<?php
db_close();
$empire=null;
?>
