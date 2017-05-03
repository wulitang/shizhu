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
CheckLevel($logininid,$loginin,$classid,"userjs");
$enews=ehtmlspecialchars($_GET['enews']);
$cid=(int)$_GET['cid'];
$url="<a href=ListUserjs.php".$ecms_hashur['whehref'].">管理用户自定义JS</a> &gt; 增加自定义JS";
$r[jsfilename]="../../d/js/js/".time().".js";
$r[jssql]="select * from [!db.pre!]ecms_news order by id desc limit 10";
//复制
if($enews=="AddUserjs"&&$_GET['docopy'])
{
	$jsid=(int)$_GET['jsid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsuserjs where jsid='$jsid'");
	$url="<a href=ListUserjs.php".$ecms_hashur['whehref'].">管理用户自定义JS</a> &gt; 复制自定义JS：<b>".$r[jsname]."</b>";
}
//修改
if($enews=="EditUserjs")
{
	$jsid=(int)$_GET['jsid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsuserjs where jsid='$jsid'");
	$url="<a href=ListUserjs.php".$ecms_hashur['whehref'].">管理用户自定义JS</a> -&gt; 修改自定义JS：<b>".$r[jsname]."</b>";
}
//js模板
$jstempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsjstemp")." order by tempid");
while($jstempr=$empire->fetch($jstempsql))
{
	$select="";
	if($r[jstempid]==$jstempr[tempid])
	{
		$select=" selected";
	}
	$jstemp.="<option value='".$jstempr[tempid]."'".$select.">".$jstempr[tempname]."</option>";
}
//当前使用的模板组
$thegid=GetDoTempGid();
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsuserjsclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$r[classid])
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
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
<title>用户自定义JS</title>
<style>
.comm-table td p{ padding:5px;}
</style>
<script>
// 管理js分类
function gljsfl(){
art.dialog.open('other/UserjsClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理js分类',width: 800, height: 540});
}
//管理JS模板
function gljsmb(){
art.dialog.open('template/ListJstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理JS模板',width: 800, height: 540});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form name="form1" method="post" action="ListUserjs.php">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="jsid" type="hidden" id="jsid" value="<?=$jsid?>"> 
        <input name="oldjsfilename" type="hidden" id="oldjsfilename" value="<?=$r[jsfilename]?>">
        <input name="cid" type="hidden" id="cid" value="<?=$cid?>">
		<tr>
			<th style="width:200px;"><h3><span>增加用户自定义JS</span></h3></th>
			<th></th>
		</tr>
        <tr>
        	<td>JS名称:</td>
            <td style="text-align:left;"><input name="jsname" type="text" id="jsname" value="<?=$r[jsname]?>" size="42"></td>
        </tr>
        <tr>
        	<td>所属分类:</td>
            <td style="text-align:left;"><select name="classid" id="classid">
        <option value="0">不隶属于任何类别</option>
        <?=$cstr?>
      </select>
        <input type="button" name="Submit6222322" value="管理分类" onclick="gljsfl()"></td>
        </tr>
        <tr>
        	<td>JS存放地址:</td>
            <td style="text-align:left;"><input name="jsfilename" type="text" id="jsfilename" value="<?=$r[jsfilename]?>" size="42"> 
        <font color="#666666"> 
        <input type="button" name="Submit4" value="选择目录" onclick="window.open('../file/ChangePath.php?<?=$ecms_hashur['ehref']?>&returnform=opener.document.form1.jsfilename.value','','width=400,height=500,scrollbars=yes');">
        (如:<strong>&quot;../../1.js</strong>&quot;表示根目录下的1.js)</font></td>
        </tr>
        <tr>
        	<td>查询SQL语句:</td>
            <td style="text-align:left;"><p>
              <input name="jssql" type="text" id="jssql" value="<?=ehtmlspecialchars(stripSlashes($r[jssql]))?>" size="72">
            </p>
              <p><font color="#666666">(如：select * from phome_ecms_news where 
        classid=1 order by id desc limit 10)</font>&nbsp;</p></td>
        </tr>
        <tr>
        	<td>使用JS模板:</td>
            <td style="text-align:left;"><select name="jstempid" id="jstempid">
          <?=$jstemp?>
        </select> <input type="button" name="Submit62223" value="管理JS模板" onclick="gljsmb()"></td>
        </tr>
        <tr>
        	<td></td>
            <td style="text-align:left;"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
        </tr>
        <tr>
        	<td></td>
            <td style="text-align:left;">表前缀可用“<strong>[!db.pre!]</strong>”表示</td>
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
