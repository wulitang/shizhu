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
CheckLevel($logininid,$loginin,$classid,"downerror");
$sql=$empire->query("select classid,classname from {$dbtbpre}enewserrorclass order by classid desc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListError.php<?=$ecms_hashur['whehref']?>">管理错误报告</a> &gt; <a href="ErrorClass.php">管理错误报告分类</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理下载地址前缀</span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
<form name="form1" method="post" action="../ecmscom.php">
 <?=$ecms_hashur['form']?>
<input name=enews type=hidden id="enews" value=AddErrorClass>
        <input name=doing type=hidden value=error>
  		<tr> 
          <td style="background:#DBEAF5; width:150px;">增加错误报告分类:</td>
		  <td style="text-align:left;background:#DBEAF5;">类别名称: 
        <input name="classname" type="text" id="classname">
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
            <th>类别名称</th>
			<th>操作</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=../ecmscom.php>
    <input type=hidden name=enews value=EditErrorClass>
	<input name=doing type=hidden value=error>
    <input type=hidden name=classid value=<?=$r[classid]?>>
	  <?=$ecms_hashur['form']?>
    <tr bgcolor="#FFFFFF">
      <td><div align="center"><?=$r[classid]?></div></td>
      <td height="25"> <div align="center">
          <input name="classname" type="text" id="classname" value="<?=$r[classname]?>">
        </div></td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="self.location.href='../ecmscom.php?enews=DelErrorClass&classid=<?=$r[classid]?>&doing=error<?=$ecms_hashur['href']?>';">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
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
