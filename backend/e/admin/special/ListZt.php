<?php
define('EmpireCMSAdmin','1');
require('../../class/connect.php');
require('../../class/db_sql.php');
require('../../class/functions.php');
require '../'.LoadLang('pub/fun.php');
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
CheckLevel($logininid,$loginin,$classid,"zt");

//修改栏目顺序
function EditZtOrder($ztid,$myorder,$userid,$username){
	global $empire,$dbtbpre;
	for($i=0;$i<count($ztid);$i++)
	{
		$newmyorder=(int)$myorder[$i];
		$ztid[$i]=(int)$ztid[$i];
		$sql=$empire->query("update {$dbtbpre}enewszt set myorder='$newmyorder' where ztid='$ztid[$i]'");
    }
	//操作日志
	insert_dolog("");
	printerror("EditZtOrderSuccess",$_SERVER['HTTP_REFERER']);
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//修改显示顺序
if($enews=="EditZtOrder")
{
	EditZtOrder($_POST['ztid'],$_POST['myorder'],$logininid,$loginin);
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add="";
$search="";
$search.=$ecms_hashur['ehref'];
$url="<a href=ListZt.php".$ecms_hashur['whehref'].">管理专题</a>";
//类别
$zcid=(int)$_GET['zcid'];
if($zcid)
{
	$add=" where zcid=$zcid";
	$search.="&zcid=$zcid";
}
//分类
$zcstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsztclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$zcid)
	{
		$select=" selected";
	}
	$zcstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
}
$totalquery="select count(*) as total from {$dbtbpre}enewszt".$add;
$query="select * from {$dbtbpre}enewszt".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by myorder,ztid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>专题</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
//增加专题
function zjzt(){
art.dialog.open('special/AddZt.php?enews=AddZt<?=$ecms_hashur['ehref']?>',
    {title: '增加专题',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理栏目
function sjgx(){
art.dialog.open('ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>',
    {title: '数据更新',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<form name="form1" method="get" action="ListZt.php">
        	<h3><span>专题按分类显示：<select name="zcid" id="zcid" onchange="document.form1.submit()">
          <option value="0">显示所有分类</option>
          <?=$zcstr?>
        </select> <a href="javascript:void(0)" onclick="zjzt()" class="add">增加专题</a> <a href="javascript:void(0)" onclick="sjgx()" class="gl">数据更新中心</a></span></h3></form>
            <div class="line"></div>
<div class="jqui">
 <form name="editorder" method="post" action="ListZt.php">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>顺序</th>
			<th>ID</th>
			<th>专题名</th>
            <th>增加时间</th>
			<th>访问量</th>
            <th>专题管理</th>
            <th>操作</th>
		</tr>
    <?
  while($r=$empire->fetch($sql))
  {
  if($r[zturl])
  {
  	$ztlink=$r[zturl];
  }
  else
  {
  	$ztlink="../../../".$r[ztpath];
  }
  ?>
   <tr bgcolor="ffffff" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="myorder[]" type="text" id="myorder[]" value="<?=$r[myorder]?>" size="2">
          <input name="ztid[]" type="hidden" id="ztid[]" value="<?=$r[ztid]?>">
        </div></td>
      <td height="25"><div align="center"> 
          <a href="../ecmschtml.php?enews=ReZtHtml&ztid=<?=$r[ztid]?><?=$ecms_hashur['href']?>"><?=$r[ztid]?></a>
        </div></td>
      <td height="25"><div align="center"> 
          <a href="<?=$ztlink?>" target="_blank"><?=$r[ztname]?></a>
        </div></td>
      <td><div align="center"><?=$r['addtime']?date("Y-m-d H:i:s",$r['addtime']):'---'?></div></td>
      <td><div align="center"> 
          <?=$r[onclick]?>
        </div></td>
      <td><a href="AddZt.php?enews=EditZt&ztid=<?=$r[ztid]?><?=$ecms_hashur['ehref']?>">修改</a> <a href="../ecmschtml.php?enews=ReZtHtml&ztid=<?=$r[ztid]?>&ecms=1<?=$ecms_hashur['href']?>">刷新</a> <a href="AddZt.php?enews=AddZt&ztid=<?=$r[ztid]?>&docopy=1<?=$ecms_hashur['ehref']?>">复制</a> <a href="../ecmsclass.php?enews=DelZt&ztid=<?=$r[ztid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除此专题？');">删除</a></td>
      <td height="25"><div align="center"><a href="#ecms" onclick="window.open('../openpage/AdminPage.php?leftfile=<?=urlencode('../special/pageleft.php?ztid='.$r[ztid].$ecms_hashur['ehref'])?>&title=<?=urlencode($r[ztname])?><?=$ecms_hashur['ehref']?>','','');">更新专题</a></div></td>
    </tr>
    <?
  }
  ?>
  		<tr>
		  <td colspan="7" style="text-align:left;">
          <div align="left" class="anniuqun"> &nbsp;&nbsp;
          <input type="submit" name="Submit5" value="修改专题顺序" onClick="document.editorder.enews.value='EditZtOrder';"> 
        <input name="enews" type="hidden" id="enews" value="EditZtOrder"> 
      <font color="#666666">(顺序值越小越前面)</font>
        </div>
          </td>
		  </tr>
  		<tr>
  		  <td colspan="7" style="text-align:left;"><?=$returnpage?></td>
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
