<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
//查询SQL，如果要显示自定义字段记得在SQL里增加查询字段
$query="select id,classid,isurl,titleurl,isqf,havehtml,istop,isgood,firsttitle,ismember,userid,username,plnum,totaldown,onclick,newstime,truetime,lastdotime,titlepic,title from ".$infotb.$ewhere." order by ".$doorder." limit $offset,$line";
$sql=$empire->query($query);
//返回头条和推荐级别名称
$ftnr=ReturnFirsttitleNameList(0,0);
$ftnamer=$ftnr['ftr'];
$ignamer=$ftnr['igr'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理信息</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link rel="stylesheet" href="/js/cikonss.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/js/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="/skins/opera.css" type="text/css" media="all" />
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
<script type="text/javascript" src="/js/yecha.js"></script>
<script type="text/javascript">
$(function(){
			
		});
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
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }

function GetSelectId(form)
{
  var ids='';
  var dh='';
  for (var i=0;i<form.elements.length;i++)
  {
	var e = form.elements[i];
	if (e.name == 'id[]')
	{
	   if(e.checked==true)
	   {
       		ids+=dh+e.value;
			dh=',';
	   }
	}
  }
  return ids;
}

function PushInfoToSp(form)
{
	var id='';
	id=GetSelectId(form);
	if(id=='')
	{
		alert('请选择要推送的信息');
		return false;
	}
	window.open('sp/PushToSp.php?<?=$ecms_hashur['ehref']?>&classid=<?=$classid?>&id='+id,'PushToSp','width=360,height=500,scrollbars=yes,left=300,top=150,resizable=yes');
}

