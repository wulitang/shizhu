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
CheckLevel($logininid,$loginin,$classid,"buygroup");
$enews=ehtmlspecialchars($_GET['enews']);
$r[gmoney]=10;
$r[gfen]=0;
$r[gdate]=0;
$url="<a href=ListBuyGroup.php".$ecms_hashur['whehref'].">管理充值类型</a> &gt; 增加充值类型";
if($enews=="EditBuyGroup")
{
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsbuygroup where id='$id' limit 1");
	$url="<a href=ListBuyGroup.php".$ecms_hashur['whehref'].">管理充值类型</a> &gt; 修改充值类型";
}
//----------会员组
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($level_r=$empire->fetch($sql))
{
	if($r[ggroupid]==$level_r[groupid])
	{$select=" selected";}
	else
	{$select="";}
	$group.="<option value=".$level_r[groupid].$select.">".$level_r[groupname]."</option>";
	if($r[gzgroupid]==$level_r[groupid])
	{$zselect=" selected";}
	else
	{$zselect="";}
	$zgroup.="<option value=".$level_r[groupid].$zselect.">".$level_r[groupname]."</option>";
	if($r[buygroupid]==$level_r[groupid])
	{$bselect=" selected";}
	else
	{$bselect="";}
	$buygroup.="<option value=".$level_r[groupid].$bselect.">".$level_r[groupname]."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加充值类型</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>增加充值类型</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="ListBuyGroup.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="id" type="hidden" id="id" value="<?=$id?>"> 
<table class="comm-table" cellspacing="0">
	<tbody>
        <tr bgcolor="#FFFFFF"> 
      <td>类型名称：</td>
      <td style="text-align:left;"><input name="gname" type="text" id="gname" value="<?=$r[gname]?>" size="35"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>购买金额：</td>
      <td style="text-align:left;"><input name="gmoney" type="text" id="gmoney" value="<?=$r[gmoney]?>" size="35">
        元</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>充值点数：</td>
      <td style="text-align:left;"><input name="gfen" type="text" id="gfen" value="<?=$r[gfen]?>" size="35">
        点</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td rowspan="3">充值有效期：</td>
      <td style="text-align:left;"><input name="gdate" type="text" id="gdate" value="<?=$r[gdate]?>" size="35">
        天</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td style="text-align:left;">充值设置转向会员组: 
        <select name="ggroupid" id="ggroupid">
          <option value=0>不设置</option>
          <?=$group?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td style="text-align:left;">到期后转向的会员组: 
        <select name="gzgroupid" id="gzgroupid">
          <option value=0>不设置</option>
          <?=$zgroup?>
        </select> </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>显示顺序：</td>
      <td style="text-align:left;"><input name="myorder" type="text" id="myorder" value="<?=$r[myorder]?>" size="35">
        <font color="#666666">(值越小显示越前面)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>可购买的会员：</td>
      <td style="text-align:left;"><select name="buygroupid" id="buygroupid">
          <option value=0>不设置</option>
          <?=$buygroup?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>类型说明：</td>
      <td style="text-align:left;"><textarea name="gsay" cols="65" rows="6" id="gsay"><?=ehtmlspecialchars($r[gsay])?></textarea></td>
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