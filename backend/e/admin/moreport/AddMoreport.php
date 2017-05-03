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
CheckLevel($logininid,$loginin,$classid,"moreport");
$enews=ehtmlspecialchars($_GET['enews']);
$r['ppath']=ReturnAbsEcmsPath();
$url="<a href=ListMoreport.php".$ecms_hashur['whehref'].">管理网站访问端</a> &gt; 增加网站访问端";
$postword='增加网站访问端';
if($enews=="EditMoreport")
{
	$pid=(int)$_GET['pid'];
	if($pid==1)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	$r=$empire->fetch1("select * from {$dbtbpre}enewsmoreport where pid='$pid' limit 1");
	$url="<a href=ListMoreport.php".$ecms_hashur['whehref'].">管理网站访问端</a> &gt; 修改网站访问端：<b>".$r[pname]."</b>";
	$postword='修改网站访问端';
}
$tgtemps='';
$tgsql=$empire->query("select gid,gname,isdefault from {$dbtbpre}enewstempgroup order by gid");
while($tgr=$empire->fetch($tgsql))
{
	$selected='';
	if($tgr['gid']==$r['tempgid'])
	{
		$selected=' selected';
	}
	$tgtemps.="<option value='".$tgr['gid']."'".$selected.">".$tgr['gname']."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title>增加网站访问端</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container" style="overflow-x:hidden;">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div class="kongbai"></div>
<form name="moreportform" method="post" action="ListMoreport.php">
  <?=$ecms_hashur['form']?>
 <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
        <input name="pid" type="hidden" id="pid" value="<?=$pid?>">
    <div id="tab"  style="overflow:hidden;">
	<div class="ui-tab-container">
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
            <div class="newscon anniuqun">
<div class="ui-tab-content">
        	<h3><span>增加网站访问端</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>访问端名称(*)</label><input name="pname" type="text" id="pname" value="<?=$r[pname]?>" size="30"><font color="#666666">(比如：手机访问端)</font></li>
            <li><label>访问端地址:</label><input name="purl" type="text" id="purl" value="<?=$r[purl]?>" size="30">
        *        <font color="#666666">(结尾需加“/”，比如：http://3g.yecha.com/)</font></li>

            <li class="jqui"><label>访问端目录:</label><input name="ppath" type="text" id="ppath" value="<?=$r[ppath]?>" size="30">
        *<font color="#666666">(需填绝对目录地址，结尾需加“/”，比如：d:/abc/3g/)</font></li>
            <li><label>通讯密钥:</label><input name="postpass" type="text" id="postpass" value="<?=$r[postpass]?>" size="30">
        *
        <input type="button" name="Submit32" value="随机" onclick="document.moreportform.postpass.value='<?=make_password(60)?>';">
      <font color="#666666">(填写10~100个任意字符，最好多种字符组合)</font></li>
            <li><label>页面模式:</label><input type="radio" name="mustdt" value="1"<?=$r[mustdt]==1?' checked':''?>>
        <a href="#empirecms" title="强制动态页面模式时，访问端首页、栏目、内容页等均采用动态页面方式显示，好处是：不用再生成静态页面">强制动态页面模式</a>
        <input type="radio" name="mustdt" value="0"<?=$r[mustdt]==0?' checked':''?>>
        <a href="#empirecms" title="与主端相同：如果主端是采用静态页面模式，需要在本访问端后台生成页面，才会同步显示。">与主端相同</a></li>
		<li class="jqui"><label>关闭访问端:</label><input name="isclose" type="checkbox" id="isclose" value="1"<?=$r[isclose]==1?' checked':''?>>
      关闭</li>
            <li><label>关闭投稿:</label><input name="closeadd" type="checkbox" id="closeadd" value="1"<?=$r[closeadd]==1?' checked':''?>>
        关闭</li>
        </ul>
       
            </div>
        </div>
        	</div>
        </div>
 <div class="line"></div>
  </div>
 <div class="sub jqui"><input type="submit" name="addnews2" value="提交" class="anniu">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit23" value="重置" class="anniu"></div>
 </div>
</div>
 </form>
 <div class="clear"></div>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>