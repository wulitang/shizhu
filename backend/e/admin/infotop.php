<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../data/dbcache/class.php");
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

//排行显示
function ecmsShowInfoTop($query,$where,$field,$topnum,$day){
	global $empire,$dbtbpre,$class_r;
	if($day)
	{
		$and=$where?' and ':' where ';
		$query.=$and."newstime>=".time()."-".($day*24*3600);
	}
	if($field=='plnum')
	{
		$word='评论数';
	}
	elseif($field=='totaldown')
	{
		$word='下载数';
	}
	elseif($field=='onclick')
	{
		$word='点击数';
	}
	$query.=" order by ".$field." desc limit ".$topnum;
	echo"<tr height='25'><td>标题</td><td>$word</td></tr>";
	$sql=$empire->query($query);
	while($r=$empire->fetch($sql))
	{
		$classurl=sys_ReturnBqClassname($r,9);
		$titleurl=sys_ReturnBqTitleLink($r);
		echo"<tr height='25'><td>[<a href='".$classurl."' target='_blank'>".$class_r[$r[classid]][classname]."</a>] <a href='$titleurl' target='_blank' title='发布时间：".date("Y-m-d H:i:s",$r[newstime])."'>".stripSlashes($r[title])."</a></td><td>".$r[$field]."</td></tr>";
	}
	echo"";
}

$where='';
//数据表
$tbname=RepPostVar($_GET['tbname']);
if(empty($tbname))
{
	$tbname=$public_r['tbname'];
}
$htb=0;
$tbsql=$empire->query("select tbname,tname from {$dbtbpre}enewstable order by tid");
while($tbr=$empire->fetch($tbsql))
{
	$select="";
	if($tbr[tbname]==$tbname)
	{
		$htb=1;
		$select=" selected";
	}
	$tbs.="<option value='".$tbr[tbname]."'".$select.">".$tbr[tname]."</option>";
}
if($htb==0)
{
	printerror('ErrorUrl','');
}
//栏目
$classid=(int)$_GET['classid'];
if($classid)
{
	$and=$where?' and ':' where ';
	if($class_r[$classid][islast])
	{
		$where.=$and."classid='$classid'";
	}
	else
	{
		$where.=$and."(".ReturnClass($class_r[$classid][sonclass]).")";
	}
}
//标题分类
$ttid=(int)$_GET['ttid'];
if($ttid)
{
	$and=$where?' and ':' where ';
	$where.=$and." ttid='$ttid'";
}
$ttclass="";
$tt_sql=$empire->query("select typeid,tname from {$dbtbpre}enewsinfotype order by typeid");
while($tt_r=$empire->fetch($tt_sql))
{
	$selected='';
	if($tt_r[typeid]==$ttid)
	{
		$selected=" selected";
	}
	$ttclass.="<option value='".$tt_r[typeid]."'".$selected.">".$tt_r[tname]."</option>";
}
//字段
$myorder=(int)$_GET['myorder'];
if($myorder==1)
{
	$field='plnum';
}
elseif($myorder==2)
{
	$field='totaldown';
}
else
{
	$field='onclick';
}
//搜索
if($_GET['keyboard'])
{
	$and=$where?' and ':' where ';
	$keyboard=RepPostVar2($_GET['keyboard']);
	$show=RepPostStr($_GET['show'],1);
	if($show==0)//搜索标题
	{
		$where.=$and."title like '%$keyboard%'";
	}
	else//搜索作者
	{
		$where.=$and."username like '%$keyboard%'";
	}
}
//显示条数
$topnum=(int)$_GET['topnum'];
if($topnum<1||$topnum>100)
{
	$topnum=10;
}
$query="select id,title,classid,newstime,isurl,titleurl,".$field." from {$dbtbpre}ecms_".$tbname.$where;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>信息排行</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script language="javascript">
function senfe(o,a,b,c,d){
 var t=document.getElementById(o).getElementsByTagName("tr");
 for(var i=0;i<t.length;i++){
  t[i].style.backgroundColor=(t[i].sectionRowIndex%2==0)?a:b;
  t[i].onclick=function(){
   if(this.x!="1"){
    this.x="1";
    this.style.backgroundColor=d;
   }else{
    this.x="0";
    this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
   }
  }
  t[i].onmouseover=function(){
   if(this.x!="1")this.style.backgroundColor=c;
  }
  t[i].onmouseout=function(){
   if(this.x!="1")this.style.backgroundColor=(this.sectionRowIndex%2==0)?a:b;
  }
 }
}
</script>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="infotop.php<?=$ecms_hashur['whehref']?>">信息排行</a></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<form name="searchform" method="GET" action="infotop.php">
<?=$ecms_hashur['eform']?>
        	<h3><span><div class="left">选择条件：</div> 
           <div id="listfileclassnav" style="width:115px; height:25px; float:left; margin-top:8px;"></div>
        <select name="tbname" id="tbname">
		<?=$tbs?>
        </select> 
        <select name="ttid" id="ttid">
            <option value="0">所有专题</option>
            <?=$ttclass?>
        </select> 
        <select name="myorder" id="myorder">
          <option value="0"<?=$myorder==0?' selected':''?>>点击排行</option>
          <option value="1"<?=$myorder==1?' selected':''?>>评论排行</option>
          <option value="2"<?=$myorder==2?' selected':''?>>下载排行</option>
        </select>
        ，显示 
        <input name="topnum" type="text" id="topnum" value="<?=$topnum?>" size="6"> 条
        ，关键字
