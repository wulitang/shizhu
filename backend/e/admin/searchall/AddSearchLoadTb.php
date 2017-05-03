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
CheckLevel($logininid,$loginin,$classid,"searchall");
$url="<a href=ListSearchLoadTb.php".$ecms_hashur['whehref'].">管理全站搜索数据源</a>&nbsp;>&nbsp;增加搜索数据源";
$word='增加全站搜索数据源';
$enews=ehtmlspecialchars($_GET['enews']);
$r['titlefield']='title';
$r['loadnum']='300';
//修改
if($enews=="EditSearchLoadTb")
{
	$lid=(int)$_GET['lid'];
	$r=$empire->fetch1("select lid,tbname,titlefield,infotextfield,smalltextfield,loadnum from {$dbtbpre}enewssearchall_load where lid='$lid'");
	$url="<a href=ListSearchLoadTb.php".$ecms_hashur['whehref'].">管理全站搜索数据源</a>&nbsp;>&nbsp;修改搜索数据源";
	$word='修改全站搜索数据源';
}
//数据表
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable order by tid");
while($tr=$empire->fetch($tsql))
{
	if($r[tbname]==$tr[tbname])
	{$select=" selected";}
	else
	{$select="";}
	$table.="<option value='".$tr[tbname]."'".$select.">".$tr[tname]."</option>";
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
<title>管理全站搜索数据源</title>
<style>
.comm-table td { line-height:25px;}
.comm-table td p{ padding:5px;}
</style>
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
<form name="form1" method="post" action="ListSearchLoadTb.php">
<?=$ecms_hashur['form']?>
<input type=hidden name=enews value=<?=$enews?>>
<input type=hidden name=lid value=<?=$lid?>>
		<tr>
			<th style="width:200px;"><h3><span><?=$word?></span></h3></th>
			<th></th>
		</tr>
        <tr>
        	<td>导入的数据表:</td>
            <td style="text-align:left;"><select name="tbname" id="tbname">
	  <?=$table?>
        </select>
        <font color="#666666">(*)</font> 
        <input name="oldtbname" type="hidden" id="oldtbname" value="<?=$r[tbname]?>"></td>
        </tr>
        <tr>
        	<td>标题字段:</td>
            <td style="text-align:left;"><input name="titlefield" type="text" id="titlefield" value="<?=$r[titlefield]?>">
        <font color="#666666">(*)</font></td>
        </tr>
		<tr bgcolor="#FFFFFF"> 
      <td>内容字段：</td>
      <td style="text-align:left;"><input name="infotextfield" type="text" id="infotextfield" value="<?=$r[infotextfield]?>">
        <font color="#666666">(*)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>简介字段：</td>
      <td style="text-align:left;"><input name="smalltextfield" type="text" id="smalltextfield" value="<?=$r[smalltextfield]?>">
        <font color="#666666">(*)</font> </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td>每组导入记录数：</td>
      <td style="text-align:left;"><input name="loadnum" type="text" id="loadnum" value="<?=$r[loadnum]?>">
        <font color="#666666">(*)</font></td>
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