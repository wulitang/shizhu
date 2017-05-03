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
CheckLevel($logininid,$loginin,$classid,"tempgroup");
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include("../../class/tempfun.php");
}
if($enews=="LoadInTempGroup")//导入
{
	include "../".LoadLang("pub/fun.php");
	$file=$_FILES['file']['tmp_name'];
    $file_name=$_FILES['file']['name'];
    $file_type=$_FILES['file']['type'];
    $file_size=$_FILES['file']['size'];
	LoadInTempGroup($_POST,$file,$file_name,$file_type,$file_size,$logininid,$loginin);
}
elseif($enews=="LoadTempGroup")//导出
{
	LoadTempGroup($_POST,$logininid,$loginin);
}
elseif($enews=="EditTempGroup")//修改
{
	EditTempGroup($_POST,$logininid,$loginin);
}
elseif($enews=="DefTempGroup")//默认
{
	DefTtempGroup($_POST,$logininid,$loginin);
}
elseif($enews=="DelTempGroup")//删除
{
	DelTempGroup($_POST,$logininid,$loginin);
}
else
{}
$sql=$empire->query("select gid,gname,isdefault from {$dbtbpre}enewstempgroup order by gid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>模板组管理</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function CheckDel(gid){
	var ok=confirm("确认要删除?");
	if(ok)
	{
		self.location.href='TempGroup.php?<?=$ecms_hashur['href']?>&enews=DelTempGroup&gid='+gid;
	}
}
</script>
<style>
.comm-table td{ height:16px; padding:8px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="TempGroup.php<?=$ecms_hashur['whehref']?>">模板组管理</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="jqui">
 <form name=tempgroup method=post action=TempGroup.php onSubmit="return confirm('确认要执行?');">
	<?=$ecms_hashur['form']?>
 <input type=hidden name=enews value=EditTempGroup>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
		<th style="width:140px;"><h3><span style="padding-left: 1px;">模板组名称</span></h3></th>
			<th></th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  	$tempgroup_options.="<option value='".$r['gid']."'>".$r['gname']."</option>";
  	$bgcolor="#FFFFFF";
	$checked="";
	$movejs=' onmouseout="this.style.backgroundColor=\'#ffffff\'" onmouseover="this.style.backgroundColor=\'#C3EFFF\'"';
  	if($r['isdefault'])
	{
		$bgcolor="#DBEAF5";
		$checked=" checked";
		$movejs='';
	}
  ?>
          <input type=hidden name=gid[] value=<?=$r[gid]?>>
          <tr bgcolor="<?=$bgcolor?>"<?=$movejs?>> 
            <td height="25"> <div align="center"> 
                <input type="radio" name="changegid" value="<?=$r[gid]?>"<?=$checked?>>
              </div></td>
            <td height="25" style="text-align:left;"> <input name="gname[]" type="text" id="gname[]" value="<?=$r[gname]?>" size="30">(ID：<?=$r[gid]?>) 
            </td>
          </tr>
  <?
  }
  ?>
  		  <tr bgcolor="#FFFFFF"> 
            <td height="25">&nbsp;</td>
            <td height="25" style="text-align:left;"><input type="submit" name="Submit3" value="修改" onClick="document.tempgroup.enews.value='EditTempGroup';">&nbsp; 
              <input type="submit" name="Submit4" value="设为默认" onClick="document.tempgroup.enews.value='DefTempGroup';">&nbsp;
              <input type="submit" name="Submit6" value="导出" onClick="document.tempgroup.enews.value='LoadTempGroup';">&nbsp;
              <input type="submit" name="Submit7" value="删除" onClick="document.tempgroup.enews.value='DelTempGroup';">&nbsp; 
              <input type="reset" name="Submit5" value="重置">
            </td>
          </tr>
	</tbody>
</table>
</form>

<form action=TempGroup.php method=post enctype="multipart/form-data" name=loadform onSubmit="return confirm('确认要执行?');">
	<?=$ecms_hashur['form']?>
<input type=hidden name=enews value=LoadInTempGroup>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
		<th style="width:140px;"><h3><span style="padding-left: 1px;">导入模板组</span></h3></th>
			<th></th>
		</tr>
<tr bgcolor="#FFFFFF"> 
            <td height="32" style="width:120px">上传模板文件</td>
            <td style="text-align:left;"> <input type="file" name="file">
              <font color="#666666">(*.temp文件)</font></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32">模板编码</td>
            <td style="text-align:left;"> <input name="ChangeChar" type="checkbox" id="ChangeChar" value="1" onClick="if(this.checked){changechardiv.style.display='';}else{changechardiv.style.display='none';}">
              模板需要转编码 &nbsp;&nbsp;<font color="#666666">(当前站点编码：<b><?=$ecms_config['sets']['pagechar']?></b>)</font></td>
          </tr>
          <tr bgcolor="#FFFFFF" id="changechardiv" style="display:none">
            <td height="32">&nbsp;</td>
            <td style="text-align:left;"> 要导入的模板编码: 
              <select name="tempchar" id="tempchar">
                <option value="GB2312">简体GB2312</option>
                <option value="UTF8">简体UTF-8</option>
                <option value="BIG5">繁体BIG5</option>
                <option value="TCUTF8">繁体UTF-8</option>
              </select>
            </td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32">覆盖模板组</td>
            <td style="text-align:left;"> <select name="gid" id="gid">
                <option value="0">新建新的模板组</option>
                <?=$tempgroup_options?>
              </select> </td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32">&nbsp;</td>
            <td height="25" style="text-align:left;"> <input type="submit" name="Submit" value="导 入">
              &nbsp;&nbsp; </td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="32" colspan="2"><font color="#666666">查看源模板编码技巧：用记事本打开.temp文件，然后搜索"<strong>charset=</strong>"就能查看编码</font></td>
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