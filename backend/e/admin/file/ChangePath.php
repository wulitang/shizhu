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
$basepath="../../..";
$filepath=RepPostStr($_GET['filepath'],1);
if(strstr($filepath,".."))
{
	$filepath="";
}
$filepath=eReturnCPath($filepath,'');
$openpath=$basepath."/".$filepath;
if(!file_exists($openpath))
{
	$openpath=$basepath;
}
$hand=@opendir($openpath);
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>选择目录</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script language="javascript">
window.resizeTo(550,600);
window.focus();
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ChangePath.php<?=$ecms_hashur['whehref']?>">选择目录</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择目录</span></h3>
            <div class="line"></div>
<div class="jqui">
<form name="chfile" method="post" action="../enews.php">
 <?=$ecms_hashur['eform']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
	<th>选择</th>
     <th>文件名 (当前目录：<strong>/ 
        <?=$filepath?>
        </strong>) &nbsp;&nbsp;&nbsp;[<a href="#ecms" onclick="javascript:history.go(-1);">返回</a>]</th>
		</tr>
<?php
	while($file=@readdir($hand))
	{
		if(empty($filepath))
		{
			$truefile=$file;
		}
		else
		{
			$truefile=$filepath."/".$file;
		}
		if($file=="."||$file=="..")
		{
			continue;
		}
		//目录
		if(is_dir($openpath."/".$file))
		{
			$filelink="ChangePath.php?filepath=".$truefile."&returnform=".$returnform.$ecms_hashur['ehref'];
			$filename=$file;
			$img="folder.gif";
			$checkbox="";
			$target="";
		}
		//文件
		else
		{
			continue;
		}
	 ?>
    <tr> 
      <td height="25"><div align="center">
          <input name="path" type="checkbox" id="path" value="../../<?=$truefile?>/" onclick="<?=$returnform?>=this.value;window.close();">
        </div></td>
      <td style="text-align:left;" height="25"><img src="../../data/images/dir/<?=$img?>" width="23" height="22"><a href="<?=$filelink?>" title="查看下级目录"> 
        <?=$filename?>
        </a></td>
    </tr>
    <?
	}
	@closedir($hand);
	?>
		  </tr>
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
