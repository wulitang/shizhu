<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"class");
$enews=$_GET['enews'];
if($_GET['from'])
{
	$listclasslink="ListPageClass.php";
}
else
{
	$listclasslink="ListClass.php";
}
//批量加栏目
if($_POST)
{
require("../class/classfun.php");
require("../class/t_functions.php");
$classname=$_POST['classname'];
$classpath=$_POST['classpath'];
$classlast=$_POST['classlast'];

$zj=$_POST['zj'];
$cname_arr=explode("\r\n",$classname);
$cpath_arr=explode("\r\n",$classpath);
$clast_arr=explode("\r\n",$classlast);
foreach($cname_arr as $k=>$v){
	if($v)
	{
		
		
	if(!empty($cpath_arr[$k]))
	{
	$_POST['classpath']=$cpath_arr[$k];
	}
	else
	{
	$_POST['classpath']=ReturnPinyinFun($v);//取拼音目录
	}
	
	if(!empty($clast_arr[$k]))
	{
	$zj=$clast_arr[$k];
	}
	
	
	if($zj==1)//判断是否终极栏目
	{
	$_POST['islast']=1;
	$_POST['listtempid']=1;
	$_POST['dtlisttempid']=1;
	$_POST['newstempid']=1;
	}
	if($zj==0)
	{
	$_POST['islast']=0;
	}
	$_POST['classname']=$v;
	$_POST['pl']=1;
	AddClass($_POST,$logininid,$loginin);
	}
}
DelhcClass();
//printerror2('添加完毕!','ListClass.php');
}

