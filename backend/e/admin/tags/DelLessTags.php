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
<title>删除使用率低的TAGS</title>
<style>
.comm-table td { line-height:25px;}
.comm-table td p{ padding:5px;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="ListTags.php<?=$ecms_hashur['whehref']?>">管理TAGS</a> &gt; 删除使用率低的TAGS</div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form name="form1" method="post" action="ListTags.php" onsubmit="return confirm('确认要操作?');">
<?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="DelLessTags">
		<tr>
			<th style="width:200px;"><h3><span>删除使用率低的TAGS</span></h3></th>
			<th></th>
		</tr>
        <tr>
        	<td>删除信息数:</td>
            <td style="text-align:left;"><font color="#FF0000"><strong>&lt;=</strong></font> 
          <input name="num" type="text" id="num" value="0" size="8">
          的TAGS</td>
        </tr>
        <tr>
        	<td></td>
            <td style="text-align:left;"><input type="submit" name="Submit2" value="删除"></td>
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