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
$time=ehtmlspecialchars($_GET['time']);
$r[money]=10;
$r[cardfen]=0;
$r[carddate]=0;
$r[endtime]="0000-00-00";
$r[card_no]=time();
$r[password]=strtolower(no_make_password(6));
$url="<a href=ListCard.php".$ecms_hashur['whehref'].">管理点卡</a> &gt; 增加点卡";
if($enews=="EditCard")
{
	$cardid=(int)$_GET['cardid'];
	$r=$empire->fetch1("select card_no,password,money,cardfen,endtime,carddate,cdgroupid,cdzgroupid from {$dbtbpre}enewscard where cardid='$cardid' limit 1");
	$url="<a href=ListCard.php".$ecms_hashur['whehref'].">管理点卡</a> &gt; 修改点卡：<b>".$r[card_no]."</b>";
}
//----------会员组
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($level_r=$empire->fetch($sql))
{
	if($r[cdgroupid]==$level_r[groupid])
	{$select=" selected";}
	else
	{$select="";}
	$group.="<option value=".$level_r[groupid].$select.">".$level_r[groupname]."</option>";
	if($r[cdzgroupid]==$level_r[groupid])
	{$zselect=" selected";}
	else
	{$zselect="";}
	$zgroup.="<option value=".$level_r[groupid].$zselect.">".$level_r[groupname]."</option>";
}
$href="AddCard.php?enews=$enews&cardid=$cardid".$ecms_hashur['ehref'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加点卡</title>
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
        	<h3><span>增加点卡</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="ListCard.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>">
          <input name="add[cardid]" type="hidden" id="add[cardid]" value="<?=$cardid?>">
          <input name="time" type="hidden" id="time" value="<?=$time?>">
<table class="comm-table" cellspacing="0">
	<tbody>
        <tr bgcolor="#FFFFFF"> 
      <td>点卡帐号：</td>
      <td style="text-align:left;"><input name="add[card_no]" type="text" id="add[card_no]" value="<?=$r[card_no]?>">
        <font color="#666666">(&lt;30位)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点卡密码：</td>
      <td style="text-align:left;"><input name="add[password]" type="text" id="add[password]" value="<?=$r[password]?>">
        <font color="#666666">(&lt;20位)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点卡金额：</td>
      <td style="text-align:left;"><input name="add[money]" type="text" id="add[money]" value="<?=$r[money]?>" size="6">
        元</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点数：</td>
      <td style="text-align:left;"><input name="add[cardfen]" type="text" id="add[cardfen]" value="<?=$r[cardfen]?>" size="6">
        点</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td rowspan="3">充值有效期:</td>
      <td style="text-align:left;"><input name="add[carddate]" type="text" id="add[carddate]" value="<?=$r[carddate]?>" size="6">
        天</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td style="text-align:left;">充值设置转向会员组:
        <select name="add[cdgroupid]" id="add[cdgroupid]">
		<option value=0>不设置</option>
		<?=$group?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td style="text-align:left;">到期后转向的会员组:
	  	<select name="add[cdzgroupid]" id="add[cdzgroupid]">
		<option value=0>不设置</option>
		<?=$zgroup?>
        </select>
	  </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>到期时间：</td>
      <td style="text-align:left;"><input name="add[endtime]" type="text" id="add[endtime]" value="<?=$r[endtime]?>" size="20" onClick="setday(this)">
        <font color="#666666">(0000-00-00为不限制)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><div align="center"> 
          <input type="submit" name="Submit" value="提交">
          &nbsp; 
          <input type="reset" name="Submit2" value="重置">
          &nbsp; 
          <input type="button" name="Submit3" value="密码随机" onClick="javascript:self.location.href='<?=$href?>'">
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