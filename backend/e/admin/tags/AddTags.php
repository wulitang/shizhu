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
$enews=ehtmlspecialchars($_GET['enews']);
$postword='增加TAGS';
$url="<a href=ListTags.php".$ecms_hashur['whehref'].">管理TAGS</a> &gt; 增加TAGS";
$fcid=(int)$_GET['fcid'];
//修改
if($enews=="EditTags")
{
	$postword='修改TAGS';
	$tagid=(int)$_GET['tagid'];
	$r=$empire->fetch1("select tagid,tagname,cid from {$dbtbpre}enewstags where tagid='$tagid'");
	$url="<a href=ListTags.php".$ecms_hashur['whehref'].">管理TAGS</a> -&gt; 修改TAGS：<b>".$r[tagname]."</b>";
}
//分类
$csql=$empire->query("select classid,classname from {$dbtbpre}enewstagsclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($r[cid]==$cr[classid])
	{
		$select=" selected";
	}
	$cs.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
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
<title>增加TAGS</title>
<style>
.comm-table td { line-height:25px;}
.comm-table td p{ padding:5px;}
</style>
<script>
// 管理TAGS分类
function gltagsfl(){
art.dialog.open('tags/TagsClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理TAGS分类',width: 540, height: 240});
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
<form name="form1" method="post" action="ListTags.php">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="tagid" type="hidden" id="tagid" value="<?=$tagid?>">
        <input name="fcid" type="hidden" id="fcid" value="<?=$fcid?>">
		<tr>
			<th style="width:200px;"><h3><span>设置TAGS</span></h3></th>
			<th></th>
		</tr>
        <tr>
        	<td>TAG名称:</td>
            <td style="text-align:left;"><input name="tagname" type="text" id="tagname" value="<?=$r[tagname]?>" size="42"> </td>
        </tr>
        <tr>
        	<td>所属分类:</td>
            <td style="text-align:left;"><select name="cid" id="cid">
          <option value="0">不分类</option>
		  <?=$cs?>
        </select> 
        <input type="button" name="Submit62223" value="管理分类" onclick="gltagsfl()"></td>
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