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
CheckLevel($logininid,$loginin,$classid,"memberf");
$url="<a href='ListMemberF.php".$ecms_hashur['whehref']."'>管理会员字段</a>";
$sql=$empire->query("select * from {$dbtbpre}enewsmemberf order by myorder,fid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理字段</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="../adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<style>
.comm-table td{ padding:8px 0; height:16px;}
</style>
<SCRIPT>
//修改会员字段
function xghyzd(fid){
art.dialog.open('member/AddMemberF.php?<?=$ecms_hashur[ehref]?>&enews=EditMemberF&fid='+fid,
    {title: '修改会员字段',lock: true,opacity: 0.5,width: 800, height: 540,
	 close: function () {
      location.reload();
    }
	});
}
</SCRIPT>
</head>

<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <?=$url?></div></div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
        	<h3><span>管理会员字段 <a href="AddMemberF.php?enews=AddMemberF<?=$ecms_hashur['ehref']?>" class="add">增加字段</a> <a href="ListMemberForm.php<?=$ecms_hashur['whehref']?>" class="gl">管理会员表单</a></span></h3>
            <div class="line"></div>
<div class="anniuqun">
<form name="form1" method="post" action="../ecmsmember.php" onSubmit="return confirm('确认要操作?');">
  <?=$ecms_hashur['form']?>
<table class="comm-table" cellspacing="0">
	<tbody>
		<tr>
			<th style="width:150px;">顺序</th>
			<th>字段名</th>
            <th>字段标识</th>
            <th>字段类型</th>
            <th>操作</th>
		</tr>
  <?
  while($r=$empire->fetch($sql))
  {
  	$ftype=$r[ftype];
  	if($r[flen])
	{
		if($r[ftype]!="TEXT"&&$r[ftype]!="MEDIUMTEXT"&&$r[ftype]!="LONGTEXT")
		{
			$ftype.="(".$r[flen].")";
		}
	}
  ?>
    <tr bgcolor="ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="myorder[]" type="text" id="myorder[]" value="<?=$r[myorder]?>" size="3">
          <input type=hidden name=fid[] value=<?=$r[fid]?>>
        </div></td>
      <td><div align="center"> 
          <?=$r[f]?>
        </div></td>
      <td><div align="center"> 
          <?=$r[fname]?>
        </div></td>
      <td><div align="center">
	  	  <?=$ftype?>
	  </div></td>
      <td><div align="center"> 
         [<a href='javascript:xghyzd(<?=$r[fid]?>)'>修改</a>]&nbsp;&nbsp;[<a href='../ecmsmember.php?enews=DelMemberF&fid=<?=$r[fid]?><?=$ecms_hashur['href']?>' onClick="return confirm('确认要删除?');">删除</a>]
        </div></td>
    </tr>
    <?
	}
	?>
  		<tr>
  		  <td colspan="5" style="text-align:left;">&nbsp;
  		    <input type="submit" name="Submit" value="修改字段顺序">
        <font color="#666666">(值越小越前面)</font> 
        <input name="enews" type="hidden" id="enews" value="EditMemberFOrder"></td>
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
<?
db_close();
$empire=null;
?>
