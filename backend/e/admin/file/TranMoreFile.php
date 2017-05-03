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
CheckLevel($logininid,$loginin,$classid,"file");
$url="<a href=TranMoreFile.php".$ecms_hashur['whehref'].">批量上传附件</a>";
$filenum=(int)$_GET['filenum'];
if(empty($filenum))
{$filenum=10;}
$o="n".$filenum;
$$o=" selected";
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>批量上传附件</title>
</head>

<body>

<div class="container" style="overflow-x:hidden;">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div></div>
<div class="kongbai"></div>
<form action="../ecmsfile.php" method="post" enctype="multipart/form-data" name="form1">
  <?=$ecms_hashur['form']?>
    <div id="tab" style="padding-bottom:0px;_margin-bottom:50px;overflow:hidden;">
	<div class="ui-tab-container">
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
            <div class="newscon anniuqun">
<div class="ui-tab-content">
        	<h3><span>批量上传附件</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>请选择要上传的附件个数：</label><select name="filenum" id="filenum" onchange=window.location='TranMoreFile.php?filenum='+this.options[this.selectedIndex].value>
          <option value="1"<?=$n1?>>1</option>
          <option value="2"<?=$n2?>>2</option>
          <option value="3"<?=$n3?>>3</option>
          <option value="4"<?=$n4?>>4</option>
          <option value="5"<?=$n5?>>5</option>
          <option value="6"<?=$n6?>>6</option>
          <option value="7"<?=$n7?>>7</option>
          <option value="8"<?=$n8?>>8</option>
          <option value="9"<?=$n9?>>9</option>
          <option value="10"<?=$n10?>>10</option>
          <option value="11"<?=$n11?>>11</option>
          <option value="12"<?=$n12?>>12</option>
          <option value="13"<?=$n13?>>13</option>
          <option value="14"<?=$n14?>>14</option>
          <option value="15"<?=$n15?>>15</option>
          <option value="16"<?=$n16?>>16</option>
          <option value="17"<?=$n17?>>17</option>
          <option value="18"<?=$n18?>>18</option>
          <option value="19"<?=$n19?>>19</option>
          <option value="20"<?=$n20?>>20</option>
        </select>
        ，上传附件类别： 
        <select name="type">
        <option value="1">图片</option>
        <option value="2">Flash文件</option>
<option value="3">多媒体文件</option>
        <option value="0">其他附件</option>
      </select></li>
        </ul>
        <table class="comm-table anniuqun" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:140px;">文件</th>
			<th style="text-align:left;">编号（便于管理附件）</th>
		</tr>
<?
		  for($i=0;$i<$filenum;$i++)
		  {
		  ?>
          <tr> 
            <td style="text-align:right;"> <input name="file[]" type="file" id="file[]"> </td>
            <td style="text-align:left;"><input name="no[]" type="text" id="no[]"></td>
          </tr>
          <?
		  }
		  ?>
    <tr> 
      <td> <div align="center"></div></td>
      <td style="text-align:left;"> <input type="submit" name="Submit" value="开始上传"> 
        <input name="enews" type="hidden" id="enews" value="TranMoreFile"> </td>
    </tr>
  </tbody>
</table>
            </div>
        </div>
        	</div>
        </div>
 <div class="line"></div>
  </div>
 </div>
</div>
 </form>
 <div class="clear"></div>
</div>
</body>
</html>
