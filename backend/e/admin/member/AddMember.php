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
$userdate=0;
$enews=ehtmlspecialchars($_GET['enews']);
$changegroupid=(int)$_GET['changegroupid'];
$url="<a href=ListMember.php".$ecms_hashur['whehref'].">管理会员</a>&nbsp;>&nbsp;增加会员";
if($enews=="EditMember")
{
	$userid=(int)$_GET['userid'];
	$button="修改资料";
	//取得用户资料
	$r=ReturnUserInfo($userid);
	$r['groupid']=$r['groupid']?$r['groupid']:eReturnMemberDefGroupid();
	$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='$userid' limit 1");
	$url="<a href=ListMember.php".$ecms_hashur['whehref'].">管理会员</a>&nbsp;>&nbsp;修改会员资料：<b>".$r[username]."</b>";
	//时间
	if($r[userdate])
	{
		$userdate=$r[userdate]-time();
		if($userdate<=0)
		{
			OutTimeZGroup($userid,$r['zgroupid']);
			if($r['zgroupid'])
			{
				$r['groupid']=$r['zgroupid'];
				$r['zgroupid']=0;
			}
			$userdate=0;
		}
		else
		{
			$userdate=round($userdate/(24*3600));
		}
	}
}
if($changegroupid)
{
	$r['groupid']=$changegroupid;
}
//取得表单
$formid=GetMemberFormId($r[groupid]);
$formfile='../../data/html/memberform'.$formid.'.php';
if($enews=="AddMember"){
	$button="增加会员";
	if($changegroupid){
		$hyzid=$changegroupid;
	} else {
		$s=$empire->fetch1("select groupid from {$dbtbpre}enewsmembergroup order by groupid");
		$hyzid=$s[groupid];
	}
	$formfile='';	
}
//----------会员组
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($level_r=$empire->fetch($sql))
{
	if($enews=="AddMember"){
		if($hyzid==$level_r[groupid])
		{$select=" selected";}
		else
		{$select="";}
	} else {
		if($r[groupid]==$level_r[groupid])
		{$select=" selected";}
		else
		{$select="";}
	}
	$group.="<option value=".$level_r[groupid].$select.">".$level_r[groupname]."</option>";
	if($r[zgroupid]==$level_r[groupid])
	{$zselect=" selected";}
	else
	{$zselect="";}
	$zgroup.="<option value=".$level_r[groupid].$zselect.">".$level_r[groupname]."</option>";
}
//风格
$spacestyle='';
$spacesql=$empire->query("select styleid,stylename from {$dbtbpre}enewsspacestyle");
while($spacer=$empire->fetch($spacesql))
{
	$selected='';
	if($spacer[styleid]==$addr[spacestyleid])
	{
		$selected=' selected';
	}
	$spacestyle.="<option value='$spacer[styleid]'".$selected.">".$spacer[stylename]."</option>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$button?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td table td{ text-align:left;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span><?=$button?></span></h3>
<div class="jqui anniuqun">
<div class="line"></div>
<form name="form1" method="post" action="ListMember.php" enctype="multipart/form-data">
 <?=$ecms_hashur['form']?>
<input type=hidden name=add[oldusername] value='<?=$r[username]?>'>
<input type=hidden name=add[userid] value='<?=$userid?>'>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>">
<table class="comm-table" cellspacing="0">
	<tbody>
        <tr bgcolor="#FFFFFF"> 
      <td style="width:150px;">用户名</td>
      <td style="text-align:left;"><input name="add[username]" type=text id="add[username]" value='<?=$r[username]?>'></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>密码</td>
      <td style="text-align:left;"><input name="add[password]" type="password" id="add[password]">
        (修改时：如不想修改,请留空)</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>审核</td>
      <td style="text-align:left;"><input name="add[checked]" type="checkbox" id="add[checked]" value="1"<?=$r[checked]==1?' checked':''?>>
        审核通过</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td valign="top">所属会员组<br> <br> <input type="button" name="Submit3" value="管理会员组" onClick="window.open('ListMemberGroup.php<?=$ecms_hashur['whehref']?>');"> 
      </td>
      <td style="text-align:left;"><select name="add[groupid]" size="6" id="add[groupid]" onchange="self.location.href='AddMember.php?<?=$ecms_hashur['ehref']?>&enews=EditMember&userid=<?=$userid?>&changegroupid='+this.options[this.selectedIndex].value;">
          <?=$group?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>邮箱</td>
      <td style="text-align:left;"><input name="add[email]" type="text" id="add[email]" value="<?=$r[email]?>" size="35"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>剩余天数</td>
      <td style="text-align:left;"><input name=add[userdate] type=text id="add[userdate]" value='<?=$userdate?>' size="6">
        天，到期后转向用户组: 
        <select name="add[zgroupid]" id="add[zgroupid]">
          <option value="0">不设置</option>
          <?=$zgroup?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>点数</td>
      <td style="text-align:left;"><input name=add[userfen] type=text id="add[userfen]" value='<?=$r[userfen]?>' size="6">
        点</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>帐户余额</td>
      <td style="text-align:left;"><input name=add[money] type=text id="add[money]" value='<?=$r[money]?>' size="6">
        元 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>空间使用模板</td>
      <td style="text-align:left;"><select name="add[spacestyleid]" id="add[spacestyleid]">
          <?=$spacestyle?>
        </select> <input type="button" name="Submit32" value="管理空间模板" onclick="window.open('ListSpaceStyle.php<?=$ecms_hashur['whehref']?>');"></td>
    </tr>
<?
if($enews=="EditMember"){
?>
    <tr bgcolor="#FFFFFF">
      <td>注册时间</td>
      <td style="text-align:left;"><?=eReturnMemberRegtime($r['registertime'],"Y-m-d H:i:s")?></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>注册IP</td>
      <td style="text-align:left;"><?=$addr[regip]?>:<?=$addr[regipport]?></td>
    </tr>
         <tr bgcolor="#FFFFFF">
      <td height="25">最后登录</td>
      <td height="25">总登录次数：<?=$addr[loginnum]?>，时间：<?=date("Y-m-d H:i:s",$addr[lasttime])?>，登录IP：<?=$addr[lastip]?>:<?=$addr[lastipport]?></td>
    </tr>
      <td colspan="2">其他信息</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"> 
        <?php
	  @include($formfile);
	  ?>
      </td>
    </tr>
<?
}
?>
	</tbody>
</table>
<div class="sub jqui jqtransformdone">
  <button id="" name="Submit" type="submit"><span><span>提交</span></span></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button id="" name="Submit2" type="reset" class=" jqTransformButton"><span><span> 重置 </span></span></button></div>
        </form>
		<div class="line"></div>
</div>

        </div>
    </div>
</div>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>