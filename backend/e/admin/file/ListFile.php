<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
require("../../data/dbcache/class.php");
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
CheckLevel($logininid,$loginin,$classid,"file");
//参数
$modtype=(int)$_GET['modtype'];
$fstb=(int)$_GET['fstb'];
//附件表
$fstb=eReturnFileStb($fstb);
//附件类型
$isinfofile=0;
$showfstb='';
if($modtype==1)//栏目
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=1";
	$tranname='栏目';
}
elseif($modtype==2)//专题
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=2";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=2";
	$tranname='专题';
}
elseif($modtype==3)//广告
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=3";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=3";
	$tranname='广告';
}
elseif($modtype==4)//反馈
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=4";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=4";
	$tranname='反馈';
}
elseif($modtype==5)//公共
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_public where 1=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_public where 1=1";
	$tranname='公共';
}
elseif($modtype==6)//会员
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_member where 1=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_member where 1=1";
	$tranname='会员';
}
elseif($modtype==7)//碎片
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=7";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=7";
	$tranname='碎片';
}
else//信息
{
	$isinfofile=1;
	$showfstb=' - 分表'.$fstb.' ';
	$query="select fileid,filename,filesize,path,filetime,classid,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_{$fstb} where 1=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_{$fstb} where 1=1";
	$tranname='信息';
}
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add='';
//附件类型
$type=(int)$_GET['type'];
if($type!=9)//其他附件
{
	$add.=" and type='$type'";
}
//选择栏目
$classid=(int)$_GET['classid'];
/*
$fcjsfile='../../data/fc/cmsclass.js';
$classoptions=GetFcfiletext($fcjsfile);
*/
//栏目
if($isinfofile==1)
{
	if($classid)
	{
		if($class_r[$classid]['islast'])
		{
			$add.=" and classid='$classid'";
		}
		else
		{
			$add.=" and ".ReturnClass($class_r[$classid]['sonclass']);
		}
		//$classoptions=str_replace("<option value='$classid'","<option value='$classid' selected",$classoptions);
	}
}
//关键字
$keyboard=RepPostVar2($_GET['keyboard']);
if(!empty($keyboard))
{
	$show=RepPostStr($_GET['show'],1);
	//搜索全部
	if($show==0)
	{
		$add.=" and (filename like '%$keyboard%' or no like '%$keyboard%' or adduser like '%$keyboard%')";
	}
	//搜索文件名
	elseif($show==1)
	{
		$add.=" and filename like '%$keyboard%'";
	}
	//搜索编号
	elseif($show==2)
	{
		$add.=" and no like '%$keyboard%'";
	}
	//搜索上传者
	else
	{
		$add.=" and adduser like '%$keyboard%'";
	}
}
$search="&classid=$classid&type=$type&modtype=$modtype&fstb=$fstb&show=$show&keyboard=$keyboard".$ecms_hashur['ehref'];
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by fileid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理附件</title>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
<style>
.comm-table td{ padding:8px 4px; height:16px;}
</style>

</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a>附件管理</a> > 位置：管理<?=$tranname?>附件<?=$showfstb?> (数据库式)&nbsp;</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理<?=$tranname?>附件<?=$showfstb?> (数据库式)</span></h3>
            <div class="line"></div>
<div class="anniuqun">
<div class="saixuan">
<form name="form2" method="get" action="ListFile.php">
 <?=$ecms_hashur['eform']?>
    <input type=hidden name=classid value="<?=$classid?>">
	<input type=hidden name=modtype value="<?=$modtype?>">
    <input type=hidden name=fstb value="<?=$fstb?>">
    		搜索: <select name="type" id="select">
          <option value="9">所有附件类型</option>
          <option value="1"<?=$type==1?' selected':''?>>图片</option>
          <option value="2"<?=$type==2?' selected':''?>>Flash文件</option>
          <option value="3"<?=$type==3?' selected':''?>>多媒体文件</option>
          <option value="0"<?=$type==0?' selected':''?>>其他附件</option>
        </select> <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
        <select name="show" id="show">
          <option value="0"<?=$show==0?' checked':''?>>不限</option>
          <option value="1"<?=$show==1?' checked':''?>>文件名</option>
          <option value="2"<?=$show==2?' checked':''?>>编号</option>
          <option value="3"<?=$show==3?' checked':''?>>上传者</option>
        </select>
		<span id="listfileclassnav"></span>
        <input type="submit" name="Submit2" value="搜索"> [<a href="../ecmsfile.php?enews=DelFreeFile<?=$ecms_hashur['href']?>" onclick="return confirm('确认要操作?');">清理失效附件</a>]
        </form>
</div>

<form name="form1" method="post" action="../ecmsfile.php" onsubmit="return confirm('确认要删除?');">
<?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th width="5%">ID</th>
            <th width="">文件名</th>
            <th width="21%">增加者</th>
            <th width="13%">文件大小</th>
            <th width="11%">增加时间</th>
            <th width="12%">操作</th>
		</tr>
    <?
	while($r=$empire->fetch($sql))
	{
		$filesize=ChTheFilesize($r[filesize]);
		$fspath=ReturnFileSavePath($r[classid],$r[fpath]);
		$filepath=$r[path]?$r[path].'/':$r[path];
		$path1=$fspath['fileurl'].$filepath.$r[filename];
		//引用
		$thisfileid=$r['fileid'];
		if($isinfofile==1&&$r['id'])
		{
			$thisfileid="<b><a href='../../public/InfoUrl/?classid=$r[classid]&id=$r[id]' target=_blank>".$r[fileid]."</a></b>";
		}
	?>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25"> <div align="center"> 
          <?=$thisfileid?>
        </div></td>
      <td> <div align="center"><?=$r[no]?>
          <br>
          <a href="<?=$path1?>" target="_blank">
          <?=$r[filename]?>
          </a>
          </div></td>
      <td> <div align="center">
          <?=$r[adduser]?>
        </div></td>
      <td> <div align="center">
          <?=$filesize?>
        </div></td>
      <td> <div align="center">
          <?=date('Y-m-d H:i:s',$r[filetime])?>
        </div></td>
      <td><div align="center">[<a href="../ecmsfile.php?enews=DelFile&fileid=<?=$r[fileid]?>&modtype=<?=$modtype?>&fstb=<?=$fstb?><?=$ecms_hashur['href']?>" onclick="return confirm('您是否要删除？');">删除</a> 
          <input name="fileid[]" type="checkbox" id="fileid[]" value="<?=$r[fileid]?>" onclick="if(this.checked){file<?=$r[fileid]?>.style.backgroundColor='#DBEAF5';}else{file<?=$r[fileid]?>.style.backgroundColor='#ffffff';}">
          ]</div></td>
    </tr>
    <?
	}
	?>
        <tr bgcolor="#FFFFFF"> 
 
      <td colspan="6" style="text-align:left">
<input type="submit" name="Submit" value="批量删除"> <input name="enews" type="hidden" id="enews" value="DelFile_all"> 
<input type=checkbox name=chkall value=on onClick=CheckAll(this.form)>
        选中全部
		<input type=hidden name=classid value="<?=$classid?>">
		<input type=hidden name=modtype value="<?=$modtype?>">
		<input type=hidden name=fstb value="<?=$fstb?>">
      </td>
    </tr>
  		<tr>
  		  <td colspan="6" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
		  </tr>
          <tr>
  		  <td colspan="6"><font color="#666666">如果ID是粗体，表示有信息引用，点击ID即可查看信息页面</font></td>
		  </tr>
	</tbody>
</table>
</form>
<?php
if($isinfofile==1)
{
?>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=5&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
<?php
}
?>
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
