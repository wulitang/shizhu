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
CheckLevel($logininid,$loginin,$classid,"template");
$url="<a href=ChangeListTemp.php".$ecms_hashur['whehref'].">批量更换栏目列表模板</a>";
//栏目
$fcfile="../../data/fc/ListEnews.php";
$class="<script src=../../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",0,0,"|-",0,0);}
//列表模板
$listtemp="";
$sql=$empire->query("select mname,mid from {$dbtbpre}enewsmod order by myorder,mid");
while($r=$empire->fetch($sql))
{
	$listtemp.="<option value=0 style='background:#99C4E3'>".$r[mname]."</option>";
	$sql1=$empire->query("select tempname,tempid from ".GetTemptb("enewslisttemp")." where modid='$r[mid]'");
	while($r1=$empire->fetch($sql1))
	{
		$listtemp.="<option value='".$r1[tempid]."'>|-".$r1[tempname]."</option>";
	}
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量更换栏目列表模板</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<style>
.comm-table td{ height:16px; padding:8px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="jqui">
<form name="form1" method="post" action="../ecmstemp.php" onSubmit="return confirm('确认要更换？');">
<?=$ecms_hashur['form']?>
 <input name="enews" type="hidden" id="enews" value="ChangeClassListtemp"> 
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
		<th style="width:180px;"><h3><span style="padding-left: 5px;">批量更换栏目列表模板</span></h3></th>
			<th></th>
		</tr>
  <tr bgcolor="#FFFFFF"> 
      <td width="15%" height="25">操作栏目：</td>
      <td width="85%" height="25" style="text-align:left;"><select name="classid" size="16" id="classid" style="width:220px">
          <option selected>所有栏目</option>
          <?=$class?>
        </select> <font color="#666666">（如选择父栏目，将应用于所有子栏目）</font> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">新的列表模板：</td>
      <td height="25" style="text-align:left;"><select name="listtempid" id="listtempid">
          <option value=0>选择列表模板</option>
		  <?=$listtemp?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25" style="text-align:left;"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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
