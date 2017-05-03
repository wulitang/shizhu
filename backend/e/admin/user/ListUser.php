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
CheckLevel($logininid,$loginin,$classid,"user");

//------------------------增加用户
function AddUser($username,$password,$repassword,$groupid,$adminclass,$checked,$styleid,$loginuserid,$loginusername){global $empire,$class_r,$dbtbpre;
	if(!$username||!$password||!$repassword)
	{printerror("EmptyUsername","history.go(-1)");}
	if($password!=$repassword)
	{printerror("NotRepassword","history.go(-1)");}
	if(strlen($password)<6)
	{
		printerror("LessPassword","history.go(-1)");
	}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where username='$username' limit 1");
	if($num)
	{printerror("ReUsername","history.go(-1)");}
	//管理目录
	for($i=0;$i<count($adminclass);$i++)
	{
		//大栏目
		if(empty($class_r[$adminclass[$i]][islast]))
		{
			if(empty($class_r[$adminclass[$i]][sonclass])||$class_r[$adminclass[$i]][sonclass]=="|")
			{
				continue;
			}
			else
			{
				$andclass=substr($class_r[$adminclass[$i]][sonclass],1);
			}
			$insert_class.=$andclass;
		}
		else
		{
			$insert_class.=$adminclass[$i]."|";
		}
    }
	$insert_class="|".$insert_class;
	$styleid=(int)$styleid;
	$groupid=(int)$groupid;
	$checked=(int)$checked;
	$filelevel=(int)$_POST['filelevel'];
	$classid=(int)$_POST['classid'];
	$rnd=make_password(20);
	$salt=make_password(8);
	$salt2=make_password(20);
	$password=DoEmpireCMSAdminPassword($password,$salt,$salt2);
	$truename=ehtmlspecialchars($_POST['truename']);
	$email=ehtmlspecialchars($_POST['email']);
	$openip=ehtmlspecialchars($_POST['openip']);
	$addtime=time();
	$addip=egetip();
	$addipport=egetipport();
	$userprikey=make_password(48);
	$sql=$empire->query("insert into {$dbtbpre}enewsuser(username,password,rnd,groupid,adminclass,checked,styleid,filelevel,salt,loginnum,lasttime,lastip,truename,email,classid,addtime,addip,userprikey,salt2,lastipport,preipport,addipport) values('$username','$password','$rnd',$groupid,'$insert_class',$checked,$styleid,'$filelevel','$salt',0,0,'','$truename','$email','$classid','$addtime','$addip','$userprikey','$salt2','$addipport','$addipport','$addipport');");
	$userid=$empire->lastid();
	//安全提问
	$equestion=(int)$_POST['equestion'];
	$eanswer=$_POST['eanswer'];
	if($equestion)
	{
		if(!$eanswer)
		{
			printerror('EmptyEAnswer','');
		}
		$eanswer=ReturnHLoginQuestionStr($userid,$username,$equestion,$eanswer);
	}
	else
	{
		$equestion=0;
		$eanswer='';
	}
	$empire->query("insert into {$dbtbpre}enewsuseradd(userid,equestion,eanswer,openip) values('$userid','$equestion','$eanswer','$openip');");
	if($sql)
	{
		$cache_enews='douserinfo';
		$cache_ecmstourl=urlencode('user/AddUser.php?enews=AddUser'.hReturnEcmsHashStrHref2(0));
		$cache_mess='AddUserSuccess';
		$cache_uid=$userid;
		$cache_url="../CreateCache.php?enews=$cache_enews&uid=$cache_uid&ecmstourl=$cache_ecmstourl&mess=$cache_mess".hReturnEcmsHashStrHref2(0);
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$username);
		//printerror("AddUserSuccess","AddUser.php?enews=AddUser".hReturnEcmsHashStrHref2(0));
		echo'<meta http-equiv="refresh" content="0;url='.$cache_url.'">';
		db_close();
		$empire=null;
		exit();
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//------------------------修改用户
function EditUser($userid,$username,$password,$repassword,$groupid,$adminclass,$oldusername,$checked,$styleid,$loginuserid,$loginusername){
	global $empire,$class_r,$dbtbpre;
	$userid=(int)$userid;
	if(!$userid||!$username)
	{printerror("EnterUsername","history.go(-1)");}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	//修改用户名
	if($oldusername<>$username)
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where username='$username' and userid<>$userid limit 1");
		if($num)
		{printerror("ReUsername","history.go(-1)");}
		//修改信息
		//$nsql=$empire->query("update {$dbtbpre}enewsnews set username='$username' where username='$oldusername'");
		//修改日志
		$lsql=$empire->query("update {$dbtbpre}enewslog set username='$username' where username='$oldusername'");
		$lsql=$empire->query("update {$dbtbpre}enewsdolog set username='$username' where username='$oldusername'");
	}
	//修改密码
	if($password)
	{
		if($password!=$repassword)
		{printerror("NotRepassword","history.go(-1)");}
		if(strlen($password)<6)
		{
			printerror("LessPassword","history.go(-1)");
		}
		$salt=make_password(8);
		$salt2=make_password(20);
		$password=DoEmpireCMSAdminPassword($password,$salt,$salt2);
		$add=",password='$password',salt='$salt',salt2='$salt2'";
	}
	//管理目录
	for($i=0;$i<count($adminclass);$i++)
	{
		//大栏目
		if(empty($class_r[$adminclass[$i]][islast]))
		{
			if(empty($class_r[$adminclass[$i]][sonclass])||$class_r[$adminclass[$i]][sonclass]=="|")
			{
				continue;
			}
			else
			{
				$andclass=substr($class_r[$adminclass[$i]][sonclass],1);
			}
			$insert_class.=$andclass;
		}
		else
		{
			$insert_class.=$adminclass[$i]."|";
		}
    }
	$insert_class="|".$insert_class;
	$styleid=(int)$styleid;
	$groupid=(int)$groupid;
	$checked=(int)$checked;
	$filelevel=(int)$_POST['filelevel'];
	$classid=(int)$_POST['classid'];
	$truename=ehtmlspecialchars($_POST['truename']);
	$email=ehtmlspecialchars($_POST['email']);
	$openip=ehtmlspecialchars($_POST['openip']);
	$sql=$empire->query("update {$dbtbpre}enewsuser set username='$username',groupid=$groupid,adminclass='$insert_class',checked=$checked,styleid=$styleid,filelevel='$filelevel',truename='$truename',email='$email',classid='$classid'".$add." where userid='$userid'");
	//安全提问
	$equestion=(int)$_POST['equestion'];
	$eanswer=$_POST['eanswer'];
	$uadd='';
	if($equestion)
	{
		if($equestion!=$_POST['oldequestion']&&!$eanswer)
		{
			printerror('EmptyEAnswer','');
		}
		if($eanswer)
		{
			$eanswer=ReturnHLoginQuestionStr($userid,$username,$equestion,$eanswer);
			$uadd=",eanswer='$eanswer'";
		}
	}
	else
	{
		$uadd=",eanswer=''";
	}
	$empire->query("update {$dbtbpre}enewsuseradd set equestion='$equestion',openip='$openip'".$uadd." where userid='$userid'");
	if($sql)
	{
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$username);
		if($_POST['oldadminclass']<>$insert_class)
		{
			DelFiletext('../../data/fc/ListEnews'.$userid.'.php');
			DelFiletext('../../data/fc/userclass'.$userid.'.js');
			//删除导航缓存
			$empire->query("delete from {$dbtbpre}enewsclassnavcache where navtype='userenews' and userid='$userid'");
			$cache_enews='douserinfo';
			$cache_ecmstourl=urlencode('user/ListUser.php'.hReturnEcmsHashStrHref2(1));
			$cache_mess='EditUserSuccess';
			$cache_uid=$userid;
			$cache_url="../CreateCache.php?enews=$cache_enews&uid=$cache_uid&ecmstourl=$cache_ecmstourl&mess=$cache_mess".hReturnEcmsHashStrHref2(0);
			echo'<meta http-equiv="refresh" content="0;url='.$cache_url.'">';
			db_close();
			$empire=null;
			exit();
		}
		printerror("EditUserSuccess","ListUser.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//-----------------------删除用户
function DelUser($userid,$loginuserid,$loginusername){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	if(!$userid)
	{printerror("NotDelUserid","history.go(-1)");}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	//验证是否最后一个管理员
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser");
	if($num<=1)
	{
		printerror("LastUserNotToDel","history.go(-1)");
	}
	$r=$empire->fetch1("select username from {$dbtbpre}enewsuser where userid='$userid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsuser where userid='$userid'");
	$sql1=$empire->query("delete from {$dbtbpre}enewsuseradd where userid='$userid'");
	if($sql)
	{
		DelFiletext('../../data/fc/ListEnews'.$userid.'.php');
		DelFiletext('../../data/fc/userclass'.$userid.'.js');
		//删除导航缓存
		$empire->query("delete from {$dbtbpre}enewsclassnavcache where navtype='userenews' and userid='$userid'");
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$r[username]);
		printerror("DelUserSuccess","ListUser.php".hReturnEcmsHashStrHref2(1));
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
	include('../../data/dbcache/class.php');
}
//增加用户
if($enews=="AddUser")
{
	$username=$_POST['username'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$groupid=$_POST['groupid'];
	$adminclass=$_POST['adminclass'];
	$checked=$_POST['checked'];
	$styleid=$_POST['styleid'];
	AddUser($username,$password,$repassword,$groupid,$adminclass,$checked,$styleid,$logininid,$loginin);
}
//修改用户
elseif($enews=="EditUser")
{
	$userid=$_POST['userid'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$groupid=$_POST['groupid'];
	$adminclass=$_POST['adminclass'];
	$oldusername=$_POST['oldusername'];
	$checked=$_POST['checked'];
	$styleid=$_POST['styleid'];
	EditUser($userid,$username,$password,$repassword,$groupid,$adminclass,$oldusername,$checked,$styleid,$logininid,$loginin);
}
//删除用户
elseif($enews=="DelUser")
{
	$userid=$_GET['userid'];
	DelUser($userid,$logininid,$loginin);
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$url="<a href=ListUser.php".$ecms_hashur['whehref'].">管理用户</a>";
//排序
$mydesc=(int)$_GET['mydesc'];
$desc=$mydesc?'asc':'desc';
$orderby=(int)$_GET['orderby'];
if($orderby==1)//用户名
{
	$order="username ".$desc.",userid desc";
	$usernamedesc=$mydesc?0:1;
}
elseif($orderby==2)//用户组
{
	$order="groupid ".$desc.",userid desc";
	$groupiddesc=$mydesc?0:1;
}
elseif($orderby==3)//状态
{
	$order="checked ".$desc.",userid desc";
	$checkeddesc=$mydesc?0:1;
}
elseif($orderby==4)//登陆次数
{
	$order="loginnum ".$desc.",userid desc";
	$loginnumdesc=$mydesc?0:1;
}
elseif($orderby==5)//最后登陆
{
	$order="lasttime ".$desc.",userid desc";
	$lasttimedesc=$mydesc?0:1;
}
else//用户ID
{
	$order="userid ".$desc;
	$useriddesc=$mydesc?0:1;
}
$search="&orderby=$orderby&mydesc=$mydesc".$ecms_hashur['ehref'];
$query="select * from {$dbtbpre}enewsuser";
$num=$empire->num($query);//取得总条数
$query=$query." order by ".$order." limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理用户</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
//管理部门
function glbm(){
art.dialog.open('user/UserClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理部门',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}

</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理用户 <a href="AddUser.php?enews=AddUser<?=$ecms_hashur['ehref']?>" class="add">增加用户</a> <a href="javascript:void()" onclick="glbm()" class="gl">管理部门</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th><a href="ListUser.php?orderby=0&mydesc=<?=$useriddesc?><?=$ecms_hashur['ehref']?>">ID</a></th>
			<th><a href="ListUser.php?orderby=1&mydesc=<?=$usernamedesc?><?=$ecms_hashur['ehref']?>">用户名</a></th>
            <th><a href="ListUser.php?orderby=2&mydesc=<?=$groupiddesc?><?=$ecms_hashur['ehref']?>">等级</a></th>
            <th><a href="ListUser.php?orderby=3&mydesc=<?=$checkeddesc?><?=$ecms_hashur['ehref']?>">状态</a></th>
            <th>证书</th>
            <th><a href="ListUser.php?orderby=4&mydesc=<?=$loginnumdesc?><?=$ecms_hashur['ehref']?>">登陆次数</a></th>
            <th><a href="ListUser.php?orderby=5&mydesc=<?=$lasttimedesc?><?=$ecms_hashur['ehref']?>">最后登陆</a></th>
            <th>操作</th>
		</tr>
 <?
  while($r=$empire->fetch($sql))
  {
  	$classname='--';
	if($r[classid])
	{
  		$cr=$empire->fetch1("select classname from {$dbtbpre}enewsuserclass where classid='$r[classid]'");
		$classname=$cr['classname'];
	}
	$gr=$empire->fetch1("select groupname from {$dbtbpre}enewsgroup where groupid='$r[groupid]'");
  	if($r[checked])
  	{$zt="禁用";}
  	else
  	{$zt="开启";}
  	$lasttime='---';
  	if($r[lasttime])
  	{
  		$lasttime=date("Y-m-d H:i:s",$r[lasttime]);
  	}
	$usercertkey='--';
  ?>
  <tr bgcolor="ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r[userid]?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r[username]?>
      </div></td>
    <td> <div align="left">用户组：
        <?=$gr[groupname]?>
        <br>
        部门&nbsp;&nbsp;&nbsp;：
        <?=$classname?>
      </div></td>
    <td><div align="center"> 
        <?=$zt?>
      </div></td>
    <td><div align="center"><?=$usercertkey?></div></td>
    <td><div align="center">
        <?=$r[loginnum]?>
      </div></td>
    <td> 时间：
      <?=$lasttime?>
      <br>
      IP&nbsp;&nbsp;&nbsp;：
      <?=$r[lastip]?$r[lastip].':'.$r[lastipport]:'---'?>
    </td>
    <td height="25"><div align="center">[<a href="AddUser.php?enews=EditUser&userid=<?=$r[userid]?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="ListUser.php?enews=DelUser&userid=<?=$r[userid]?><?=$ecms_hashur['href']?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  		<tr>
  		  <td colspan="8" style="text-align:left;"><?=$returnpage?></td>
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
