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
//参数
$returnform=RepPostVar($_GET['returnform']);
//基目录
$openpath="../../data/face";
$hand=@opendir($openpath);
$url="<a href=ListAllPl.php".$ecms_hashur['whehref'].">管理评论</a>&nbsp;>&nbsp;<a href=ChangePlfaceFile.php".$ecms_hashur['whehref'].">选择文件</a>";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择文件</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<style>
.comm-table td table td{ text-align:left;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择文件</span></h3>
			<div class="line"></div>
<div class="jqui anniuqun">
<form name="chfile" method="post" action="../enews.php">
 <?=$ecms_hashur['form']?>
<input name=enews type=hidden value=SetPl>
<table class="comm-table" cellspacing="0">
	<tbody>
        <tr bgcolor="#FFFFFF"> 
      <td style="width:150px;">文件名</td>
      <td style="text-align:left;">(当前目录：<strong>/e/data/face/</strong>)</td>
    </tr>
    <?php
	while($file=@readdir($hand))
	{
		$truefile=$file;
		if($file=="."||$file=="..")
		{
			continue;
		}
		//目录
		if(is_dir($openpath."/".$file))
		{
			continue;
		}
		$filetype=GetFiletype($file);
		if(!strstr($ecms_config['sets']['tranpicturetype'],','.$filetype.','))
		{
			continue;
		}
	 ?>
    <tr bgcolor="#FFFFFF">
	<td style="width:150px;"><?=$truefile?></td>
      <td  style="text-align:left;"><a href="#ecms" onclick="<?=$returnform?>='<?=$truefile?>';window.close();" title="选择"> 
        <img src="../../data/face/<?=$truefile?>" border=0>
        </a></td>
    </tr>
    <?
	}
	@closedir($hand);
	?>
	</tbody>
</table>
        </form>
</div>
<div class="line"></div>
        </div>
    </div>
</div>
</div>
</body>
</html>