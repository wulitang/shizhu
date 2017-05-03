<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../member/class/user.php");
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
CheckLevel($logininid,$loginin,$classid,"member");

$enews=$_POST['enews'];
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=='ClearMember')
{
	@set_time_limit(0);
	include('../../member/class/member_adminfun.php');
	include('../../member/class/member_modfun.php');
	admin_ClearMember($_POST,$logininid,$loginin);
}

//会员组
$group='';
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($level_r=$empire->fetch($sql))
{
	$group.="<option value=".$level_r[groupid].">".$level_r[groupname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>清理会员</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<script src="../ecmseditor/fieldfile/setday.js"></script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href=ListMember.php<?=$ecms_hashur['whehref']?>">管理会员</a>&nbsp;>&nbsp;清理会员</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>清理会员</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="ClearMember.php" onSubmit="return confirm('确认要删除?');">
 <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="ClearMember">
<table class="comm-table" cellspacing="0">
	<tbody>
         <tr bgcolor="#FFFFFF"> 
      <td style="width:150px">用户名包含字符：</td>
      <td style="text-align:left;"><input name=username type=text id="username"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>邮箱地址包含字符：</td>
      <td style="text-align:left;"><input name=email type=text id="email"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>用户ID 介于：</td>
      <td style="text-align:left;"><input name="startuserid" type="text" id="startuserid">
        -- 
        <input name="enduserid" type="text" id="enduserid"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td valign="top">所属会员组：</td>
      <td style="text-align:left;"><select name="groupid" id="groupid">
          <option value="0">不限</option>
          <?=$group?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td valign="top">注册时间 介于：</td>
      <td style="text-align:left;"><input name="startregtime" type="text" id="startregtime" onClick="setday(this)">
        -- 
        <input name="endregtime" type="text" id="endregtime" onClick="setday(this)">
        <font color="#666666">(格式：2011-01-27)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>点数 介于：</td>
      <td style="text-align:left;"><input name="startuserfen" type="text" id="startuserfen">
        -- 
        <input name="enduserfen" type="text" id="enduserfen"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>帐户余额 介于：</td>
      <td style="text-align:left;"><input name="startmoney" type="text" id="startmoney">
        -- 
        <input name="endmoney" type="text" id="endmoney"></td>
    </tr>
	<tr bgcolor="#FFFFFF"> 
      <td>是否审核：</td>
      <td style="text-align:left;"><input name="checked" type="radio" value="0" checked>
        不限 
        <input name="checked" type="radio" value="1">
        已审核会员 
        <input name="checked" type="radio" value="2">
        未审核会员</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td style="text-align:left;"><input type="submit" name="Submit" value="删除会员">
      </td>
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
