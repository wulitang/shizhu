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
CheckLevel($logininid,$loginin,$classid,"template");

//增加标签模板
function AddBqtemp($tempname,$modid,$subnews,$rownum,$showdate,$temptext,$listvar,$add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$tempname||!$temptext||!$modid||!$listvar)
	{printerror("EmptyTempname","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
    $classid=(int)$add['classid'];
	$tempname=hRepPostStr($tempname,1);
    $temptext=RepPhpAspJspcode($temptext);
	$listvar=RepPhpAspJspcode($listvar);
	if($add['autorownum'])
	{
		$rownum=substr_count($temptext,'<!--list.var');
	}
	//变量处理
	$modid=(int)$modid;
	$subnews=(int)$subnews;
	$rownum=(int)$rownum;
	$docode=(int)$add[docode];
	$gid=(int)$add['gid'];
	$sql=$empire->query("insert into ".GetDoTemptb("enewsbqtemp",$gid)."(tempname,temptext,modid,showdate,listvar,subnews,rownum,classid,docode) values('$tempname','".eaddslashes2($temptext)."',$modid,'$showdate','".eaddslashes2($listvar)."',$subnews,$rownum,$classid,'$docode')");
	$lastid=$empire->lastid();
	//备份模板
	AddEBakTemp('bqtemp',$gid,$lastid,$tempname,$temptext,$subnews,0,$listvar,$rownum,$modid,$showdate,0,$classid,$docode,$userid,$username);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=".$lastid."<br>tempname=".$tempname."&gid=$gid");
		printerror("AddBqTempSuccess","AddBqtemp.php?enews=AddBqtemp&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//------------------------修改标签模板
function EditBqtemp($tempid,$tempname,$modid,$subnews,$rownum,$showdate,$temptext,$listvar,$add,$userid,$username){
	global $empire,$dbtbpre;
	$tempid=(int)$tempid;
	if(!$tempname||!$temptext||!$modid||!$listvar||!$tempid)
	{printerror("EmptyTempname","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
    $classid=(int)$add['classid'];
	$tempname=hRepPostStr($tempname,1);
    $temptext=RepPhpAspJspcode($temptext);
	$listvar=RepPhpAspJspcode($listvar);
	if($add['autorownum'])
	{
		$rownum=substr_count($temptext,'<!--list.var');
	}
	//变量处理
	$modid=(int)$modid;
	$subnews=(int)$subnews;
	$rownum=(int)$rownum;
	$docode=(int)$add[docode];
	$gid=(int)$add['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewsbqtemp",$gid)." set tempname='$tempname',temptext='".eaddslashes2($temptext)."',modid=$modid,showdate='$showdate',listvar='".eaddslashes2($listvar)."',subnews=$subnews,rownum=$rownum,classid=$classid,docode='$docode' where tempid='$tempid'");
	//备份模板
	AddEBakTemp('bqtemp',$gid,$tempid,$tempname,$temptext,$subnews,0,$listvar,$rownum,$modid,$showdate,0,$classid,$docode,$userid,$username);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=".$tempid."<br>tempname=".$tempname."&gid=$gid");
		printerror("EditBqtempSuccess","ListBqtemp.php?classid=$add[cid]&modid=$add[mid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//-------------------------删除标签模板
function DelBqtemp($tempid,$add,$userid,$username){
	global $empire,$dbtbpre;
	$tempid=(int)$tempid;
	if(!$tempid)
	{printerror("NotDelTemplateid","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$r=$empire->fetch1("select tempname from ".GetDoTemptb("enewsbqtemp",$gid)." where tempid='$tempid'");
	//删除模板
	$sql=$empire->query("delete from ".GetDoTemptb("enewsbqtemp",$gid)." where tempid='$tempid'");
	//删除模板备份
	DelEbakTempAll('bqtemp',$gid,$tempid);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=".$tempid."<br>tempname=".$r[tempname]."&gid=$gid");
		printerror("DelBqtempSuccess","ListBqtemp.php?classid=$add[cid]&modid=$add[mid]&gid=$gid".hReturnEcmsHashStrHref2(0));
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
	include("../../class/tempfun.php");
}
//增加标签模板
if($enews=="AddBqtemp")
{
	$tempname=$_POST['tempname'];
	$temptext=$_POST['temptext'];
	$modid=$_POST['modid'];
	$showdate=$_POST['showdate'];
	$subnews=$_POST['subnews'];
	$listvar=$_POST['listvar'];
	$rownum=$_POST['rownum'];
	AddBqtemp($tempname,$modid,$subnews,$rownum,$showdate,$temptext,$listvar,$_POST,$logininid,$loginin);
}
//修改标签模板
elseif($enews=="EditBqtemp")
{
	$tempid=$_POST['tempid'];
	$tempname=$_POST['tempname'];
	$temptext=$_POST['temptext'];
	$modid=$_POST['modid'];
	$showdate=$_POST['showdate'];
	$subnews=$_POST['subnews'];
	$listvar=$_POST['listvar'];
	$rownum=$_POST['rownum'];
	EditBqtemp($tempid,$tempname,$modid,$subnews,$rownum,$showdate,$temptext,$listvar,$_POST,$logininid,$loginin);
}
//删除标签模板
elseif($enews=="DelBqtemp")
{
	$tempid=$_GET['tempid'];
	DelBqtemp($tempid,$_GET,$logininid,$loginin);
}

$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$url=$urlgname."<a href=ListBqtemp.php?gid=$gid".$ecms_hashur['ehref'].">管理标签模板</a>";
$search="&gid=$gid".$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select tempid,tempname,modid from ".GetDoTemptb("enewsbqtemp",$gid);
$totalquery="select count(*) as total from ".GetDoTemptb("enewsbqtemp",$gid);
//类别
$add="";
$classid=(int)$_GET['classid'];
if($classid)
{
	$add=" where classid=$classid";
	$search.="&classid=$classid";
}
//模型
$modid=(int)$_GET['modid'];
if($modid)
{
	if(empty($add))
	{
		$add=" where modid=$modid";
	}
	else
	{
		$add.=" and modid=$modid";
	}
	$search.="&modid=$modid";
}
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by tempid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsbqtempclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$classid)
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
}
//模型
$mstr="";
$msql=$empire->query("select mid,mname from {$dbtbpre}enewsmod where usemod=0 order by myorder,mid");
while($mr=$empire->fetch($msql))
{
	$select="";
	if($mr[mid]==$modid)
	{
		$select=" selected";
	}
	$mstr.="<option value='".$mr[mid]."'".$select.">".$mr[mname]."</option>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理标签模板</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
//管理标签模板分类
function glbqmbfl(){
art.dialog.open('template/BqtempClass.php?<?=$ecms_hashur[ehref]?>&gid=<?=$gid?>',
    {title: '管理标签模板分类',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
<style>
.comm-table td{ height:16px; padding:8px 0; font-size:12px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
 <form name="form1" method="get" action="ListBqtemp.php">
 <?=$ecms_hashur['eform']?>
  <input type=hidden name=gid value="<?=$gid?>">
        	<h3><span>限制显示： 
        <select name="classid" id="classid" onChange="document.form1.submit()">
          <option value="0">显示所有分类</option>
		  <?=$cstr?>
        </select>
        <select name="modid" id="modid" onChange="document.form1.submit()">
          <option value="0">显示所有系统模型</option>
		  <?=$mstr?>
        </select> <a href="AddBqtemp.php?enews=AddBqtemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="add">增加标签模板</a> <a href="javascript:void()" onclick="glbqmbfl()" class="gl">管理标签模板分类</a></span></h3></form>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>模板名</th>
            <th>所属系统模型</th>
            <th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  $modr=$empire->fetch1("select mid,mname from {$dbtbpre}enewsmod where mid=$r[modid]");
  ?>
  <tr bgcolor="ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r[tempid]?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r[tempname]?>
      </div></td>
    <td><div align="center">[<a href="ListBqtemp.php?classid=<?=$classid?>&modid=<?=$modr[mid]?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>"><?=$modr[mname]?></a>]</div></td>
    <td><div align="center"> [<a href="AddBqtemp.php?enews=EditBqtemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&mid=<?=$modid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="AddBqtemp.php?enews=AddBqtemp&docopy=1&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&mid=<?=$modid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">复制</a>] 
        [<a href="ListBqtemp.php?enews=DelBqtemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&mid=<?=$modid?>&gid=<?=$gid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="4" style="text-align:left;"><?=$returnpage?></td>
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
<?
db_close();
$empire=null;
?>
