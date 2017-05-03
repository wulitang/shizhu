<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
require("class/hShopSysFun.php");
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
CheckLevel($logininid,$loginin,$classid,"precode");

//返回会员组列表
function ReturnPreGroupids($groupid){
	$count=count($groupid);
	if(!$count)
	{
		return '';
	}
	$gids='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$groupid[$i]=(int)$groupid[$i];
		if(!$groupid[$i])
		{
			continue;
		}
		$gids.=$dh.$groupid[$i];
		$dh=',';
	}
	if($gids)
	{
		$gids=','.$gids.',';
	}
	return $gids;
}

//增加优惠码
function AddPrecode($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add['prename']||!$add['precode']||!$add['premoney'])
	{
		printerror("EmptyPrecode","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"precode");
	$add['precode']=RepPostVar($add['precode']);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsshop_precode where precode='$add[precode]' limit 1");
	if($num)
	{
		printerror("RePrecode","history.go(-1)");
	}
	$addtime=time();
	$add['prename']=eaddslashes(ehtmlspecialchars($add['prename']));
	$add['premoney']=(int)$add['premoney'];
	$add['pretype']=(int)$add['pretype'];
	$add['reuse']=(int)$add['reuse'];
	$add['endtime']=$add['endtime']?to_time($add['endtime']):0;
	$add['musttotal']=(int)$add['musttotal'];
	$add['usenum']=(int)$add['usenum'];
	$groupids=ReturnPreGroupids($add['groupid']);
	$add['classid']=trim($add['classid']);
	$classids=$add['classid']?','.$add['classid'].',':'';
	$sql=$empire->query("insert into {$dbtbpre}enewsshop_precode(prename,precode,premoney,pretype,reuse,addtime,endtime,groupid,classid,musttotal,usenum) values('$add[prename]','$add[precode]','$add[premoney]','$add[pretype]','$add[reuse]','$addtime','$add[endtime]','$groupids','".eaddslashes($classids)."','$add[musttotal]','$add[usenum]');");
	$id=$empire->lastid();
	if($sql)
	{
		//操作日志
	    insert_dolog("id=$id&precode=$add[precode]<br>premoney=$add[premoney]&pretype=$add[pretype]");
		printerror("AddPrecodeSuccess","AddPrecode.php?enews=AddPrecode".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//批量增加优惠码
function AddMorePrecode($add,$userid,$username){
	global $empire,$dbtbpre;
	$donum=(int)$add['donum'];
	$precodenum=(int)$add['precodenum'];
	$add['prename']=eaddslashes(ehtmlspecialchars($add['prename']));
	$add['premoney']=(int)$add['premoney'];
	$add['pretype']=(int)$add['pretype'];
	$add['reuse']=(int)$add['reuse'];
	$add['endtime']=$add['endtime']?to_time($add['endtime']):0;
	$add['musttotal']=(int)$add['musttotal'];
	$add['usenum']=(int)$add['usenum'];
	$groupids=ReturnPreGroupids($add['groupid']);
	$add['classid']=trim($add['classid']);
	$classids=$add['classid']?','.$add['classid'].',':'';
	if(!$donum||!$precodenum||!$add['prename']||!$add['premoney'])
	{
		printerror("EmptyMorePrecode","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"precode");
	$addtime=time();
	//写入优惠码
	$no=1;
    while($no<=$donum)
	{
		$precode=strtoupper(make_password($precodenum));
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsshop_precode where precode='$precode' limit 1");
		if(!$num)
		{
			$sql=$empire->query("insert into {$dbtbpre}enewsshop_precode(prename,precode,premoney,pretype,reuse,addtime,endtime,groupid,classid,musttotal,usenum) values('$add[prename]','$precode','$add[premoney]','$add[pretype]','$add[reuse]','$addtime','$add[endtime]','$groupids','".eaddslashes($classids)."','$add[musttotal]','$add[usenum]');");
			$no+=1;
	    }
    }
	if($sql)
	{
		//操作日志
		insert_dolog("prenum=$donum&premoney=$add[premoney]&pretype=$add[pretype]");
		printerror("AddMorePrecodeSuccess","AddMorePrecode.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改优惠码
function EditPrecode($add,$userid,$username){
	global $empire,$dbtbpre;
	$add['id']=(int)$add['id'];
	if(!$add['prename']||!$add['precode']||!$add['premoney']||!$add['id'])
	{
		printerror("EmptyPrecode","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"precode");
	$add['precode']=RepPostVar($add['precode']);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsshop_precode where precode='$add[precode]' and id<>".$add[id]." limit 1");
	if($num)
	{
		printerror("RePrecode","history.go(-1)");
	}
	$time=(int)$add['time'];
	$add['prename']=eaddslashes(ehtmlspecialchars($add['prename']));
	$add['premoney']=(int)$add['premoney'];
	$add['pretype']=(int)$add['pretype'];
	$add['reuse']=(int)$add['reuse'];
	$add['endtime']=$add['endtime']?to_time($add['endtime']):0;
	$add['musttotal']=(int)$add['musttotal'];
	$add['usenum']=(int)$add['usenum'];
	$groupids=ReturnPreGroupids($add['groupid']);
	$add['classid']=trim($add['classid']);
	$classids=$add['classid']?','.$add['classid'].',':'';
	$sql=$empire->query("update {$dbtbpre}enewsshop_precode set prename='$add[prename]',precode='$add[precode]',premoney='$add[premoney]',pretype='$add[pretype]',reuse='$add[reuse]',endtime='$add[endtime]',groupid='$groupids',classid='$classids',musttotal='$add[musttotal]',usenum='$add[usenum]' where id='$add[id]'");
	if($sql)
	{
		//操作日志
		insert_dolog("id=$add[id]&precode=$add[precode]<br>premoney=$add[premoney]&pretype=$add[pretype]");
		printerror("EditPrecodeSuccess","ListPrecode.php?time=$time".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除优惠码
function DelPrecode($add,$userid,$username){
	global $empire,$dbtbpre;
	$id=(int)$add['id'];
	if(!$id)
	{
		printerror("NotChangePrecodeid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"precode");
	$time=(int)$add['time'];
	$r=$empire->fetch1("select precode,premoney,pretype from {$dbtbpre}enewsshop_precode where id='$id'");
	$sql=$empire->query("delete from {$dbtbpre}enewsshop_precode where id='$id'");
	if($sql)
	{
		//操作日志
		insert_dolog("id=$id&precode=$r[precode]<br>premoney=$r[premoney]&pretype=$r[pretype]");
		printerror("DelPrecodeSuccess","ListPrecode.php?time=$time".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//批量删除优惠码
function DelPrecode_all($add,$userid,$username){
	global $empire,$dbtbpre;
	$id=$add['id'];
	$count=count($id);
	if(!$count)
	{
		printerror("NotChangePrecodeid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"precode");
	$ids='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$ids.=$dh.intval($id[$i]);
		$dh=',';
	}
	$time=(int)$add['time'];
	$sql=$empire->query("delete from {$dbtbpre}enewsshop_precode where id in (".$ids.")");
	if($sql)
	{
		//操作日志
		insert_dolog("");
		printerror("DelPrecodeSuccess","ListPrecode.php?time=$add[time]".hReturnEcmsHashStrHref2(0));
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
if($enews=="AddPrecode")//增加优惠码
{
	AddPrecode($_POST,$logininid,$loginin);
}
elseif($enews=="EditPrecode")//修改优惠码
{
	EditPrecode($_POST,$logininid,$loginin);
}
elseif($enews=="DelPrecode")//删除优惠码
{
	DelPrecode($_GET,$logininid,$loginin);
}
elseif($enews=="AddMorePrecode")//批量增加优惠码
{
	AddMorePrecode($_POST,$logininid,$loginin);
}
elseif($enews=="DelPrecode_all")//批量删除优惠码
{
	DelPrecode_all($_POST,$logininid,$loginin);
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;
$page_line=25;
$add="";
$and=' where ';
$search="";
$search.=$ecms_hashur['ehref'];
$time=(int)$_GET['time'];
//搜索
$sear=(int)$_GET['sear'];
if($sear)
{
	$show=(int)$_GET['show'];
	$keyboard=$_GET['keyboard'];
	$keyboard=RepPostVar2($keyboard);
	if($keyboard)
	{
		if($show==1)//优惠码名称
		{
			$add.=$and."prename like '%$keyboard%'";
		}
		elseif($show==2)//优惠码
		{
			$add.=$and."precode='$keyboard'";
		}
		else//金额
		{
			$add.=$and."premoney='$keyboard'";
		}
		$and=' and ';
	}
	//类型
	$pretype=(int)$_GET['pretype'];
	if($pretype)
	{
		if($pretype==1)//金额
		{
			$add.=$and."pretype=0";
		}
		else//百分比
		{
			$add.=$and."pretype=1";
		}
		$and=' and ';
	}
	//重复使用
	$reuse=(int)$_GET['reuse'];
	if($reuse)
	{
		if($reuse==1)//一次性使用
		{
			$add.=$and."reuse=0";
		}
		else//可重复使用
		{
			$add.=$and."reuse=1";
		}
		$and=' and ';
	}
	$search.="&sear=1&pretype=$pretype&reuse=$reuse&show=$show&keyboard=$keyboard";
}
//过期
if($time)
{
	$todaytime=time();
	$search.="&time=$time";
	$add.=$and."endtime>0 and endtime<".$todaytime;
}
$offset=$line*$page;
$totalquery="select count(*) as total from {$dbtbpre}enewsshop_precode".$add;
$num=$empire->gettotal($totalquery);
$query="select id,prename,precode,pretype,premoney,reuse,addtime,endtime from {$dbtbpre}enewsshop_precode".$add;
$query.=" order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理优惠码</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
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
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListPrecode.php<?=$ecms_hashur['whehref']?>">管理优惠码</a><?=$time?' &gt; 过期优惠码':''?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理优惠码 <a href="AddPrecode.php?enews=AddPrecode<?=$ecms_hashur['ehref']?>" class="add">增加优惠码</a> <a href="AddMorePrecode.php<?=$ecms_hashur['whehref']?>" class="add">批量增加优惠码</a> <a href="ListPrecode.php?time=1<?=$ecms_hashur['ehref']?>" class="gl">管理过期优惠码</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">

<div class="saixuan">
<form name=search method=GET action=ListPrecode.php>
  <?=$ecms_hashur['eform']?>
搜索： 
        <select name="show" id="show">
          <option value="1"<?=$show==1?' selected':''?>>优惠码名称</option>
          <option value="2"<?=$show==2?' selected':''?>>优惠码</option>
          <option value="3"<?=$show==3?' selected':''?>>金额</option>
        </select>
		<input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>"> 
        <select name="pretype" id="pretype">
          <option value="0"<?=$pretype==0?' selected':''?>>不限类型</option>
          <option value="1"<?=$pretype==1?' selected':''?>>减金额的优惠码</option>
          <option value="2"<?=$pretype==2?' selected':''?>>百分比的优惠码</option>
        </select>
        <select name="reuse" id="reuse">
          <option value="0"<?=$reuse==0?' selected':''?>>不限使用</option>
          <option value="1"<?=$reuse==1?' selected':''?>>一次性使用</option>
          <option value="2"<?=$reuse==2?' selected':''?>>可重复使用</option>
        </select> 
        <input type="submit" name="Submit" value="搜索"> <input name="sear" type="hidden" id="sear" value="1"> 
        <input name="time" type="hidden" id="time" value="<?=$time?>">
  </form>
</div>

  <form name="listcardform" method="post" action="ListPrecode.php" onSubmit="return confirm('确认要删除?');">
<?=$ecms_hashur['form']?>
    <input type="hidden" name="enews" value="DelPrecode_all">
	<input name="time" type="hidden" id="time" value="<?=$time?>">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th width="2%"></th>
            <th width="5%"> ID</th>
            <th width="28%">优惠码名称</th>
            <th width="31%"> 优惠码</th>
            <th width="12%"> 金额(元)</th>
            <th width="7%">重复使用</th>
            <th width="15%"> 操作</th>
		</tr>
   <?
  while($r=$empire->fetch($sql))
  {
  	if($r['pretype']==1)
	{
		$premoney=$r['premoney'].'%';
	}
	else
	{
  		$premoney=$r['premoney'].'元';
	}
  ?>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>">
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[id]?>
        </div></td>
      <td><div align="center"><a title="<?="增加时间：".date('Y-m-d H:i:s',$r[addtime])."\r\n过期时间：".date('Y-m-d',$r[endtime])?>"><?=$r[prename]?></a></div></td>
      <td height="25"> <div align="center">  
          <?=$r[precode]?>
          </div></td>
      <td height="25"> <div align="center"> 
          <?=$premoney?>
        </div></td>
      <td><div align="center"><?=$r['reuse']==1?'可重复':'一次'?></div></td>
      <td height="25"> <div align="center">[<a href="AddPrecode.php?enews=EditPrecode&id=<?=$r[id]?>&time=<?=$time?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="ListPrecode.php?enews=DelPrecode&id=<?=$r[id]?>&time=<?=$time?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>]</div></td>
    </tr>
    <?
  }
  ?>
  			<tr>
          <td><input type=checkbox name=chkall value=on onClick="CheckAll(this.form)"></td>
  		  <td colspan="6" style="text-align:left;"><input type="submit" name="Submit2" value="删除选中"></td>
		  </tr>
  		<tr>
  		  <td colspan="7" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
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