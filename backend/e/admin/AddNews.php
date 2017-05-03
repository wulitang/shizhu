<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../data/dbcache/class.php");
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

$enews=ehtmlspecialchars($_GET['enews']);
$classid=(int)$_GET['classid'];
if(empty($class_r[$classid][classid]))
{
	printerror("ErrorUrl","history.go(-1)");
}
//验证权限
$doselfinfo=CheckLevel($logininid,$loginin,$classid,"news");
if(!$class_r[$classid][tbname]||!$class_r[$classid][classid])
{
	printerror("ErrorUrl","history.go(-1)");
}
//非终极栏目
if(!$class_r[$classid]['islast'])
{
	printerror("AddInfoErrorClassid","history.go(-1)");
}
$fun_r['AdminInfo']='管理信息';
$bclassid=$class_r[$classid][bclassid];
$id=(int)$_GET['id'];
//附件验证码
if($enews=="AddNews")
{
	if(!$doselfinfo['doaddinfo'])//增加权限
	{
		printerror("NotAddInfoLevel","history.go(-1)");
	}
	$filepass=time();
	$word='增加信息';
	$ecmsfirstpost=1;
}
else
{
	if(!$doselfinfo['doeditinfo'])//编辑权限
	{
		printerror("NotEditInfoLevel","history.go(-1)");
	}
	$filepass=$id;
	$word='修改信息';
	$ecmsfirstpost=0;
}
//审核
$ecmscheck=(int)$_GET['ecmscheck'];
$addecmscheck='';
$indexchecked=1;
if($ecmscheck)
{
	$addecmscheck='&ecmscheck='.$ecmscheck;
	$indexchecked=0;
}
//模型
$modid=$class_r[$classid][modid];
$enter=$emod_r[$modid]['enter'];
//导航
$url=AdminReturnClassLink($classid).'&nbsp;>&nbsp;'.$word;
//会员组
$sql1=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($l_r=$empire->fetch($sql1))
{
	$ygroup.="<option value=".$l_r[groupid].">".$l_r[groupname]."</option>";
}
if($enews=="AddNews")
{
	$group=str_replace(" value=".$class_r[$classid][groupid].">"," value=".$class_r[$classid][groupid]." selected>",$ygroup);
}
//初始化数据
$r=array();
$newstime=time();
$r[newstime]=date("Y-m-d H:i:s");
$todaytime=$r[newstime];
$r[checked]=$class_r[$classid][checked];
$r[newspath]=date($class_r[$classid][newspath]);
$r[onclick]=0;
$r[userfen]=0;
$titlefontb="";
$titlefonti="";
$titlefonts="";
$voteeditnum=8;
$voter[width]=500;
$voter[height]=300;
$voter[dotime]='0000-00-00';
$r[dokey]=1;
$titleurl='';
if($public_r['onclickrnd'])
{
	$onclick_rndr=explode(',',$public_r['onclickrnd']);
	$r[onclick]=rand(intval($onclick_rndr[0]),intval($onclick_rndr[1]));
	$r[totaldown]=$r[onclick];
}
else
{
	$r[totaldown]=0;
	$r[onclick]=0;
}
//----------- 特殊模型初始化 -----------
//下载地址前缀
if(strstr($enter,',downpath,')||strstr($enter,',onlinepath,'))
{
	$downurlqz="";
	$newdownqz="";
	$downsql=$empire->query("select urlname,url,urlid from {$dbtbpre}enewsdownurlqz order by urlid");
	while($downr=$empire->fetch($downsql))
	{
		$downurlqz.="<option value='".$downr[url]."'>".$downr[urlname]."</option>";
		$newdownqz.="<option value='".$downr[urlid]."'>".$downr[urlname]."</option>";
	}
}
//html编辑器
if($emod_r[$modid]['editorf']&&$emod_r[$modid]['editorf']!=',')
{
	include('ecmseditor/infoeditor/fckeditor.php');
}

