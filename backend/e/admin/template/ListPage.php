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
CheckLevel($logininid,$loginin,$classid,"userpage");
$gid=(int)$_GET['gid'];
if(!$gid)
{
	$gid=GetDoTempGid();
}
$search="&gid=$gid".$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select id,title,path,tempid from {$dbtbpre}enewspage";
$totalquery="select count(*) as total from {$dbtbpre}enewspage";
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
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewspageclass order by classid");
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
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<title>管理自定义页面</title>
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
//管理自定义页面分类
function glzdyymfl(){
art.dialog.open('template/PageClass.php?<?=$ecms_hashur[ehref]?>&gid=<?=$gid?>',
    {title: '管理自定义页面分类',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
//管理自定义页面模板
function glzdyymmb(){
art.dialog.open('template/ListPagetemp.php?<?=$ecms_hashur[ehref]?>&gid=<?=$gid?>',
    {title: '管理自定义页面模板',lock: true,opacity: 0.5, width: 800, height: 540
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ListPage.php<?=$ecms_hashur['whehref']?>">管理自定义页面</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择类别： <select name="classid" id="classid" onchange=window.location='ListPage.php?<?=$ecms_hashur['ehref']?>&gid=<?=$gid?>&classid='+this.options[this.selectedIndex].value>
        <option value="0">显示所有类别</option>
        <?=$cstr?>
      </select> <a href="AddPage.php?enews=AddUserpage&gid=<?=$gid?><?=$ecms_hashur['ehref']?>" class="add">增加自定义页面</a> <a href="javascript:void(0)" onclick="glzdyymfl()" class="gl">管理自定义页面分类</a> <a href="javascript:void(0)" onclick="glzdyymmb()" class="gl">管理自定义页面模板</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="form1" method="post" action="../ecmscom.php">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th><input type=checkbox name=chkall value=on onclick=CheckAll(this.form)></th>
			<th>ID</th>
			<th>页面名称</th>
            <th>页面模式</th>
	    <th>页面地址</th>
            <th>操作</th>
		</tr>
    <?
  while($r=$empire->fetch($sql))
  {
  //绝对地址
  if(strstr($r['path'],".."))
  {
  $path="../".$r['path'];
  }
  else
  {
  $path=$r['path'];
  }
  $jspath=$public_r['newsurl'].str_replace("../../","",$r['path']);
  ?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>">
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[id]?>
        </div></td>
      <td height="25"> <div align="center"><a href="<?=$path?>" target=_blank> 
          <?=$r[title]?>
          </a></div></td>
      <td><div align="center"><?=$r['tempid']?'模板式':'页面式'?></div></td>
      <td><div align="center">
        <input name="textfield" type="text" value="<?=$jspath?>">
      </div></td>
      <td height="25"> <div align="center">[<a href="AddPage.php?enews=EditUserpage&id=<?=$r[id]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">修改</a>]&nbsp;[<a href="AddPage.php?enews=AddUserpage&docopy=1&id=<?=$r[id]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['ehref']?>">复制</a>]&nbsp;[<a href="../ecmscom.php?enews=DelUserpage&id=<?=$r[id]?>&cid=<?=$classid?>&gid=<?=$gid?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
    </tr>
    <?
  }
  ?>
   
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="6" style="text-align:left;"> 
        <?=$returnpage?>
        &nbsp;&nbsp;&nbsp; <input type="submit" name="Submit3" value="刷新"> <input name="enews" type="hidden" id="enews" value="DoReUserpage"> 
      </td>
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
