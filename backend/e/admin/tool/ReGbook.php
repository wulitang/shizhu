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
CheckLevel($logininid,$loginin,$classid,"gbook");
$lyid=(int)$_GET['lyid'];
$bid=(int)$_GET['bid'];
$r=$empire->fetch1("select * from {$dbtbpre}enewsgbook where lyid='$lyid' limit 1");
$username="游客";
if($r['userid'])
{
	$username="<a href='../member/AddMember.php?enews=EditMember&userid=".$r['userid'].$ecms_hashur['ehref']."' target=_blank>".$r['username']."</a>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>回复留言</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > 回复/修改留言</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>回复/修改留言</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="gbook.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="ReGbook">
        <input name="lyid" type="hidden" id="lyid" value="<?=$lyid?>">
        <input name="bid" type="hidden" id="bid" value="<?=$bid?>">
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr bgcolor="#FFFFFF"> 
      <td>留言发表者:</td>
      <td style="text-align:left;"> 
        <?=$r[name]?>&nbsp;(<?=$username?>)
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>留言内容:</td>
      <td style="text-align:left;"> 
        <?=nl2br($r[lytext])?>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>发布时间:</td>
      <td style="text-align:left;">
        <?=$r[lytime]?>&nbsp;
        (IP:
        <?=$r[ip]?>)
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><strong>回复内容:</strong></td>
      <td style="text-align:left;"><textarea name="retext" cols="60" rows="9" id="retext"><?=$r[retext]?></textarea> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td style="text-align:left;"><input type="Submit" name="Submit" value="提交" id="tijiao">
        <input type="reset" name="Submit2" value="重置"></td>
    </tr>
	</tbody>
</table>
        </form>
</div>
        </div>
    </div>
</div>
</div>
</body>
</html>