//预设投票
if($enews=="AddNews")
{
	$infoclassr=$empire->fetch1("select definfovoteid from {$dbtbpre}enewsclass where classid='$classid'");
	$definfovoteid=0;
	if($infoclassr['definfovoteid'])
	{
		$definfovoteid=$infoclassr['definfovoteid'];
	}
	elseif($emod_r[$modid]['definfovoteid'])
	{
		$definfovoteid=$emod_r[$modid]['definfovoteid'];
	}
	if($definfovoteid)
	{
		//投票
		$voter=$empire->fetch1("select * from {$dbtbpre}enewsvotemod where voteid='$definfovoteid'");
		if($voter['voteid']&&$voter[votetext])
		{
			$d_record=explode("\r\n",$voter[votetext]);
			for($i=0;$i<count($d_record);$i++)
			{
				$j=$i+1;
				$d_field=explode("::::::",$d_record[$i]);
				$allvote.="<tr><td width='9%'><div align=center>".$j."</div></td><td width='65%'><input name=vote_name[] type=text value='".$d_field[0]."' size=30></td><td width='26%'><input name=vote_num[] type=text value='".$d_field[1]."' size=6></td></tr>";
			}
			$voteeditnum=$j;
			$allvote="<table width='100%' border=0 cellspacing=1 cellpadding=3>".$allvote."</table>";
		}
	}
}

