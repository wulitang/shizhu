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
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include("../../class/com_functions.php");
}
if($enews=="AddGbookClass")
{
	AddGbookClass($_POST,0,$logininid,$loginin);
}
elseif($enews=="EditGbookClass")
{
	EditGbookClass($_POST,0,$logininid,$loginin);
}
elseif($enews=="DelGbookClass")
{
	$bid=$_GET['bid'];
	DelGbookClass($bid,0,$logininid,$loginin);
}
else
{}
$sql=$empire->query("select bid,bname,checked,groupid from {$dbtbpre}enewsgbookclass order by bid desc");
//----------会员组
$sql1=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($l_r=$empire->fetch($sql1))
{
	$membergroup.="<option value='".$l_r[groupid]."'>".$l_r[groupname]."</option>";
}
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
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="gbook.php<?=$ecms_hashur['whehref']?>">管理留言</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="GbookClass.php<?=$ecms_hashur['whehref']?>">管理留言分类</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理广告类别</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th width="100px">ID</th>
			<th>分类名称</th>
            <th>留言板地址</th>
			<th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
   $gourl=$public_r[newsurl]."e/tool/gbook/?bid=".$r[bid];
   $checked="";
   if($r[checked])
   {
   	$checked=" checked";
   }
   $thismembergroup=str_replace("<option value='".$r[groupid]."'>","<option value='".$r[groupid]."' selected>",$membergroup);
  ?>
  <form name=form2 method=post action=GbookClass.php>
	  <?=$ecms_hashur['form']?>
    <input type=hidden name=enews value=EditGbookClass>
    <input type=hidden name=bid value=<?=$r[bid]?>>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center">
          <?=$r[bid]?>
        </div></td>
      <td height="25"> <div align="center"> 
          <input name="bname" type="text" id="bname" value="<?=$r[bname]?>">
          <select name="groupid" id="groupid">
            <option value="0">游客</option>
            <?=$thismembergroup?>
          </select>
          <input name="checked" type="checkbox" id="checked" value="1"<?=$checked?>>
          审核</div></td>
      <td><div align="center">
          <input name="textfield" type="text" size="32" value="<?=$gourl?>">
          [<a href="<?=$gourl?>" target="_blank">访问</a>]</div></td>
      <td height="25"><div align="center"> 
          <input type="submit" name="Submit3" value="修改">
          &nbsp; 
          <input type="button" name="Submit4" value="删除" onclick="self.location.href='GbookClass.php?enews=DelGbookClass&bid=<?=$r[bid]?><?=$ecms_hashur['href']?>';">
        </div></td>
    </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
<form name="form1" method="post" action="GbookClass.php">
  <?=$ecms_hashur['form']?>
<input name=enews type=hidden id="enews" value=AddGbookClass>
  		<tr> 
          <td style="background:#DBEAF5;">增加留言分类:</td>
		  <td colspan="4" style="text-align:left;background:#DBEAF5;">分类名称: 
        <input name="bname" type="text" id="bname">
        <select name="groupid" id="groupid">
          <option value="0">游客</option>
          <?=$membergroup?>
        </select>
        <input name="checked" type="checkbox" id="checked" value="1">
        需要审核 
        <input type="submit" name="Submit" value="增加">
        <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
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
