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

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
	include("../../class/com_functions.php");
}
if($enews=="ReMoreFeedbackClassFile")
{
	ReMoreFeedbackClassFile(0,$logininid,$loginin);
}
//验证权限
CheckLevel($logininid,$loginin,$classid,"feedbackf");
include "../".LoadLang("pub/fun.php");
if($enews=="AddFeedbackClass")
{
	AddFeedbackClass($_POST,$logininid,$loginin);
}
elseif($enews=="EditFeedbackClass")
{
	EditFeedbackClass($_POST,$logininid,$loginin);
}
elseif($enews=="DelFeedbackClass")
{
	DelFeedbackClass($_GET,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=23;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select bid,bname from {$dbtbpre}enewsfeedbackclass";
$totalquery="select count(*) as total from {$dbtbpre}enewsfeedbackclass";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by bid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="feedback.php<?=$ecms_hashur['whehref']?>">管理信息反馈</a>&nbsp;&gt;&nbsp;<a href="FeedbackClass.php<?=$ecms_hashur['whehref']?>">管理反馈分类</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理充值类型 <a href="AddFeedbackClass.php?enews=AddFeedbackClass<?=$ecms_hashur['ehref']?>" class="add">增加反馈分类</a> <a href="ListFeedbackF.php<?=$ecms_hashur['whehref']?>" class="gl">管理反馈字段</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>分类名称</th>
            <th>反馈提交地址</th>
            <th>操作</th>
		</tr>
<?
  while($r=$empire->fetch($sql))
  {
  	$gourl=$public_r[newsurl].'e/tool/feedback/?bid='.$r[bid];
  ?>
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <?=$r[bid]?>
        </div></td>
      <td height="25"> <div align="center"> 
          <a href="feedback.php?bid=<?=$r[bid]?><?=$ecms_hashur['ehref']?>" target="_blank" title="管理分类下的反馈信息"><?=$r[bname]?></a>
        </div></td>
      <td><div align="center"> 
          <input name="textfield" type="text" size="38" value="<?=$gourl?>">
          [<a href="<?=$gourl?>" target="_blank">访问</a>]</div></td>
      <td height="25"><div align="center">[<a href="AddFeedbackClass.php?enews=EditFeedbackClass&bid=<?=$r[bid]?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="AddFeedbackClass.php?enews=AddFeedbackClass&bid=<?=$r[bid]?>&docopy=1<?=$ecms_hashur['ehref']?>">复制</a>] [<a href="FeedbackClass.php?enews=DelFeedbackClass&bid=<?=$r[bid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>] </div></td>
    </tr>
  <?
  }
  db_close();
  $empire=null;
  ?>
  		<tr>
  		  <td colspan="3" style="height:35px; overflow:hidden;margin:0;background:#F2F2F2; padding:10px 0;"><?=$returnpage?></td>
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