//-----------------------------------------修改信息
if($enews=="EditNews")
{
	//索引表
	$index_r=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$class_r[$classid][tbname]."_index where id='$id' limit 1");
	if(!$index_r['id']||$index_r['classid']!=$classid)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	//返回表
	$infotb=ReturnInfoMainTbname($class_r[$classid][tbname],$index_r['checked']);
	//主表
	$r=$empire->fetch1("select * from ".$infotb." where id='$id' limit 1");
	//签发表
	if($r[isqf])
	{
		$wfinfor=$empire->fetch1("select tstatus,checktno from {$dbtbpre}enewswfinfo where id='$id' and classid='$classid' limit 1");
	}
	//只能编辑自己的信息
	if($doselfinfo['doselfinfo']&&($r[userid]<>$logininid||$r[ismember]))
	{
		printerror("NotDoSelfinfo","history.go(-1)");
	}
	//已审核信息不可修改
	if($doselfinfo['docheckedit']&&$index_r['checked'])
	{
		printerror("NotEditCheckInfoLevel","history.go(-1)");
	}
	//返回表信息
	$infodatatb=ReturnInfoDataTbname($class_r[$classid][tbname],$index_r['checked'],$r['stb']);
	//副表
	$finfor=$empire->fetch1("select ".ReturnSqlFtextF($modid)." from ".$infodatatb." where id='$id' limit 1");
	$r=array_merge($r,$finfor);
	//时间
	$r['checked']=$index_r['checked'];
	$newstime=$r['newstime'];
	$r['newstime']=date("Y-m-d H:i:s",$r['newstime']);
	//链接地址
	$titleurl=$r['titleurl'];
	if(!$r['isurl'])
	{
		$r['titleurl']='';
	}
	//会员组
	$group=str_replace(" value=".$r[groupid].">"," value=".$r[groupid]." selected>",$ygroup);
	//内容存文本
	$savetxtf=$emod_r[$modid]['savetxtf'];
	$newstext_url='';
	if($savetxtf)
	{
		$newstext_url=$r[$savetxtf];
		$r[$savetxtf]=GetTxtFieldText($r[$savetxtf]);
    }
	//标题属性
	if(strstr($r[titlefont],','))
	{
		$tfontr=explode(',',$r[titlefont]);
		$r[titlecolor]=$tfontr[0];
		$r[titlefont]=$tfontr[1];
	}
	if(strstr($r[titlefont],"b|"))
	{
		$titlefontb=" checked";
	}
	if(strstr($r[titlefont],"i|"))
	{
		$titlefonti=" checked";
	}
	if(strstr($r[titlefont],"s|"))
	{
		$titlefonts=" checked";
	}
	//投票
	$pubid=ReturnInfoPubid($classid,$id);
	$voter=$empire->fetch1("select * from {$dbtbpre}enewsinfovote where pubid='$pubid' limit 1");
	if($voter['id']&&$voter[votetext])
	{
		$d_record=explode("\r\n",$voter[votetext]);
		for($i=0;$i<count($d_record);$i++)
		{
			$j=$i+1;
			$d_field=explode("::::::",$d_record[$i]);
			$allvote.="<tr><td width='9%'><div align=center>".$j."</div></td><td width='65%'><input name=vote_name[] type=text value='".$d_field[0]."' size=30></td><td width='26%'><input name=vote_num[] type=text value='".$d_field[1]."' size=6><input type=hidden name=vote_id[] value=".$j."><input type=checkbox name=delvote_id[] value=".$j.">删除</td></tr>";
		}
		$voteeditnum=$j;
		$allvote="<table width='100%' border=0 cellspacing=1 cellpadding=3>".$allvote."</table>";
	}
}
//标题分类
$cttidswhere='';
$tts='';
$caddr=$empire->fetch1("select ttids from {$dbtbpre}enewsclassadd where classid='$classid'");
if($caddr['ttids']!='-')
{
	if($caddr['ttids']&&$caddr['ttids']!=',')
	{
		$cttidswhere=' and typeid in ('.substr($caddr['ttids'],1,-1).')';
	}
	$ttsql=$empire->query("select typeid,tname from {$dbtbpre}enewsinfotype where mid='$modid'".$cttidswhere." order by myorder");
	while($ttr=$empire->fetch($ttsql))
	{
		$select='';
		if($ttr[typeid]==$r[ttid])
		{
			$select=' selected';
		}
		$tts.="<option value='$ttr[typeid]'".$select.">$ttr[tname]</option>";
	}
}
//内容模板
$t_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewsnewstemp")." order by modid,tempid");
while($nt=$empire->fetch($t_sql))
{
	if($nt[tempid]==$r[newstempid])
	{
		$select=" selected";
	}
	else
	{
		$select="";
	}
	$newstemp.="<option value=".$nt[tempid].$select.">".$nt[tempname]."</option>";
}
//模板
$votetemp="";
$vtsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsvotetemp")." order by tempid");
while($vtr=$empire->fetch($vtsql))
{
	if($voter[tempid]==$vtr[tempid])
	{
		$select=" selected";
	}
	else
	{
		$select="";
	}
	$votetemp.="<option value='".$vtr[tempid]."'".$select.">".$vtr[tempname]."</option>";
}
//同时发布
if(empty($voter['copyids'])||$voter['copyids']=='1')
{
	$copyclassidshowiframe='<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="ShowClassNav.php?ecms=1'.$ecms_hashur['ehref'].'" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>';
	$copyclassids='
	<h3><span>同时发布到以下栏目: &nbsp;&nbsp;<input type="checkbox" name="copyinfotitleurl" value="1">采用索引链接</span></h3>
	<ul>
                    <li><label>&nbsp;</label><div id="copyinfoshowclassnav"></div></li>      
             		</ul>
	';
}
else
{
	$copyclassidshowiframe='';
	$copyclassids='<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
              <tr>
                <td>同时发布到以下栏目:</td>
              </tr>
              <tr>
                <td height="25" bgcolor="#FFFFFF" id="copyinfoshowclassnav">本信息已同步发布到其他栏目,信息ID:<br>'.$voter[copyids].'</td>
              </tr>
            </table>';
}
//表单文件
$modfile="../data/html/".$modid.".php";
//栏目链接
$getcurlr['classid']=$classid;
$classurl=sys_ReturnBqClassname($getcurlr,9);
//当前使用的模板组
$thegid=GetDoTempGid();
$phpmyself=urlencode(eReturnSelfPage(1));
//返回头条和推荐级别名称
$ftnr=ReturnFirsttitleNameList($r['firsttitle'],$r['isgood']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$word?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jquery.bigcolorpicker.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="adminstyle/1/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="adminstyle/1/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="../data/images/tabpane.js"></script> 
<script type="text/javascript" src="adminstyle/1/js/jquery.tagsinput.js"></script>
<script type='text/javascript' src='adminstyle/1/js/jquery-ui.min.js'></script>
<script type='text/javascript' src='/e/extend/keyword/getkeyword.js'></script>
<link rel="stylesheet" type="text/css" href="adminstyle/1/js/jquery-ui.css" />

 <script type="text/javascript"> 
 $(document).ready(function(){
			var tab = new $.fn.tab({
				tabList:"#tab .ui-tab-container .ui-tab-list li",
				contentList:"#tab .ui-tab-container .ui-tab-content"
			});
		});	
function chgBg(obj,color){
 if (document.all || document.getElementById)
   obj.style.backgroundColor=color;
 else if (document.layers)
   obj.bgColor=color;
}
  setLinkSrc( "luna" ); 
</script>
<script>
function dovoteadd(){
	var i;
	var str="";
	var oldi=0;
	var j=0;
	oldi=parseInt(document.add.v_editnum.value);
	for(i=1;i<=document.add.v_vote_num.value;i++)
	{
		j=i+oldi;
		str=str+"<tr><td width='9%' height=20> <div align=center>"+j+"</div></td><td width='65%'> <div align=center><input type=text name=vote_name[] size=30></div></td><td width='26%'> <div align=center><input type=text name=vote_num[] value=0 size=6></div></td></tr>";
	}
	document.getElementById('addvote').innerHTML="<table width='100%' border=0 cellspacing=1 cellpadding=3>"+str+"</table>";
}

function doSpChangeFile(name,url,filesize,filetype,idvar){
	document.getElementById(idvar).value=url;
	if(document.add.filetype!=null)
	{
		if(document.add.filetype.value=='')
		{
			document.add.filetype.value=filetype;
		}
	}
	if(document.add.filesize!=null)
	{
		if(document.add.filesize.value=='')
		{
			document.add.filesize.value=filesize;
		}
	}
}

function SpOpenChFile(type,field){
	window.open('ecmseditor/FileMain.php?<?=$ecms_hashur['ehref']?>&classid=<?=$classid?>&infoid=<?=$id?><?=$addecmscheck?>&filepass=<?=$filepass?>&type='+type+'&sinfo=1&tranfrom=2&field='+field,'','width=700,height=550,scrollbars=yes');
}

//上传多附件时,图集
function eTranMoreForMorepic(htmlstr,fnum){
	var morepicdiv=document.getElementById("defmorepicid").innerHTML;
	var thismorepicnum=parseInt(document.add.morepicnum.value);
	var enews="<?=$enews?>";
	if(enews=='AddNews')
	{
		if(document.add.havetmpic.value==0)
		{
			document.getElementById("defmorepicid").innerHTML+=htmlstr;
			document.add.morepicnum.value=thismorepicnum+fnum;
		}
		else
		{
			document.getElementById("defmorepicid").innerHTML+=htmlstr;
			document.add.morepicnum.value=thismorepicnum+fnum;
		}
	}
	else
	{
		document.getElementById("defmorepicid").innerHTML+=htmlstr;
		document.add.morepicnum.value=thismorepicnum+fnum;
	}
	document.getElementById("addpicdown").innerHTML="";
	document.add.havetmpic.value=1;
}

</script>
<script src="ecmseditor/fieldfile/setday.js"></script>
<script src="../data/html/postinfo.js"></script>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/jquery.bigcolorpicker.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function bs(){
	var f=document.add;
	if(f.title.value.length==0){alert("标题还没写");f.title.focus();return false;}
}
function foreColor(){
	$("[src='../data/images/color.gif']").bigColorpicker(function(el,color){
				$("input[name=titlecolor]").val(color);
	});
}
function FieldChangeColor(obj){
  if(!Error())	return;
  var arr = showModalDialog("../data/html/selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
  if (arr != null) obj.value=arr;
  else obj.focus();
}
</script>
<script>
//选择tag
function chosetag(id,classid,bclassid){
art.dialog.open('tags/ChangeTags.php?<?=$ecms_hashur[ehref]?>&form=add&field=infotags',
    {title: '选择Tags',width: 710, height: 220});
}
//管理内容模板
function glnrmb(){
art.dialog.open('template/ListNewstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理内容模板',width: 800, height: 540});
}
//管理投票模板
function gltpmb(){
art.dialog.open('template/ListVotetemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理投票模板',width: 800, height: 540});
}
//增加作者
function zjzz(){
art.dialog.open('NewsSys/writer.php<?=$ecms_hashur['whehref']?>',
    {title: '增加作者',width: 800, height: 540});
}
//增加来源
function zjly(){
art.dialog.open('NewsSys/BeFrom.php<?=$ecms_hashur['whehref']?>',
    {title: '增加来源',width: 800, height: 540});
}
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" onload="document.add.title.focus();">
<div class="container" style="overflow-x:hidden;">
<div class="nynav"><?=$enews=='EditNews'?'<a href="user/ListDolog.php?classid='.$classid.'&id='.$id.$ecms_hashur['ehref'].'" target="_blank" class="right" style="margin-right:10px;">[查看本信息操作日志]</a>':''?><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div class="kongbai"></div>
<div class="saixuan">
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
  <form name="searchinfo" method="GET" action="ListNews.php">
    <tr> 
      <td width="42%" title="增加信息后使用本操作将信息显示到前台"> <select name="dore">
          <option value="1">刷新当前栏目</option>
          <option value="2">刷新首页</option>
          <option value="3">刷新父栏目</option>
          <option value="4">刷新当前栏目与父栏目</option>
          <option value="5">刷新父栏目与首页</option>
          <option value="6" selected>刷新当前栏目、父栏目与首页</option>
        </select> <input type="button" name="Submit12" value="提交" onclick="self.location.href='ecmsinfo.php?<?=$ecms_hashur['href']?>&enews=AddInfoToReHtml<?=$addecmscheck?>&classid=<?=$classid?>&dore='+document.searchinfo.dore.value;" class="anniu"> 
      </td>
      <td width="58%"><div align="right">[<font color="#ffffff"><a href=../../ target=_blank>预览首页</a></font>] 
          [<font color="#ffffff"><a href="<?=$classurl?>" target=_blank>预览栏目</a></font>] 
          [<font color="#ffffff"><a href="file/ListFile.php?type=9&classid=<?=$classid?><?=$ecms_hashur['ehref']?>">附件管理</a></font>] 
          [<a href="AddClass.php?enews=EditClass&classid=<?=$classid?><?=$ecms_hashur['ehref']?>">栏目设置</a>] 
          [<a href="ecmschtml.php?enews=ReAllNewsJs&from=<?=$phpmyself?><?=$ecms_hashur['ehref']?>">刷新所有信息JS</a>] 
        </div></td>
    </tr>
	</form>
