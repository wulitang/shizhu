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
CheckLevel($logininid,$loginin,$classid,"sp");
$enews=ehtmlspecialchars($_GET['enews']);
$postword='增加碎片分类';
$url="<a href=ListSp.php".$ecms_hashur['whehref'].">管理碎片</a>&nbsp;>&nbsp;<a href=ListSpClass.php".$ecms_hashur['whehref'].">管理碎片分类</a>&nbsp;>&nbsp;增加碎片分类";
//修改
if($enews=="EditSpClass")
{
	$postword='修改碎片分类';
	$classid=(int)$_GET['classid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsspclass where classid='$classid'");
	$url="<a href=ListSp.php".$ecms_hashur['whehref'].">管理碎片</a>&nbsp;>&nbsp;<a href=ListSpClass.php".$ecms_hashur['whehref'].">管理碎片分类</a>&nbsp;>&nbsp;修改碎片分类：".$r[classname];
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加碎片分类</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="jqui">
<style>
.comm-table td{ text-align:left;}
.comm-table2 td{ padding:5px;}
</style>
<form name="form1" method="post" action="ListSpClass.php">
 <?=$ecms_hashur['form']?>
<input type=hidden name=enews value=<?=$enews?>>
<div class="line"></div>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span>增加碎片分类</span></h3></th>
			<th></th>
		</tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">分类名称(*)</td>
      <td height="25" bgcolor="#FFFFFF"> <input name="classname" type="text" id="classname" size="42" value="<?=$r[classname]?>"> 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="classid" type="hidden" id="classid" value="<?=$r[classid]?>"> </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">分类说明</td>
      <td bgcolor="#FFFFFF"><textarea name="classsay" cols="60" rows="5" id="classsay"><?=ehtmlspecialchars($r[classsay])?></textarea></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="center"></div></td>
      <td bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="提交"> &nbsp;&nbsp; 
        <input type="reset" name="Submit2" value="重置"> </td>
    </tr>
  </tbody>
</table>
<div class="line"></div>
</form>
</div>
        </div>
    </div>
</div>
</div>
</body>
</html>