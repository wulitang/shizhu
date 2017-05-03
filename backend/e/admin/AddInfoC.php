<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
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
CheckLevel($logininid,$loginin,$classid,"cj");
//--------------------操作的栏目
$fcfile="../data/fc/ListEnews.php";
$do_class="<script src=../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$do_class=ShowClass_AddClass("","n",0,"|-",0,0);}
db_close();
$empire=null;
if($_GET['from'])
{
	$listclasslink="ListPageInfoClass.php";
}
else
{
	$listclasslink="ListInfoClass.php";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加采集节点</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function changecj(obj)
{
	if(obj.newsclassid.value=="nono")
	{
		alert("请选择栏目");
	}
	else
	{
		self.location.href='AddInfoClass.php?<?=$ecms_hashur['ehref']?>&enews=AddInfoClass&from=<?=RepPostStr($_GET['from'],1)?>&newsclassid='+obj.newsclassid.value;
	}
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php<?=$ecms_hashur[whehref]?>">后台首页</a> > 采集&nbsp;&gt;&nbsp;<a href='<?=$listclasslink?><?=$ecms_hashur[whehref]?>'>管理节点</a>&nbsp;&gt;&nbsp;增加节点</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>增加采集节点</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form name="form1" method="post" action="enews.php">
  <?=$ecms_hashur['eform']?>
		<tr>
			<th style="width:200px;">请选择要增加采集的栏目</th>
			<th></th>
		</tr>
        <tr>
        	<td>选择终极栏目</td>
            <td style="text-align:left;"><select name="newsclassid" id="newsclassid" onchange='javascript:changecj(document.form1);'>
            <option value=''>选择栏目</option>
            <option value='0'>非采集节点(父节点)</option>
            <?=$do_class?>
          </select></td>
        </tr>
        <tr>
        	<td></td>
            <td style="text-align:left;">(采集节点要选择终极栏目)</td>
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
