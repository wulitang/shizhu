<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"class");

//展开
if($_GET['doopen'])
{
	$open=(int)$_GET['open'];
	SetDisplayClass($open);
}
//图标
if(getcvar('displayclass',1))
{
	$img="<a href='ListClass.php?doopen=1&open=0".$ecms_hashur['ehref']."' title='展开'><img src='../data/images/displaynoadd.gif' width='15' height='15' border='0'></a>";
}
else
{
	$img="<a href='ListClass.php?doopen=1&open=1".$ecms_hashur['ehref']."' title='收缩'><img src='../data/images/displayadd.gif' width='15' height='15' border='0'></a>";
}
//缓存
$displayclass=(int)getcvar('displayclass',1);
$fcfile="../data/fc/ListClass".$displayclass.".php";
$fclistclass='';
if(file_exists($fcfile))
{
	$fclistclass=str_replace(AddCheckViewTempCode(),'',ReadFiletext($fcfile));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理栏目</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link rel="stylesheet" href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<SCRIPT lanuage="JScript">
function turnit(ss)
{
 if (ss.style.display=="") 
  ss.style.display="none";
 else
  ss.style.display=""; 
}
var newWindow = null
</SCRIPT>
<script type="text/javascript">
//增加栏目
function zjlm(){
art.dialog.open('AddClass.php?enews=AddClass<?=$ecms_hashur['ehref']?>',
    {title: '增加栏目',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//批量增加栏目
function plzjlm(){
art.dialog.open('PLAddClass.php?enews=AddClass<?=$ecms_hashur['ehref']?>',
    {title: '批量增加栏目',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//刷新首页
function sxsy(){
art.dialog.open('ecmschtml.php?enews=ReIndex<?=$ecms_hashur['href']?>',
    {title: '刷新首页',lock: true,opacity: 0.5, width:535, height: 270,
	init: function () {
    	var that = this, i = 2;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
//刷新所有栏目页
function sxsylmy(){
art.dialog.open('ecmschtml.php?enews=ReListHtml_all&from=ListClass.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>',
    {title: '刷新所有栏目页',lock: true,opacity: 0.5,width:535, height: 270,
	init: function () {
    	var that = this, i = 2;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
//刷新所有信息页面
function sxsyxxym(){
art.dialog.open('ReHtml/DoRehtml.php?enews=ReNewsHtml&start=0&from=ListClass.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>',
    {title: '刷新所有信息页面',lock: true,opacity: 0.5,width:535, height:600,
	init: function () {
    	var that = this, i = 2;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
//刷新所有JS调用
function sxsyjsdy(){
art.dialog.open('ecmschtml.php?enews=ReAllNewsJs&from=ListClass.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>',
    {title: '刷新所有JS调用',lock: true,opacity: 0.5,width:535, height: 270,
	init: function () {
    	var that = this, i = 2;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
//修改栏目
function editlm(classid){
art.dialog.open('AddClass.php?<?=$ecms_hashur['ehref']?>&classid='+classid+'&enews=EditClass',
    {title: '修改"'+document.getElementById('classname'+classid).innerHTML+'" 栏目',lock: true,opacity: 0.5, width: 950, height: 520,
	close: function () {
      location.reload();
    }
	});
}
//复制栏目
function copylm(classid){
art.dialog.open('AddClass.php?<?=$ecms_hashur['ehref']?>&classid='+classid+'&enews=AddClass&docopy=1',
    {title: '复制 "'+document.getElementById('classname'+classid).innerHTML+'" 栏目',lock: true,opacity: 0.5, width: 950, height: 520,
	close: function () {
      location.reload();
    }
	});
}
//删除栏目
function dellm(classid){
	var classname=document.getElementById('classname'+classid).innerHTML;
	var dialog=art.dialog({
	 title:'删除警告',
	 follow: document.getElementById('class'+classid),
	 content: '你确定要删除 "'+classname+'" 栏目么?',
	 button: [{
    	name: '确定',
        callback: function () {
           art.dialog.open('ecmsclass.php?<?=$ecms_hashur['href']?>&classid='+classid+'&enews=DelClass',
    {title: '删除栏目',lock: true,opacity: 0.5, width:535, height: 320,
	init: function () {
    	var that = this, i = 2;
        var fn = function () {
            that.title( i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
      location.reload();
    }
	});
            return false;
        },
        focus: true
    },
		{
		 name:'取消',	
		}
	]
	})
}
//调用地址
function tvurl(classid){
art.dialog.open('view/ClassUrl.php?<?=$ecms_hashur['ehref']?>&classid='+classid,
    {title: 'JS调用',lock: true,opacity: 0.5, width: 700, height: 280});
}
//刷新栏目
function relist(classid){
art.dialog.open('enews.php?<?=$ecms_hashur['href']?>&enews=ReListHtml&from=ListClass.php<?=urlencode($ecms_hashur['whehref'])?>&classid='+classid,
    {title: '刷新 "'+document.getElementById('classname'+classid).innerHTML+'" 栏目',lock: true,opacity: 0.5, width:485, height: 270,
		init: function () {
    	var that = this, i = 2;
        var fn = function () {
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
//刷新信息
function renews(classid,tbname){
art.dialog.open('ReHtml/DoRehtml.php?<?=$ecms_hashur['href']?>&enews=ReNewsHtml&from=ListClass.php<?=urlencode($ecms_hashur['whehref'])?>&classid='+classid+'&tbname[]='+tbname,
    {title: '刷新 "'+document.getElementById('classname'+classid).innerHTML+'" 栏目信息',lock: true,opacity: 0.5, width:485, height: 270,
		init: function () {
    	var that = this, i = 2;
        var fn = function () {
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
//归档
function docinfo(classid){
	var classname=document.getElementById('classname'+classid).innerHTML;
	var dialog=art.dialog({
	 title:'归档警告',
	 follow: document.getElementById('docinfo'+classid),
	 content: '你确定要归档 "'+classname+'" 栏目么?',
	 button: [{
    	name: '确定',
        callback: function () {
           art.dialog.open('ecmsinfo.php?<?=$ecms_hashur['href']?>&enews=InfoToDoc&ecmsdoc=1&docfrom=ListClass.php<?=urlencode($ecms_hashur['whehref'])?>&classid='+classid,
    {title: '归档 "'+classname+'" 栏目信息',lock: true,opacity: 0.5, width:800, height: 320});
        },
        focus: true
    },
		{
		 name:'取消',	
		}
	]
	})
}
//刷新JS
function rejs(classid){
art.dialog.open('ecmschtml.php?<?=$ecms_hashur['href']?>&enews=ReSingleJs&doing=0&classid='+classid,
    {title: '刷新 "'+document.getElementById('classname'+classid).innerHTML+'" JS',lock: true,opacity: 0.5, width:485, height: 270,
		init: function () {
    	var that = this, i = 2;
        var fn = function () {
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
	close: function () {
	  clearInterval(timer);
    }
	});
}
//标题分类
function ttc(classid){
art.dialog.open('ClassInfoType.php?<?=$ecms_hashur['ehref']?>&classid='+classid,
    {title: '管理"'+document.getElementById('classname'+classid).innerHTML+'" 栏目标题分类',lock: true,opacity: 0.5, width: 485, height: 520
	});
}
</script>
<style>
.comm-table2 td{ padding:4px 15px; }
</style>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <a href="ListClass.php<?=$ecms_hashur['whehref']?>">管理栏目</a> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理栏目 
      		<a href="javascript:void(0)" onclick="zjlm()" class="add">增加栏目</a> <a href="javascript:void(0)" onclick="plzjlm()" class="add">批量增加栏目</a> <a href="javascript:void(0)" onclick="sxsy()" class="sx">刷新首页</a> <a href="javascript:void(0)" onclick="sxsylmy()" class="sx">刷新所有栏目页</a> <a href="javascript:void(0)" onclick="sxsyxxym()" class="sx">刷新所有信息页面</a> <a href="javascript:void(0)" onclick="sxsyjsdy()" class="sx">刷新所有JS调用</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="comm-table2">
  <form name=editorder method=post action=ecmsclass.php onSubmit="return confirm('确认要操作?');">
  <?=$ecms_hashur['form']?>
    <tr> 
      <th>顺序</th>
      <th><?=$img?></th>
      <th>ID</th>
      <th>栏目名</th>
      <th>访问</th>
      <th>栏目管理</th>
      <th>操作</th>
    </tr>
    <?php
	echo $fclistclass;
	?>
    <tr> 
      <td height="25" colspan="7"> <div align="left" class="anniuqun"> &nbsp;&nbsp;
          <input type="submit" name="Submit5" value="修改栏目顺序" onClick="document.editorder.enews.value='EditClassOrder';document.editorder.action='ecmsclass.php';">&nbsp;&nbsp;
          <input name="enews" type="hidden" id="enews" value="EditClassOrder">
          <input type="submit" name="Submit7" value="刷新栏目页面" onClick="document.editorder.enews.value='GoReListHtmlMoreA';document.editorder.action='ecmschtml.php';">&nbsp;&nbsp;
          <input type="submit" name="Submit72" value="终极栏目属性转换" onClick="document.editorder.enews.value='ChangeClassIslast';document.editorder.action='ecmsclass.php';">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="7"><strong>终极栏目属性转换说明(只能选择单个栏目)：</strong><br>
        如果你选择的是<font color="#FF0000">非终极栏目</font>，则转为<font color="#FF0000">终极栏目</font><font color="#666666">(此栏目不能有子栏目)</font><br>
        如果你选择的是<font color="#FF0000">终极栏目</font>，则转为<font color="#FF0000">非终极栏目</font><font color="#666666">(请先把当前栏目的数据转移，否则会出现冗余数据)<br>
        </font><strong>修改栏目顺序:顺序值越小越前面</strong></td>
    </tr>
    <input name="from" type="hidden" value="ListClass.php<?=$ecms_hashur['whehref']?>">
    <input name="gore" type="hidden" value="0">
  </form>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="comm-table2">
  <tr> 
    <th><div align="center">名称</div></th>
    <th>调用地址</th>
   	<th>名称</th>
    <th>调用地址</th>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF"><div align="center">热门信息调用</div></td>
    <td height="25" bgcolor="#FFFFFF"> <input name="textfield" type="text" value="<?=$public_r[newsurl]?>d/js/js/hotnews.js">
      [<a href="ecmschtml.php?enews=ReHot_NewNews<?=$ecms_hashur['href']?>">刷新</a>][<a href="view/js.php?js=hotnews&p=js<?=$ecms_hashur['ehref']?>" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center">横向搜索表单</div></td>
    <td bgcolor="#FFFFFF"> <div align="left"> 
        <input name="textfield3" type="text" value="<?=$public_r[newsurl]?>d/js/js/search_news1.js">
        [<a href="view/js.php?js=search_news1&p=js<?=$ecms_hashur['ehref']?>" target="_blank">预览</a>]</div></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF"> <div align="center">最新信息调用</div></td>
    <td height="25" bgcolor="#FFFFFF"> <input name="textfield2" type="text" value="<?=$public_r[newsurl]?>d/js/js/newnews.js">
      [<a href="ecmschtml.php?enews=ReHot_NewNews<?=$ecms_hashur['href']?>">刷新</a>][<a href="view/js.php?js=newnews&p=js<?=$ecms_hashur['ehref']?>" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center">纵向搜索表单</div></td>
    <td bgcolor="#FFFFFF"> <div align="left"> 
        <input name="textfield4" type="text" value="<?=$public_r[newsurl]?>d/js/js/search_news2.js">
        [<a href="view/js.php?js=search_news2&p=js<?=$ecms_hashur['ehref']?>" target="_blank">预览</a>]</div></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF"><div align="center">推荐信息调用</div></td>
    <td height="25" bgcolor="#FFFFFF"><input name="textfield22" type="text" value="<?=$public_r[newsurl]?>d/js/js/goodnews.js">
      [<a href="ecmschtml.php?enews=ReHot_NewNews<?=$ecms_hashur['href']?>">刷新</a>][<a href="view/js.php?js=goodnews&p=js<?=$ecms_hashur['ehref']?>" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center">搜索页面地址</div></td>
    <td bgcolor="#FFFFFF"> <div align="left"> 
        <input name="textfield5" type="text" value="<?=$public_r[newsurl]?>search">
        [<a href="../../search" target="_blank">预览</a>]</div></td>
  </tr>
  <tr> 
    <td height="24" bgcolor="#FFFFFF"> 
      <div align="center">控制面板地址</div></td>
    <td height="24" bgcolor="#FFFFFF">
<input name="textfield52" type="text" value="<?=$public_r[newsurl]?>e/member/cp">
      [<a href="../member/cp" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center"></div></td>
    <td bgcolor="#FFFFFF"><div align="center"></div></td>
  </tr>
  <tr> 
    <td height="25" colspan="4">js调用方式：&lt;script src=js地址&gt;&lt;/script&gt;</td>
  </tr>
</table>
</div>
<div class="line"></div>
        </div>
    </div>
</div>
</div>
</body>
</html>
<?php
db_close();
$empire=null;
?>
