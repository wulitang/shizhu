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
$url="<a href=LoadTemp.php".$ecms_hashur['whehref'].">批量导入栏目模板</a>";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量导入栏目模板</title>
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
<form name="form1" method="post" action="../ecmstemp.php" onSubmit="return confirm('确认要导入？');">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="LoadTempInClass">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
		<th style="width:180px;"><h3><span style="padding-left: 5px;">批量导入栏目模板</span></h3></th>
			<th></th>
		</tr>
  <tr bgcolor="#FFFFFF"> 
  <td>导入模板</td>
      <td height="25"> <div align="center"><br>
          <input type="submit" name="Submit" value="开始导入模板">
          <br>
          <br>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF">
    <td>说明</td>
      <td><div align="center">(说明：非终极栏目有效，请将要导入的模板上传至：<a href="ShowLoadTempPath.php<?=$ecms_hashur['whehref']?>" target="_blank"><strong>/e/data/LoadTemp</strong></a>,然后点击导入模板．<br>
          模板文件命名形式：<strong><font color="#FF0000">栏目ID.htm</font></strong> ,系统会搜索相应的&quot;ID文件&quot;进行导入．)</div></td>
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
