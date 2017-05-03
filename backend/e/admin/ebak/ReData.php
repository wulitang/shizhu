<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("class/functions.php");
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
CheckLevel($logininid,$loginin,$classid,"dbdata");
$mypath=ehtmlspecialchars($_GET['mypath']);
$mydbname=ehtmlspecialchars($_GET['mydbname']);
$selectdbname=$ecms_config['db']['dbname'];
if($mydbname)
{
	$selectdbname=$mydbname;
}
$bakpath=$public_r['bakdbpath'];
$db='';
if($public_r['ebakcanlistdb'])
{
	$db.="<option value='".$selectdbname."' selected>".$selectdbname."</option>";
}
else
{
	$sql=$empire->query("SHOW DATABASES");
	while($r=$empire->fetch($sql))
	{
		if($r[0]==$selectdbname)
		{$select=" selected";}
		else
		{$select="";}
		$db.="<option value='".$r[0]."'".$select.">".$r[0]."</option>";
	}
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>恢复数据</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
			
		});
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?>  </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>恢复数据</span></h3>
            <div class="line"></div>
<form action="phome.php" method="post" name="ebakredata" target="_blank" onsubmit="return confirm('确认要恢复？');">
  <?=$ecms_hashur['form']?>
<input name="phome" type="hidden" id="phome" value="ReData"> 
			<ul>
            		<li class="jqui"><label>恢复数据源目录(*)</label><?=$bakpath?>
        / 
        <input name="mypath" type="text" id="mypath" value="<?=$mypath?>">
        <input type="button" name="Submit2" value="选择目录" onclick="javascript:window.open('ChangePath.php?change=1<?=$ecms_hashur['ehref']?>','','width=600,height=500,scrollbars=yes');"> </li>
                    <li><label>要导入的数据库:</label><select name="add[mydbname]" size="23" id="add[mydbname]" style="width=200">
          <?=$db?>
        </select></li>
                    <li class="jqui"><label>恢复选项:</label>每组恢复间隔： 
        <input name="add[waitbaktime]" type="text" id="add[waitbaktime]" value="0" size="2">
        秒</li>
        <li class="jqui"><label>&nbsp;</label><input type="submit" value="开始恢复" style="padding: 5px 10px;"></li>
            </ul>
            </form>
			<div class="line"></div>
        </div>
    </div>
</div>

</div>
</body>
</html>