</table>
</div>
	<?php
	if($enews=='EditNews')
	{
	?>
<div class="saixuan">
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
	<form name="doinfoform" method="post" action="ecmsinfo.php" onsubmit="return confirm('确认要执行此操作?');">
  <?=$ecms_hashur['form']?>
    <tr> 
      <td height="32" colspan="2"> <div align="left" class="anniuqun">审核原因: 
          <input name="causetext" type="text" id="causetext" style="width:250px;">
          <input type="submit" name="Submit3" value="审核通过" onclick="document.doinfoform.doing.value='2';">
          <input type="submit" name="Submit32" value="取消审核" onclick="document.doinfoform.doing.value='3';">
          <input type="submit" name="Submit33" value="删除" onclick="document.doinfoform.doing.value='1';">
          <font color="#666666">（不填原因表示不通知会员）</font>
          <input name="enews" type="hidden" id="enews" value="DoInfoAndSendNotice">
		  <input name="bclassid" type="hidden" id="bclassid" value="<?=$bclassid?>">
          <input name="classid" type="hidden" id="classid" value="<?=$classid?>">
          <input name="id" type="hidden" id="id" value="<?=$id?>">
          <input name="ecmsfrom" type="hidden" id="ecmsfrom" value="<?=RepPostStrUrl($_SERVER['HTTP_REFERER'])?>">
          <input name="doing" type="hidden" id="doing">
          <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
        </div></td>
    </tr>
	</form>
</table>
</div>
	<?php
	}
	?>
