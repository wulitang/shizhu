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

//--------------------------增加内容模板
function AddNewsTemplate($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[tempname]||!$add[temptext]||!$add[modid])
	{printerror("EmptyTempname","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
    $classid=(int)$add['classid'];
	$add[tempname]=hRepPostStr($add[tempname],1);
    $add[temptext]=RepPhpAspJspcode($add[temptext]);
	$add[temptext]=RepTemplateJsUrl($add[temptext],1,0);//替换JS地址
	$add[modid]=(int)$add[modid];
	$gid=(int)$add['gid'];
	$sql=$empire->query("insert into ".GetDoTemptb("enewsnewstemp",$gid)."(tempname,temptext,showdate,modid,classid,isdefault) values('$add[tempname]','".eaddslashes2($add[temptext])."','$add[showdate]',$add[modid],$classid,0);");
	$tempid=$empire->lastid();
	//备份模板
	AddEBakTemp('newstemp',$gid,$tempid,$add[tempname],$add[temptext],0,0,'',0,$add[modid],$add[showdate],0,$classid,0,$userid,$username);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=".$tempid."<br>tempname=".$add[tempname]."&gid=$gid");
		printerror("AddNewsTempSuccess","AddNewstemp.php?enews=AddNewstemp&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//--------------------------修改内容模板
function EditNewsTemplate($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$add[tempid]=(int)$add[tempid];
	if(!$add[tempid]||!$add[tempname]||!$add[temptext]||!$add[modid])
	{printerror("EmptyTempname","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
    $classid=(int)$add['classid'];
	$add[tempname]=hRepPostStr($add[tempname],1);
    $add[temptext]=RepPhpAspJspcode($add[temptext]);
	$add[temptext]=RepTemplateJsUrl($add[temptext],1,0);//替换JS地址
	$add[modid]=(int)$add[modid];
	$gid=(int)$add['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewsnewstemp",$gid)." set tempname='$add[tempname]',temptext='".eaddslashes2($add[temptext])."',showdate='$add[showdate]',modid=$add[modid],classid=$classid where tempid='$add[tempid]'");
	//将信息设为未生成
	$mr=$empire->fetch1("select tbname from {$dbtbpre}enewsmod where mid='$add[modid]'");
	//$usql=$empire->query("update {$dbtbpre}ecms_".$mr[tbname]." set havehtml=0 where newstempid='$add[tempid]'");
	//备份模板
	AddEBakTemp('newstemp',$gid,$add[tempid],$add[tempname],$add[temptext],0,0,'',0,$add[modid],$add[showdate],0,$classid,0,$userid,$username);
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		//删除动态模板缓存文件
		DelOneTempTmpfile('text'.$add[tempid]);
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=".$add[tempid]."<br>tempname=".$add[tempname]."&gid=$gid");
		printerror("EditNewsTempSuccess","ListNewstemp.php?classid=$add[cid]&modid=$add[mid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//------------------------删除内容模板
function DelNewsTemp($tempid,$add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$tempid=(int)$tempid;
	if(!$tempid)
	{printerror("NotDelTemplateid","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$r=$empire->fetch1("select tempname,modid from ".GetDoTemptb("enewsnewstemp",$gid)." where tempid='$tempid'");
	$dotempname=$r['tempname'];
	$sql=$empire->query("delete from ".GetDoTemptb("enewsnewstemp",$gid)." where tempid='$tempid'");
	//将信息设为未生成
	$mr=$empire->fetch1("select tbname from {$dbtbpre}enewsmod where mid='$r[modid]'");
	//$usql=$empire->query("update {$dbtbpre}ecms_".$mr[tbname]." set havehtml=0 where newstempid='$tempid'");
	//删除备份
	DelEbakTempAll('newstemp',$gid,$tempid);
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		//删除动态模板缓存文件
		DelOneTempTmpfile('text'.$tempid);
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=".$tempid."<br>tempname=".$dotempname."&gid=$gid");
		printerror("DelNewsTempSuccess","ListNewstemp.php?classid=$add[cid]&modid=$add[mid]&gid=$gid".hReturnEcmsHashStrHref2(0));
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
//增加内容模板
if($enews=="AddNewstemp")
{
	AddNewsTemplate($_POST,$logininid,$loginin);
}
//修改内容模板
elseif($enews=="EditNewstemp")
{
	EditNewsTemplate($_POST,$logininid,$loginin);
}
//删除内容模板
elseif($enews=="DelNewstemp")
{
	$tempid=$_GET['tempid'];
	DelNewsTemp($tempid,$_GET,$logininid,$loginin);
}

$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$search="&gid=$gid".$ecms_hashur['ehref'];
$url=$urlgname."<a href=ListNewstemp.php?gid=$gid".$ecms_hashur['ehref'].">管理内容模板</a>";
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select tempid,tempname,modid from ".GetDoTemptb("enewsnewstemp",$gid);
$totalquery="select count(*) as total from ".GetDoTemptb("enewsnewstemp",$gid);
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
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsnewstempclass order by classid");
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理内容模板</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
//管理内容模板分类
function glnrmbfl(){
art.dialog.open('template/NewstempClass.php?<?=$ecms_hashur[ehref]?>&gid=<?=$gid?>',
    {title: '管理内容模板分类',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
<style>
.comm-table td{ height:16px; padding:8px 0;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
    <form name="form1" method="get" action="ListNewstemp.php">
  <input type=hidden name=gid value="<?=$gid?>">
        	<h3><span>限制显示： 
        <select name="classid" id="classid" onChange="document.form1.submit()">
          <option value="0">显示所有分类</option>
		  <?=$cstr?>
        </select>
        <select name="modid" id="modid" onChange="document.form1.submit()">
          <option value="0">显示所有系统模型</option>
		  <?=$mstr?>
        </select> <a href="AddNewstemp.php?enews=AddNewstemp&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="add">增加内容模板</a> <a href="javascript:void()" onclick="glnrmbfl()" class="gl">管理内容模板分类</a></span></h3></form>
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
    <td><div align="center">[<a href="ListNewstemp.php?classid=<?=$classid?>&modid=<?=$modr[mid]?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>"><?=$modr[mname]?></a>]</div></td>
    <td height="25"><div align="center"> [<a href="AddNewstemp.php?enews=EditNewstemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&mid=<?=$modid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="AddNewstemp.php?enews=AddNewstemp&docopy=1&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&mid=<?=$modid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">复制</a>] 
        [<a href="ListNewstemp.php?enews=DelNewstemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&mid=<?=$modid?>&gid=<?=$gid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
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