function DelhcClass(){
	global $empire,$dbtbpre;
	DelListEnews();
	//删除导航缓存
	$empire->query("delete from {$dbtbpre}enewsclassnavcache");
	$cache_enews='doclass,doinfo,douserinfo,domod,dostemp';
	$cache_ecmstourl=urlencode("history.go(-1)");
	$cache_mess='DelListEnewsSuccess';
	$cache_url="CreateCache.php?enews=$cache_enews&ecmstourl=$cache_ecmstourl&mess=$cache_mess".hReturnEcmsHashStrHref2(0);
	//操作日志
	insert_dolog("");
	//printerror("DelListEnewsSuccess","history.go(-1)");
	echo'<meta http-equiv="refresh" content="0;url='.$cache_url.'">';
	db_close();
	$empire=null;
	exit();
}
$url="<a href=".$listclasslink.$ecms_hashur['whehref'].">栏目管理</a>&nbsp;>&nbsp;批量增加栏目";
$zt="";
$b_ok=1;
$hiddenclass="<script>document.getElementById('smallclass').style.display='none';document.getElementById('smallclasssetinfo').style.display='none';document.getElementById('smallclasssettemp').style.display='none';document.getElementById('smallcgtoinfo').style.display='none';</script>";
//初使化数据
$r[myorder]=0;
$r[listorder]="id DESC";
$r[reorder]="newstime DESC";
$islast="";
$filename0=" checked";
$filename1="";
$filename2="";
$openpl0=" checked";
$openpl1="";
$openadd0=" checked";
$openadd1="";
$r[classtype]=".html";
$r[filetype]=".html";
$r[newspath]="Y-m-d";
$r[link_num]="10";
$r[lencord]=25;
$read="";
$r[newline]=10;
$r[hotline]=10;
$r[goodline]=10;
$r[hotplline]=10;
$r[firstline]=10;
$r[maxnum]=0;
$r[addinfofen]=0;
$r[doctime]=0;
$r[down_num]=2;
$r[online_num]=2;
$checked=" checked";
$r['addreinfo']=1;
$defaultbclassid=" selected";
$islist="";
$islast="<input name=islast type=checkbox id=islast onclick='small_class(this.checked)' value='1'>是";
//复制栏目
$docopy=$_GET['docopy'];
if($docopy&&$enews=="AddClass")
{
	$copyclass=1;
}
$ecmsfirstpost=1;
//修改栏目
if($enews=="EditClass"||$copyclass)
{
	$ecmsfirstpost=0;
	if($copyclass)
	{
		$thisdo="复制";
	}
	else
	{
		$thisdo="修改";
	}
	$read="";
	$classid=(int)$_GET['classid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsclass where classid='$classid'");
	$addr=$empire->fetch1("select * from {$dbtbpre}enewsclassadd where classid='$classid'");
	if(!empty($r[bclassid]))
	{$defaultbclassid="";}
	$url="<a href=".$listclasslink.">管理栏目</a>&nbsp;>&nbsp;".$thisdo."栏目：".$r[classname];
	if($r[islist])
	{$islist=" checked";}
	//修改大栏目
	if(!$r[islast])
	{
		//主栏目
		if(empty($r[bclassid]))
		{
			$b_ok=1;
		}
		//中级栏目
		else
		{
			$b_ok=1;
		}
	}
	//终级栏目
	else
	{
		$hiddenclass="<script>document.getElementById('bigclasssettemp').style.display='none';document.getElementById('bigclasssetclasstext').style.display='none';</script>";
		$b_ok=0;
	}
	//终级类别
	if($r[islast])
	{
		$islast="<b>是</b>";
		$islastcheck=" checked";
	}
	else
	{
		$islast="<b>否</b>";
		$islastcheck="";
	}
	$islast.="<input type=hidden name=islast value='".$r[islast]."'>";
	if($r[filename]==1)
	{
		$filename0="";
		$filename1=" checked";
		$filename2="";
	}
	elseif($r[filename]==2)
	{
		$filename0="";
		$filename1="";
		$filename2=" checked";
	}
	else
	{}
	if($r[openpl])
	{
		$openpl0="";
		$openpl1=" checked";
	}
	if($r[checkpl])
	{
		$checkpl=" checked";
	}
	if($r[openadd])
	{
		$openadd0="";
		$openadd1=" checked";
	}
	//栏目目录
	$mycr=GetPathname($r[classpath]);
	$pripath=$mycr[1];
	$classpath=$mycr[0];
	$read="";
	//复制栏目
	if($copyclass)
	{
		$r[classname].="(1)";
		$classpath.="1";
		$read="";
		$islast="<input name=islast type=checkbox id=islast onclick='small_class(this.checked)' value='1'".$islastcheck.">是";
    }
	if($r[checked])
	{$checked=" checked";}
	else
	{$checked="";}
}
//系统模型
$m_sql=$empire->query("select mid,mname,usemod from {$dbtbpre}enewsmod order by myorder,mid");
while($m_r=$empire->fetch($m_sql))
{
	if(empty($m_r[usemod]))
	{
		if($m_r[mid]==$r[modid])
		{$m_d=" selected";}
		else
		{$m_d="";}
		$mod_options.="<option value=".$m_r[mid].$m_d.">".$m_r[mname]."</option>";
	}
	//列表模板
	$listtemp_options.="<option value=0 style='background:#99C4E3'>".$m_r[mname]."</option>";
	$dtlisttemp_options.="<option value=0 style='background:#99C4E3'>".$m_r[mname]."</option>";
	$lt_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewslisttemp")." where modid='$m_r[mid]'");
	while($lt_r=$empire->fetch($lt_sql))
	{
		//静态
		if($lt_r[tempid]==$r[listtempid])
		{$lt_d=" selected";}
		else
		{$lt_d="";}
		$listtemp_options.="<option value=".$lt_r[tempid].$lt_d."> |-".$lt_r[tempname]."</option>";
		//动态
		if($lt_r[tempid]==$r[dtlisttempid])
		{$lt_dt=" selected";}
		else
		{$lt_dt="";}
		$dtlisttemp_options.="<option value=".$lt_r[tempid].$lt_dt."> |-".$lt_r[tempname]."</option>";
	}
	//搜索模板
	$searchtemp.="<option value=0 style='background:#99C4E3'>".$m_r[mname]."</option>";
	$st_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewssearchtemp")." where modid='$m_r[mid]'");
	while($st_r=$empire->fetch($st_sql))
	{
		if($st_r[tempid]==$r[searchtempid])
		{$st_d=" selected";}
		else
		{$st_d="";}
		$searchtemp.="<option value=".$st_r[tempid].$st_d."> |-".$st_r[tempname]."</option>";
	}
	//内容模板
	$newstemp_options.="<option value=0 style='background:#99C4E3'>".$m_r[mname]."</option>";
	$nt_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewsnewstemp")." where modid='$m_r[mid]'");
	while($nt_r=$empire->fetch($nt_sql))
	{
		if($nt_r[tempid]==$r[newstempid])
		{$nt_d=" selected";}
		else
		{$nt_d="";}
		$newstemp_options.="<option value=".$nt_r[tempid].$nt_d."> |-".$nt_r[tempname]."</option>";
	}
}
//栏目
$fcfile="../data/fc/ListEnews.php";
$fcjsfile="../data/fc/cmsclass.js";
if(file_exists($fcjsfile)&&file_exists($fcfile))
{
	$options=GetFcfiletext($fcjsfile);
	$options=str_replace("<option value='$r[bclassid]'","<option value='$r[bclassid]' selected",$options);
}
else
{
	$options=ShowClass_AddClass("",$r[bclassid],0,"|-",0,0);
}
//会员组
$qgroup='';
$qgbr='';
$qgi=0;
$cgroup='';
$sql1=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($l_r=$empire->fetch($sql1))
{
	if($r[groupid]==$l_r[groupid])
	{$select=" selected";}
	else
	{$select="";}
	$group.="<option value=".$l_r[groupid].$select.">".$l_r[groupname]."</option>";
	//投稿
	$qgi++;
	if($qgi%6==0)
	{
		$qgbr='<br>';
	}
	else
	{
		$qgbr='';
	}
	$qgchecked='';
	if(strstr($r[qaddgroupid],','.$l_r[groupid].','))
	{
		$qgchecked=' checked';
	}
	$qgroup.="<input type=checkbox name=qaddgroupidck[] value='".$l_r[groupid]."'".$qgchecked.">".$l_r[groupname]."&nbsp;".$qgbr;
	//栏目页权限
	$cgchecked='';
	if(strstr($r[cgroupid],','.$l_r[groupid].','))
	{
		$cgchecked=' checked';
	}
	$cgroup.="<input type=checkbox name=cgroupidck[] value='".$l_r[groupid]."'".$cgchecked.">".$l_r[groupname]."&nbsp;".$qgbr;
}
//js模板
$jstempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsjstemp")." order by tempid");
while($jstempr=$empire->fetch($jstempsql))
{
	$select="";
	if($r[jstempid]==$jstempr[tempid])
	{
		$select=" selected";
	}
	$jstemp.="<option value='".$jstempr[tempid]."'".$select.">".$jstempr[tempname]."</option>";
}
//封面模板
$classtempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsclasstemp")." order by tempid");
while($classtempr=$empire->fetch($classtempsql))
{
	$select="";
	if($r[classtempid]==$classtempr[tempid])
	{
		$select=" selected";
	}
	$classtemp.="<option value='".$classtempr[tempid]."'".$select.">".$classtempr[tempname]."</option>";
}
//评论模板
$pltempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewspltemp")." order by tempid");
while($pltempr=$empire->fetch($pltempsql))
{
	$select="";
	if($r[pltempid]==$pltempr[tempid])
	{
		$select=" selected";
	}
	$pltemp.="<option value='".$pltempr[tempid]."'".$select.">".$pltempr[tempname]."</option>";
}
//WAP模板
$wapstyles='';
$wapstyle_sql=$empire->query("select styleid,stylename from {$dbtbpre}enewswapstyle order by styleid");
while($wapstyle_r=$empire->fetch($wapstyle_sql))
{
	$select="";
	if($r[wapstyleid]==$wapstyle_r[styleid])
	{
		$select=" selected";
	}
	$wapstyles.="<option value='".$wapstyle_r[styleid]."'".$select.">".$wapstyle_r[stylename]."</option>";
}
//预设投票
$infovotesql=$empire->query("select voteid,ysvotename from {$dbtbpre}enewsvotemod order by voteid desc");
while($infovoter=$empire->fetch($infovotesql))
{
	$select="";
	if($r[definfovoteid]==$infovoter[voteid])
	{
		$select=" selected";
	}
	$definfovote.="<option value='".$infovoter[voteid]."'".$select.">".$infovoter[ysvotename]."</option>";
}
//优化方案
$yh_options='';
$yhsql=$empire->query("select id,yhname from {$dbtbpre}enewsyh order by id");
while($yhr=$empire->fetch($yhsql))
{
	$select='';
	if($r[yhid]==$yhr[id])
	{
		$select=' selected';
	}
	$yh_options.="<option value='".$yhr[id]."'".$select.">".$yhr[yhname]."</option>";
}
//工作流
$workflows='';
$wfsql=$empire->query("select wfid,wfname from {$dbtbpre}enewsworkflow order by myorder,wfid");
while($wfr=$empire->fetch($wfsql))
{
	$select='';
	if($r[wfid]==$wfr[wfid])
	{
		$select=' selected';
	}
	$workflows.="<option value='".$wfr[wfid]."'".$select.">".$wfr[wfname]."</option>";
}
//当前使用的模板组
$thegid=GetDoTempGid();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理栏目</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="adminstyle/<?=$loginadminstyleid?>/tab.winclassic.css" disabled="disabled" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script> 
<script type="text/javascript">
	

</script>

<style type="text/css"> 
   .dynamic-tab-pane-control .tab-page { 
          width:                100%;
 } 
  .dynamic-tab-pane-control .tab-page .dynamic-tab-pane-control .tab-page { 
         height:                150px; 
 } 
  form { 
         margin:        0; 
         padding:        0; 
 } 
  /* over ride styles from webfxlayout */ 
  .dynamic-tab-pane-control h2 { 
         font-size:12px;
		 font-weight:normal;
		 text-align:        center; 
         width:                auto;
		 height:            20; 
 } 
   .dynamic-tab-pane-control h2 a { 
         display:        inline; 
         width:                auto; 
 } 
  .dynamic-tab-pane-control a:hover { 
         background: transparent; 
 } 
  </style>
 <script type="text/javascript" src="../data/images/tabpane.js"></script> <script type="text/javascript"> 
  function setLinkSrc( sStyle ) { 
         document.getElementById( "luna-tab-style-sheet" ).disabled = sStyle != "luna"; 
  
         //document.documentElement.style.background = "";
         //document.body.style.background = sStyle == "webfx" ? "white" : "ThreeDFace"; 
 } 
function chgBg(obj,color){
 if (document.all || document.getElementById)
   obj.style.backgroundColor=color;
 else if (document.layers)
   obj.bgColor=color;
}
  setLinkSrc( "luna" ); 
  </script>
  
<script>
function small_class(mycheck)
{
if(mycheck)
{
document.getElementById('smallclass').style.display="";
document.getElementById('smallclasssetinfo').style.display="";
document.getElementById('smallclasssettemp').style.display="";
document.getElementById('bigclasssettemp').style.display="none";
document.getElementById('bigclasssetclasstext').style.display="none";
document.getElementById('smallcgtoinfo').style.display="";
}
else
{
document.getElementById('smallclass').style.display="none";
document.getElementById('smallclasssetinfo').style.display="none";
document.getElementById('smallclasssettemp').style.display="none";
document.getElementById('bigclasssettemp').style.display="";
document.getElementById('bigclasssetclasstext').style.display="";
document.getElementById('smallcgtoinfo').style.display="none";
}
}

function mybclass()
{
bclass=new Array();
bclass[0]=new Array();
bclass[0][0]='';
<?
//-----------类别js数组
$psql=$empire->query("select classid,classpath from {$dbtbpre}enewsclass order by classid");
$i=0;
while($pr=$empire->fetch($psql))
{
?>
bclass[<?=$pr[classid]?>]=new Array();
bclass[<?=$pr[classid]?>][0]="<?=$pr[classpath]?>/";
<?
}
?>
}
mybclass();

function changeitem(myfrm)
{var SelectedBigId;
SelectedBigId=myfrm.bclassid.options[myfrm.bclassid.selectedIndex].value;
myfrm.pripath.value=bclass[SelectedBigId][0];
	if(myfrm.enews.value=='EditClass')
	{
		if(!myfrm.ecmsclasstype.value==1)
		{
			myfrm.classpath.focus();
		}
	}
	else
	{
		if(!myfrm.ecmsclasstype[1].checked)
		{
			myfrm.classpath.focus();
		}
	}
}

//检查
function CheckForm(obj)
{
if(obj.classname.value=="")
{
alert("请输入栏目名称");
return false;
}
if(obj.enews.value=='EditClass')
{
	if(obj.ecmsclasstype.value==1)
	{
		return true;
	}
}
else
{
	if(obj.ecmsclasstype[1].checked)
	{
		return true;
	}
}
if(obj.classpath.value=="")
{
alert("请输入栏目目录");
return false;
}
//终极栏目
if(<?=$enews=='EditClass'?'obj.islast.value==1':'obj.islast.checked'?>)
{
	if(obj.modid.value==0||obj.modid.value=="")
	{
	alert("请选择所属系统模型");
	return false;
	}
	if(obj.listtempid.value==0)
	{
	alert("请到“模板选项”选择列表模板");
	return false;
	}
	if(obj.newstempid.value==0)
	{
	alert("请到“模板选项”选择内容模板");
	return false;
	}
	if(obj.filetype.value=="")
	{
	alert("请输入信息文件扩展名");
	return false;
	}
}
//大栏目
else
{
	if(obj.islist[1].checked&&obj.listtempid.value==0)//列表式
	{
		alert("请到“模板选项”选择列表模板");
		return false;
	}
	else if(obj.islist[0].checked&&obj.classtempid.value==0)
	{
		alert("到“模板选项”选择封面模板");
		return false;
	}
	else if(obj.islist[2].checked&&obj.classtext.value=='')
	{
		alert("请到“模板选项”设置页面内容");
		return false;
	}
}
return true;
}

//修改绑定信息
function EditBdInfo(obj){
	var infoid=obj.bdinfoid.value;
	var r;
	r=infoid.split(',');
	if(infoid==''||r.length==1)
	{
		alert('请输入绑定信息ID');
		return false;
	}
	window.open('AddNews.php?<?=$ecms_hashur['ehref']?>&enews=EditNews&classid='+r[0]+'&id='+r[1]);
}
</script>
<script>
//管理系统模型
function glxtmx(){
art.dialog.open('db/ListTable.php<?=$ecms_hashur['whehref']?>',
    {title: '管理系统模型',width: 800, height: 540});
}
//管理优化方案
function glyh(){
art.dialog.open('db/ListYh.php<?=$ecms_hashur[whehref]?>',
    {title: '管理优化方案',width: 800, height: 540});
}
//管理预设投票
function glystp(){
art.dialog.open('other/ListVoteMod.php<?=$ecms_hashur[whehref]?>',
    {title: '管理预设投票',width: 800, height: 540});
}
//管理封面模板
function glfmmb(){
art.dialog.open('template/ListClasstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理封面模板',width: 800, height: 540});
}
//管理列表模板
function gllbmb(){
art.dialog.open('template/ListListtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理列表模板',width: 800, height: 540});
}
//管理内容模板
function glnrmb(){
art.dialog.open('template/ListNewstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理内容模板',width: 800, height: 540});
}
//管理评论模板
function glplmb(){
art.dialog.open('template/ListPltemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理评论模板',width: 800, height: 540});
}
//管理搜索模板
function glssmb(){
art.dialog.open('template/ListSearchtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理搜索模板',width: 800, height: 540});
}
//管理WAP模板
function glwapmb(){
art.dialog.open('template/ListSearchtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理WAP模板',width: 800, height: 540});
}
//管理内容关键字
function glnrgjz(){
art.dialog.open('NewsSys/key.php<?=$ecms_hashur[whehref]?>',
    {title: '管理内容关键字',width: 800, height: 540});
}
//管理JS模板
function gljsmb(){
art.dialog.open('template/ListJstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理JS模板',width: 800, height: 540});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div class="kongbai"></div>
   <form name="form1" method="post" action="" onSubmit="return CheckForm(document.form1);">
    <input type=hidden name=enews value=<?=$enews?>>
    <div id="tab"  style="padding-bottom:50px;_margin-bottom:100px;">
	<div class="ui-tab-container">
	<ul class="clearfix ui-tab-list">
		<li class="ui-tab-active">1.基本属性</li>
		<li>2.栏目选项</li>
		<li>3.选择模板</li>
        <li>4.生成文章</li>
        <li>JS调用设置</li>
	</ul>
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
			<h3><span>批量增加栏目</span></h3>
            <div class="line"></div>
            <ul>
            	<li><label>所属父栏目</label><select name="bclassid" size="12" id="bclassid" onchange='javascript:changeitem(document.form1);' style="width:320px">
              <option value="0"<?=$defaultbclassid?>>根栏目</option>
              <?=$options?>
            </select></li>
            
                <li>
