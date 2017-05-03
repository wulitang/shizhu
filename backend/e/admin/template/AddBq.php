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
CheckLevel($logininid,$loginin,$classid,"bq");
$enews=ehtmlspecialchars($_GET['enews']);
$cid=ehtmlspecialchars($_GET['cid']);
$url="<a href=ListBq.php".$ecms_hashur['whehref'].">管理标签</a>&nbsp;>&nbsp;增加标签";
//修改标签
if($enews=="EditBq")
{
	$bqid=(int)$_GET['bqid'];
	$url="<a href=ListBq.php".$ecms_hashur['whehref'].">管理标签</a>&nbsp;>&nbsp;修改标签";
	$r=$empire->fetch1("select bqname,bqsay,funname,bq,issys,bqgs,isclose,classid,myorder from {$dbtbpre}enewsbq where bqid='$bqid'");
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsbqclass order by classid");
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

//--------------------html编辑器
include('../ecmseditor/infoeditor/fckeditor.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加模板标签</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function on()
{
var f=document.add
f.HTML.value=f.bqsay.value;
}
function bs(){
var f=document.add
f.bqsay.value=f.HTML.value;
}
function br(){
if(!confirm("是否复位？")){return false;}
document.add.title.select()
}
//管理标签分类
function glbqfl(){
art.dialog.open('template/BqClass.php<?=$ecms_hashur['whehref']?>',
    {title: '管理标签分类',width: 1050, height: 540});
}
</script><noscript>
<iframe src=*.htm></iframe>
</noscript>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<div class="jqui">
<style>
.comm-table td{ text-align:left;}
.comm-table2 td{ padding:5px;}
</style>
<form action="../ecmstemp.php" method="post" name="add" id="add">
 <?=$ecms_hashur['form']?>
<input name="add[bqid]" type="hidden" id="add[bqid]" value="<?=$bqid?>"> 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="add[cid]" type="hidden" id="add[cid]" value="<?=$cid?>">
<div class="line"></div>
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;"><h3><span>增加模板标签</span></h3></th>
			<th></th>
		</tr>
    <tr> 
      <td height="25" style="text-align:center;">标签名：</td>
      <td height="25"><input name="add[bqname]" type="text" id="add[bqname]" value="<?=$r[bqname]?>" size="38">
        <font color="#666666">(如”调用文字信息标签”)</font></td>
    </tr>
    <tr> 
      <td height="25" style="text-align:center;">标签符号：</td>
      <td height="25"><input name="add[bq]" type="text" id="add[bq]" value="<?=$r[bq]?>" size="38">
        <font color="#666666">(如：[ad]参数[/ad]，则符号为”ad”)</font></td>
    </tr>
    <tr> 
      <td height="25" style="text-align:center;">所属类别：</td>
      <td height="25"><select name="add[classid]" id="add[classid]">
          <option value="0">不隶属于任何类别</option>
          <?=$cstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onClick="glbqfl()"></td>
    </tr>
    <tr> 
      <td height="25" style="text-align:center;">函数名：</td>
      <td height="25"><input name="add[funname]" type="text" id="add[funname]" value="<?=$r[funname]?>" size="38"> 
      </td>
    </tr>
    <tr> 
      <td height="25">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"> <p>系统标签：(相对于e/class/t_functions.php文件的函数名)<br>
          用户自定义标签：(相对于e/class/userfun.php文件的函数名，函数命名请以”<strong><font color="#FF0000">user_</font></strong>”开头)</p></td>
    </tr>
    <tr> 
      <td height="25" style="text-align:center;">标签格式：</td>
      <td height="25"><input name="add[bqgs]" type="text" id="add[bqgs]" value="<?=stripSlashes($r[bqgs])?>" size="80"></td>
    </tr>
    <tr> 
      <td height="25">&nbsp;</td>
      <td height="25">如：<font color="#FF0000">[phomenews]栏目ID/专题ID,显示条数,标题截取数,是否显示时间,操作类型[/phomenews]</font></td>
    </tr>
    <tr> 
      <td height="25" valign="top" style="text-align:center;">标签说明：</td>
      <td height="25"> 
        <?=ECMS_ShowEditorVar('bqsay',stripSlashes($r[bqsay]),'Default','../ecmseditor/infoeditor/')?>
      </td>
    </tr>
    <tr> 
      <td height="25" style="text-align:center;">是否开启标签：</td>
      <td height="25"><input type="radio" name="add[isclose]" value="0"<?=$r[isclose]==0?' checked':''?>>
        是 
        <input type="radio" name="add[isclose]" value="1"<?=$r[isclose]==1?' checked':''?>>
        否 <font color="#666666">（开启才会在模板中生效）</font></td>
    </tr>
    <tr>
      <td height="25" style="text-align:center;">排序：</td>
      <td height="25"><input name="add[myorder]" type="text" id="add[myorder]" value="<?=$r[myorder]?>" size="38">
        <font color="#666666">(值越大越前面)</font></td>
    </tr>
    <tr> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </tbody>
</table>
<div class="line"></div>
</form>
</div>
        </div>
    </div>
</div>
</div>
</body>
</html>