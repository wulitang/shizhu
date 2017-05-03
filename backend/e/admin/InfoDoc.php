<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"infodoc");
$enews=ehtmlspecialchars($_GET['enews']);
$url="<a href=InfoDoc.php".$ecms_hashur['whehref'].">信息批量归档</a>";
//--------------------操作的栏目
$fcfile="../data/fc/ListEnews.php";
$do_class="<script src=../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$do_class=ShowClass_AddClass("","n",0,"|-",0,0);}
//表
$selecttable="";
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable order by tid");
while($tr=$empire->fetch($tsql))
{
	$selecttable.="<option value='".$tr[tbname]."'>".$tr[tname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息批量归档</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script src="ecmseditor/fieldfile/setday.js"></script>
<style>
.comm-table td { text-align:left;}
.comm-table td p{ padding:5px;}
.comm-table td table{ border-right:1px solid #EFEFEF; border-top:1px solid #EFEFEF;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>信息批量归档</span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table anniuqun" cellspacing="0">
	<tbody>
<form name="form1" method="get" action="ecmsinfo.php" onSubmit="return confirm('确认要执行此操作？');">
  <?=$ecms_hashur['form']?>
<input name="enews" type="hidden" id="enews" value="InfoToDoc">
          <input name="ecmsdoc" type="hidden" id="ecmsdoc" value="2">
          <input name="docfrom" type="hidden" id="docfrom" value="InfoDoc.php<?=$ecms_hashur['whehref']?>">
        </div></td>
    </tr>
    <tr> 
      <td width="28%" height="25" valign="top" bgcolor="#FFFFFF">
<div align="center"> 
          <p> 
            <select name="classid[]" size="21" multiple id="classid[]" style="width:200">
              <?=$do_class?>
            </select></td>
            <td>
            <table width="500px" border="0" cellpadding="3" cellspacing="1" class="tableborder">
          <tr bgcolor="#FFFFFF"> 
            <td width="26%" height="32">归档数据表</td>
            <td width="74%"><select name="tbname" id="tbname">
                <option value=''>------ 选择数据表 ------</option>
                <?=$selecttable?>
              </select></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32"> <input name="retype" type="radio" value="0" checked>
              按天数归档 </td>
            <td>归档大于 <input name="doctime" type="text" id="doctime" value="100" size="6">
              天的信息</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="32">&nbsp;</td>
            <td>还原归档小于
              <input name="doctime1" type="text" id="doctime1" value="100" size="6">
              天的信息</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32"> <input name="retype" type="radio" value="1">
              按时间归档</td>
            <td>从 
              <input name="startday" type="text" size="12" onClick="setday(this)">
              到 
              <input name="endday" type="text" size="12" onClick="setday(this)">
              之间的信息</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32"> <input name="retype" type="radio" value="2">
              按ID归档</td>
            <td>从 
              <input name="startid" type="text" value="0" size="6">
              到 
              <input name="endid" type="text" value="0" size="6">
              之间的信息</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32">执行操作</td>
            <td><input name="doing" type="radio" value="0" checked>
              归档 <input type="radio" name="doing" value="1">
              还原归档</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32">&nbsp;</td>
            <td><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="32" colspan="2"> <font color="#666666"><strong>说明:</strong><br>
              选择多个栏目请用CTRL/SHIFT<br>
              如果归档起始时间与ID不填则不限制下限</font></td>
          </tr>
        </table>
            </td>
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
