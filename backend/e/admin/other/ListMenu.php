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
CheckLevel($logininid,$loginin,$classid,"menu");

//增加菜单
function AddMenu($add,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$add['classid'];
	if(!$classid||!$add[menuname]||!$add[menuurl])
	{
		printerror("EmptyMenu","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"menu");
	$myorder=(int)$add['myorder'];
	$add['menuname']=hRepPostStr($add['menuname'],1);
	$add['menuurl']=hRepPostStr($add['menuurl'],1);
	$add['addhash']=(int)$add['addhash'];
	$sql=$empire->query("insert into {$dbtbpre}enewsmenu(menuname,menuurl,myorder,classid,addhash) values('".$add[menuname]."','".$add[menuurl]."','$myorder','$classid','$add[addhash]');");
	$lastid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("classid=$classid<br>menuid=".$lastid."&menuname=".$add[menuname]);
		printerror("AddMenuSuccess","ListMenu.php?classid=$classid".hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改菜单
function EditMenu($add,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$add['classid'];
	$menuid=$add['menuid'];
	$delmenuid=$add['delmenuid'];
	$menuname=$add['menuname'];
	$menuurl=$add['menuurl'];
	$myorder=$add['myorder'];
	$addhash=$add['addhash'];
	$count=count($menuid);
	if(!$classid||!$count)
	{
		printerror("EmptyMenu","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"menu");
	//删除
	$del=0;
	$ids='';
	$delcount=count($delmenuid);
	if($delcount)
	{
		$dh='';
		for($j=0;$j<$delcount;$j++)
		{
			$ids.=$dh.intval($delmenuid[$j]);
			$dh=',';
		}
		$empire->query("delete from {$dbtbpre}enewsmenu where menuid in (".$ids.")");
		$del=1;
	}
	//修改
	for($i=0;$i<$count;$i++)
	{
		$menuid[$i]=(int)$menuid[$i];
		if(strstr(','.$ids.',',','.$menuid[$i].','))
		{
			continue;
		}
		$myorder[$i]=(int)$myorder[$i];
		$menuname[$i]=hRepPostStr($menuname[$i],1);
		$menuurl[$i]=hRepPostStr($menuurl[$i],1);
		$addhash[$i]=(int)$addhash[$i];
		$empire->query("update {$dbtbpre}enewsmenu set menuname='".$menuname[$i]."',menuurl='".$menuurl[$i]."',myorder='".$myorder[$i]."',addhash='".$addhash[$i]."' where menuid='".$menuid[$i]."'");
	}
	//操作日志
	insert_dolog("classid=$classid&del=$del");
	printerror("EditMenuSuccess","ListMenu.php?classid=$classid".hReturnEcmsHashStrHref2(0));
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="AddMenu")//增加菜单
{
	AddMenu($_POST,$logininid,$loginin);
}
elseif($enews=="EditMenu")//修改菜单
{
	EditMenu($_POST,$logininid,$loginin);
}
else
{}

$classid=(int)$_GET['classid'];
if(!$classid)
{
	printerror("ErrorUrl","history.go(-1)");
}
$cr=$empire->fetch1("select classid,classname,issys,classtype,groupids from {$dbtbpre}enewsmenuclass where classid='$classid'");
if(!$cr['classid'])
{
	printerror("ErrorUrl","history.go(-1)");
}
$classtype='';
if($cr['classtype']==1)
{
	$classtype='常用操作';
}
elseif($cr['classtype']==2)
{
	$classtype='插件菜单';
}
elseif($cr['classtype']==3)
{
	$classtype='扩展菜单';
}
$menuclassname=$classtype."：".$cr['classname'];
$sql=$empire->query("select menuid,menuname,menuurl,myorder,addhash from {$dbtbpre}enewsmenu where classid='$classid' order by myorder,menuid");
//用户组
$gline=6;
$gno=0;
$group='';
$groupsql=$empire->query("select groupid,groupname from {$dbtbpre}enewsgroup order by groupid");
while($groupr=$empire->fetch($groupsql))
{
	$gno++;
	$br='';
	if($gno%$gline==0)
	{
		$br='<br>';
	}
	$select='';
	if(strstr($cr[groupids],','.$groupr[groupid].','))
	{
		$select=' checked';
	}
	$group.="<input name='groupid[]' type='checkbox' id='groupid[]' value='".$groupr[groupid]."'".$select.">".$groupr[groupname]."&nbsp;&nbsp;".$br;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理菜单</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/js/yecha.js"></script>
<script type="text/javascript">
$(function(){
			
		});
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

</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="MenuClass.php<?=$ecms_hashur['whehref']?>">管理菜单</a>&nbsp;>&nbsp;<a href="ListMenu.php?classid=<?=$classid?><?=$ecms_hashur['ehref']?>"><?=$menuclassname?></a>&nbsp;>&nbsp;菜单列表  </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理菜单</span></h3>
            <div class="line"></div>
<div>
  <form name="form2" method="post" action="ListMenu.php" onsubmit="return confirm('确认要提交?');">
  <?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>删除</th>
			<th>显示顺序</th>
			<th>菜单名称</th>
			<th>链接地址</th>
		</tr>
    <?php
  while($r=$empire->fetch($sql))
  {
  ?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#DBEAF5'"> 
      <td><div align="center"> 
          <input name="delmenuid[]" type="checkbox" id="delmenuid[]" value="<?=$r[menuid]?>">
        </div></td>
      <td> <input name="myorder[]" type="text" id="myorder[]" value="<?=$r[myorder]?>" size="4"> 
      </td>
      <td height="25"> <input name="menuname[]" type="text" id="menuname[]" value="<?=$r[menuname]?>"> 
        <input name="menuid[]" type="hidden" id="menuid[]" value="<?=$r[menuid]?>"> 
      </td>
      <td height="25"><input name="menuurl[]" type="text" id="menuurl[]" value="<?=$r[menuurl]?>" size="60">
      <select name="addhash[]" id="addhash[]">
          <option value="0"<?=$r[addhash]==0?' selected':''?>>普通链接</option>
          <option value="1"<?=$r[addhash]==1?' selected':''?>>刺猬模式链接</option>
          <option value="2"<?=$r[addhash]==2?' selected':''?>>金刚模式链接</option>
        </select></td>
    </tr>
    <?php
  }
  ?>
  <tr>
		  <td><input type=checkbox name=chkall value=on onclick=CheckAll(this.form)></td>
		  <td colspan="3" style=" text-align:left;padding-left:15px"><input type="submit" name="Submit2" value="提交" class="anniu"> 
        <input name="enews" type="hidden" id="enews" value="EditMenu">
        <input name="classid" type="hidden" id="classid" value="<?=$classid?>"> &nbsp; 
        &nbsp; <font color="#666666">(说明：顺序值越小显示越前面) </font></td>
  </tr>
	</tbody>
</table>
</form>

<form name="form1" method="post" action="ListMenu.php">
 <?=$ecms_hashur['form']?>
<input name=enews type=hidden id="enews" value=AddMenu> <input name="classid" type="hidden" id="classid" value="<?=$classid?>"> 
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="text-align:left; padding-left:15px">增加菜单:  </th>
		</tr>
		<tr>
			<td colspan="5" style="text-align:left;padding-left:15px">
    	菜单名称: 
        <input name="menuname" type="text" id="menuname">
        &nbsp;显示顺序:
        <input name="myorder" type="text" id="myorder" value="0" size="3">
        &nbsp;链接地址: 
        <input name="menuurl" type="text" id="menuurl" size="30">
        <select name="addhash" id="addhash">
          <option value="0">普通链接</option>
          <option value="1">刺猬模式链接</option>
          <option value="2">金刚模式链接</option>
        </select> 
        <input type="submit" name="Submit" value="增加" class="anniu"> <br> 
<font color="#666666">说明：链接地址从后台算起，比如后台首页链接地址是：main.php</font>		
            </td>
			</tr>
            <tr> 
    </tr>
	</tbody>
</table>
</form>
  <form name="form2" method="post" action="MenuClass.php">
    <?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="text-align:left; padding-left:15px">显示本分类菜单的用户组权限: </th>
		</tr>
    <tr>
      <td colspan="5" style="text-align:left;padding-left:15px">
        <input name=enews type=hidden id="enews" value=EditMenuClassGroup>
          <input name="classid" type="hidden" id="classid" value="<?=$classid?>">      
		  <?=$group?>
		  <input type="submit" name="Submit3" value="设置">
         <font color="#666666">(说明：不选为不限制。)</font></td>
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
<?php
db_close();
$empire=null;
?>