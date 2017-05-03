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

//修改密码
function EditPassword($userid,$username,$oldpassword,$password,$repassword,$styleid,$oldstyleid,$add){
	global $empire,$dbtbpre,$gr;
	$styleid=(int)$styleid;
	$oldstyleid=(int)$oldstyleid;
	$username=RepPostVar($username);
	$oldpassword=RepPostVar($oldpassword);
	$password=RepPostVar($password);
	$truename=RepPostStr($add[truename]);
	$email=RepPostStr($add[email]);
	if(!$userid||!$username)
	{
		printerror("EmptyOldPassword","history.go(-1)");
	}
	//修改密码
	$a='';
	if($oldpassword)
	{
		if(!$username||!$oldpassword)
		{
			printerror("EmptyOldPassword","history.go(-1)");
		}
		if(!trim($password)||!trim($repassword))
		{
			printerror("EmptyNewPassword","history.go(-1)");
		}
		if($password<>$repassword)
		{
			printerror("NotRepassword","history.go(-1)");
		}
		if(strlen($password)<6)
		{
			printerror("LessPassword","history.go(-1)");
		}
		$user_r=$empire->fetch1("select userid,password,salt,salt2 from {$dbtbpre}enewsuser where username='".$username."' limit 1");
		if(!$user_r['userid'])
		{
			printerror("OldPasswordFail","history.go(-1)");
		}
		$ch_oldpassword=DoEmpireCMSAdminPassword($oldpassword,$user_r['salt'],$user_r['salt2']);
		if($user_r['password']!=$ch_oldpassword)
		{
			printerror("OldPasswordFail","history.go(-1)");
		}
		$salt=make_password(8);
		$salt2=make_password(20);
		$password=DoEmpireCMSAdminPassword($password,$salt,$salt2);
		$a=",password='$password',salt='$salt',salt2='$salt2'";
	}
	//风格
	if($gr['dochadminstyle'])
	{
		$a.=",styleid='$styleid'";
	}
	$sql=$empire->query("update {$dbtbpre}enewsuser set truename='$truename',email='$email'".$a." where username='$username'");
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
	$empire->query("update {$dbtbpre}enewsuseradd set equestion='$equestion'".$uadd." where userid='$userid'");
	if($sql)
	{
		//操作日志
		insert_dolog("");
		//改变风格
		if($styleid!=$oldstyleid)
		{
			$styler=$empire->fetch1("select path from {$dbtbpre}enewsadminstyle where styleid='$styleid'");
			if($styler['path'])
			{
				$set=esetcookie("loginadminstyleid",$styler['path'],0,1);
			}
			printerror("EditPasswordSuccessLogin","../index.php");
			//echo"Edit password success!<script>parent.location.href='../admin.php".hReturnEcmsHashStrHref2(1)."';</script>";
			exit();
		}
		else
		{
			printerror("EditPasswordSuccess","EditPassword.php".hReturnEcmsHashStrHref2(1));
		}
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$gr=$empire->fetch1("select dochadminstyle from {$dbtbpre}enewsgroup where groupid='$loginlevel'");

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//修改密码
if($enews=="EditPassword")
{
	$oldpassword=$_POST['oldpassword'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$styleid=(int)$_POST['styleid'];
	$oldstyleid=(int)$_POST['oldstyleid'];
	EditPassword($logininid,$loginin,$oldpassword,$password,$repassword,$styleid,$oldstyleid,$_POST);
}

$r=$empire->fetch1("select userid,styleid,truename,email from {$dbtbpre}enewsuser where userid='$logininid'");
$addur=$empire->fetch1("select equestion from {$dbtbpre}enewsuseradd where userid='$r[userid]'");
if($gr['dochadminstyle'])
{
	//后台样式
	$stylesql=$empire->query("select styleid,stylename,path from {$dbtbpre}enewsadminstyle order by styleid");
	$style="";
	while($styler=$empire->fetch($stylesql))
	{
		if($r[styleid]==$styler[styleid])
		{$sselect=" selected";}
		else
		{$sselect="";}
		$style.="<option value=".$styler[styleid].$sselect.">".$styler[stylename]."</option>";
	}
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<SCRIPT>
//管理后台样式
function glhtys(){
art.dialog.open('template/AdminStyle.php<?=$ecms_hashur['whehref']?>',
    {title: '管理后台样式',lock: true,opacity: 0.5,width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</SCRIPT>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="EditPassword.php<?=$ecms_hashur['whehref']?>">修改个人资料</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>修改资料</span></h3>
<div class="jqui anniuqun">
<form name="form1" method="post" action="EditPassword.php">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="EditPassword">
<table class="comm-table" cellspacing="0">
	<tbody>
        <tr>
        	<td>用户名：</td>
            <td style="text-align:left;"><?=$loginin?></td>
        </tr>
        <tr>
        	<td>旧密码:</td>
            <td style="text-align:left;"><input name="oldpassword" type="password" id="oldpassword" size="32"> 
        <font color="#666666">(不修改密码,请留空) </font></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
      <td>新密码：</td>
      <td style="text-align:left;"><input name="password" type="password" id="password" size="32"> 
        <font color="#666666">(不修改密码,请留空) </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>重复新密码：</td>
      <td style="text-align:left;"><input name="repassword" type="password" id="repassword" size="32"> 
        <font color="#666666">(不修改密码,请留空) </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>安全提问：</td>
      <td style="text-align:left;"> <select name="equestion" id="equestion">
          <option value="0"<?=$addur[equestion]==0?' selected':''?>>无安全提问</option>
          <option value="1"<?=$addur[equestion]==1?' selected':''?>>母亲的名字</option>
          <option value="2"<?=$addur[equestion]==2?' selected':''?>>爷爷的名字</option>
          <option value="3"<?=$addur[equestion]==3?' selected':''?>>父亲出生的城市</option>
          <option value="4"<?=$addur[equestion]==4?' selected':''?>>您其中一位老师的名字</option>
          <option value="5"<?=$addur[equestion]==5?' selected':''?>>您个人计算机的型号</option>
          <option value="6"<?=$addur[equestion]==6?' selected':''?>>您最喜欢的餐馆名称</option>
          <option value="7"<?=$addur[equestion]==7?' selected':''?>>驾驶执照的最后四位数字</option>
        </select> <font color="#666666"> 
        <input name="oldequestion" type="hidden" id="oldequestion" value="<?=$addur[equestion]?>">
        (如果启用安全提问，登录时需填入相应的项目才能登录)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>安全回答：</td>
      <td style="text-align:left;"><input name="eanswer" type="text" id="eanswer" size="32"> 
        <font color="#666666">(如果修改答案，请在此输入新答案)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>姓名：</td>
      <td style="text-align:left;"><input name="truename" type="text" id="truename" value="<?=$r[truename]?>" size="32"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>邮箱：</td>
      <td style="text-align:left;"><input name="email" type="text" id="email" value="<?=$r[email]?>" size="32"></td>
    </tr>
    <?php
	if($gr['dochadminstyle'])
	{
	?>
    <tr bgcolor="#FFFFFF"> 
      <td>操作界面：</td>
      <td style="text-align:left;"><select name="styleid" id="styleid">
          <?=$style?>
        </select> <input type="button" name="Submit6222322" value="管理后台样式" onClick="glhtys()"> 
        <input name="oldstyleid" type="hidden" id="oldstyleid" value="<?=$r[styleid]?>"> 
      </td>
    </tr>
    <?php
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td style="text-align:left;"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置">
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><font color="#666666">说明：密码设置6位以上，且密码不能包含：$ 
        &amp; * # &lt; &gt; ' &quot; / \ % ; 空格</font></td>
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
