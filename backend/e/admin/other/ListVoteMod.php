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
CheckLevel($logininid,$loginin,$classid,"votemod");

//增加预设投票
function AddVoteMod($ysvotename,$title,$votename,$votenum,$delvid,$vid,$voteclass,$doip,$dotime,$width,$height,$tempid,$userid,$username){
	global $empire,$dbtbpre;
	if(!$ysvotename||!$tempid)
	{
		printerror("EmptyVoteTitle","history.go(-1)");
	}
	//返回组合
	$votetext=ReturnVote($votename,$votenum,$delvid,$vid,0);
	//统计总票数
	for($i=0;$i<count($votename);$i++)
	{
		$t_votenum+=$votenum[$i];
	}
	$dotime=date("Y-m-d");
	$t_votenum=(int)$t_votenum;
	$voteclass=(int)$voteclass;
	$width=(int)$width;
	$height=(int)$height;
	$doip=(int)$doip;
	$tempid=(int)$tempid;
	$sql=$empire->query("insert into {$dbtbpre}enewsvotemod(title,votetext,voteclass,doip,dotime,tempid,width,height,votenum,ysvotename) values('$title','$votetext',$voteclass,$doip,'$dotime',$tempid,$width,$height,$t_votenum,'$ysvotename');");
	$voteid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("voteid=".$voteid."<br>title=".$title);
		printerror("AddVoteSuccess","AddVoteMod.php?enews=AddVoteMod".hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改预设投票
function EditVoteMod($voteid,$ysvotename,$title,$votename,$votenum,$delvid,$vid,$voteclass,$doip,$dotime,$width,$height,$tempid,$userid,$username){
	global $empire,$dbtbpre;
	$voteid=(int)$voteid;
	if(!$voteid||!$ysvotename||!$tempid)
	{
		printerror("EmptyVoteTitle","history.go(-1)");
	}
	//返回组合
	$votetext=ReturnVote($votename,$votenum,$delvid,$vid,1);
	//统计总票数
	for($i=0;$i<count($votename);$i++)
	{
		$t_votenum+=$votenum[$i];
	}
	//处理变量
	$t_votenum=(int)$t_votenum;
	$voteclass=(int)$voteclass;
	$width=(int)$width;
	$height=(int)$height;
	$doip=(int)$doip;
	$tempid=(int)$tempid;
	$sql=$empire->query("update {$dbtbpre}enewsvotemod set title='$title',votetext='$votetext',voteclass=$voteclass,doip=$doip,dotime='$dotime',tempid=$tempid,width=$width,height=$height,votenum=$t_votenum,ysvotename='$ysvotename' where voteid='$voteid'");
	if($sql)
	{
		//操作日志
		insert_dolog("voteid=".$voteid."<br>title=".$title);
		printerror("EditVoteSuccess","ListVoteMod.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除预设投票
function DelVoteMod($voteid,$userid,$username){
	global $empire,$dbtbpre;
	$voteid=(int)$voteid;
	if(!$voteid)
	{
		printerror("NotDelVoteid","history.go(-1)");
	}
	$r=$empire->fetch1("select title from {$dbtbpre}enewsvotemod where voteid='$voteid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsvotemod where voteid='$voteid'");
	if($sql)
	{
		//操作日志
		insert_dolog("voteid=".$voteid."<br>title=".$r[title]);
		printerror("DelVoteSuccess","ListVoteMod.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}


$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//增加投票
if($enews=="AddVoteMod")
{
	$title=$_POST['title'];
	$votename=$_POST['votename'];
	$votenum=$_POST['votenum'];
	$delvid=$_POST['delvid'];
	$vid=$_POST['vid'];
	$voteclass=$_POST['voteclass'];
	$doip=$_POST['doip'];
	$dotime=$_POST['dotime'];
	$width=$_POST['width'];
	$height=$_POST['height'];
	$tempid=$_POST['tempid'];
	$ysvotename=$_POST['ysvotename'];
	AddVoteMod($ysvotename,$title,$votename,$votenum,$delvid,$vid,$voteclass,$doip,$dotime,$width,$height,$tempid,$logininid,$loginin);
}
//修改投票
elseif($enews=="EditVoteMod")
{
	$voteid=$_POST['voteid'];
	$title=$_POST['title'];
	$votename=$_POST['votename'];
	$votenum=$_POST['votenum'];
	$delvid=$_POST['delvid'];
	$vid=$_POST['vid'];
	$voteclass=$_POST['voteclass'];
	$doip=$_POST['doip'];
	$dotime=$_POST['dotime'];
	$width=$_POST['width'];
	$height=$_POST['height'];
	$tempid=$_POST['tempid'];
	$ysvotename=$_POST['ysvotename'];
	EditVoteMod($voteid,$ysvotename,$title,$votename,$votenum,$delvid,$vid,$voteclass,$doip,$dotime,$width,$height,$tempid,$logininid,$loginin);
}
//删除投票
elseif($enews=="DelVoteMod")
{
	$voteid=$_GET['voteid'];
	DelVoteMod($voteid,$logininid,$loginin);
}

$search=$ecms_hashur['ehref'];
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=20;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select voteid,ysvotename,title,voteclass from {$dbtbpre}enewsvotemod";
$num=$empire->num($query);//取得总条数
$query=$query." order by voteid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="<a href=ListVoteMod.php".$ecms_hashur['whehref'].">管理预设投票</a>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>投票</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
 //增加预设投票
function zjystp(){
art.dialog.open('other/AddVoteMod.php?<?=$ecms_hashur[ehref]?>&enews=AddVoteMod',
    {title: '增加预设投票',lock: true,opacity: 0.5, width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理预设投票 <a href="javascript:void(0)" onclick="zjystp()" class="add">增加预设投票</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>投票名称</th>
			<th>类型</th>
            <th>操作</th>
		</tr>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  	$voteclass=empty($r['voteclass'])?'单选':'多选';
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r[voteid]?>
      </div></td>
    <td height="25"> 
      <?=$r[ysvotename]?>
    </td>
    <td height="25"><div align="center"> 
        <?=$voteclass?>
      </div></td>
    <td height="25"><div align="center">[<a href="AddVoteMod.php?enews=EditVoteMod&voteid=<?=$r[voteid]?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="ListVoteMod.php?enews=DelVoteMod&voteid=<?=$r[voteid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除?');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="4">&nbsp; 
      <?=$returnpage?>
    </td>
  </tr>
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
<?
db_close();
$empire=null;
?>
