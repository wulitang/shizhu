<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"pubvar");

//增加变量
function AddPubVar($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[myvar]=RepPostVar($add[myvar]);
	if(!$add[myvar]||!$add[varname])
	{
		printerror("EmptyPubVar","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"pubvar");
	//验证重复
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspubvar where myvar='$add[myvar]' limit 1");
	if($num)
	{
		printerror("RePubVar","history.go(-1)");
	}
	$classid=(int)$add[classid];
	$tocache=(int)$add[tocache];
	$add[myorder]=(int)$add[myorder];
	$add[varvalue]=AddAddsData(RepPhpAspJspcode($add[varvalue]));
	$sql=$empire->query("insert into {$dbtbpre}enewspubvar(myvar,varname,varvalue,varsay,myorder,classid,tocache) values('$add[myvar]','$add[varname]','".$add[varvalue]."','$add[varsay]','$add[myorder]','$classid','$tocache');");
	$lastid=$empire->lastid();
	if($tocache)
	{
		GetConfig();
	}
	if($sql)
	{
		//操作日志
	    insert_dolog("varid=".$lastid."<br>var=".$add[myvar]);
		printerror("AddPubVarSuccess","AddPubVar.php?enews=AddPubVar".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改变量
function EditPubVar($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[varid]=(int)$add['varid'];
	$add[myvar]=RepPostVar($add[myvar]);
	if(!$add[varid]||!$add[myvar]||!$add[varname])
	{
		printerror("EmptyPubVar","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"pubvar");
	if($add[myvar]!=$add[oldmyvar])
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspubvar where myvar='$add[myvar]' and varid<>$add[varid] limit 1");
		if($num)
		{
			printerror("RePubVar","history.go(-1)");
		}
	}
	$add[varvalue]=AddAddsData(RepPhpAspJspcode($add[varvalue]));
	$classid=(int)$add[classid];
	$tocache=(int)$add[tocache];
	$add[myorder]=(int)$add[myorder];
	$sql=$empire->query("update {$dbtbpre}enewspubvar set myvar='$add[myvar]',varname='$add[varname]',varvalue='".$add[varvalue]."',varsay='$add[varsay]',myorder='$add[myorder]',classid='$classid',tocache='$tocache' where varid='$add[varid]'");
	if($tocache||$add['oldtocache'])
	{
		GetConfig();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("varid=".$add[varid]."<br>var=".$add[myvar]);
		printerror("EditPubVarSuccess","ListPubVar.php?classid=$add[cid]".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除变量
function DelPubVar($varid,$cid,$userid,$username){
	global $empire,$dbtbpre;
	$varid=(int)$varid;
	if(!$varid)
	{
		printerror("NotDelPubVarid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"pubvar");
	$r=$empire->fetch1("select myvar,tocache from {$dbtbpre}enewspubvar where varid='$varid'");
	$sql=$empire->query("delete from {$dbtbpre}enewspubvar where varid='$varid'");
	if($r['tocache'])
	{
		GetConfig();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("varid=".$varid."<br>var=".$r[myvar]);
		printerror("DelPubVarSuccess","ListPubVar.php?classid=$cid".hReturnEcmsHashStrHref2(0));
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
//增加变量
if($enews=="AddPubVar")
{
	$add=$_POST;
	AddPubVar($add,$logininid,$loginin);
}
//修改变量
elseif($enews=="EditPubVar")
{
	$add=$_POST;
	EditPubVar($add,$logininid,$loginin);
}
//删除变量
elseif($enews=="DelPubVar")
{
	$varid=$_GET['varid'];
	$cid=$_GET['cid'];
	DelPubVar($varid,$cid,$logininid,$loginin);
}

$search='';
$search.=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select varid,myvar,varvalue,varname,tocache,classid from {$dbtbpre}enewspubvar";
$totalquery="select count(*) as total from {$dbtbpre}enewspubvar";
//分类
$add='';
$classid=(int)$_GET['classid'];
if($classid)
{
	$add=" where classid='$classid'";
	$search.="&classid=$classid";
}
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by myorder,varid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewspubvarclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$classid)
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理扩展变量</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
			
		});
//增加扩展变量
function zjkzbl(){
art.dialog.open('pub/AddPubVar.php?<?=$ecms_hashur[ehref]?>&enews=AddPubVar',
    {title: '增加扩展变量',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理扩展变量分类
function glkzblfl(){
art.dialog.open('pub/PubVarClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理扩展变量分类',lock: true,opacity: 0.5, width: 800, height: 540});
}

//修改扩展变量
function editbl(varid,cid){
art.dialog.open('pub/AddPubVar.php?<?=$ecms_hashur[ehref]?>&enews=EditPubVar&varid=' + varid + '&cid=' + cid,
    {title: '修改扩展变量',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ListPubVar.php<?=$ecms_hashur['whehref']?>">管理扩展变量</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择分类： 
      <select name="classid" id="classid" onchange=window.location='ListPubVar.php<?=$ecms_hashur['ehref']?>&classid='+this.options[this.selectedIndex].value>
          <option value="0">显示所有分类</option>
		  <?=$cstr?>
        </select> <a href="javascript:void(0)" onclick="zjkzbl()" class="add">增加扩展变量</a> <a href="javascript:void(0)" onclick="glkzblfl()"class="gl">管理扩展变量分类</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>变量名</th>
			<th>变量标识</th>
			<th>所属分类</th>
			<th>写入缓存</th>
			<th>操作</th>
		</tr>
<?php
  while($r=$empire->fetch($sql))
  {
  	//分类
	$cname='未分类';
	if($r['classid'])
	{
		$lcr=$empire->fetch1("select classname from {$dbtbpre}enewspubvarclass where classid='$r[classid]'");
		$cname='<a href="ListPubVar.php?classid='.$r[classid].$ecms_hashur['ehref'].'">'.$lcr[classname].'</a>';
	}
  	if($r[tocache])
  	{
  		$tocache='<font color=red>写入</font>';
  	}
  	else
  	{
 	 	$tocache='不写入';
  	}
  ?>
		<tr>
			<td><?=$r[varid]?></td>
			<td><?=$r[myvar]?></td>
			<td><?=$r[varname]?></td>
			<td><?=$cname?></td>
			<td><?=$tocache?></td>
			<td>[<a href="javascript:editbl(<?=$r[varid]?>,<?=$classid?>)">修改</a>]&nbsp;[<a href="ListPubVar.php?enews=DelPubVar&varid=<?=$r[varid]?>&cid=<?=$classid?><?=$ecms_hashur['href']?>" onClick="return confirm('确认要删除？');">删除</a>]</td>
		</tr>
  <?php
  }
  ?>
		<tr>
		  <td colspan="6" class="txtleft"><?=$returnpage?></td>
		  </tr>
		<tr>
		  <td colspan="6">扩展变量调用说明</td>
		  </tr>
		<tr>
			<td colspan="6" align="left" class="tixing txtleft">
          <h5>1、写入缓存的变量调用</h5><br />
在任意php或模板中用$public_r['add_变量名']可取得变量内容。<br />
<br /><h5>2、没有写入缓存的变量调用</h5><br />
可以用内置变量返回函数(数据库读取变量内容)：ReturnPublicAddVar(变量名)。取多个变量内容可用逗号隔开，例子：<br />
取得单变量内容：$value=ReturnPublicAddVar('myvar'); //$value就是变量内容。<br />
取得多个变量内容：$value=ReturnPublicAddVar('myvar1,myvar2,myvar3'); //$value['myvar1']才是变量内容。
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
<?php
db_close();
$empire=null;
?>
