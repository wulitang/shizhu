<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../class/com_functions.php");
require "../".LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"gbook");
$enews=$_GET['enews'];
if(empty($enews))
{$enews=$_POST['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="DelGbook")
{
	$lyid=$_GET['lyid'];
	$bid=$_GET['bid'];
	DelGbook($lyid,$bid,$logininid,$loginin);
}
elseif($enews=="ReGbook")
{
	$lyid=$_POST['lyid'];
	$bid=$_POST['bid'];
	$retext=$_POST['retext'];
	ReGbook($lyid,$retext,$bid,$logininid,$loginin);
}
elseif($enews=="DelGbook_all")
{
	$lyid=$_POST['lyid'];
	$bid=$_POST['bid'];
	DelGbook_all($lyid,$bid,$logininid,$loginin);
}
elseif($enews=="CheckGbook_all")
{
	$lyid=$_POST['lyid'];
	$bid=$_POST['bid'];
	CheckGbook_all($lyid,$bid,$logininid,$loginin);
}
else
{}
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=12;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$search='';
$search.=$ecms_hashur['ehref'];
$add='';
$and=' where ';
//选择分类
$bid=(int)$_GET['bid'];
if($bid)
{
	$add.=$and."bid='$bid'";
	$search.="&bid=$bid";
	$and=' and ';
}
//是否审核
$checked=(int)$_GET['checked'];
if($checked)
{
	if($checked==1)//已审核
	{
		$add.=$and."checked=0";
	}
	else//待审核
	{
		$add.=$and."checked=1";
	}
	$and=' and ';
	$search.="&checked=$checked";
}
//搜索
$sear=(int)$_GET['sear'];
if($sear)
{
	$keyboard=RepPostVar2($_GET['keyboard']);
	$show=(int)$_GET['show'];
	if($keyboard)
	{
		if($show==1)//留言者
		{
			$add.=$and."name like '%$keyboard%'";
		}
		elseif($show==2)//留言内容
		{
			$add.=$and."lytext like '%$keyboard%'";
		}
		elseif($show==3)//邮箱
		{
			$add.=$and."email like '%$keyboard%'";
		}
		else//留言IP
		{
			$add.=$and."ip like '%$keyboard%'";
		}
		$and=' and ';
		$search.="&show=$show&keyboard=$keyboard";
	}
}
$query="select lyid,name,email,`mycall`,lytime,lytext,retext,bid,ip,checked,userid,username,eipport from {$dbtbpre}enewsgbook".$add;
$totalquery="select count(*) as total from {$dbtbpre}enewsgbook".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by lyid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="<a href=gbook.php".$ecms_hashur['whehref'].">管理留言</a>";
$gbclass=ReturnGbookClass($bid,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>留言管理</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
//留言分类管理
function lyflgl(){
art.dialog.open('tool/GbookClass.php<?=$ecms_hashur['whehref']?>',
    {title: '留言分类管理',lock: true,opacity: 0.5,width: 900, height: 570,
	 close: function () {
      location.reload();
    }
	});
}
//回复留言
function hfly(lyid,bid){
art.dialog.open('tool/ReGbook.php?<?=$ecms_hashur[ehref]?>&lyid='+lyid+'&bid='+bid,
    {title: '回复留言',lock: true,opacity: 0.2,width:700, height:420,
	 close: function () {
      location.reload();
    }
	});
}
</script>
<style>
.comm-table{ text-align:left;border:1px solid #CCCCCC;width:700px; margin-top:10px;box-shadow:inset 0 -2px 1px #fff;}
.comm-table th{ height:28px; margin:0; padding:0 8px; line-height:28px; font-size:12px;}
.comm-table td{ height:28px;text-align:left; border:none; line-height:28px; padding:0 5px;}
.comm-table td table{ border-top:1px solid #EFEFEF; background:none;}
.comm-table td table td{ background:none;}
</style>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择留言分类:
        <select name="bid" id="bid" onchange=window.location='gbook.php?<?=$ecms_hashur['ehref']?>&bid='+this.options[this.selectedIndex].value>
          <option value="0">显示全部留言</option>
          <?=$gbclass?>
        </select> <a href="javascript:lyflgl()" class="gl">留言分类管理</a> <a href="DelMoreGbook.php<?=$ecms_hashur['whehref']?>" class="del">批量删除留言</a></span></h3>
            <div class="line"></div>
<div class="anniuqun" style="display:flex">
<div class="saixuan">
<form name="searchform" method="GET" action="gbook.php">
<?=$ecms_hashur['eform']?>
      搜索：
          <select name="show" id="show">
            <option value="1"<?=$show==1?' selected':''?>>留言者</option>
            <option value="2"<?=$show==2?' selected':''?>>留言内容</option>
            <option value="3"<?=$show==3?' selected':''?>>邮箱</option>
            <option value="4"<?=$show==4?' selected':''?>>IP地址</option>
          </select>
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="checked" id="checked">
            <option value="0"<?=$checked==0?' selected':''?>>不限</option>
            <option value="1"<?=$checked==1?' selected':''?>>已审核</option>
            <option value="2"<?=$checked==2?' selected':''?>>待审核</option>
          </select>
          <input type="submit" name="Submit3" value="搜索">
          <input name="bid" type="hidden" id="bid" value="<?=$bid?>">
		  <input name="sear" type="hidden" id="sear" value="1">
	  </form>
</div>
</div>
<form name=thisform method=post action=gbook.php onSubmit="return confirm('确认要执行操作?');">
<?=$ecms_hashur['form']?>
<?
while($r=$empire->fetch($sql))
{
$br=$empire->fetch1("select bname from {$dbtbpre}enewsgbookclass where bid='$r[bid]'");
//审核
$checked="";
if($r[checked])
{
$checked=" title='未审核' style='background:#99C4E3'";
}
$username="游客";
if($r['userid'])
{
	$username="<a href='../member/AddMember.php?enews=EditMember&userid=".$r['userid'].$ecms_hashur['ehref']."' target=_blank>".$r['username']."</a>";
}
?>
  <table border="0" align="center" cellpadding="3" cellspacing="1" class="comm-table">
    <tr> 
      <th>发布者: <?=stripSlashes($r[name])?>&nbsp;(<?=$username?>)
        </th>  
      <th style="text-align:right">发布时间: <?=$r[lytime]?>&nbsp;(IP: <?=$r[ip]?>:<?=$r[eipport]?>)</th>
  </tr>
  <tr> 
    <td height="23" colspan="2"> <table border=0 width='100%' cellspacing=1 cellpadding=10>
        <tr> 
          <td width='100%' bgcolor='#FFFFFF' style='word-break:break-all'> 
           <?=nl2br(stripSlashes($r[lytext]))?>
          </td>
        </tr>
      </table>
      <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" style='word-break:break-all; border:1px solid #F2F2F2; background:#FFFCEF; margin:5px 0; color:#DB7C22;'>
        <tr> 
          <td><span class="huifu"></span><strong>管理员回复:</strong> 
          <?
          if(empty($r[retext])){
			  echo "<a href='javascript:hfly(".$r[lyid].",".$bid.")' class='regbook'>点击回复</a>";
			  } else {
				  echo nl2br(stripSlashes($r[retext]))."&nbsp;<a href='javascript:hfly(".$r[lyid].",".$bid.")' class='regbook'>编辑此回复</a>";
				  }
		  ?>
          </td>
        </tr>
      </table> 
    </td>
  </tr>
  <tr>
    <td height="23" colspan="2"><div align="right">
       <table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr style="background:#F2F2F2;">
            <td width="65%"><span class="mail">邮箱:<?=$r[email]?></span> | <span class="phone">电话:<?=$r[mycall]?></span></td>
            <td width="35%" style="text-align:right;"><strong>操作:</strong>&nbsp;[<a href="gbook.php?enews=DelGbook&lyid=<?=$r[lyid]?>&bid=<?=$bid?><?=$ecms_hashur['href']?>" onClick="return confirm('确认要删除?');">删除</a>] 
                  <input name="lyid[]" type="checkbox" id="lyid[]" value="<?=$r[lyid]?>"<?=$checked?>>
                </td>
          </tr>
        </table>
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
          <tr>
            <td><div align="center">所属留言分类:<a href="gbook.php?bid=<?=$r[bid]?><?=$ecms_hashur['ehref']?>"><?=$br[bname]?></a></div></td>
          </tr>
        </table>
        </div></td>
  </tr>
</table>
<br>
<?
}
?>
<div class="clear"></div>
<table width="700" border="0" align="center" cellpadding="3" cellspacing="1">
    <tr>
      <td style="text-align:right;padding:0 11px;">
        <input type="submit" name="Submit" value="审核留言" onClick="document.thisform.enews.value='CheckGbook_all';">
        &nbsp;<input type="submit" name="Submit2" value="删除留言" onClick="document.thisform.enews.value='DelGbook_all';">
        <input name="enews" type="hidden" id="enews" value="DelGbook_all">
        <input name="bid" type="hidden" id="bid" value="<?=$bid?>">
        &nbsp;<input type=checkbox name=chkall value=on onclick=CheckAll(this.form) title="全选"></td>
    </tr>
    <tr> 
      <td style="padding:10px 0;">
        <?=$returnpage?> 
      </td>
  </tr>
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
<?
db_close();
$empire=null;
?>