<form name="add" method="post" enctype="multipart/form-data" action="ecmsinfo.php" onsubmit="return EmpireCMSInfoPostFun(document.add,'<?=$modid?>');" class="newsbox">
  <?=$ecms_hashur['form']?>
    <div id="tab"  style="padding-bottom:50px;_margin-bottom:100px;overflow:hidden;">
	<div class="ui-tab-container">
	<ul class="clearfix ui-tab-list">
		<li class="ui-tab-active">基本信息</li>
		<li>选项设置</li>
		<li>投票设置</li>
	</ul>
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
			<h3><span>
           <?=$word?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" name="addnews2" value="提交" class="anniu"> <input type="reset" name="Submit23" value="重置" class="anniu">
					<input type=hidden value=<?=$enews?> name=enews> <input type=hidden value=<?=$classid?> name=classid> 
					<input type=hidden value=<?=$bclassid?> name=bclassid> <input name=id type=hidden value=<?=$id?>> 
					<input type=hidden value="<?=$filepass?>" name=filepass> <input type=hidden value="<?=$r[username]?>" name=username> 
					<input name="oldfilename" type="hidden" value="<?=$r[filename]?>">  
					<input name="oldgroupid" type="hidden" value="<?=$r[groupid]?>"> 
					<input name="oldchecked" type="hidden" value="<?=$r[checked]?>">  
					<input name="newstext_url" type="hidden" value="<?=$newstext_url?>">
					<input name="ecmsfrom" type="hidden" value="<?=RepPostStrUrl($_SERVER['HTTP_REFERER'])?>">
					<input name="ecmsnfrom" type="hidden" value="<?=RepPostStrUrl($_GET['ecmsnfrom'])?>">
					<input name="fstb" type="hidden" value="<?=$r[fstb]?>">
					<input name="oldttid" type="hidden" value="<?=$r[ttid]?>">
					<input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
            <input name="ztids" type="hidden" id="ztids">
            <input name="zcids" type="hidden" id="zcids">
            <input name="oldztids" type="hidden" id="oldztids">
            <input name="oldzcids" type="hidden" id="oldzcids">
