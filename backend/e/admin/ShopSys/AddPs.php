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
CheckLevel($logininid,$loginin,$classid,"shopps");
$enews=ehtmlspecialchars($_GET['enews']);
$url="<a href=ListPs.php".$ecms_hashur['whehref'].">管理配送方式</a>&nbsp;>&nbsp;增加配送方式";
if($enews=="EditPs")
{
	$pid=(int)$_GET['pid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsshopps where pid='$pid'");
	$url="<a href=ListPs.php".$ecms_hashur['whehref'].">管理配送方式</a>&nbsp;>&nbsp;修改配送方式：<b>".$r[pname]."</b>";
}
//--------------------html编辑器
include('../ecmseditor/infoeditor/fckeditor.php');
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td{ padding:6px 4px; height:16px;}
.comm-table td table{ border-top:1px solid #EFEFEF; border-right:1px solid #EFEFEF;}
</style>
<title>配送方式</title>
<script>
function on()
{
var f=document.add
f.HTML.value=f.psay.value;
}
function bs(){
var f=document.add
f.psay.value=f.HTML.value;
}
function br(){
if(!confirm("是否复位？")){return false;}
document.add.title.select()
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>增加配送方式</span></h3>
<div class="jqui anniuqun">
<form name="add" method="post" action="ListPs.php">
 <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> 
<input name="pid" type="hidden" id="pid" value="<?=$pid?>">
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr bgcolor="#FFFFFF"> 
      <td height="25" style="width:100px;">方式名称:</td>
      <td height="25" style="text-align:left;"><input name="pname" type="text" id="pname" value="<?=$r[pname]?>">
        (如:普通邮递) </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">运费金额:</td>
      <td height="25" style="text-align:left;"><input name="price" type="text" id="price" value="<?=$r[price]?>" size="8">
        元</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" valign="top">详细说明:</td>
      <td height="25" style="text-align:left;">
		<?=ECMS_ShowEditorVar('psay',$r[psay],'Default','../ecmseditor/infoeditor/')?>      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">是否启用</td>
      <td height="25" style="text-align:left;"><input type="radio" name="isclose" value="0"<?=$r[isclose]==0?' checked':''?>>
开启
  <input type="radio" name="isclose" value="1"<?=$r[isclose]==1?' checked':''?>>
关闭</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25" style="text-align:left;"> <input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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
