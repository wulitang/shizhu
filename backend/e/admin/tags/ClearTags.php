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
CheckLevel($logininid,$loginin,$classid,"tags");

//清理多余数据
function ClearTags($start,$line,$userid,$username){
	global $empire,$dbtbpre,$class_r,$fun_r;
	$line=(int)$line;
	if(empty($line))
	{
		$line=500;
	}
	$start=(int)$start;
	$b=0;
	$sql=$empire->query("select id,classid,tid,tagid from {$dbtbpre}enewstagsdata where tid>$start order by tid limit ".$line);
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r['tid'];
		if(empty($class_r[$r[classid]]['tbname']))
		{
			$empire->query("delete from {$dbtbpre}enewstagsdata where tid='$r[tid]'");
			$empire->query("update {$dbtbpre}enewstags set num=num-1 where tagid='$r[tagid]'");
			continue;
		}
		$index_r=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$class_r[$r[classid]]['tbname']."_index where id='$r[id]' limit 1");
		if(!$index_r['id'])
		{
			$empire->query("delete from {$dbtbpre}enewstagsdata where tid='$r[tid]'");
			$empire->query("update {$dbtbpre}enewstags set num=num-1 where tagid='$r[tagid]'");
		}
		else
		{
			//返回表
			$infotb=ReturnInfoMainTbname($class_r[$r[classid]]['tbname'],$index_r['checked']);
			//主表
			$infor=$empire->fetch1("select stb from ".$infotb." where id='$r[id]' limit 1");
			//返回表信息
			$infodatatb=ReturnInfoDataTbname($class_r[$r[classid]]['tbname'],$index_r['checked'],$infor['stb']);
			//副表
			$finfor=$empire->fetch1("select infotags from ".$infodatatb." where id='$r[id]' limit 1");
			$tagr=$empire->fetch1("select tagname from {$dbtbpre}enewstags where tagid='$r[tagid]'");
			if(!stristr(','.$finfor['infotags'].',',','.$tagr['tagname'].','))
			{
				$empire->query("delete from {$dbtbpre}enewstagsdata where tid='$r[tid]'");
				$empire->query("update {$dbtbpre}enewstags set num=num-1 where tagid='$r[tagid]'");
			}
			elseif($index_r['classid']!=$r[classid])
			{
				$empire->query("update {$dbtbpre}enewstagsdata set classid='$index_r[classid]' where tid='$r[tid]'");
			}
		}
	}
	if(empty($b))
	{
		//操作日志
		insert_dolog("");
		printerror('ClearTagsSuccess','ClearTags.php'.hReturnEcmsHashStrHref2(1));
	}
	echo"<meta http-equiv=\"refresh\" content=\"0;url=ClearTags.php?enews=ClearTags&line=$line&start=$newstart".hReturnEcmsHashStrHref(0)."\">".$fun_r[OneClearTagsSuccess]."(ID:<font color=red><b>".$newstart."</b></font>)";
	exit();
}

$enews=$_GET['enews'];
if($enews)
{
	hCheckEcmsRHash();
	include("../../data/dbcache/class.php");
	include "../".LoadLang("pub/fun.php");
	ClearTags($_GET[start],$_GET[line],$logininid,$loginin);
}

db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<title>清理多余TAGS信息</title>
<style>
.comm-table td { line-height:25px;}
.comm-table td p{ padding:5px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href=ListTags.php<?=$ecms_hashur['whehref']?>>管理TAGS</a> &gt; 清理多余TAGS信息</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form name="tagsclear" method="get" action="ClearTags.php" onsubmit="return confirm('确认要操作?');">
<?=$ecms_hashur['form']?>
<input name=enews type=hidden value=ClearTags>
		<tr>
			<th style="width:200px;"><h3><span>清理多余TAGS信息</span></h3></th>
			<th></th>
		</tr>
        <tr>
        	<td>每组整理数:</td>
            <td style="text-align:left;"><input name="line" type="text" id="line" value="500">  </td>
        </tr>
        <tr>
        	<td></td>
            <td style="text-align:left;"><input type="submit" name="Submit" value="开始清理"> <input type="reset" name="Submit2" value="重置"></td>
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