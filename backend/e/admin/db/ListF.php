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
CheckLevel($logininid,$loginin,$classid,"f");
$tid=(int)$_GET['tid'];
$tbname=RepPostVar($_GET['tbname']);
if(!$tid||!$tbname)
{
	printerror("ErrorUrl","history.go(-1)");
}
$url="数据表:[".$dbtbpre."ecms_".$tbname."]&nbsp;>&nbsp;<a href=ListF.php?tid=$tid&tbname=$tbname".$ecms_hashur['ehref'].">字段管理</a>";
$sql=$empire->query("select * from {$dbtbpre}enewsf where tid='$tid' order by myorder,fid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理字段</title>
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
//关闭浮动层
function exit() {
	var origin = artDialog.open.origin;
	art.dialog.close();
};

</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理字段 <a href="AddF.php?enews=AddF&tid=<?=$tid?>&tbname=<?=$tbname?><?=$ecms_hashur['ehref']?>" class="add">增加字段</a></span></h3>
            <div class="line"></div>
<form name="form1" method="post" action="../ecmsmod.php">
 <?=$ecms_hashur['form']?>
<table class="comm-table f12" cellspacing="0">
	<tbody>
		<tr>
			<th>顺序</th>
			<th>表</th>
			<th>字段名</th>
			<th>字段标识</th>
            <th>字段类型</th>
            <th>采集项</th>
            <th>操作</th>
		</tr>
    <?php
  	while($r=$empire->fetch($sql))
  	{
  		$ftype=$r[ftype];
  		if($r[flen])
  		{
			if($r[ftype]!="TEXT"&&$r[ftype]!="MEDIUMTEXT"&&$r[ftype]!="LONGTEXT"&&$r[ftype]!="DATE"&&$r[ftype]!="DATETIME")
			{
				$ftype.="(".$r[flen].")";
			}
  		}
  		if($r[iscj])
  		{$iscj="是";}
  		else
  		{$iscj="否";}
  		if($r[isadd])
  		{
  			$do="[<a href='AddF.php?tid=$tid&tbname=$tbname&enews=EditF&fid=".$r[fid].$ecms_hashur['ehref']."'>修改</a>]&nbsp;&nbsp;[<a href='../ecmsmod.php?tid=$tid&tbname=$tbname&enews=DelF&fid=".$r[fid].$ecms_hashur['href']."' onclick=\"return confirm('确认要删除？');\">删除</a>]";
 		 }
  		else
  		{
  			$ftype="系统字段";
  			$r[f]="<a title='系统字段'><font color=red>".$r[f]."</font></a>";
  			$do="<a href='EditSysF.php?tid=$tid&tbname=$tbname&fid=".$r[fid].$ecms_hashur['ehref']."'><font color=red>[修改系统字段]</font></a>";
  		}
  		if($r[tbdataf]==1)
  		{
  			$tbdataf=$r[isadd]?"<a href='ChangeDataTableF.php?tid=$tid&tbname=$tbname&fid=".$r[fid].$ecms_hashur['ehref']."' title='点击将字段转移到主表'>副表</a>":"副表";
  		}
  		else
  		{
			$tbdataf=$r[isadd]?"<a href='ChangeDataTableF.php?tid=$tid&tbname=$tbname&fid=".$r[fid].$ecms_hashur['ehref']."' title='点击将字段转移到副表'>主表</a>":"主表";
  		}
  ?>
		<tr bgcolor="#ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
			<td> <input name="myorder[]" type="text" id="myorder[]" value="<?=$r[myorder]?>" size="3">
          <input type=hidden name=fid[] value=<?=$r[fid]?>></td>
            <td><?=$tbdataf?></td>
            <td><?=$r[f]?></td>
            <td><?=$r[fname]?></td>
            <td><?=$ftype?></td>
            <td><?=$iscj?></td>
            <td><?=$do?></td>
		</tr>
  <?php
  }
  ?>
  <tr bgcolor="#ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
		  <td></td><td colspan="6" class="jqui" style="text-align:left;"><input type="submit" name="Submit" value="修改字段顺序">
        <input name="enews" type="hidden" id="enews" value="EditFOrder"> <input name="tid" type="hidden" id="tid" value="<?=$tid?>"> 
        <input name="tbname" type="hidden" id="tbname" value="<?=$tbname?>"></td>
		  </tr>
   <tr bgcolor="#ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'">
		  <td></td><td colspan="6"><font color="#666666">说明：顺序值越小越显示前面，红色字段名为系统字段，点击"主表"/"副表"可以进行字段转移.</font></td>
		  </tr>
	</tbody>
</table>
</form>
        </div>
	<div class="line"></div>
    </div>
</div>
</div>
</body>
</html>
<?
db_close();
$empire=null;
?>