<input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
        <select name="show" id="show">
          <option value="0"<?=$show==0?' selected':''?>>标题</option>
          <option value="1"<?=$show==1?' selected':''?>>发布者</option>
        </select> 
        <input type="submit" name="Submit" value="显示排行" class="anniu"></span></h3>
</form>
            <div class="line"></div>
<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <tr valign="top"> 
    <td width="50%" style="padding:5px;"> 
      <table width="98%" border="0" cellpadding="3" cellspacing="1" class="comm-table2" style="margin-bottom:5px;">
        <tr> 
          <th colspan="2" style="border-left:1px solid #CDCDCD;">24小时排行</th>
          </tr>
            <?php ecmsShowInfoTop($query,$where,$field,$topnum,1);?>
      </table></td>
    <td width="50%" style="padding:5px;"><table width="98%" border="0" align="right" cellpadding="3" cellspacing="1"  class="comm-table2">
        <tr> 
          <th colspan="2" style="border-left:1px solid #CDCDCD;">一周排行</th>
        </tr>
            <?php ecmsShowInfoTop($query,$where,$field,$topnum,7);?>
      </table> </td>
  </tr>
  <tr valign="top"> 
    <td style="padding:5px;"> 
      <table width="98%" border="0" cellpadding="3" cellspacing="1" class="comm-table2" style="margin-bottom:5px;">
        <tr> 
         <th colspan="2" style="border-left:1px solid #CDCDCD;">一个月排行</th>
        </tr>
            <?php ecmsShowInfoTop($query,$where,$field,$topnum,30);?>
      </table></td>
    <td style="padding:5px;"><table width="98%" border="0" align="right" cellpadding="3" cellspacing="1" class="comm-table2" style="margin-bottom:5px;">
        <tr> 
          <th colspan="2" style="border-left:1px solid #CDCDCD;">三个月排行</th>
        </tr>
            <?php ecmsShowInfoTop($query,$where,$field,$topnum,90);?>
      </table>
      
    </td>
  </tr>
  <tr valign="top"> 
    <td style="padding:5px;"><table width="98%" border="0" cellpadding="3" cellspacing="1" class="comm-table2" style="margin-bottom:5px;">
        <tr> 
          <th colspan="2" style="border-left:1px solid #CDCDCD;">一年排行</th>
        </tr>
            <?php ecmsShowInfoTop($query,$where,$field,$topnum,365);?>
      </table> </td>
    <td style="padding:5px;"><table width="98%" border="0" align="right" cellpadding="3" cellspacing="1" class="comm-table2" style="margin-bottom:5px;">
        <tr> 
          <th colspan="2" style="border-left:1px solid #CDCDCD;">所有排行</th>
        </tr>
            <?php ecmsShowInfoTop($query,$where,$field,$topnum,0);?>
      </table></td>
  </tr>
</table>
<div class="line"></div>
        </div>
    </div>
</div>
</div>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="ShowClassNav.php?ecms=5&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
  <script language="javascript">
senfe("changecolor","#FFFFFF","#F0F0F0","#bce774","#bce774");
</script>
</body>
</html>
<?php
db_close();
$empire=null;
?>