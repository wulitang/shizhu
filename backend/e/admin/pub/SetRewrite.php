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
CheckLevel($logininid,$loginin,$classid,"public");

//设置伪静态参数
function SetRewrite($add,$userid,$username){
	global $empire,$dbtbpre;
	CheckLevel($userid,$username,$classid,"public");//验证权限
	$sql=$empire->query("update {$dbtbpre}enewspublic set rewriteinfo='".eaddslashes($add[rewriteinfo])."',rewriteclass='".eaddslashes($add[rewriteclass])."',rewriteinfotype='".eaddslashes($add[rewriteinfotype])."',rewritetags='".eaddslashes($add[rewritetags])."',rewritepl='".eaddslashes($add[rewritepl])."' limit 1");
	if($sql)
	{
		GetConfig();
		//操作日志
		insert_dolog("");
		printerror("SetRewriteSuccess","SetRewrite.php".hReturnEcmsHashStrHref2(1));
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
}
if($enews=="SetRewrite")//设置伪静态参数
{
	SetRewrite($_POST,$logininid,$loginin);
}

$r=$empire->fetch1("select rewriteinfo,rewriteclass,rewriteinfotype,rewritetags,rewritepl from {$dbtbpre}enewspublic limit 1");
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>设置伪静态</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript">
$(function(){
			
		});
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="SetRewrite.php<?=$ecms_hashur['whehref']?>">伪静态设置</a></div></div>
<form name="setpublic" method="post" action="SetRewrite.php">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" value="SetRewrite">
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>伪静态参数设置</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th width="150" class="first">页面</th>
			<th class="impt">标记</th>
			<th>格式</th>
			<th>对应页面</th>
		</tr>
		<tr>
			<td class="first">信息内容页</td>
			<td>[!--classid--],[!--id--],[!--page--]</td>
			<td><lable>/</lable>
        <input name="rewriteinfo" type="text" id="rewriteinfo" value="<?=$r[rewriteinfo]?>" size="22">
<i>[<a href="#empirecms" onClick="document.setpublic.rewriteinfo.value='showinfo-[!--classid--]-[!--id--]-[!--page--].html';">设为默认</a>]</i>
			</td>
			<td>/e/action/ShowInfo.php?classid=栏目ID&amp;id=信息ID&amp;page=分页号</td>
		</tr>
		<tr>
			<td class="first">
				信息列表页
			</td>
			<td>
				[!--classid--],[!--page--]
			</td>
			<td>
				<lable>/</lable>
        <input name="rewriteclass" type="text" id="rewriteclass" value="<?=$r[rewriteclass]?>" size="22">
      <i>[<a href="#empirecms" onClick="document.setpublic.rewriteclass.value='listinfo-[!--classid--]-[!--page--].html';">设为默认</a>]</i>
			</td>
			<td>
				/e/action/ListInfo/index.php?classid=栏目ID&amp;page=分页号
			</td>
		</tr>
		<tr>
			<td class="first">
				标题分类列表页
			</td>
			<td>
				[!--ttid--],[!--page--]
			</td>
			<td>
				<lable>/</lable>
                <input name="rewriteinfotype" type="text" id="rewriteinfotype" value="<?=$r[rewriteinfotype]?>" size="22">
<i>[<a href="#empirecms" onClick="document.setpublic.rewriteinfotype.value='infotype-[!--ttid--]-[!--page--].html';">设为默认</a>]</i>
			</td>
			<td>
				/e/action/InfoType/index.php?ttid=标题分类ID&amp;page=分页号
			</td>
		</tr>
		<tr>
			<td class="first">
				TAGS信息列表页
			</td>
			<td>
				[!--tagname--],[!--page--]
			</td>
			<td>
				<lable>/</lable>
        <input name="rewritetags" type="text" id="rewritetags" value="<?=$r[rewritetags]?>" size="22">
        <i>[<a href="#empirecms" onClick="document.setpublic.rewritetags.value='tags-[!--tagname--]-[!--page--].html';">设为默认</a>]</i>
			</td>
			<td>
				/e/tags/index.php?tagname=TAGS名称&amp;page=分页号
			</td>
		</tr>
		    <tr>
      <td class="first">评论列表页</td>
      <td>[!--doaction--],[!--classid--],[!--id--],<br>
      [!--page--],[!--myorder--],[!--tempid--]</td>
      <td><lable>/</lable>
        <input name="rewritepl" type="text" id="rewritepl" value="<?=$r[rewritepl]?>" size="22">
</i>[<a href="#empirecms" onclick="document.setpublic.rewritepl.value='comment-[!--doaction--]-[!--classid--]-[!--id--]-[!--page--]-[!--myorder--]-[!--tempid--].html';">默认</a>]</i>
      <td>/e/pl/index.php?doaction=事件&amp;classid=栏目ID&amp;id=信息ID&amp;page=分页号&amp;myorder=排序&amp;tempid=评论模板ID</td>
    </tr>
		<tr>
			<td colspan="4">
            <input type="submit" name="Submit" value="提交">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="重置">
			</td>
		</tr>
		<tr>
			<td colspan="4" class="tixing">
           说明：采用静态页面时不需要设置，只有当采用动态页面时可通过设置伪静态来提高SEO优化，如果不启用请留空。注意：伪静态会增加服务器负担，修改伪静态格式后你需要修改服务器的 Rewrite 规则设置。
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="line"></div>
        </div>
    </div>
</div>
</form>
</div>
</body>
</html>
