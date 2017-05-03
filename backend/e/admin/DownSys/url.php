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
CheckLevel($logininid,$loginin,$classid,"downurl");

//增加url地址
function AddDownurl($add,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($add[urlname]))
	{printerror("EmptyDownurl","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"downurl");
	$downtype=(int)$add['downtype'];
	$sql=$empire->query("insert into {$dbtbpre}enewsdownurlqz(urlname,url,downtype) values('$add[urlname]','$add[url]',$downtype);");
	$urlid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("urlid=".$urlid);
		printerror("AddDownurlSuccess","url.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改url地址
function EditDownurl($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[urlid]=(int)$add[urlid];
	if(empty($add[urlname])||empty($add[urlid]))
	{printerror("EmptyDownurl","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"downurl");
	$downtype=(int)$add['downtype'];
	$sql=$empire->query("update {$dbtbpre}enewsdownurlqz set urlname='$add[urlname]',url='$add[url]',downtype=$downtype where urlid='$add[urlid]'");
	if($sql)
	{
		//操作日志
		insert_dolog("urlid=".$add[urlid]);
		printerror("EditDownurlSuccess","url.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除url地址
function DelDownurl($urlid,$userid,$username){
	global $empire,$dbtbpre;
	$urlid=(int)$urlid;
	if(empty($urlid))
	{printerror("NotChangeDownurlid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"downurl");
	$sql=$empire->query("delete from {$dbtbpre}enewsdownurlqz where urlid='$urlid'");
	if($sql)
	{
		//操作日志
		insert_dolog("urlid=".$urlid);
		printerror("DelDownurlSuccess","url.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//增加前缀
if($enews=="AddDownurl")
{
	AddDownurl($_POST,$logininid,$loginin);
}
//修改前缀
elseif($enews=="EditDownurl")
{
	EditDownurl($_POST,$logininid,$loginin);
}
//删除前缀
elseif($enews=="DelDownurl")
{
	$urlid=$_GET['urlid'];
	DelDownurl($urlid,$logininid,$loginin);
}
else
{}
$sql=$empire->query("select urlid,urlname,url,downtype from {$dbtbpre}enewsdownurlqz order by urlid desc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理下载地址前缀</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="url.php<?=$ecms_hashur['whehref']?>">管理下载地址前缀</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理下载地址前缀</span></h3>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
<form name="form1" method="post" action="url.php">
  <?=$ecms_hashur['form']?>
  <input type=hidden name=enews value=AddDownurl>
  		<tr> 
          <td style="background:#DBEAF5;">增加下载地址前缀:</td>
		  <td style="text-align:left;background:#DBEAF5;">名称: 
        <input name="urlname" type="text" id="urlname">
        地址: 
        <input name="url" type="text" id="url" value="http://" size="38">
        下载方式: <select name="downtype" id="downtype">
          <option value="0">HEADER</option>
          <option value="1">META</option>
          <option value="2">READ</option>
        </select> <input type="submit" name="Submit" value="增加"> <input type="reset" name="Submit2" value="重置"></td>
		  </tr>
 </form>
</table>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>下载地址前缀管理</th>
			<th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
  <form name=form2 method=post action=url.php>
  <input type=hidden name=enews value=EditDownurl>
  <input type=hidden name=urlid value=<?=$r[urlid]?>>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25">名称: 
        <input name="urlname" type="text" id="urlname" value="<?=$r[urlname]?>">
        地址: 
        <input name="url" type="text" id="url" value="<?=$r[url]?>" size="30">
        <select name="downtype" id="downtype">
          <option value="0"<?=$r['downtype']==0?' selected':''?>>HEADER</option>
          <option value="1"<?=$r['downtype']==1?' selected':''?>>META</option>
          <option value="2"<?=$r['downtype']==2?' selected':''?>>READ</option>
        </select> </td>
    <td height="25"><div align="center">
          <input type="submit" name="Submit3" value="修改">&nbsp;
          <input type="button" name="Submit4" value="删除" onclick="if(confirm('确认要删除?')){self.location.href='url.php?enews=DelDownurl&urlid=<?=$r[urlid]?><?=$ecms_hashur['href']?>';}">
        </div></td>
  </tr>
  </form>
  <?
  }
  db_close();
  $empire=null;
  ?>
  <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2" style="text-align:left;">
	  下载方式说明：<br>
      <strong>HEADER：</strong>使用header转向，通常设为这个。<br>
      <strong>META：</strong>直接转自，如果是FTP地址推荐选择这个。<br>
      <strong>READ：</strong>使用PHP程序读取，防盗链较强，但较占资源，服务器本地小文件可选择。
	  </td>
    </tr>
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
