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
CheckLevel($logininid,$loginin,$classid,"bq");
$enews=$_POST['enews'];
$url="<a href=ListBq.php".$ecms_hashur['whehref'].">管理标签</a>&nbsp;>&nbsp;<a href=LoadInBq.php".$ecms_hashur['whehref'].">导入标签</a>";
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="LoadInBq")
{
	include('../../class/tempfun.php');
	$file=$_FILES['file']['tmp_name'];
	$file_name=$_FILES['file']['name'];
	$file_type=$_FILES['file']['type'];
	$file_size=$_FILES['file']['size'];
	$r=LoadInBq($_POST,$file,$file_name,$file_type,$file_size,$logininid,$loginin);
}
else
{
	//类别
	$cstr="";
	$csql=$empire->query("select classid,classname from {$dbtbpre}enewsbqclass order by classid");
	while($cr=$empire->fetch($csql))
	{
		$cstr.="<option value='".$cr[classid]."'>".$cr[classname]."</option>";
	}
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>导入标签</title>
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
<form action="../ecmstemp.php" method="post" name="add" id="add">
 <?=$ecms_hashur['form']?>
<input name="add[bqid]" type="hidden" id="add[bqid]" value="<?=$bqid?>"> 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="add[cid]" type="hidden" id="add[cid]" value="<?=$cid?>">
<div class="line"></div>
<?
if($enews=="LoadInBq")
{
?>
<form name="form2" method="post" action="">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span style="padding-left: 1px;">导入标签完毕</span></h3></th>
			<th></th>
		</tr>
    <tr> 
      <td style="text-align: right;">导入标签名称：</td>
      <td><? echo $r[0]."&nbsp;(".$r[3].")";?></td>
    </tr>
    <tr> 
      <td style="text-align: right;">标签函数内容：</td>
      <td><textarea name="funvalue" cols="86" rows="16" id="funvalue"><?=ehtmlspecialchars($r[5])?></textarea></td>
    </tr>
    <tr> 
      <td><div align="center">说明：导入标签后，请把函数内容复制到e/class/userfun.php文件</div></td>
    </tr>
  </tbody>
</table>
</form>
<?
}
else
{
?>
<form action="LoadInBq.php" method="post" enctype="multipart/form-data" name="form1" onsubmit="return confirm('确认要导入？');">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="LoadInBq">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span style="padding-left: 1px;">导入标签</span></h3></th>
			<th></th>
		</tr>
    <tr> 
      <td style="text-align: right;">标签所属分类：</td>
      <td><select name="classid" id="classid">
                <option value="0">不隶属于任何分类</option>
                <?=$cstr?>
              </select></td>
    </tr>
    <tr> 
      <td style="text-align: right;">导入标签文件：</td>
      <td><input type="file" name="file">
              (*.bq)</td>
    </tr>
    <tr>
	<td>&nbsp;</td>
      <td>
          <input type="submit" name="Submit" value="马上导入">
          &nbsp;&nbsp;
          <input type="reset" name="Submit2" value="重置">
        </td>
    </tr>
  </tbody>
</table>
</form>
<?
}
?>
<div class="line"></div>
</form>
</div>
        </div>
    </div>
</div>
</div>
</body>
</html>