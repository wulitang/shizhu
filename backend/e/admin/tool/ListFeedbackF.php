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
CheckLevel($logininid,$loginin,$classid,"feedbackf");
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include("../../class/com_functions.php");
}
if($enews=="AddFeedbackF")
{
	AddFeedbackF($_POST,$logininid,$loginin);
}
elseif($enews=="EditFeedbackF")
{
	EditFeedbackF($_POST,$logininid,$loginin);
}
elseif($enews=="DelFeedbackF")
{
	DelFeedbackF($_GET,$logininid,$loginin);
}
elseif($enews=="EditFeedbackFOrder")
{
	EditFeedbackFOrder($_POST['fid'],$_POST['myorder'],$logininid,$loginin);
}
$url="<a href=feedback.php".$ecms_hashur['whehref'].">管理信息反馈</a>&nbsp;>&nbsp;<a href=FeedbackClass.php".$ecms_hashur['whehref'].">信息反馈分类管理</a>&nbsp;>&nbsp;<a href=ListFeedbackF.php".$ecms_hashur['whehref'].">管理反馈字段</a>";
$sql=$empire->query("select * from {$dbtbpre}enewsfeedbackf order by myorder,fid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理字段</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<style>
.comm-table td{ padding:8px 0; height:16px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理反馈字段 <a href="AddFeedbackF.php?enews=AddFeedbackF<?=$ecms_hashur['ehref']?>" class="add">新建字段</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">
<form name="form1" method="post" action="ListFeedbackF.php" onSubmit="return confirm('确认要操作?');">
 <?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:100px;">顺序</th>
			<th>字段名</th>
            <th>字段标识</th>
            <th>字段类型</th>
            <th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  	$ftype=$r[ftype];
  	if($r[flen])
	{
		if($r[ftype]!="TEXT"&&$r[ftype]!="MEDIUMTEXT"&&$r[ftype]!="LONGTEXT")
		{
			$ftype.="(".$r[flen].")";
		}
	}
  ?>
    <tr> 
      <td height="25"><div align="center"> 
          <input name="myorder[]" type="text" id="myorder[]" value="<?=$r[myorder]?>" size="3">
          <input type=hidden name=fid[] value=<?=$r[fid]?>>
        </div></td>
      <td height="25"><div align="center"> 
          <?=$r[f]?>
        </div></td>
      <td><div align="center"> 
          <?=$r[fname]?>
        </div></td>
      <td><div align="center">
	  	  <?=$ftype?>
	  </div></td>
      <td height="25"><div align="center"> 
         [<a href='AddFeedbackF.php?enews=EditFeedbackF&fid=<?=$r[fid]?><?=$ecms_hashur['ehref']?>'>修改</a>]&nbsp;&nbsp;[<a href='ListFeedbackF.php?enews=DelFeedbackF&fid=<?=$r[fid]?><?=$ecms_hashur['href']?>' onclick="return confirm('确认要删除?');">删除</a>]
        </div></td>
    </tr>
    <?
	}
	?>
  		<tr>
          <td></td>
  		  <td colspan="4" style="text-align:left;">&nbsp;&nbsp;
<input type="submit" name="Submit" value="修改字段顺序">
        (值越小越前面) <input name="enews" type="hidden" id="enews" value="EditFeedbackFOrder"> </td>
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
<?
db_close();
$empire=null;
?>
