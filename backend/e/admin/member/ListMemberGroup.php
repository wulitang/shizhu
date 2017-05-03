<?php
define('EmpireCMSAdmin','1');
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../member/class/user.php");
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
CheckLevel($logininid,$loginin,$classid,"member");
$url="<a href=ListMemberGroup.php".$ecms_hashur['whehref'].">管理会员组</a>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理会员组</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
//发送短消息
function send(groupid){
var groupname=document.getElementById('hy'+groupid).innerHTML;
art.dialog.open('member/SendMsg.php?<?=$ecms_hashur[ehref]?>&groupid='+groupid,
    {title: '给'+groupname+'发送短消息',lock: true,opacity: 0.5, width: 800, height: 540});
}
//增加会员组
function zjhyz(){
art.dialog.open('member/AddMemberGroup.php?<?=$ecms_hashur[ehref]?>&enews=AddMemberGroup',
    {title: '增加会员组',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//修改会员组
function xghyz(groupid){
	var groupname=document.getElementById('hy'+groupid).innerHTML;
art.dialog.open('member/AddMemberGroup.php?<?=$ecms_hashur[ehref]?>&enews=EditMemberGroup&groupid='+groupid,
    {title: '修改 '+groupname+' 会员组',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//删除会员组
function schyz(groupid){
	var groupname=document.getElementById('hy'+groupid).innerHTML;
	var dialog=art.dialog({
	 title:'删除警告',
	 follow: document.getElementById('sc'+groupid),
	 content: '你确定要删除 "'+groupname+'" 么?',
	 button: [{
    	name: '确定',
        callback: function () {
           art.dialog.open('ecmsmember.php?<?=$ecms_hashur[href]?>&enews=DelMemberGroup&groupid='+groupid,
    {title: '删除会员组',lock: true,opacity: 0.5, width:800, height: 320,
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

</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理会员组 <a href="javascript:zjhyz()" class="add">增加会员组</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>会员组名称</th>
            <th>级别值</th>
            <th>发送短消息</th>
            <th>注册地址</th>
            <th>操作</th>
		</tr>
 <?
  $sql=$empire->query("select * from {$dbtbpre}enewsmembergroup order by groupid");
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"> <div align="center"> 
        <?=$r[groupid]?>
      </div></td>
    <td height="25"> <div align="center" id="hy<?=$r[groupid]?>"> 
        <?=$r[groupname]?>
      </div></td>
    <td><div align="center"> 
        <?=$r[level]?>
      </div></td>
    <td><div align="center"><a href="javascript:send(<?=$r[groupid]?>)" target=_blank>发送信息</a></div></td>
    <td><div align="center"><a href="../../member/register/?groupid=<?=$r[groupid]?>" target="_blank">注册地址</a></div></td>
    <td height="25"> <div align="center">[<a href="javascript:xghyz(<?=$r[groupid]?>)">修改</a>] 
        [<a href="javascript:schyz(<?=$r[groupid]?>)" id="sc<?=$r[groupid]?>">删除</a>]</div></td>
  </tr>
  <?
  }
  db_close();
  $empire=null;
  ?>
  		<tr>
  		  <td colspan="6"><font color="#666666">说明：级别值越高，查看信息的权限越大</font></td>
		  </tr>
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
