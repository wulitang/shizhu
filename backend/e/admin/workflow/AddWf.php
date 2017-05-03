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
CheckLevel($logininid,$loginin,$classid,"workflow");
$enews=ehtmlspecialchars($_GET['enews']);
$postword='增加工作流';
$r[myorder]=0;
$url="<a href=ListWf.php".$ecms_hashur['whehref'].">管理工作流</a> &gt; 增加工作流";
//修改
if($enews=="EditWorkflow")
{
	$postword='修改工作流';
	$wfid=(int)$_GET['wfid'];
	$r=$empire->fetch1("select wfid,wfname,wftext,myorder from {$dbtbpre}enewsworkflow where wfid='$wfid'");
	$url="<a href=ListWf.php".$ecms_hashur['whehref'].">管理工作流</a> -&gt; 修改工作流：<b>".$r[wfname]."</b>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>工作流</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/js/yecha.js"></script>
<script type="text/javascript">
$(function(){
			
		});
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?>  </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span><?=$postword?></span></h3>
            <div class="line"></div>
           <form name="form1" method="post" action="ListWf.php">
  <?=$ecms_hashur['form']?>
           <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="wfid" type="hidden" id="wfid" value="<?=$wfid?>"> 
			<ul>
            		<li class="jqui"><label>工作流名称</label><input name="wfname" type="text" id="wfname" value="<?=$r[wfname]?>" size="25"> </li>
                    <li><label>工作流描述</label><textarea name="wftext" cols="60" rows="5" id="wftext"><?=ehtmlspecialchars($r[wftext])?></textarea></li>
                    <li class="jqui"><label>排序</label>
        <input name="myorder" type="text" id="myorder" value="<?=$r[myorder]?>" size="10">
        <font color="#666666">(值越小显示越前面)</font></li>
        			<li class="jqui"><label>&nbsp;</label><input type="submit" name="Submit" value="提交"> &nbsp; <input type="reset" name="Submit2" value="重置"></li>
            </ul>
            </form>
			<div class="line"></div>
        </div>
    </div>
</div>

</div>
</body>
</html>
