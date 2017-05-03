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

//增加封面模板
function AddClasstemp($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyClasstempname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$classid=(int)$add['classid'];
	$gid=(int)$add['gid'];
	$add[tempname]=hRepPostStr($add[tempname],1);
	$add[temptext]=RepPhpAspJspcode($add[temptext]);
	$sql=$empire->query("insert into ".GetDoTemptb("enewsclasstemp",$gid)."(tempname,temptext,classid) values('$add[tempname]','".eaddslashes2($add[temptext])."',$classid);");
	$tempid=$empire->lastid();
	//备份模板
	AddEBakTemp('classtemp',$gid,$tempid,$add[tempname],$add[temptext],0,0,'',0,0,'',0,$classid,0,$userid,$username);
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("AddClasstempSuccess","AddClasstemp.php?enews=AddClasstemp&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改封面模板
function EditClasstemp($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$tempid=(int)$add['tempid'];
	if(!$tempid||!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyClasstempname","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$classid=(int)$add['classid'];
	$gid=(int)$add['gid'];
	$add[tempname]=hRepPostStr($add[tempname],1);
	$add[temptext]=RepPhpAspJspcode($add[temptext]);
	$sql=$empire->query("update ".GetDoTemptb("enewsclasstemp",$gid)." set tempname='$add[tempname]',temptext='".eaddslashes2($add[temptext])."',classid=$classid where tempid=$tempid");
	//备份模板
	AddEBakTemp('classtemp',$gid,$tempid,$add[tempname],$add[temptext],0,0,'',0,0,'',0,$classid,0,$userid,$username);
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		//删除动态模板缓存文件
		DelOneTempTmpfile('classtemp'.$tempid);
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("EditClasstempSuccess","ListClasstemp.php?classid=$add[cid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除封面模板
function DelClasstemp($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$tempid=(int)$add['tempid'];
	if(!$tempid)
	{
		printerror("EmptyClasstempid","history.go(-1)");
    }
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$r=$empire->fetch1("select tempname from ".GetDoTemptb("enewsclasstemp",$gid)." where tempid=$tempid");
	$sql=$empire->query("delete from ".GetDoTemptb("enewsclasstemp",$gid)." where tempid=$tempid");
	//删除备份记录
	DelEbakTempAll('classtemp',$gid,$tempid);
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		//删除动态模板缓存文件
		DelOneTempTmpfile('classtemp'.$tempid);
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$r[tempname]&gid=$gid");
		printerror("DelClasstempSuccess","ListClasstemp.php?classid=$add[cid]&gid=$gid".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//操作
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include("../../class/tempfun.php");
}
//增加模板
if($enews=="AddClasstemp")
{
	AddClasstemp($_POST,$logininid,$loginin);
}
//修改模板
elseif($enews=="EditClasstemp")
{
	EditClasstemp($_POST,$logininid,$loginin);
}
//删除模板
elseif($enews=="DelClasstemp")
{
	DelClasstemp($_GET,$logininid,$loginin);
}
else
{}
$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$url=$urlgname."<a href=ListClasstemp.php?gid=$gid".$ecms_hashur['ehref'].">管理封面模板</a>";
$search="&gid=$gid".$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select tempid,tempname from ".GetDoTemptb("enewsclasstemp",$gid);
$totalquery="select count(*) as total from ".GetDoTemptb("enewsclasstemp",$gid);
//类别
$add="";
$classid=(int)$_GET['classid'];
if($classid)
{
	$add=" where classid=$classid";
	$search.="&classid=$classid";
}
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by tempid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsclasstempclass order by classid");
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
<title>管理封面模板</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
//增加封面模板
function zjfmmb(){
art.dialog.open('template/AddClasstemp.php?<?=$ecms_hashur[ehref]?>&enews=AddClasstemp&gid=<?=$gid?>',
    {title: '增加封面模板',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理封面模板分类
function glfmmbfl(){
art.dialog.open('template/ClassTempClass.php?<?=$ecms_hashur[ehref]?>&gid=<?=$gid?>',
    {title: '管理封面模板分类',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<form name="form1" method="get" action="ListClasstemp.php">
 <?=$ecms_hashur['eform']?>
<input type=hidden name=gid value="<?=$gid?>">
        	<h3><span>限制显示：<select name="classid" id="classid" onChange="document.form1.submit()">
          <option value="0">显示所有分类</option>
		  <?=$cstr?>
        </select> <a href="javascript:void()" onclick="zjfmmb()" class="add">增加封面模板</a> <a href="javascript:void()" onclick="glfmmbfl()" class="gl">管理封面模板分类</a></span></h3></form>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>模板名</th>
            <th>操作</th>
		</tr>
  <?php
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r[tempid]?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r[tempname]?>
      </div></td>
    <td height="25"><div align="center"> [<a href="AddClasstemp.php?enews=EditClasstemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="AddClasstemp.php?enews=AddClasstemp&docopy=1&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">复制</a>] 
        [<a href="ListClasstemp.php?enews=DelClasstemp&tempid=<?=$r[tempid]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="7" style="text-align:left;"><?=$returnpage?></td>
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
<?php
db_close();
$empire=null;
?>
