<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$r=ReturnLeftLevel($loginlevel);
//菜单显示
$showfastmenu=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmenuclass where classtype=1 limit 1");//常用菜单
$showextmenu=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmenuclass where classtype=3 limit 1");//扩展菜单
$showshopmenu=stristr($public_r['closehmenu'],',shop,')?0:1;
//图片识别
if(stristr($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0'))
{
	$menufiletype='.gif';
}
else
{
	$menufiletype='.png';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<TITLE><?=$public_r[sitename]?></TITLE>
<LINK href="adminstyle/1/adminmain.css" rel=stylesheet>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="/skins/opera.css" rel="stylesheet" type="text/css" />
<link href="adminstyle/1/yecha/bootstrap.css" rel="stylesheet">
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type='text/javascript' src="adminstyle/1/js/html5.js"></script>
<script type='text/javascript' src="adminstyle/1/js/ie6.pngfix.js"></script>
<script src="adminstyle/1/js/jquery-1.9.1.min.js"></script>
<script src="adminstyle/1/js/jquery.menu-aim.js"></script>
<script src="adminstyle/1/js/bootstrap.min.js"></script>
<script  type="text/javascript">
function change_bg(obj)
{
    var a=document.getElementById("Menu").getElementsByTagName("a");
    for(var i=0;i<a.length;i++)
    {
        a[i].className="";
    }
    obj.className="current";
}
function ziliao(){
art.dialog.open('user/EditPassword.php<?=$ecms_hashur[whehref]?>',
    {title: '个人资料信息',lock: true,opacity: 0.5, width: 800, height: 520});
}
//增加常用菜单
function addmenu(){
art.dialog.open('other/MenuClass.php?classtype=1<?=$ecms_hashur['ehref']?>',
    {title: '增加常用菜单',width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</script>

<SCRIPT>
function switchSysBar(){
	if(switchPoint.innerText==3)
	{
		switchPoint.innerText=4
		document.all("frmTitle").style.display="none"
	}
	else
	{
		switchPoint.innerText=3
		document.all("frmTitle").style.display=""
	}
}
function switchSysBarInfo(){
	switchPoint.innerText=3
	document.all("frmTitle").style.display=""
}

function about(){
	window.showModalDialog("adminstyle/1/page/about.htm","ABOUT","dialogwidth:300px;dialogheight:150px;center:yes;status:no;scroll:no;help:no");
}

function over(obj){
		if(obj.className=="flyoutLink")
		{
			obj.style.backgroundColor='#B5C4EC'
			obj.style.borderColor = '#380FA6'
		}
		else if(obj.className=="flyoutLink1")
		{
		    obj.style.backgroundColor='#B5C4EC'
			obj.style.borderColor = '#380FA6'				
		}
}
function out(obj){
		if(obj.className=="flyoutLink")
		{
			obj.style.backgroundColor='#C9F1FF'
			obj.style.borderColor = 'C9F1FF'
		}
		else if(obj.className=="#flyoutLink1")
		{
		    obj.style.backgroundColor='#FBF9F9'
			obj.style.borderColor = '#FBF9F9'				
		}
}

function show(d){
	if(obj=document.all(d))	obj.style.visibility="visible";
}
function hide(d){
	if(obj=document.all(d))	obj.style.visibility="hidden";
}
function JumpToLeftMenu(url){
	document.getElementById("left").src=url;
}
function JumpToMain(url){
	document.getElementById("main").src=url;
}
</SCRIPT>
<script type="text/javascript">
$(document).ready(
function(){
	$(".tjxx").click(
		function(){
		if ($("select[name=classid]").val()==""){
			art.dialog({
				icon: 'error',
				content: '请选择你要发布信息的栏目！',
				lock: true,opacity: 0.5
			});
			return false;
		}	
		}
	);
var leftwidth = document.getElementById("left").clientWidth;
   var rightwidth = document.body.clientWidth - leftwidth;
   document.getElementById("right").style.width = rightwidth + "px";
   var tophight = document.getElementById("top").clientHeight;
   var centerhight = document.body.clientHeight - tophight;
   document.getElementById("center").style.height = centerhight + "px";
   document.getElementById("left").style.height = centerhight + "px";
   document.getElementById("right").style.height = centerhight + "px";
var timer;
art.dialog({
    content: '<?=$loginin?>!欢迎您的登陆!',
 width: 200,
    height: 80,
 left: '100%',
    top: '100%',
    fixed: true,
    drag: false,
    resize: false,
    init: function () {
     var that = this, i = 5;
        var fn = function () {
            that.title('系统帮助 ' + i + ' 秒后关闭');
            !i && that.close();
            i --;
        };
        timer = setInterval(fn, 1000);
        fn();
    },
    close: function () {
     clearInterval(timer);
    }
}).show();})
window.onresize = function(){
var leftwidth = document.getElementById("left").clientWidth;
   var rightwidth = document.body.clientWidth - leftwidth;
   document.getElementById("right").style.width = rightwidth + "px";
   var tophight = document.getElementById("top").clientHeight;
   var centerhight = document.body.clientHeight - tophight;
   document.getElementById("center").style.height = centerhight + "px";
   document.getElementById("left").style.height = centerhight + "px";
   document.getElementById("right").style.height = centerhight + "px";
            }
$(window).resize();
</script>
  <script>
        var $menu = $(".dropdown-menu");
        $menu.menuAim({
            activate: activateSubmenu,
            deactivate: deactivateSubmenu
        });
        function activateSubmenu(row) {
            var $row = $(row),
                submenuId = $row.data("submenuId"),
                $submenu = $("#" + submenuId),
                offset = $menu.offset(),
                height = $menu.outerHeight(),
                width = $menu.outerWidth();

            // Show the submenu
            $submenu.css({
                display: "block",
                top: offset.top,
                left: offset.left + width - 5,  // main should overlay submenu
                height: height - 4  // padding for main dropdown's arrow
            });
            $row.find("a").addClass("maintainHover");
        }

        function deactivateSubmenu(row) {
            var $row = $(row),
                submenuId = $row.data("submenuId"),
                $submenu = $("#" + submenuId);
            $submenu.css("display", "none");
            $row.find("a").removeClass("maintainHover");
        }
    </script>
<style type="text/css">
html,body
{ 
width:100%; 
height:100%; 
margin:0px; 
padding:0px; 
border:0px; 
overflow:hidden;
/* overflow-x: scroll; */
}
</style>

</HEAD>
<body>
<div id="top">
<div class="header">
 <form name="menuform" id="menuform">
 <input type="hidden" name="onclickmenu" value="">
    	<div class="logo"><a href="main.php<?=$ecms_hashur[whehref]?>" target="main" title="返回后台首页"></a></div>
        <div class="nav" id="Menu">
        	<ul>
            	<li><a href="adminstyle/1/left.php?ecms=system<?=$ecms_hashur['ehref']?>" target="left" class="current" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/1.png" /><span>系统设置</span></a></li>
                <li><a href="ListEnews.php<?=$ecms_hashur[whehref]?>" target="left" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/2.png" /><span>新闻管理</span></a></li>
                <li><a href="adminstyle/1/left.php?ecms=classdata<?=$ecms_hashur['ehref']?>" target="left" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/3.png" /><span>栏目分类</span></a></li>
                <li><a href="adminstyle/1/left.php?ecms=template<?=$ecms_hashur['ehref']?>" target="left" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/4.png" /><span>模板编辑</span></a></li>
                <li><a href="adminstyle/1/left.php?ecms=usercp<?=$ecms_hashur['ehref']?>" target="left" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/5.png" /><span>会员管理</span></a></li>
                <li><a href="adminstyle/1/left.php?ecms=tool<?=$ecms_hashur['ehref']?>" target="left" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/6.png" /><span>插件管理</span></a></li>
                <li style="<?=$showextmenu?'':'display:none'?>"><a href="adminstyle/1/left.php?ecms=extend<?=$ecms_hashur['ehref']?>" target="left" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/10.png" /><span>扩展菜单</span></a></li>
                <li><a href="#" onClick="window.open('openpage/AdminPage.php?leftfile=<?=urlencode('../ShopSys/pageleft.php'.$ecms_hashur['whehref'])?>&mainfile=<?=urlencode('../other/OtherMain.php'.$ecms_hashur['whehref'])?>&title=<?=urlencode('商城系统管理')?><?=$ecms_hashur['ehref']?>','AdminShopSys','');"><img src="adminstyle/1/yecha/ico/7.png" /><span>商城管理</span></a></li>
                <li><a href="adminstyle/1/left.php?ecms=other<?=$ecms_hashur['ehref']?>" target="left" onClick="change_bg(this)"><img src="adminstyle/1/yecha/ico/9.png" /><span>其他管理</span></a></li>
            </ul>
        </div>
  </form>
</div>
<div class="navmenu">
     	<div class="username"><span>欢迎您, <?=$loginin?> </span><a href="javascript:void(0)" onclick="ziliao()" class="logout" title="修改密码" target="main"></a><a href="#ecms" onClick="if(confirm('确认要退出?')){JumpToMain('ecmsadmin.php?enews=exit');}" class="zhzx">退出登陆</a></div>
     	
        <div class="kuaijie">
        	<div>
        	<ul>
            	<li><a>常用菜单：</a></li>
            	<li><a href="javascript:void()" target="main" class="addmenu" onclick="addmenu()">增加</a></li>
                <?php
$b=0;
//自定义常用操作菜单
$menucsql=$empire->query("select classid,classname from {$dbtbpre}enewsmenuclass where classtype=1 order by myorder,classid");
while($menucr=$empire->fetch($menucsql))
{
	$menujsvar='diymenu'.$menucr['classid'];
	$b=1;
?>
   <li style="<?=$showfastmenu?'':'display:none'?>"><div class="nav-collapse collapse"><a href="#" target="_blank" data-toggle="dropdown" class="dropdown-toggle"><?=$menucr['classname']?></a>
   <ul class="dropdown-menu" role="menu">
   <?php
		$menusql=$empire->query("select menuid,menuname,menuurl from {$dbtbpre}enewsmenu where classid='$menucr[classid]' order by myorder,menuid");
		while($menur=$empire->fetch($menusql))
		{
			if(!(strstr($menur['menuurl'],'://')||substr($menur['menuurl'],0,1)=='/'))
			{
				$menur['menuurl']='../../'.$menur['menuurl'];
			}
		?>
                    <li><a href="<?=$menur['menuurl']?>" target="main"><?=$menur['menuname']?></a></li>
    <?php
		}
		?>
   </ul>
   </li>
  <?php
}
//没菜单
if(!$b)
{
	$notrecordword="";
	echo"<li>$notrecordword</li>";
}
?>
               
                </li>
               
            </ul>
            </div>
        </div>
        <div class="addnewsxx">
        <form name="searchinfo" method="GET" action="AddNews.php<?=$ecms_hashur[whehref]?>" target="main">
        <input type="hidden" name="enews" value="AddNews" />
        <select name="classid">
            <?
            $lm=$empire->query("select classid,classname from {$dbtbpre}enewsclass where islast='1' order by classid");        //查询新闻表最新10条记录
			if (mysql_num_rows($lm) <1) {echo '<option value="">暂无信息栏目</option>';} else {echo '<option value="" selected="selected">请选择发布信息栏目</option>';};
			while($lan=$empire->fetch($lm))        //循环获取查询记录
			{
					echo '<option value="'.$lan[classid].'">'.$lan[classname].'</option>';
			}
			?>
        </select>
        <input type="submit" class="tjxx" value=""/>
        <form></div>
        <div class="refresh"><a href="ReHtml/ChangeData.php<?=$ecms_hashur[whehref]?>" target="main"><span>数据更新</span></a></div>
        <div class="viewindex"><a href="/" target="_blank"><span>查看首页</span></a></div>
     </div>
</div>
<div id="center" style=" width:100%;">
<div id="left" style="width:200px; background-color:#666; float:left; height:100%;">
<IFRAME frameBorder="0" id="left" name="left" scrolling="auto" src="ListEnews.php<?=$ecms_hashur[whehref]?>" style="HEIGHT:100%;VISIBILITY:inherit;WIDTH:200px;Z-INDEX:2"></IFRAME>
</div>
<div id="right" style="float:left; height:100%; overflow:auto;">
<IFRAME id="main" name="main" style="WIDTH: 100%; HEIGHT: 100%" src="main.php<?=$ecms_hashur[whehref]?>" frameBorder=0></IFRAME>
</div>
</div>
</body>


</HTML>