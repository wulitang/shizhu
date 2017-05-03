<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../data/dbcache/class.php");
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

$classid=(int)$_GET['classid'];
$r['classid']=$classid;
$url=sys_ReturnBqClassname($r,9);
$jspath=$public_r['newsurl'].'d/js/class/class'.$classid.'_';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http//www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http//www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>调用地址</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td table td{ text-alignleft;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > 调用地址</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>调用地址</span></h3>
			<div class="line"></div>
<div class="jqui anniuqun">
<form name="chfile" method="post" action="../enews.php">
 <?=$ecms_hashur['form']?>
<input name=enews type=hidden value=SetPl>
<table class="comm-table" cellspacing="0">
	<tbody>
        <tr bgcolor="#FFFFFF"> 
	 <th>属性</th>
      <th>调用地址</th>
      <th>预览</th>
    </tr>
    <tr bgcolor="#FFFFFF"> 
    <td width="22%" height="25">栏目地址:</td>
    <td width="71%" height="25"> <input name="textfield" type="text" value="<?=$url?>" size="35"></td>
    <td width="7%" height="25"> <div align="center"><a href="<?=$url?>" target="_blank">预览</a></div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">最新信息JS:</td>
    <td height="25"> <input name="textfield2" type="text" value="<?=$jspath?>newnews.js" size="35"></td>
    <td height="25"> <div align="center"><a href="js.php?classid=<?=$classid?>&js=<? echo urlencode($jspath."newnews.js");?><?=$ecms_hashur['ehref']?>" target="_blank">预览</a></div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">热门信息JS:</td>
    <td height="25"> <input name="textfield3" type="text" value="<?=$jspath?>hotnews.js" size="35"></td>
    <td height="25"> <div align="center"><a href="js.php?classid=<?=$classid?>&js=<? echo urlencode($jspath."hotnews.js");?><?=$ecms_hashur['ehref']?>" target="_blank">预览</a></div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">推荐信息JS:</td>
    <td height="25"> <input name="textfield4" type="text" value="<?=$jspath?>goodnews.js" size="35"></td>
    <td height="25"> <div align="center"><a href="js.php?classid=<?=$classid?>&js=<? echo urlencode($jspath."goodnews.js");?><?=$ecms_hashur['ehref']?>" target="_blank">预览</a></div></td>
  </tr>

  <tr bgcolor="#FFFFFF"> 
    <td height="25">热点评论信息JS:</td>
    <td height="25"> <input name="textfield4" type="text" value="<?=$jspath?>hotplnews.js" size="35"></td>
    <td height="25"> <div align="center"><a href="js.php?classid=<?=$classid?>&js=<? echo urlencode($jspath."hotplnews.js");?><?=$ecms_hashur['ehref']?>" target="_blank">预览</a></div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">头条信息JS:</td>
    <td height="25"> <input name="textfield4" type="text" value="<?=$jspath?>firstnews.js" size="35"></td>
    <td height="25"> <div align="center"><a href="js.php?classid=<?=$classid?>&js=<? echo urlencode($jspath."firstnews.js");?><?=$ecms_hashur['ehref']?>" target="_blank">预览</a></div></td>
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