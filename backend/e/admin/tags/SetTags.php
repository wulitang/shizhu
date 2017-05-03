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
CheckLevel($logininid,$loginin,$classid,"tags");
$r=$empire->fetch1("select opentags,tagstempid,usetags,chtags,tagslistnum from {$dbtbpre}enewspublic limit 1");
//系统模型
$usetags='';
$chtags='';
$i=0;
$modsql=$empire->query("select mid,mname from {$dbtbpre}enewsmod order by myorder,mid");
while($modr=$empire->fetch($modsql))
{
	$i++;
	if($i%4==0)
	{
		$br="<br>";
	}
	else
	{
		$br="";
	}
	$select='';
	if(strstr($r[usetags],','.$modr[mid].','))
	{
		$select=' checked';
	}
	$usetags.="<input type=checkbox name=umid[] value='$modr[mid]'".$select.">$modr[mname]&nbsp;&nbsp;".$br;
	$chselect='';
	if(strstr($r[chtags],','.$modr[mid].','))
	{
		$chselect=' checked';
	}
	$chtags.="<input type=checkbox name=cmid[] value='$modr[mid]'".$chselect.">$modr[mname]&nbsp;&nbsp;".$br;
}
//列表模板
$listtemp_options='';
$msql=$empire->query("select mid,mname from {$dbtbpre}enewsmod order by myorder,mid");
while($mr=$empire->fetch($msql))
{
	$listtemp_options.="<option value=0 style='background:#99C4E3'>".$mr[mname]."</option>";
	$l_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewslisttemp")." where modid='$mr[mid]'");
	while($l_r=$empire->fetch($l_sql))
	{
		if($l_r[tempid]==$r[tagstempid])
		{$l_d=" selected";}
		else
		{$l_d="";}
		$listtemp_options.="<option value=".$l_r[tempid].$l_d."> |-".$l_r[tempname]."</option>";
	}
}
//当前使用的模板组
$thegid=GetDoTempGid();
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
<title>TAGS</title>
<style>
.comm-table td { line-height:25px;}
.comm-table td p{ padding:5px;}
</style>
<script>
// 管理TAGS列表模板
function gllbmb(){
art.dialog.open('template/ListListtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>',
    {title: '管理列表模板',width: 800, height: 540});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListTags.php<?=$ecms_hashur['whehref']?>">管理TAGS</a> &gt; 设置TAGS</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form name="form1" method="post" action="ListTags.php">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="SetTags">
		<tr>
			<th style="width:200px;"><h3><span>设置TAGS</span></h3></th>
			<th></th>
		</tr>
        <tr>
        	<td>前台开启TAGS:</td>
            <td style="text-align:left;"><input type="radio" name="opentags" value="1"<?=$r[opentags]==1?' checked':''?>>
        开启 
        <input type="radio" name="opentags" value="0"<?=$r[opentags]==0?' checked':''?>>
        关闭</td>
        </tr>
        <tr>
        	<td>默认使用的模板:</td>
            <td style="text-align:left;"><select name="tagstempid" id="tagstempid">
          <?=$listtemp_options?>
</select> <input type="button" name="Submit62223" value="管理列表模板" onclick="gllbmb()"></td>
        </tr>
        <tr>
        	<td>每页显示信息:</td>
            <td style="text-align:left;"><input name="tagslistnum" type="text" id="tagslistnum" value="<?=$r[tagslistnum]?>" size="42"></td>
        </tr>
        <tr>
        	<td>使用TAGS的系统模型:</td>
            <td style="text-align:left;"><?=$usetags?></td>
        </tr>
        <tr>
        	<td>只能选择TAGS的系统模型:</td>
            <td style="text-align:left;"><?=$chtags?></td>
        </tr>
        <tr>
        	<td></td>
            <td style="text-align:left;"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
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