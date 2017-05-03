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
//CheckLevel($logininid,$loginin,$classid,"pl");

$plr=$empire->fetch1("select pldatatbs,pldeftb from {$dbtbpre}enewspl_set limit 1");
$tr=explode(',',$plr['pldatatbs']);
//今日评论
$pur=$empire->fetch1("select lasttimepl,lastnumpl,lastnumpltb,todaytimeinfo,todaytimepl,todaynumpl,yesterdaynumpl from {$dbtbpre}enewspublic_update limit 1");
//更新昨日信息
$todaydate=date('Y-m-d');
if(date('Y-m-d',$pur['todaytimeinfo'])<>$todaydate||date('Y-m-d',$pur['todaytimepl'])<>$todaydate)
{
	DoUpdateYesterdayAddDataNum();
	$pur=$empire->fetch1("select lasttimepl,lastnumpl,lastnumpltb,todaytimeinfo,todaytimepl,todaynumpl,yesterdaynumpl from {$dbtbpre}enewspublic_update limit 1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>评论</title>
<link rel="stylesheet" type="text/css" href="../adminstyle/1/yecha/yecha.css" />
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
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
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="PlMain.php<?=$ecms_hashur['whehref']?>">评论统计</a></div></div>
<div class="kongbai"></div>
    <div id="tab">
	<div class="ui-tab-container">
	<ul class="clearfix ui-tab-list">
		<li><a href="../info/InfoMain.php<?=$ecms_hashur['whehref']?>">信息统计</a></li>
		<li class="ui-tab-active"><a href="../pl/PlMain.php<?=$ecms_hashur['whehref']?>">评论统计</a></li>
		<li><a href="../other/OtherMain.php<?=$ecms_hashur['whehref']?>">其他统计</a></li>
	</ul>
	<div class="ui-tab-bd">
<table class="comm-table2" cellspacing="0" id="changecolor">
<form name="form1" method="post" action="">
	<tbody>
		<tr>
		  <th colspan="5" align="left" style="padding-left:10px;">评论发布统计 (今日评论数：<?=$pur['todaynumpl']?>，昨天评论数：<?=$pur['yesterdaynumpl']?>)</th>
		  </tr>
		<tr>
			<th>分表</th>
			<th>已审核</th>
			<th>待审核</th>
            <th>总数</th>
			<th>从 
      <font color="#0360AF"><?=date('Y-m-d H:i:s',$pur['lasttimepl'])?></font>
    截止至现在的新增数量</th>
		</tr>
	  <?php
	  $j=0;
	  $alltbpls=0;
	  $count=count($tr)-1;
	  for($i=1;$i<$count;$i++)
	  {
	  	$j++;
		$bgcolor='#FFFFFF';
		if($j%2==0)
		{
			$bgcolor='';
		}
		$thistb=$tr[$i];
		$restbname="评论表".$thistb;
		$pltbname='enewspl_'.$thistb;
		$alltbpls=eGetTableRowNum($dbtbpre.$pltbname);
		$checktbpls=$empire->gettotal("select count(*) as total from ".$dbtbpre.$pltbname." where checked=1");
		$tbpls=$alltbpls-$checktbpls;
		if($thistb==$plr['pldeftb'])
		{
			$restbname='<b>'.$restbname.'</b>';
		}
		$exp='|'.$thistb.',';
		$addnumr=explode($exp,$pur['lastnumpltb']);
		$addnumrt=explode('|',$addnumr[1]);
		$addnum=(int)$addnumrt[0];
		$totalalltbpls+=$alltbpls;
		$totalchecktbpls+=$checktbpls;
		$totaltbpls+=$tbpls;
	  ?>
 <tr bgcolor="<?=$bgcolor?>"> 
    <td height="25" align="center">
		<div align="center"><a href="ListAllPl.php?restb=<?=$tr[$i]?><?=$ecms_hashur['ehref']?>" title="*<?=$pltbname?>" target="_blank">
	    <?=$restbname?>
    </a></div></td>
    <td align="center"><div><a href="ListAllPl.php?restb=<?=$tr[$i]?>&checked=1<?=$ecms_hashur['ehref']?>" target="_blank"><?=$tbpls?></a></div></td>
    <td align="center"><div><a href="ListAllPl.php?restb=<?=$tr[$i]?>&checked=2<?=$ecms_hashur['ehref']?>" target="_blank"><?=$checktbpls?></a></div></td>
    <td align="center"><a href="ListAllPl.php?restb=<?=$tr[$i]?><?=$ecms_hashur['ehref']?>" target="_blank"><?=$alltbpls?></a></td>
    <td align="center"><?=$addnum?></td>
  </tr>
    <?
	}
	?>
 		<tr>
    <td height="25" align="center"><div>总计：</div></td>
    <td align="center"><div><?=$totaltbpls?></div></td>
    <td align="center"><div><?=$totalchecktbpls?></div></td>
    <td align="center"><?=$totalalltbpls?></td>
    <td align="center"><?=$pur['lastnumpl']?></td>
    </tr>
 		<tr>
 		  <td colspan="5" style="padding:10px;"><input type="button" name="Submit" value="重置截止统计" onClick="if(confirm('确认要重置评论数统计?')){self.location.href='../ecmscom.php?enews=ResetAddDataNum&type=pl&from=pl/PlMain.php<?=urlencode($ecms_hashur['whehref'])?><?=$ecms_hashur['href']?>';}" class="anniu"> <font color="#666666">说明：点击"已审核"、"未审核"或"未审核投稿"数可进入相应的管理。</font></td>
 		  </tr>
	</tbody>
  </form>
</table>
 <div class="line"></div>
  </div>
 </div>
</div>
 <div class="clear"></div>
 </div>
 <script language="javascript">
senfe("changecolor","#FFFFFF","#F0F0F0","#bce774","#bce774");
</script>
</body>
</html>
<?php
db_close();
$empire=null;
?>