function PushInfoToZt(form)
{
	var id='';
	id=GetSelectId(form);
	if(id=='')
	{
		alert('请选择要推送的信息');
		return false;
	}
	window.open('special/PushToZt.php?<?=$ecms_hashur['ehref']?>&classid=<?=$classid?>&id='+id,'PushToZt','width=360,height=500,scrollbars=yes,left=300,top=150,resizable=yes');
}
//修改信息
function editnews(id,classid,bclassid){
if(document.getElementById("newck").checked){
   art.dialog.open('AddNews.php?enews=EditNews&id='+id+'&classid='+classid+'&bclassid='+bclassid+'<?=$addecmscheck?><?=$ecms_hashur['ehref']?>',
    {title: '修改信息',lock: true,opacity: 0.5, width: 950, height: 520,
	close: function () {
       if(art.dialog.data('issubmit')==true)
                location.reload();
    }
	});
} else {
	window.open('AddNews.php?enews=EditNews&id='+id+'&classid='+classid+'&bclassid='+bclassid+'<?=$addecmscheck?><?=$ecms_hashur['ehref']?>','','fullscreen=1')
	}
}
//简改信息
function jeditnews(id,classid,bclassid){
art.dialog.open('info/EditInfoSimple.php?enews=EditNews&id='+id+'&classid='+classid+'&bclassid='+bclassid+'<?=$addecmscheck?><?=$ecms_hashur['ehref']?>',
    {title: '简改信息',lock: true,opacity: 0.5, width: 800, height: 450,
	close: function () {
       if(art.dialog.data('issubmit')==true)
                location.reload();
    }
	});
}
//删除信息
function delnews(id,classid,bclassid){
art.dialog.open('ecmsinfo.php?enews=DelNews&id='+id+'&classid='+classid+'&bclassid='+bclassid+'<?=$addecmscheck?><?=$ecms_hashur['href']?>>',
    {title: '删除信息成功',lock: true,opacity: 0.5, width: 800, height: 320,
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
}
//查看标题图
function ckpic(id){
	var titlepic=document.getElementById('titlepic'+id).innerHTML;
art.dialog.open(titlepic,
    {title: '查看缩略图',lock: true,opacity: 0.2,width:400,height:300,button: [
        {
            name: '新窗口中查看',
            callback:function () {window.open(titlepic,'','fullscreen=1')}
        },{name: '关闭'}]
	});
}
</script>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="main.php">后台首页</a> > <?=$url?></div>
<div style=" background:none; float:right; padding-right:20px;">[<a href="AddClass.php?enews=EditClass&classid=<?=$classid?><?=$ecms_hashur['ehref']?>">栏目设置</a>] 
        [<a href="AddInfoClass.php?enews=AddInfoClass&newsclassid=<?=$classid?><?=$ecms_hashur['ehref']?>">增加采集</a>] 
        [<a href="ListInfoClass.php<?=$ecms_hashur['whehref']?>">管理采集</a>] [<a href="ecmschtml.php?enews=ReAllNewsJs&from=<?=$phpmyself?><?=$ecms_hashur['href']?>">刷新所有信息JS</a>]</div>
</div>
<div id="tab" style="margin-top:35px;">
<div class="ui-tab-bd">
<div class="ui-tab-content">
<form name="AddNewsForm" method="get">
        	<h3><span>信息列表 <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" title="已发布信息总数：<?=$classinfos?>" <?=$indexchecked==1?' class="glon"':'class="gl"'?> style=" margin-left:10px;">已发布 (<?=$classinfos?>)</a>
            <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?>&ecmscheck=1<?=$ecms_hashur['ehref']?>" <?=$indexchecked==0?' class="glon"':'class="gl"'?> title="待审核信息总数：<?=$classckinfos?>">待审核 (<?=$classckinfos?>)</a>
            <a href="ListInfoDoc.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>" class="gl">归档</a></span></h3>
</form>
            <div class="line"></div>
           	<div class="saixuan">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="searchinfo" method="GET" action="ListNews.php">
    <?=$ecms_hashur['eform']?>
    <tr> 
      <td width="35%">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> <div align="left"  class="anniuqun"> 
                <input type=button name=button value="增加信息" onClick="self.location.href='AddNews.php?enews=AddNews&ecmsnfrom=1<?=$addecmscheck?>&bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$ecms_hashur['ehref']?>'">
               <select name="dore">
                  <option value="1">刷新当前栏目</option>
                  <option value="2">刷新首页</option>
                  <option value="3">刷新父栏目</option>
                  <option value="4">刷新当前栏目与父栏目</option>
                  <option value="5">刷新父栏目与首页</option>
                  <option value="6" selected>刷新当前栏目、父栏目与首页</option>
                </select>
                <input type="button" name="Submit12" value="提交" onclick="self.location.href='ecmsinfo.php?<?=$ecms_hashur['href']?>&enews=AddInfoToReHtml<?=$addecmscheck?>&classid=<?=$classid?>&dore='+document.searchinfo.dore.value;">
              </div></td>
          </tr>
        </table>
      </td>
      <td width="65%">
<div align="right">搜索: 
          <select name="showspecial" id="showspecial">
            <option value="0"<?=$showspecial==0?' selected':''?>>不限属性</option>
			<option value="1"<?=$showspecial==1?' selected':''?>>置顶</option>
            <option value="2"<?=$showspecial==2?' selected':''?>>推荐</option>
            <option value="3"<?=$showspecial==3?' selected':''?>>头条</option>
			<option value="7"<?=$showspecial==7?' selected':''?>>投稿</option>
            <option value="5"<?=$showspecial==5?' selected':''?>>签发</option>
			<option value="8"<?=$showspecial==8?' selected':''?>>我的信息</option>
          </select>
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>" size="16">
          <select name="show">
            <option value="0" selected>不限字段</option>
            <?=$searchoptions_r['select']?>
          </select>
		  <?=$stts?>
          <select name="infolday" id="infolday">
            <option value="1"<?=$infolday==1?' selected':''?>>全部时间</option>
            <option value="86400"<?=$infolday==86400?' selected':''?>>1 天</option>
            <option value="172800"<?=$infolday==172800?' selected':''?>>2 
            天</option>
            <option value="604800"<?=$infolday==604800?' selected':''?>>一周</option>
            <option value="2592000"<?=$infolday==2592000?' selected':''?>>1 
            个月</option>
            <option value="7948800"<?=$infolday==7948800?' selected':''?>>3 
            个月</option>
            <option value="15897600"<?=$infolday==15897600?' selected':''?>>6 
            个月</option>
            <option value="31536000"<?=$infolday==31536000?' selected':''?>>1 
            年</option>
          </select>
          <input type="submit" name="Submit2" value="搜索" class="anniu">
          <input name="sear" type="hidden" id="sear" value="1">
          <input name="bclassid" type="hidden" id="bclassid" value="<?=$bclassid?>">
          <input name="classid" type="hidden" id="classid" value="<?=$classid?>">
		  <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
        </div></td>
    </tr>
  </form>
</table>      
            </div>
<form name="listform" method="post" action="ecmsinfo.php" onsubmit="return confirm('确认要执行此操作？');">
<?=$ecms_hashur['form']?>
<input type=hidden name=classid value=<?=$classid?>>
<input type=hidden name=bclassid value=<?=$bclassid?>>
<input type=hidden name=enews value=DelNews_all>
<input type=hidden name=doing value=0>
  <input name="ecmsdoc" type="hidden" id="ecmsdoc" value="0">
  <input name="docfrom" type="hidden" id="docfrom" value="<?=$phpmyself?>">
  <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
  <div class="xwlistmenu">
  	<table>
<tr> 
              <td width="60%"><font color="#ffffff"><a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?>&sear=1&showspecial=8<?=$ecms_hashur['ehref']?>">我的信息</a> 
              | <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?>&sear=1&showspecial=5<?=$ecms_hashur['ehref']?>">签发信息</a> 
              | <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?>&sear=1&showspecial=7<?=$ecms_hashur['ehref']?>">投稿信息</a> 
              | <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?>&showretitle=1&srt=1<?=$ecms_hashur['ehref']?>" title="查询重复标题，并保留一条信息">查询重复标题A</a> 
              | <a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?>&showretitle=1&srt=0<?=$ecms_hashur['ehref']?>" title="查询重复标题的信息(不保留信息)">查询重复标题B</a> 
              | <a href="ecmsinfo.php?bclassid=<?=$bclassid?>&classid=<?=$classid?><?=$addecmscheck?>&enews=SetAllCheckInfo<?=$ecms_hashur['href']?>" title="本栏目所有信息全设为审核状态" onclick="return confirm('确认要操作?');">审核栏目全部信息</a></font> 
              </td>
            <td width="40%"> <div align="right"><font color="#ffffff"><a href="openpage/AdminPage.php?leftfile=<?=urlencode('../file/FileNav.php'.$ecms_hashur['whehref'])?>&mainfile=<?=urlencode('../file/ListFile.php?type=9&classid='.$classid.$ecms_hashur['ehref'])?>&title=<?=urlencode('管理附件')?><?=$ecms_hashur['ehref']?>" target="_blank">管理附件</a> | <a href="ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>" target=_blank>更新数据</a> | <a href=../../ target=_blank>预览首页</a> | <a href="<?=$classurl?>" target=_blank>预览栏目</a></font></div></td>
          </tr>
        </table></div></td>
    </tr>
    </table>
  
<table class="comm-table2" cellspacing="0" id="changecolor">
	<tbody>
		<tr>
			<th></th>
			<th>ID</th>
			<th>标题</th>
            <th>发布者</th>
			<th>发布时间</th>
			<th>点击</th>
            <th>评论</th>
			<th>操作</th>
		</tr>
    <?php
	while($r=$empire->fetch($sql))
	{
		//状态
		$st='';
		if($r[istop])//置顶
		{
			$st.="<font color=red>[顶".$r[istop]."]</font>";
		}
		if($r[isgood])//推荐
		{
			$st.="<font color=red>[".$ignamer[$r[isgood]-1]."]</font>";
		}
		if($r[firsttitle])//头条
		{
			$st.="<font color=red>[".$ftnamer[$r[firsttitle]-1]."]</font>";
		}
		//时间
		$truetime=date("Y-m-d H:i:s",$r[truetime]);
		$lastdotime=date("Y-m-d H:i:s",$r[lastdotime]);
		$oldtitle=$r[title];
		$r[title]=stripSlashes(sub($r[title],0,50,false));
		//审核
		if(empty($indexchecked))
		{
			$checked=" title='未审核' style='background:#99C4E3'";
			$titleurl="ShowInfo.php?classid=$r[classid]&id=$r[id]".$addecmscheck.$ecms_hashur['ehref'];
		}
		else
		{
			$checked="";
			$titleurl=sys_ReturnBqTitleLink($r);
		}
		//会员投稿
		if($r[ismember])
		{
			$r[username]="<a href='member/AddMember.php?enews=EditMember&userid=".$r[userid].$ecms_hashur['ehref']."' target='_blank'><font color=red>".$r[username]."</font></a>";
		}
		//签发
		$qf="";
		if($r[isqf])
		{
			$qfr=$empire->fetch1("select checktno,tstatus from {$dbtbpre}enewswfinfo where id='$r[id]' and classid='$r[classid]' limit 1");
			if($qfr[checktno]=='100')
			{
				$qf="(<font color='red'>已通过</font>)";
			}
			elseif($qfr[checktno]=='101')
			{
				$qf="(<font color='red'>返工</font>)";
			}
			elseif($qfr[checktno]=='102')
			{
				$qf="(<font color='red'>已否决</font>)";
			}
			else
			{
				$qf="(<font color='red'>$qfr[tstatus]</font>)";
			}
			$qf="<a href='#ecms' onclick=\"window.open('workflow/DoWfInfo.php?classid=$r[classid]&id=$r[id]".$addecmscheck.$ecms_hashur['ehref']."','','width=600,height=520,scrollbars=yes');\">".$qf."</a>";
		}
		//标题图片
		$showtitlepic="";
		if($r[titlepic])
		{
			$showtitlepic="<a href='javascript:ckpic(".$r[id].")' title='预览标题图片'><img src='../data/images/showimg.gif' border=0></a><div style='display:none' id='titlepic".$r[id]."'>".$r[titlepic]."</div>";
		}
		//未生成
		$myid="<a href='ecmschtml.php?enews=ReSingleInfo&classid=$r[classid]&id[]=".$r[id].$ecms_hashur['href']."'>".$r['id']."</a>";
		if(empty($r[havehtml]))
		{
			$myid="<a href='ecmschtml.php?enews=ReSingleInfo&classid=$r[classid]&id[]=".$r[id].$ecms_hashur['href']."' title='未生成'><b>".$r[id]."</b></a>";
		}
	?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'" id=news<?=$r[id]?>> 
      <td>
      <div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>"<?=$checked?>>
		  <input name="infoid[]" type="hidden" value="<?=$r['id']?>">
        </div></td>
      <td height="32"> <div align="center"> 
          <?=$myid?>
        </div></td>
      <td height="25"> <div align="left"> 
      <? if($ecmscheck){echo "<a href='/e/ecmsshop/view/?id=".$r[id]."&classid=".$r[classid].$ecms_hashur['href']."' style='color:red;' target='_blank'>【预览未审核信息】</a>";}?>
          <?=$st?>
          <?=$showtitlepic?>
          <a href='<?=$titleurl?>' target=_blank title="<?=$oldtitle?>"> 
          <?=$r[title]?>
          </a> 
          <?=$qf?>
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[username]?>
        </div></td>
      <td height="25" title="<? echo"增加时间：".$truetime."\r\n最后修改：".$lastdotime;?>"> <div align="center"> 
		  <input name="newstime[]" type="text" value="<?=date("Y-m-d H:i:s",$r[newstime])?>" size="20">
        </div></td>
      <td height="25"> <div align="center"> <a title="下载次数:<?=$r[totaldown]?>"> 
          <?=$r[onclick]?>
          </a> </div></td>
      <td><div align="center"><a href="pl/ListPl.php?id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>" target=_blank title="管理评论"><u> 
          <?=$r[plnum]?>
          </u></a></div></td>
      <td height="25"> <div align="center"><a href="AddNews.php?enews=EditNews&id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>">修改</a> | <a href="#empirecms" onclick="window.open('info/EditInfoSimple.php?enews=EditNews&id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>','EditInfoSimple','width=600,height=360,scrollbars=yes,resizable=yes');">简改</a> | <a href="ecmsinfo.php?enews=DelNews&id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?><?=$addecmscheck?><?=$ecms_hashur['href']?>" onClick="return confirm('确认要删除？');">删除</a>
        </div></td>
    </tr>
    <?
	}
	?>
    <tr bgcolor="#FFFFFF" class="anniuqun"> 
      <td height="25"> <div align="center"> 
           <input type=checkbox name=chkall value=on onClick="CheckAll(this.form)">
        </div></td>
      <td height="25" colspan="7">
      <div class="lpad"> 
      <div class="right"><span id="moveclassnav"></span>
                <input type="submit" name="Submit5" value="移动" onClick="document.listform.enews.value='MoveNews_all';document.listform.action='ecmsinfo.php';">
                <input type="submit" name="Submit6" value="复制" onClick="document.listform.enews.value='CopyNews_all';document.listform.action='ecmsinfo.php';"></div>
          <input type="submit" name="Submit3" value="删除" onClick="document.listform.enews.value='DelNews_all';document.listform.action='ecmsinfo.php';">
		  <?php
		  if($ecmscheck)
		  {
		  ?>
		  <input type="submit" name="Submit8" value="审核" onClick="document.listform.enews.value='CheckNews_all';document.listform.action='ecmsinfo.php';">
		  <?php
		  }
		  else
		  {
		  ?>
		  <input type="submit" name="Submit9" value="取消审核" onClick="document.listform.enews.value='NoCheckNews_all';document.listform.action='ecmsinfo.php';">
		  <input type="submit" name="Submit8" value="刷新" onClick="document.listform.enews.value='ReSingleInfo';document.listform.action='ecmschtml.php';">
		  <input type="button" name="Submit112" value="推送" onClick="PushInfoToSp(this.form);">
		  <?php
		  }
		  ?>
          <select name="isgood" id="isgood">
            <option value="0">不推荐</option>
			<?=$ftnr['igname']?>
          </select>
          <input type="submit" name="Submit82" value="推荐" onClick="document.listform.enews.value='GoodInfo_all';document.listform.doing.value='0';document.listform.action='ecmsinfo.php';">
          <select name="firsttitle" id="firsttitle">
            <option value="0">取消头条</option>
            <?=$ftnr['ftname']?>
          </select>
          <input type="submit" name="Submit823" value="头条" onClick="document.listform.enews.value='GoodInfo_all';document.listform.doing.value='1';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit11" value="归档" onClick="document.listform.enews.value='InfoToDoc';document.listform.doing.value='0';document.listform.action='ecmsinfo.php';">
          <select name="istop" id="select">
            <option value="0">不置顶</option>
            <option value="1">一级置顶</option>
            <option value="2">二级置顶</option>
            <option value="3">三级置顶</option>
            <option value="4">四级置顶</option>
            <option value="5">五级置顶</option>
            <option value="6">六级置顶</option>
			<option value="7">七级置顶</option>
			<option value="8">八级置顶</option>
			<option value="9">九级置顶</option>
          </select>
          <input type="submit" name="Submit7" value="置顶" onClick="document.listform.enews.value='TopNews_all';document.listform.action='ecmsinfo.php';">
		  <input type="submit" name="Submit7" value="修改时间" onClick="document.listform.enews.value='EditMoreInfoTime';document.listform.action='ecmsinfo.php';">
		  <input type="button" name="Submit52" value="推送至专题" onClick="PushInfoToZt(this.form);">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="8" style=" height:35px; overflow:hidden; padding:0; margin:0;background:#F2F2F2; padding-top:10px;"> 
        <?=$returnpage?>
      　 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="8"><font color="#666666">备注：多选框蓝色为未审核信息；发布者红色为会员投稿；信息ID粗体为未生成,点击ID可刷新页面.</font></td>
    </tr>
	</tbody>
</table>
</form>
        </div>
        <div class="line"></div>
    </div>
</div>
</div>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="ShowClassNav.php?ecms=3<?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
<script language="javascript">
senfe("changecolor","#F2F2F2","#F7F7F7","","#bce774");
</script>
</body>
</html>