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
CheckLevel($logininid,$loginin,$classid,"m");
$enews=$_GET['enews'];
if($enews)
{
	hCheckEcmsRHash();
}
//导出模型
if($enews=="LoadOutMod")
{
	include("../../class/moddofun.php");
	LoadOutMod($_GET,$logininid,$loginin);
}
$tid=(int)$_GET['tid'];
$tbname=RepPostVar($_GET['tbname']);
if(!$tid||!$tbname)
{
	printerror("ErrorUrl","history.go(-1)");
}
$url="数据表:[".$dbtbpre."ecms_".$tbname."]&nbsp;>&nbsp;<a href=ListM.php?tid=$tid&tbname=$tbname".$ecms_hashur['ehref'].">管理系统模型</a>";
$sql=$empire->query("select * from {$dbtbpre}enewsmod where tid='$tid' order by myorder,mid");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理系统模型</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/js/yecha.js"></script>
<script type="text/javascript">
$(function(){
			
		});
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理系统模型 <a href="AddM.php?enews=AddM&tid=<?=$tid?>&tbname=<?=$tbname?><?=$ecms_hashur['ehref']?>" class="add">增加系统模型</a> <a href="LoadInM.php<?=$ecms_hashur['whehref']?>" class="dr">导入系统模型</a></span></h3>
            <div class="line"></div>
<table class="comm-table f12" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>模型名称</th>
			<th>启用</th>
			<th>操作</th>
		</tr>
<?php
  while($r=$empire->fetch($sql))
  {
  	//默认
	$defbgcolor='#ffffff';
	$movejs=' onmouseout="this.style.backgroundColor=\'#ffffff\'" onmouseover="this.style.backgroundColor=\'#C3EFFF\'"';
	if($r[isdefault])
	{
		$defbgcolor='#DBEAF5';
		$movejs='';
	}
	$do="[<a href='../../DoInfo/ChangeClass.php?mid=".$r[mid]."' target=_blank>投稿地址</a>]&nbsp;&nbsp;[<a href='AddM.php?tid=$tid&tbname=$tbname&enews=AddM&mid=".$r[mid].$ecms_hashur['ehref']."&docopy=1'>复制</a>]&nbsp;&nbsp;[<a href='ListM.php?tid=$tid&tbname=$tbname&enews=LoadOutMod&mid=".$r[mid].$ecms_hashur['href']."' onclick=\"return confirm('确认要导出?');\">导出</a>]&nbsp;&nbsp;[<a href='../ecmsmod.php?tid=$tid&tbname=$tbname&enews=DefM&mid=".$r[mid].$ecms_hashur['href']."' onclick=\"return confirm('确认要设置为默认系统模型?');\">设为默认</a>]&nbsp;&nbsp;[<a href='AddM.php?tid=$tid&tbname=$tbname&enews=EditM&mid=".$r[mid].$ecms_hashur['ehref']."'>修改</a>]&nbsp;&nbsp;[<a href='../ecmsmod.php?tid=$tid&tbname=$tbname&enews=DelM&mid=".$r[mid].$ecms_hashur['href']."' onclick=\"return confirm('确认要删除？');\">删除</a>]";
	$usemod=$r[usemod]==0?'是':'<font color="red">否</font>';
	?>
		 <tr bgcolor="<?=$defbgcolor?>"<?=$movejs?>> 
            <td><?=$r[mid]?></td>
            <td><?=$r[mname]?></td>
            <td><?=$usemod?></td>
            <td><?=$do?></td>
		</tr>
  <?php
  }
  ?>
	</tbody>
</table>
        </div>
	<div class="line"></div>
    </div>
</div>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>
