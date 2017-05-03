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
CheckLevel($logininid,$loginin,$classid,"card");
$enews=ehtmlspecialchars($_GET['enews']);
$url="<a href=ListCard.php".$ecms_hashur['whehref'].">管理点卡</a> &gt; <a href=AddMoreCard.php".$ecms_hashur['whehref'].">批量增加点卡</a>";
//----------会员组
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($level_r=$empire->fetch($sql))
{
	$group.="<option value=".$level_r[groupid].">".$level_r[groupname]."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量增加点卡</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<script src="../ecmseditor/fieldfile/setday.js"></script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>批量增加点卡</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="ListCard.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="AddMoreCard">
<table class="comm-table" cellspacing="0">
	<tbody>
      <tr bgcolor="#FFFFFF"> 
      <td>批量生成点卡数量：</td>
      <td style="text-align:left;"><input name="donum" type="text" id="donum" value="10" size="6">
        个</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点卡帐号位数：</td>
      <td style="text-align:left;"><input name="cardnum" type="text" id="cardnum" value="8" size="6">
        位 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点卡密码位数：</td>
      <td style="text-align:left;"><input name="passnum" type="text" id="passnum" value="6" size="6">
        位 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点卡金额：</td>
      <td style="text-align:left;"><input name="money" type="text" id="money" value="10" size="6">
        元</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点数：</td>
      <td style="text-align:left;"><input name="cardfen" type="text" id="cardfen" value="0" size="6">
        点</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td rowspan="3">充值有效期:</td>
      <td style="text-align:left;"><input name="carddate" type="text" id="carddate" value="0" size="6">
        天</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td style="text-align:left;">充值设置转向会员组: 
        <select name="cdgroupid" id="select2">
          <option value=0>不设置</option>
          <?=$group?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td style="text-align:left;">到期后转向的会员组: 
        <select name="cdzgroupid" id="cdzgroupid">
          <option value=0>不设置</option>
          <?=$group?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>到期时间：</td>
      <td style="text-align:left;"><input name="endtime" type="text" id="endtime" value="0000-00-00" size="20" onClick="setday(this)">
        (0000-00-00为不限制)</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><div align="center"> 
          <input type="submit" name="Submit" value="提交">
          &nbsp; 
          <input type="reset" name="Submit2" value="重置">
        </div></td>
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
<?
db_close();
$empire=null;
?>