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
CheckLevel($logininid,$loginin,$classid,"table");
$url="<a href=ListTable.php".$ecms_hashur['whehref'].">管理数据表</a>&nbsp;>&nbsp;导入系统模型";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>导入系统模型</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script src="../ecmseditor/fieldfile/setday.js"></script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php<?=$ecms_hashur[whehref]?>">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>导入系统模型</span></h3>
            <div class="line"></div>
           <form action="../ecmsmod.php" method="post" enctype="multipart/form-data" name="form1" onsubmit="return confirm('确认要导入?');">
			  <?=$ecms_hashur['form']?>
			<ul>
            		<li class="jqui"><label>存放的数据表名:</label><i><?=$dbtbpre?>ecms_</i><input name="tbname" type="text" id="tbname" value="<?=$r[tbname]?>" size="10">
        *<font color="#666666">(如:news,只能由字母、数字组成)</font></li>
                    <li class="jqui"><label>选择导入模型文件:</label><input type="file" name="file">
        *<font color="#666666">.mod</font></li>

                    <li class="jqui"><label>&nbsp;</label><input type="submit" name="Submit" value="马上导入" style="padding: 5px 10px;"> 
        <input type="reset" name="Submit2" value="重置" style="padding: 5px 10px;">
        <input name="enews" type="hidden" id="enews" value="LoadInMod"></li>
            </ul>
            </form>
        </div>
        <div class="line"></div>
    </div>
</div>
</div>
</body>
</html>