<input type="hidden" name="havetmpic" value="0">
            </span></h3>
            <div class="line"></div>
            <div class="newscon anniuqun">
            <?php
		include($modfile);
		?>
        	</div>
        </div>

        <div class="ui-tab-content" style="display:none">
        	<h3><span>选项设置</span></h3>
            <div class="line"></div>
            <ul>
                	<li><label>置顶级别:</label>
                  <select name="istop">
                    <option value="0"<?=$r[istop]==0?' selected':''?>>不置顶</option>
                    <option value="1"<?=$r[istop]==1?' selected':''?>>一级置顶</option>
                    <option value="2"<?=$r[istop]==2?' selected':''?>>二级置顶</option>
                    <option value="3"<?=$r[istop]==3?' selected':''?>>三级置顶</option>
                    <option value="4"<?=$r[istop]==4?' selected':''?>>四级置顶</option>
                    <option value="5"<?=$r[istop]==5?' selected':''?>>五级置顶</option>
                    <option value="6"<?=$r[istop]==6?' selected':''?>>六级置顶</option>
                    <option value="7"<?=$r[istop]==7?' selected':''?>>七级置顶</option>
                    <option value="8"<?=$r[istop]==8?' selected':''?>>八级置顶</option>
					<option value="9"<?=$r[istop]==9?' selected':''?>>九级置顶</option>
                  </select>
                  内容模板:
                  <select name="newstempid">
                    <option value="0"<?=$r[newstempid]==0?' selected':''?>>使用默认模板</option>
                    <?=$newstemp?>
                  </select> <input type="button" name="Submit62222" value="管理内容模板" onclick="glnrmb()" class="anniu"> 
            		</li>
                    <li><label>权限设置:</label><select name="groupid">
                    <option value="0">游客</option>
                    <?=$group?>
                  </select>
                  查看扣除点数: 
                  <input name="userfen" type="text" value="<?=$r[userfen]?>" size="6">
                  , 
                  <input type=checkbox name=closepl value=1<?=$r[closepl]==1?" checked":""?>>
                  关闭评论 </li>
                    <li><label>点击数:</label><input name="onclick" type="text" id="onclick" value="<?=$r[onclick]?>" size="5">&nbsp;下载数: 
                  <input name="totaldown" type="text" id="totaldown" value="<?=$r[totaldown]?>" size="5"></li>
                    <li><label>文件名:</label>
                    <input name="newspath" type="text" id="newspath" value="<?=$r[newspath]?>" size="10">
                  /
                  <input name="filename" type="text" value="<?=$r[filename]?>" size="10">
                  <font color="#666666">(日期目录/文件名)</font>
                    </li>
                     <?php
			  if(strstr($public_r['usetags'],','.$modid.','))
			  {
			  	$infotag_readonly='';
				$infotag_copykeyboard='&nbsp;&nbsp;<input type="button" name="Submit3" value="复制关键字" onclick="document.add.infotags.value=document.add.keyboard.value;">';
			  	if(strstr($public_r['chtags'],','.$modid.','))
				{
					$infotag_readonly=' readonly';
					$infotag_copykeyboard='';
				}
			  ?>
                    <li class="anniuqun"><label>TAGS标签:</label>
                    <input name="infotags" type="text" id="infotags" value="<?=$r[infotags]?>" size="32"<?=$infotag_readonly?>> 
                  <input type="button" name="Submit" value="选择" onclick="chosetag()">
                  <?=$infotag_copykeyboard?>
                  <input name="oldinfotags" type="hidden" id="oldinfotags" value="<?=$r[infotags]?>">
                  <font color="#333333">(多个用&quot;,&quot;逗号格开)</font>
                    </li>
                <?php
			  }
			  ?>
              <?php
			  if($class_r[$classid]['link_num'])
			  {
			  ?>
                    <li><label>相关链接:</label><input type="radio" name="info_diyotherlink" value="0"<?=$voter[diyotherlink]==0?' checked':''?>>
                  按关键字查询 
                  <input type="radio" name="info_diyotherlink" value="1"<?=$voter[diyotherlink]==1?' checked':''?>>
                  <a href="#empirecms" title="查看和增加的相关链接" onclick="if(document.getElementsByName('info_diyotherlink')[1].checked==true){window.open('info/OtherLink.php?<?=$ecms_hashur['ehref']?>&classid=<?=$classid?>&id=<?=$id?>&enews=<?=$enews?>&form=add&field=info_keyid&keyid='+document.add.info_keyid.value+'&keyboard='+document.add.keyboard.value+'&title='+document.add.title.value,'','width=780,height=550,scrollbars=yes,resizable=yes');}else{alert('请先选择手动相关链接');}">手动相关链接</a>
                  <input name="info_keyid" type="hidden" id="info_keyid" value="<?=$r[keyid]?>"></li>
              </tr>
			  <?php
			  }
			  ?> 
                    </ul>
                    <h3><span>所属专题</span></h3>
                 	<ul>
                    <li><label>&nbsp;</label><a href="#empirecms" onclick="window.open('special/PushToZt.php?sinfo=1&classid=<?=$classid?>&id=<?=$id?><?=$ecms_hashur['ehref']?>','PushToZt','width=360,height=500,scrollbars=yes,left=300,top=150,resizable=yes');">点击选择信息所属专题</a></li>      
             		</ul>
                    <h3><span>定时发布</span></h3>
                 	<ul>
                    <li><label>上线时间：</label><input name="info_infouptime" type="text" id="info_infouptime" value="<?=$voter[infouptime]?date('Y-m-d H:i:s',$voter[infouptime]):''?>">
                        [<a href="#empirecms" onclick="document.add.info_infouptime.value='<?=$todaytime?>'">当前时间</a>]</li>      		
                    <li><label>下线时间：</label><input name="info_infodowntime" type="text" id="info_infodowntime" value="<?=$voter[infodowntime]?date('Y-m-d H:i:s',$voter[infodowntime]):''?>">
                        [<a href="#empirecms" onclick="document.add.info_infodowntime.value='<?=$todaytime?>'">当前时间</a>]</li>
             		</ul>
                    <?php
			if($wfinfor[checktno]==101)
			{
			?>
                    <h3><span>信息送审</span></h3>
                 	<ul>
                    <li><label>&nbsp;</label><input name="reworkflow" type="checkbox" value="1">
                  重新送审<font color="#333333">（信息被返工后，修改后可选择重新送审）</font></li>      
             		</ul>
                    <?php
			}
			?>
					<?=$copyclassids?>
                    
        </div>
        
        <div class="ui-tab-content" style="display:none">
        	<h3><span>投票设置</span></h3>
            <div class="line"></div>
            	<ul>
                	<li><label>主题标题</label>
            <input name="vote_title" type="text" size="60" value="<?=$voter[title]?>"></li>
                    <li><label>投票项目</label>
								<table border="0" cellspacing="1" cellpadding="3" class=" comm-table2" style="width:600px;">
									<tbody>
                                    <tr align="center">
                                        <th style="border-left: 1px solid #CDCDCD;">编号</th>
                                        <th>项目名称</th>
                                        <th>投票数</th>
									</tr>
                                    <?php
								if(($voter['id']&&$voter[votetext])||$definfovoteid)
								{
									echo"$allvote";
								}
								else
								{
								?>
                                <tr> 
											<td height="24" width="9%"> <div align="center">1</div></td>
											<td height="24" width="65%"> <div align="center"> 
											<input name="vote_name[]" type="text" size="30">
											</div></td>
											<td height="24" width="26%"> <div align="center"> 
											<input name="vote_num[]" type="text" value="0" size="6">
											</div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">2</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">3</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">4</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">5</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">6</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">7</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">8</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                  <?php
			  }
			  ?>
              <tr> 
                <td colspan="3">投票扩展数量: 
                  <input name="v_vote_num" type="text" value="1" size="6"> <input type="button" name="Submit52" value="输出地址" onclick="javascript:dovoteadd();" class="anniu"> 
                  <input name="v_editnum" type="hidden" value="<?=$voteeditnum?>"> 
                </td>
              </tr>
              <tr> 
                <td id="addvote" colspan="3"></td>
              </tr>
                                    </tbody>
								</table>
                    </li>
                	<li><label>投票类型</label>
                    <input name="vote_class" type="radio" value="0"<?=$voter['voteclass']==0?' checked':''?>>
            单选 
            <input type="radio" name="vote_class" value="1"<?=$voter['voteclass']==1?' checked':''?>>
            多选
                    </li>
                    
                    <li><label>限制IP</label>
                    <input type="radio" name="dovote_ip" value="0"<?=$voter['doip']==0?' checked':''?>>
            不限制 
            <input name="dovote_ip" type="radio" value="1"<?=$voter['doip']==1?' checked':''?>>
            限制(限制后同一IP只能投一次票)
                    </li>
                    <li><label>过期时间</label>
                    <input name="vote_olddotime" type=hidden value="<?=$voter[dotime]?>"> 
            <input name="vote_dotime" type="text" value="<?=$voter[dotime]?>" size="12" onClick="setday(this)">
            (超过此期限,将不能投票,0000-00-00为不限制)</li>
            <li><label>查看投票窗口</label>
                    宽度: 
        <input name="vote_width" type="text" value="<?=$voter[width]?>" size="6">
        高度: 
        <input name="vote_height" type="text" value="<?=$voter[height]?>" size="6"></li>
            <li><label>选择模板</label>
                   <select name="vote_tempid">
              <?=$votetemp?>
            </select> <input type="button" name="Submit62223" value="管理投票模板" onclick="gltpmb()" class="anniu"> </li>
                </ul>
 </div>
 <div class="line"></div>
  </div>
 <div class="sub jqui"><input type="submit" name="addnews2" value="提交" class="anniu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit23" value="重置" class="anniu"></div>
 <!--[if lte IE 6]>
 <div class="floatsub"><input type="submit" name="Submit" value="提 交" onClick="document.form1.enews.value='SetEnews';document.form1.action='SetEnews.php';document.form1.target='';" class="shezhi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" name="Submit2" value="重 置" class="chongzhi"></div>
 <![endif]-->
 </div>
</div>
 </form>
 <div class="clear"></div>
</div>



<?=$copyclassidshowiframe?>
</body>
</html>
<?php
db_close();
$empire=null;
?>