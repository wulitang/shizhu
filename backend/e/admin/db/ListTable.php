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
//CheckLevel($logininid,$loginin,$classid,"table");
$url="<a href='ListTable.php".$ecms_hashur['whehref']."'>管理数据表</a>";
$sql=$empire->query("select tid,tname,tbname,isdefault from {$dbtbpre}enewstable order by tid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理数据表</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
			
		});
//增加数据表
function zjsjb(){
art.dialog.open('db/AddTable.php?<?=$ecms_hashur[ehref]?>&enews=AddTable',
    {title: '增加数据表',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//导入系统模型
function drxtmx(){
art.dialog.open('db/LoadInM.php<?=$ecms_hashur['whehref']?>',
    {title: '导入系统模型',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//修改数据表
function editdb(obj){
art.dialog.open('db/AddTable.php?<?=$ecms_hashur[ehref]?>&enews=EditTable&tid='+obj,
    {title: '修改数据表',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//删除数据表
function deldb(tid){
art.dialog.open('ecmsmod.php?<?=$ecms_hashur[href]?>&enews=DelTable&tid='+tid,
    {title: '删除数据表',lock: true,opacity: 0.5, width: 800, height: 540,
	init: function () {
    	var that = this, i = 3;
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
}
//管理字段
function glzd(tid,tbname){
art.dialog.open('db/ListF.php?<?=$ecms_hashur[ehref]?>&tid=' + tid + '&tbname=' + tbname,
    {title: '管理字段',id: 'glzdbox',lock: true,opacity: 0.5, width: 800, height: 650,
	close: function () {
      location.reload();
    }
	});
}
//管理系统模型
function glxtmx(tid,tbname){
art.dialog.open('db/ListM.php?<?=$ecms_hashur[ehref]?>&tid=' + tid + '&tbname=' + tbname,
    {title: '管理系统模型',lock: true,opacity: 0.5, width: 800, height: 540,
	close: function () {
      location.reload();
    }
	});
}
//管理分表
function glfb(tid,tbname){
art.dialog.open('db/ListDataTable.php?<?=$ecms_hashur[ehref]?>&tid=' + tid + '&tbname=' + tbname,
    {title: '管理分表',lock: true,opacity: 0.5, width: 800, height: 280,
	close: function () {
      location.reload();
    }
	});
}
//复制数据表
function copydb(tid){
art.dialog.open('db/CopyTable.php?<?=$ecms_hashur[ehref]?>&enews=CopyNewTable&tid='+tid,
    {title: '复制数据表',lock: true,opacity: 0.5, width: 800, height: 500,
	close: function () {
      location.reload();
    }
	});
}
</script>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?> </div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>选择分类： 
      <select name="classid" id="classid" onchange=window.location='ListPubVar.php?classid='+this.options[this.selectedIndex].value>
          <option value="0">显示所有分类</option>
		  <?=$cstr?>
        </select> <a href="javascript:void(0)" onclick="zjsjb()" class="add">增加数据表</a> <a href="javascript:void(0)" onclick="drxtmx()" class="gl">导入系统模型</a></span></h3>
            <div class="line"></div>
<div class="jqui">
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th>ID</th>
			<th>表名称</th>
			<th>管理</th>
			<th>操作</th>
		</tr>
  <?php
  while($r=$empire->fetch($sql))
  {
	//默认表
	if($r[isdefault])
	{
		$bgcolor="#DBEAF5";
		$movejs='';
	}
	else
	{
		$bgcolor="#ffffff";
		$movejs=' onmouseout="this.style.backgroundColor=\'#ffffff\'" onmouseover="this.style.backgroundColor=\'#C3EFFF\'"';
	}
  ?>
		<tr>
			<td><?=$r[tid]?></td>
			<td><?=$r[tname]?>&nbsp;( <?=$dbtbpre?>ecms_<b><?=$r[tbname]?></b> ) </td>
			<td>[<a href="javascript:glzd(<?=$r[tid]?>,'<?=$r[tbname]?>')"><strong>管理字段</strong></a>] &nbsp;
        [<a href="ListM.php?tid=<?=$r[tid]?>&tbname=<?=$r[tbname]?><?=$ecms_hashur['ehref']?>"><strong>管理系统模型</strong></a>] &nbsp;
        [<a href="javascript:glfb(<?=$r[tid]?>,'<?=$r[tbname]?>')"><strong>管理分表</strong></a>]</td>
			<td> [<a href="../ecmsmod.php?enews=DefaultTable&tid=<?=$r[tid]?>" onClick="return confirm('确认要默认?');"><strong>设为默认表</strong></a>] &nbsp;
        [<a href="javascript:copydb(<?=$r[tid]?>)"><strong>复制</strong></a>] &nbsp;
        [<a href="AddTable.php?enews=EditTable&tid=<?=$r[tid]?><?=$ecms_hashur['ehref']?>"><strong>修改</strong></a>] &nbsp;
        [<a href="javascript:deldb(<?=$r[tid]?>)" onClick="return confirm('确认要删除?');"><strong>删除</strong></a>] </td>
		</tr>
  <?php
  }
  ?>
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
<?php
db_close();
$empire=null;
?>