<input name="oldbclassid" type="hidden" id="oldbclassid" value="<?=$r[bclassid]?>"> 
            <input name="classid" type="hidden" id="classid" value="<?=$classid?>"> 
            <input name="oldclassname" type="hidden" id="oldclassname" value="<?=$r[classname]?>"> 
            <input name="oldislast" type="hidden" id="oldislast" value="<?=$r[islast]?>"> 
            <input name="ecmsclasstype" type="hidden" value="0">
            <input name="pripath" type="hidden" id="pripath" value="<?=$pripath?>" size="30">
              <input name="oldclasspath" type="hidden" id="oldclasspath" value="<?=$r[classpath]?>"> 
<input name="oldcpath" type="hidden" id="oldcpath" value="<?=$classpath?>">
                <label>所属父栏目</label>
<style>
.fltable td{ padding:5px;}
.blue{ color:#5C7BDD;}
</style>
 <table width="100" border="0" cellspacing="0" cellpadding="0" align="left" class="fltable">
  <tr>
    <td>栏目名称</td>
    <td>栏目目录</td>
    <td>是否终极</td>
  </tr>
  <tr>
    <td valign="top"><textarea name="classname" cols="26" rows="10" id="classname"></textarea></td>
    <td valign="top"><textarea name="classpath" cols="26" rows="10" id="classpath"></textarea></td>
    <td valign="top"><textarea name="classlast" cols="8" rows="10" id="classlast"></textarea></td>
  </tr>
  <tr><td colspan="3"><input type="radio" value="1" name="zj"/> 全终级栏目 <input type="radio" value="0" name="zj"/> 全父级栏目</td></tr>
</table></li>
                <li>
                <label>绑定的系统模型</label><select name="modid" id="modid">
                <?=$mod_options?>
              </select></li>
<li><label>帮助信息</label>栏目名称：<span class="blue">一行一个，不得包含特殊字符</span><br /><label>&nbsp;</label>栏目目录：<span class="blue">一行一个，不得包含特殊字符</span><br /><label>&nbsp;</label>注：<span class="blue">栏目目录和栏目名称为一一对应，没有对应则以栏目名称的全拼</span><br /><label>&nbsp;</label>是否终极：<span class="blue">一行一个，1表示终极栏目，0表示父栏目,没有表示父级</span></li>

             <div class="clear"></div>
            </ul>
            
        </div>
		<div class="ui-tab-content" style="display:none">
        	<h3><span>栏目选项</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>是否显示到导航</label><input type="radio" name="showclass" value="0"<?=$r[showclass]==0?' checked':''?>>
            <i>显示</i> 
            <input type="radio" name="showclass" value="1"<?=$r[showclass]==1?' checked':''?>>
            <i>不显示</i><font color="#666666">（如：导航标签，地图标签）</font></li>
            <li class="jqui"><label>显示排序</label><input name="myorder" type="text" id="myorder" value="<?=$r[myorder]?>"> 
            <font color="#666666">(值越小越前面)</font></li>
            <li><label>栏目访问权限</label><?=$cgroup?> </li>
            <div>
            <li><label>&nbsp;</label><input name="cgtoinfo" type="checkbox" id="cgtoinfo" value="1"<?=$r[cgtoinfo]?' checked':''?>>
            访问权限应用于信息<font color="#666666">(选择后信息的查看权限可以不设置)</font></li>
            </div>
            <li class="jqui"><label>开放投稿</label> 
             <input type="radio" name="openadd" value="0"<?=$openadd0?>>
            <i>开启</i> 
            <input type="radio" name="openadd" value="1"<?=$openadd1?>>
            <i>关闭</i> 
            <input name="oldopenadd" type="hidden" id="oldopenadd" value="<?=$r[openadd]?>"></li>
            </ul>
            <div>
            <h3><span>前台投稿设置</span></h3>
            <ul>
            <li class="jqui"><label>验证码</label> 
              <i>开启验证码:</i> 
              <input name="qaddshowkey" type="checkbox" id="qaddshowkey2" value="1"<?=$r['qaddshowkey']==1?' checked':''?>></li>
            <li><label>投稿权限</label><?=$qgroup?><font color="#666666">(不选为不限)</font>
            </li>
            <li><label>投稿生成列表</label><select name="qaddlist" id="qaddlist">
                  <option value="0"<?=$r['qaddlist']==0?' selected':''?>>不生成</option>
                  <option value="1"<?=$r['qaddlist']==1?' selected':''?>>生成当前栏目</option>
                  <option value="2"<?=$r['qaddlist']==2?' selected':''?>>生成首页</option>
                  <option value="3"<?=$r['qaddlist']==3?' selected':''?>>生成父栏目</option>
                  <option value="4"<?=$r['qaddlist']==4?' selected':''?>>生成当前栏目与父栏目</option>
                  <option value="5"<?=$r['qaddlist']==5?' selected':''?>>生成父栏目与首页</option>
                  <option value="6"<?=$r['qaddlist']==6?' selected':''?>>生成当前栏目、父栏目与首页</option>
                </select></li>
            <li class="jqui"><label>投稿审核</label><input type="radio" name="checkqadd" value="0"<?=$r['checkqadd']==0?' checked':''?>>
              <i>需要审核</i> 
              <input type="radio" name="checkqadd" value="1"<?=$r['checkqadd']==1?' checked':''?>>
              <i>无需审核</i></li>
            <li class="jqui"><label>发布信息增加</label><input name="addinfofen" type="text" id="addinfofen2" value="<?=$r[addinfofen]?>" size="6">
              <i>点数</i> <font color="#666666">(不增加请设为0,扣点请设为负数)</font></li>
            <li><label>管理投稿</label><select name="adminqinfo" id="adminqinfo">
                <option value="0"<?=$r['adminqinfo']==0?' selected':''?>>不能管理信息</option>
                <option value="1"<?=$r['adminqinfo']==1?' selected':''?>>可管理未审核信息</option>
                <option value="2"<?=$r['adminqinfo']==2?' selected':''?>>只可编辑未审核信息</option>
                <option value="3"<?=$r['adminqinfo']==3?' selected':''?>>只可删除未审核信息</option>
                <option value="4"<?=$r['adminqinfo']==4?' selected':''?>>可管理所有信息</option>
                <option value="5"<?=$r['adminqinfo']==5?' selected':''?>>只可编辑所有信息</option>
                <option value="6"<?=$r['adminqinfo']==6?' selected':''?>>只可删除所有信息</option>
              </select>
              <input name="qeditchecked" type="checkbox" id="qeditchecked" value="1"<?=$r['qeditchecked']==1?' checked':''?>>
              编辑信息需要审核</li>
            </ul>
            
            <h3><span>后台信息发布设置</span></h3>
                       <ul>
            <li><label>增加/编辑信息</label> 
              <input name="addreinfo" type="checkbox" id="addreinfo" value="1"<?=$r['addreinfo']==1?' checked':''?>>
              生成内容页；生成列表： 
              <select name="haddlist" id="haddlist">
                <option value="0"<?=$r['haddlist']==0?' selected':''?>>不生成</option>
                <option value="1"<?=$r['haddlist']==1?' selected':''?>>生成当前栏目</option>
                <option value="2"<?=$r['haddlist']==2?' selected':''?>>生成首页</option>
                <option value="3"<?=$r['haddlist']==3?' selected':''?>>生成父栏目</option>
                <option value="4"<?=$r['haddlist']==4?' selected':''?>>生成当前栏目与父栏目</option>
                <option value="5"<?=$r['haddlist']==5?' selected':''?>>生成父栏目与首页</option>
                <option value="6"<?=$r['haddlist']==6?' selected':''?>>生成当前栏目、父栏目与首页</option>
              </select></li>
            <li><label>&nbsp;</label><input name="repreinfo" type="checkbox" id="repreinfo2" value="1"<?=$r[repreinfo]==1?' checked':''?>>
              生成上一篇信息
            </li>
            <li><label>&nbsp;</label><input name="sametitle" type="checkbox" id="sametitle" value="1"<?=$r['sametitle']==1?' checked':''?>>
              检测标题重复
            </li>
            <li class="jqui"><label>审核设置</label><input name="checked" type="checkbox" id="checked" value="1"<?=$checked?>>
              <i>直接审核</i></li>
            <li><label>使用工作流</label><select name="wfid" id="wfid">
                <option value="0">不使用工作流</option>
                <?=$workflows?>
              </select></li>
            <li><label>信息预设投票</label><select name="definfovoteid" id="definfovoteid">
                <option value="0">不设置</option>
                <?=$definfovote?>
              </select> <input type="button" name="Submit622" value="管理预设投票" onClick="glystp()" class="anniu"> 
              <font color="#666666">(增加信息时默认的投票项)</font></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25">默认查看信息权限</td>
            <td><select name="groupid" id="groupid">
                <option value="0">游客</option>
                <?=$group?>
              </select> <font color="#666666">(增加信息时默认的会员组权限)</font></li>
            </ul>
            <h3><span>其他设置</span></h3>
            <ul>
            	<li class="jqui"><label>评论功能</label><input type="radio" name="openpl" value="0"<?=$openpl0?>>
              <i>开启</i> 
              <input type="radio" name="openpl" value="1"<?=$openpl1?>>
              <i>关闭，评论需要审核: </i> 
              <input name="checkpl" type="checkbox" id="checkpl2" value="1"<?=$checkpl?>>
              <i>是</i> 
            </li>
            <li class="jqui"><label>信息归档</label><i>归档大于</i> 
              <input name="doctime" type="text" id="doctime" value="<?=$r[doctime]?>" size="6">
              <i>天的信息</i><font color="#666666">(0为不归档)</font>
            </li>
            </ul>
            <h3><span>特殊模型设置</span></h3>
            <ul>
            	<li class="jqui"><label>下载/影视模型</label><i>每行显示</i> 
              <input name="down_num" type="text" id="link_num3" value="<?=$r[down_num]?>" size="5">
              <i>个下载地址，</i> 
              <input name="online_num" type="text" id="down_num" value="<?=$r[online_num]?>" size="5">
              <i>个在线观看地址</i>
            </li>
            </ul>
            </div>
        </div>
		<div class="ui-tab-content" style="display:none">
        	<h3><span>模版设置</span></h3>
            <div class="line"></div>
                <ul>
                <div id="bigclasssettemp">
                <li class="jqui"><label>页面显示模式</label>
                <input type="radio" name="islist" value="0"<?=$r[islist]==0?' checked':''?>>
              <i>封面式</i> 
              <input type="radio" name="islist" value="1"<?=$r[islist]==1?' checked':''?>>
              <i>列表式</i> 
              <input type="radio" name="islist" value="2"<?=$r[islist]==2?' checked':''?>>
              <i>页面内容式</i> 
              <input type="radio" name="islist" value="3"<?=$r[islist]==3?' checked':''?> onClick="bdinfo.style.display='';">
              <i>栏目绑定信息</i> 
              <input name="oldislist" type="hidden" id="oldislist" value="<?=$r[islist]?>"></li>
              <li><label>&nbsp;</label><font color="#666666">说明：封面式要选择封面模板、列表式要选择列表模板、内容式要录入页面内容</font></li>
              <?php
		  	$bdinfostyle=$r[islist]==3?'':' style="display:none"';
		  	?>
              		<li id="bdinfo"<?=$bdinfostyle?>><label>&nbsp;</label>绑定信息ID：<input name="bdinfoid" type="text" id="bdinfoid" value="<?=$r[bdinfoid]?>">
              <a href="#empirecms" onClick="EditBdInfo(document.form1);">[修改信息]</a>
              <font color="#666666">(格式：栏目ID,信息ID)</font></li>
                    <li><label>封面模板</label><select name="classtempid">
                <?=$classtemp?>
              </select> <input type="button" name="Submit6223" value="管理封面模板" onClick="glfmmb()"></li>
              </div>
                    <li><label>所属列表模板</label>静态： 
            <select name="listtempid" id="listtempid">
              <?=$listtemp_options?>
            </select>
            ，动态： 
            <select name="dtlisttempid">
              <?=$dtlisttemp_options?>
            </select> <input type="button" name="Submit6222" value="管理列表模板" onClick="gllbmb()"></li>
            <div>
            <li><label>所属内容模板</label><select name="newstempid" id="newstempid">
                <?=$newstemp_options?>
              </select> <input type="button" name="Submit62222" value="管理内容模板" onClick="glnrmb()" class="anniu">
              *( 
              <input name="tobetempinfo" type="checkbox" id="tobetempinfo" value="1">
              应用于已生成的信息 )</li>
              <li><label>所属评论模板</label><select name="pltempid" id="pltempid">
                <option value="0">使用默认模板 </option>
                <?=$pltemp?>
              </select> <input type="button" name="Submit62" value="管理评论模板" onClick="glplmb()" class="anniu"></li>
               </div>
              <li><label>搜索模板</label><select name="searchtempid" id="searchtempid">
              <option value="0">使用默认模板 </option>
              <?=$searchtemp?>
            </select> <input type="button" name="Submit62" value="管理搜索模板" onClick="glssmb()" class="anniu"></li>
              <li><label>WAP模板</label><select name="wapstyleid" id="wapstyleid">
              <option value="0">使用默认模板</option>
              <?=$wapstyles?>
            </select> <input type="button" name="Submit623" value="管理WAP模板" onClick="glwapmb()" class="anniu">
            ( 
            <input name="wapstylesclass" type="checkbox" id="wapstylesclass" value="1">
            应用于子栏目)</li>
            <div>
              <li class="jqui"><label>页面内容</label>请将内容<a href="#ecms" onClick="window.clipboardData.setData('Text',document.form1.classtext.value);document.form1.classtext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onClick="window.open('template/editor.php?getvar=opener.document.form1.classtext.value&returnvar=opener.document.form1.classtext.value&fun=ReturnHtml<?=$ecms_hashur['ehref']?>','editclasstext','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑<font color="#666666">(支持标签同封面模板)</font></li>
              <li><label>&nbsp;</label><textarea name="classtext" cols="80" rows="23" id="classtext" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($addr[classtext]))?></textarea></li>
              </div>               
            </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>生成设置</span></h3>
            <div class="line"></div>
            <ul>
                	<li class="jqui"><label>栏目页模式</label><input type="radio" name="listdt" value="0"<?=$r[listdt]==0?' checked':''?>>
            <i>静态页面</i> 
            <input type="radio" name="listdt" value="1"<?=$r[listdt]==1?' checked':''?>>
            <i>动态页面</i> 
            <input name="oldlistdt" type="hidden" id="oldlistdt" value="<?=$r[listdt]?>"></li>
                    <li class="jqui"><label>内容页模式</label><input type="radio" name="showdt" value="0"<?=$r[showdt]==0?' checked':''?>>
            <i>静态页面</i>  
            <input type="radio" name="showdt" value="1"<?=$r[showdt]==1?' checked':''?>>
            <i>动态生成</i> <font color="#666666"> 
            <input type="radio" name="showdt" value="2"<?=$r[showdt]==2?' checked':''?>>
            </font><i>动态页面</i></li>
                    <li><label>管理信息排序方式</label><input name="listorder" type="text" id="listorder" value="<?=$r[listorder]?>" size="20"> 
            <select name="lorderselect" onChange="document.form1.listorder.value=this.value">
              <option value="id DESC"></option>
              <option value="newstime DESC">按发布时间降序排序</option>
              <option value="id DESC">按ID降序排序</option>
              <option value="onclick DESC">按点击率降序排序</option>
              <option value="totaldown DESC">按下载数降序排序</option>
              <option value="plnum DESC">按评论数降序排序</option>
            </select></li>
                    <li><label>列表式页面排序方式</label><input name="reorder" type="text" id="reorder" value="<?=$r[reorder]?>" size="20">
            <select name="orderselect" onChange="document.form1.reorder.value=this.value">
              <option value="newstime DESC"></option>
              <option value="newstime DESC">按发布时间降序排序</option>
              <option value="id DESC">按ID降序排序</option>
              <option value="onclick DESC">按点击率降序排序</option>
              <option value="totaldown DESC">按下载数降序排序</option>
              <option value="plnum DESC">按评论数降序排序</option>
            </select></li>
                    <li><label>是否生成</label><input name="nreclass" type="checkbox" value="1"<?=$r[nreclass]==1?' checked':''?>>
            不生成栏目页， 
            <input name="nreinfo" type="checkbox" value="1"<?=$r[nreinfo]==1?' checked':''?>>
            不生成内容页， 
            <input name="nrejs" type="checkbox" value="1"<?=$r[nrejs]==1?' checked':''?>>
            不生成JS调用， 
            <input name="nottobq" type="checkbox" value="1"<?=$r[nottobq]==1?' checked':''?>>
            标签不调用</li>
                    <li><label>栏目文件扩展名</label><input name="classtype" type="text" id="classtype" value="<?=$r[classtype]?>" size="20"> 
            <select name="select" onChange="document.form1.classtype.value=this.value">
              <option value=".html">扩展名</option>
              <option value=".html">.html</option>
              <option value=".htm">.htm</option>
              <option value=".php">.php</option>
              <option value=".shtml">.shtml</option>
            </select> <input name="oldclasstype" type="hidden" id="oldclasstype" value="<?=$r[classtype]?>"> 
            <font color="#666666">(如.html,.xml,.htm等)</font></li>
                    <li><label>显示总记录数</label><input name="maxnum" type="text" id="maxnum" value="<?=$r[maxnum]?>" size="20">
            条<font color="#666666">(0为显示所有记录)</font></li>
                    <li><label>生成静态页数</label><input name="repagenum" type="text" id="repagenum" value="<?=$r[repagenum]?>" size="20">
            页<font color="#666666">(超过分页采用动态链接，0为不限)</font></li>
                    <li><label>生成信息每页显示</label><input name="lencord" type="text" id="lencord" value="<?=$r[lencord]?>" size="20">
            条记录 
            <input name="oldlencord" type="hidden" id="oldlencord3" value="<?=$r[lencord]?>"></li>
            		<div id="smallclasssetinfo">
             		<li><label>相关链接显示</label><input name="link_num" type="text" id="link_num" value="<?=$r[link_num]?>" size="20">
              条记录<font color="#666666">(0为不生成相关链接)</font></li>
                    <li><label>内容页存放目录</label><input type="radio" name="infopath" value="0"<?=$r[ipath]==''?' checked':''?>>
              栏目目录 
              <input type="radio" name="infopath" value="1"<?=$r[ipath]<>''?' checked':''?>>
              自定义： / 
              <input name="ipath" type="text" id="ipath" value="<?=$r[ipath]?>"> 
              <font color="#666666">(从根目录开始)</font></li>
                    <li><label>内容页目录存放形式</label><input name="newspath" type="text" id="newspath" value="<?=$r[newspath]?>" size="20"> 
              <select name="select2" onChange="document.form1.newspath.value=this.value">
                <option value="Y-m-d">选择</option>
                <option value="Y-m-d">2005-01-27</option>
                <option value="Y/m-d">2005/01-27</option>
                <option value="Y/m/d">2005/01/27</option>
                <option value="Ymd">20050127</option>
                <option value="">不设置目录</option>
              </select> <font color="#666666">(如Y-m-d，Y/m-d等形式)</font></li>
                    <li><label>内容页文件命名形式</label>[前缀] 
              <input name="filename_qz" type="text" id="filename_qz" value="<?=$r[filename_qz]?>" size="15">
              命名: 
              <input type="radio" name="filename" value="0"<?=$r[filename]==0?' checked':''?>>
              <a title="信息ID：1.html">信息ID</a> 
              <input type="radio" name="filename" value="1"<?=$r[filename]==1?' checked':''?>>
              <a title="unix时间戳+信息ID：12102462981.html">time()</a> 
              <input type="radio" name="filename" value="4"<?=$r[filename]==4?' checked':''?>>
              <a title="日期+信息ID：201210011.html">date()</a>
              <input type="radio" name="filename" value="5"<?=$r[filename]==5?' checked':''?>>
              <a title="各表信息在同一个目录不会重复：1000010000000001.html">公共信息ID</a> 
              <input type="radio" name="filename" value="2"<?=$r[filename]==2?' checked':''?>>
              <a title="MD5加密地址：c4ca4238a0b923820dcc509a6f75849b.html">md5()</a> 
              <input type="radio" name="filename" value="3"<?=$r[filename]==3?' checked':''?>>
              <a title="信息ID目录：/1/">目录</a></li>
                    <li><label>内容页文件扩展名</label><input name="filetype" type="text" id="filetype" value="<?=$r[filetype]?>" size="20"> 
              <select name="select3" onChange="document.form1.filetype.value=this.value">
                <option value=".html">扩展名</option>
                <option value=".html">.html</option>
                <option value=".htm">.htm</option>
                <option value=".php">.php</option>
                <option value=".shtml">.shtml</option>
              </select> <font color="#666666">(如.html,.xml,.htm等)</font></li>
                    <li><label>内容关键字替换</label><select name="keycid" id="keycid">
                <option value="0"<?=$r['keycid']==0?' selected':''?>>替换所有</option>
                <option value="-1"<?=$r['keycid']==-1?' selected':''?>>不替换</option>
				<?php
				$keycsql=$empire->query("select classid,classname from {$dbtbpre}enewskeyclass");
				while($keycr=$empire->fetch($keycsql))
				{
				?>
					<option value="<?=$keycr['classid']?>"<?=$r['keycid']==$keycr['classid']?' selected':''?>><?=$keycr['classname']?></option>
				<?php
				}
				?>
              </select>
              <input type="button" name="Submit6232" value="管理内容关键字" onClick="glnrgjz()" class="anniu"></li>
                    </div>
            </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>JS调用相关设置</span></h3>
            <div class="line"></div>
            <ul>
                	<li><label>所用JS模板</label><select name="jstempid" id="jstempid">
	  <?=$jstemp?>
        </select>
            <input type="button" name="Submit62223" value="管理JS模板" onClick="gljsmb()" class="anniu"></li>
                    <li><label>最新信息JS显示</label><input name="newline" type="text" id="newline" value="<?=$r[newline]?>" size="20">
            条记录</li>
                    <li><label>热门信息JS显示</label><input name="hotline" type="text" id="hotline" value="<?=$r[hotline]?>" size="20">
            条记录</li>
                    <li><label>推荐信息JS显示</label><input name="goodline" type="text" id="goodline" value="<?=$r[goodline]?>" size="20">
            条记录</li>
                    <li><label>热门评论信息JS显示</label><input name="hotplline" type="text" id="hotplline" value="<?=$r[hotplline]?>" size="20">
            条记录</li>
              		<li><label>头条信息JS显示</label><input name="firstline" type="text" id="firstline" value="<?=$r[firstline]?>" size="20">
            条记录</li>
            </ul>
        </div>
  <?php
  $classfnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsclassf");
  if($classfnum)
  {
  	$editorfnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsclassf where fform='editor' limit 1");	
	if($editorfnum)
	{
		include('ecmseditor/infoeditor/fckeditor.php');
	}
  ?>
  <div class="ui-tab-content" style="display:none">
        	<h3><span>自定义字段设置</span></h3>
            <div class="line"></div>
            <ul>
                	<li>
		<?php
		@include('../data/html/classaddform.php');
		?></li>
                    <li><strong>栏目自定义字段调用说明</strong><br>
            内置调用栏目自定义字段函数：ReturnClassAddField(栏目ID,字段名)，栏目ID=0为当前栏目ID。取多个字段内容可用逗号隔开，例子：<br>
            取得'classtext'字段内容：$value=ReturnClassAddField(0,'classtext'); //$value就是字段内容。<br>
            取得多个字段内容：$value=ReturnClassAddField(1,'classid,classtext'); //$value['classtext']才是字段内容。</li>
            </ul>
        </div>
    <?php
  }
  ?>
 <div class="line"></div>
  </div>
 <div class="sub jqui">
 <input type=hidden name=from value="<?=$_GET['from']?>">
  <input type="submit" name="Submit" value="提交">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="reset" name="Submit2" value=" 重置 "></div>
 <!--[if lte IE 6]>
 <div class="floatsub"> <input type="submit" name="Submit" value="提交" class="shezhi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" name="Submit2" value="重 置" class="chongzhi"></div>
 <![endif]-->
 </div>
</div>
 </form>
 <div class="clear"></div>
<?=$hiddenfileserver?>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>
<?=$hiddenclass?>
