<?php
define('EmpireCMSAdmin','1');
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../class/t_functions.php");
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
CheckLevel($logininid,$loginin,$classid,"public");

//参数设置
function SetEnews($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"public");
	$add[newsurl]=ehtmlspecialchars($add[newsurl],ENT_QUOTES);
	if(empty($add[indextype])){
		$add[indextype]=".html";
	}
	if(empty($add[searchtype])){
		$add[searchtype]=".html";
	}
	//备份目录
	if(empty($add[bakdbpath])){
		$add[bakdbpath]="bdata";
	}
	if(!file_exists("ebak/".RepPathStr($add[bakdbpath])))
	{
		printerror("NotBakDbPath","");
	}
	if(empty($add[bakdbzip])){
		$add[bakdbzip]="zip";
	}
	if(!file_exists("ebak/".RepPathStr($add[bakdbzip]))){
		printerror("NotbakZipPath","");
	}
	//函数是否存在
    if(!function_exists($add['listpagefun'])||!function_exists($add['textpagefun'])||!function_exists($add['listpagelistfun']))
	{
		printerror("NotPageFun","history.go(-1)");
    }
	//adfile
	$add['adfile']=RepFilenameQz($add['adfile']);
	//修改ftp密码
	if($add[ftppassword])
	{
		$a="ftppassword='$add[ftppassword]',";
    }
	//变量处理
	$add[filesize]=(int)$add[filesize];
	$add[hotnum]=(int)$add[hotnum];
	$add[newnum]=(int)$add[newnum];
	$add[relistnum]=(int)$add[relistnum];
	$add[renewsnum]=(int)$add[renewsnum];
	$add[min_keyboard]=(int)$add[min_keyboard];
	$add[max_keyboard]=(int)$add[max_keyboard];
	$add[search_num]=(int)$add[search_num];
	$add[search_pagenum]=(int)$add[search_pagenum];
	$add[newslink]=(int)$add[newslink];
	$add[checked]=(int)$add[checked];
	$add[searchtime]=(int)$add[searchtime];
	$add[loginnum]=(int)$add[loginnum];
	$add[logintime]=(int)$add[logintime];
	$add[addnews_ok]=(int)$add[addnews_ok];
	$add[register_ok]=(int)$add[register_ok];
	$add[goodlencord]=(int)$add[goodlencord];
	$add[goodnum]=(int)$add[goodnum];
	$add[exittime]=(int)$add[exittime];
	$add[smalltextlen]=(int)$add[smalltextlen];
	$add[defaultgroupid]=(int)$add[defaultgroupid];
	$add[phpmode]=(int)$add[phpmode];
	$add[install]=(int)$add[install];
	$add[hotplnum]=(int)$add[hotplnum];
	$add[dorepnum]=(int)$add[dorepnum];
	$add[loadtempnum]=(int)$add[loadtempnum];
	$add[firstnum]=(int)$add[firstnum];
	$add[min_userlen]=(int)$add[min_userlen];
	$add[max_userlen]=(int)$add[max_userlen];
	$add[min_passlen]=(int)$add[min_passlen];
	$add[max_passlen]=(int)$add[max_passlen];
	$add[filechmod]=(int)$add[filechmod];
	$add[sametitle]=(int)$add[sametitle];
	$add[addrehtml]=(int)$add[addrehtml];
	$add[loginkey_ok]=(int)$add[loginkey_ok];
	$add[limittype]=(int)$add[limittype];
	$add[redodown]=(int)$add[redodown];
	$add[candocode]=(int)$add[candocode];
	$add[opennotcj]=(int)$add[opennotcj];
	$add[reuserpagenum]=(int)$add[reuserpagenum];
	$add[revotejsnum]=(int)$add[revotejsnum];
	$add[readjsnum]=(int)$add[readjsnum];
	$add[qaddtran]=(int)$add[qaddtran];
	$add[qaddtransize]=(int)$add[qaddtransize];
	$add[ebakthisdb]=(int)$add[ebakthisdb];
	$add[delnewsnum]=(int)$add[delnewsnum];
	$add[markpos]=(int)$add[markpos];
	$add[adminloginkey]=(int)$add[adminloginkey];
	$add[php_outtime]=(int)$add[php_outtime];
	$add[addreinfo]=(int)$add[addreinfo];
	$add[rssnum]=(int)$add[rssnum];
	$add[rsssub]=(int)$add[rsssub];
	$add[dorepdlevelnum]=(int)$add[dorepdlevelnum];
	$add[listpagelistnum]=(int)$add[listpagelistnum];
	$add[infolinknum]=(int)$add[infolinknum];
	$add[searchgroupid]=(int)$add[searchgroupid];
	$add[opencopytext]=(int)$add[opencopytext];
	$add[reuserjsnum]=(int)$add[reuserjsnum];
	$add[reuserlistnum]=(int)$add[reuserlistnum];
	$add[opentitleurl]=(int)$add[opentitleurl];
	$add['qaddtranfile']=(int)$add['qaddtranfile'];
	$add['qaddtranfilesize']=(int)$add['qaddtranfilesize'];
	$add['sendmailtype']=(int)$add['sendmailtype'];
	$add['loginemail']=(int)$add['loginemail'];
	$add['feedbacktfile']=(int)$add['feedbacktfile'];
	$add['feedbackfilesize']=(int)$add['feedbackfilesize'];
	$add['searchtempvar']=(int)$add['searchtempvar'];
	$add['showinfolevel']=(int)$add['showinfolevel'];
	$add['spicwidth']=(int)$add['spicwidth'];
	$add['spicheight']=(int)$add['spicheight'];
	$add['spickill']=(int)$add['spickill'];
	$add['jpgquality']=(int)$add['jpgquality'];
	$add['markpct']=(int)$add['markpct'];
	$add['redoview']=(int)$add['redoview'];
	$add['reggetfen']=(int)$add['reggetfen'];
	$add['regbooktime']=(int)$add['regbooktime'];
	$add['revotetime']=(int)$add['revotetime'];
	$add['fpath']=(int)$add['fpath'];
	$add['openmembertranimg']=(int)$add['openmembertranimg'];
	$add['memberimgsize']=(int)$add['memberimgsize'];
	$add['openmembertranfile']=(int)$add['openmembertranfile'];
	$add['memberfilesize']=(int)$add['memberfilesize'];
	$add['openspace']=(int)$add['openspace'];
	$add['realltime']=(int)$add['realltime'];
	$add['textpagelistnum']=(int)$add['textpagelistnum'];
	$add['memberlistlevel']=(int)$add['memberlistlevel'];
	$add['ebakcanlistdb']=(int)$add['ebakcanlistdb'];
	$add['keytog']=(int)$add['keytog'];
	$add['keytime']=(int)$add['keytime'];
	$add['regkey_ok']=(int)$add['regkey_ok'];
	$add['opengetdown']=(int)$add['opengetdown'];
	$add['gbkey_ok']=(int)$add['gbkey_ok'];
	$add['fbkey_ok']=(int)$add['fbkey_ok'];
	$add['newaddinfotime']=(int)$add['newaddinfotime'];
	$add['classnavline']=(int)$add['classnavline'];
	$add['docnewsnum']=(int)$add['docnewsnum'];
	$add['dtcanbq']=(int)$add['dtcanbq'];
	$add['dtcachetime']=(int)$add['dtcachetime'];
	$add['regretime']=(int)$add['regretime'];
	$add['regemailonly']=(int)$add['regemailonly'];
	$add['repkeynum']=(int)$add['repkeynum'];
	$add['getpasstime']=(int)$add['getpasstime'];
	$add['acttime']=(int)$add['acttime'];
	$add['regacttype']=(int)$add['regacttype'];
	$add['opengetpass']=(int)$add['opengetpass'];
	$add['hlistinfonum']=(int)$add['hlistinfonum'];
	if(empty($add['hlistinfonum']))
	{
		$add['hlistinfonum']=30;
	}
	$add['qlistinfonum']=(int)$add['qlistinfonum'];
	if(empty($add['qlistinfonum']))
	{
		$add['qlistinfonum']=30;
	}
	$add['dtncanbq']=(int)$add['dtncanbq'];
	$add['dtncachetime']=(int)$add['dtncachetime'];
	$add['readdinfotime']=(int)$add['readdinfotime'];
	$add['qeditinfotime']=(int)$add['qeditinfotime'];
	$add['ftpmode']=(int)$add['ftpmode'];
	$add['ftpssl']=(int)$add['ftpssl'];
	$add['ftppasv']=(int)$add['ftppasv'];
	$add['ftpouttime']=(int)$add['ftpouttime'];
	$add['onclicktype']=(int)$add['onclicktype'];
	$add['onclickfilesize']=(int)$add['onclickfilesize'];
	$add['onclickfiletime']=(int)$add['onclickfiletime'];
	$add['closeqdt']=(int)$add['closeqdt'];
	$add['settop']=(int)$add['settop'];
	$add['qlistinfomod']=(int)$add['qlistinfomod'];
	$add['gb_num']=(int)$add['gb_num'];
	$add['member_num']=(int)$add['member_num'];
	$add['space_num']=(int)$add['space_num'];
	$add['infolday']=(int)$add['infolday'];
	$add['filelday']=(int)$add['filelday'];
	$add['baktempnum']=(int)$add['baktempnum'];
	$add['dorepkey']=(int)$add['dorepkey'];
	$add['dorepword']=(int)$add['dorepword'];
	$add['indexpagedt']=(int)$add['indexpagedt'];
	$add['closeqdtmsg']=AddAddsData($add['closeqdtmsg']);
	$add['openfileserver']=(int)$add['openfileserver'];
	$add['fieldandtop']=(int)$add['fieldandtop'];
	$add['fieldandclosetb']=$add['fieldandclosetb']?','.$add['fieldandclosetb'].',':'';
	$add['firsttitlename']=ehtmlspecialchars(str_replace("\r\n","|",$add['firsttitlename']));
	$add['isgoodname']=ehtmlspecialchars(str_replace("\r\n","|",$add['isgoodname']));
	$add['closelisttemp']=ehtmlspecialchars($add['closelisttemp']);
	$add['ipaddinfonum']=(int)$add['ipaddinfonum'];
	$add['ipaddinfotime']=(int)$add['ipaddinfotime'];
	$add['indexaddpage']=(int)$add['indexaddpage'];
	$add['modmemberedittran']=(int)$add['modmemberedittran'];
	$add['modinfoedittran']=(int)$add['modinfoedittran'];
	//提交IP
	$doiptypes='';
	$doiptype=$add['doiptype'];
	$doiptypecount=count($doiptype);
	if($doiptypecount)
	{
		$doiptypes=',';
		for($di=0;$di<$doiptypecount;$di++)
		{
			$doiptypes.=$doiptype[$di].',';
		}
	}
	//关闭相关模块
	$closemodss='';
	$closemods=$add['closemods'];
	$closemodscount=count($closemods);
	if($closemodscount)
	{
		$closemodss=',';
		for($cmi=0;$cmi<$closemodscount;$cmi++)
		{
			$closemodss.=$closemods[$cmi].',';
		}
	}
	//关闭后台菜单
	$closehmenus='';
	$closehmenu=$add['closehmenu'];
	$closehmenucount=count($closehmenu);
	if($closehmenucount)
	{
		$closehmenus=',';
		for($chmi=0;$chmi<$closehmenucount;$chmi++)
		{
			$closehmenus.=$closehmenu[$chmi].',';
		}
	}
	//限制操作的时间点
	$timecloses='';
	$timeclose=$add['timeclose'];
	$timeclosecount=count($timeclose);
	if($timeclosecount)
	{
		$timecloses=',';
		for($tci=0;$tci<$timeclosecount;$tci++)
		{
			$timecloses.=$timeclose[$tci].',';
		}
	}
	//限制使用时间的操作
	$timeclosedos='';
	$timeclosedo=$add['timeclosedo'];
	$timeclosedocount=count($timeclosedo);
	if($timeclosedocount)
	{
		$timeclosedos=',';
		for($tcdi=0;$tcdi<$timeclosedocount;$tcdi++)
		{
			$timeclosedos.=$timeclosedo[$tcdi].',';
		}
	}

	$add[filetype]="|".$add[filetype]."|";
	$add[qimgtype]="|".$add['qaddtranimgtype']."|";
	$add[qfiletype]="|".$add['qaddtranfiletype']."|";
	$add[feedbackfiletype]="|".$add['feedbackfiletype']."|";
	$add[memberimgtype]="|".$add['memberimgtype']."|";
	$add[memberfiletype]="|".$add['memberfiletype']."|";
	$sql=$empire->query("update {$dbtbpre}enewspublic set ".$a."sitename='$add[sitename]',newsurl='$add[newsurl]',email='$add[email]',filetype='$add[filetype]',filesize=$add[filesize],hotnum=$add[hotnum],newnum=$add[newnum],relistnum=$add[relistnum],renewsnum=$add[renewsnum],min_keyboard=$add[min_keyboard],max_keyboard=$add[max_keyboard],search_num=$add[search_num],search_pagenum=$add[search_pagenum],newslink=$add[newslink],checked=$add[checked],searchtime=$add[searchtime],loginnum=$add[loginnum],logintime=$add[logintime],addnews_ok=$add[addnews_ok],register_ok=$add[register_ok],indextype='$add[indextype]',goodlencord=$add[goodlencord],goodtype='$add[goodtype]',goodnum=$add[goodnum],searchtype='$add[searchtype]',exittime=$add[exittime],smalltextlen=$add[smalltextlen],defaultgroupid=$add[defaultgroupid],fileurl='$add[fileurl]',phpmode=$add[phpmode],ftphost='$add[ftphost]',ftpport='$add[ftpport]',ftpusername='$add[ftpusername]',ftppath='$add[ftppath]',ftpmode='$add[ftpmode]',install=$add[install],hotplnum=$add[hotplnum],dorepnum=$add[dorepnum],loadtempnum=$add[loadtempnum],firstnum=$add[firstnum],bakdbpath='$add[bakdbpath]',bakdbzip='$add[bakdbzip]',downpass='$add[downpass]',min_userlen=$add[min_userlen],max_userlen=$add[max_userlen],min_passlen=$add[min_passlen],max_passlen=$add[max_passlen],filechmod=$add[filechmod],loginkey_ok=$add[loginkey_ok],limittype=$add[limittype],redodown=$add[redodown],candocode=$add[candocode],opennotcj=$add[opennotcj],reuserpagenum=$add[reuserpagenum],revotejsnum=$add[revotejsnum],readjsnum=$add[readjsnum],qaddtran=$add[qaddtran],qaddtransize=$add[qaddtransize],ebakthisdb=$add[ebakthisdb],delnewsnum=$add[delnewsnum],markpos=$add[markpos],markimg='$add[markimg]',marktext='$add[marktext]',markfontsize='$add[markfontsize]',markfontcolor='$add[markfontcolor]',markfont='$add[markfont]',adminloginkey=$add[adminloginkey],php_outtime=$add[php_outtime],listpagefun='$add[listpagefun]',textpagefun='$add[textpagefun]',adfile='$add[adfile]',notsaveurl='$add[notsaveurl]',rssnum=$add[rssnum],rsssub=$add[rsssub],dorepdlevelnum=$add[dorepdlevelnum],listpagelistfun='$add[listpagelistfun]',listpagelistnum=$add[listpagelistnum],infolinknum=$add[infolinknum],searchgroupid=$add[searchgroupid],opencopytext=$add[opencopytext],reuserjsnum=$add[reuserjsnum],reuserlistnum=$add[reuserlistnum],opentitleurl='$add[opentitleurl]',qaddtranimgtype='$add[qimgtype]',qaddtranfile=$add[qaddtranfile],qaddtranfilesize=$add[qaddtranfilesize],qaddtranfiletype='$add[qfiletype]',sendmailtype=$add[sendmailtype],smtphost='$add[smtphost]',fromemail='$add[fromemail]',loginemail=$add[loginemail],emailusername='$add[emailusername]',emailpassword='$add[emailpassword]',smtpport='$add[smtpport]',emailname='$add[emailname]',feedbacktfile=$add[feedbacktfile],feedbackfilesize=$add[feedbackfilesize],feedbackfiletype='$add[feedbackfiletype]',searchtempvar=$add[searchtempvar],showinfolevel=$add[showinfolevel],navfh='".eaddslashes($add[navfh])."',spicwidth=$add[spicwidth],spicheight=$add[spicheight],spickill=$add[spickill],jpgquality=$add[jpgquality],markpct=$add[markpct],redoview=$add[redoview],reggetfen=$add[reggetfen],regbooktime=$add[regbooktime],revotetime=$add[revotetime],fpath=$add[fpath],filepath='$add[filepath]',openmembertranimg=$add[openmembertranimg],memberimgsize=$add[memberimgsize],openmembertranfile=$add[openmembertranfile],memberfilesize=$add[memberfilesize],memberimgtype='$add[memberimgtype]',memberfiletype='$add[memberfiletype]',canposturl='$add[canposturl]',openspace='$add[openspace]',realltime=$add[realltime],closeip='$add[closeip]',openip='$add[openip]',hopenip='$add[hopenip]',closewords='$add[closewords]',closewordsf='$add[closewordsf]',textpagelistnum=$add[textpagelistnum],memberlistlevel=$add[memberlistlevel],ebakcanlistdb=$add[ebakcanlistdb],keytog='$add[keytog]',keyrnd='$add[keyrnd]',keytime='$add[keytime]',regkey_ok='$add[regkey_ok]',opengetdown='$add[opengetdown]',gbkey_ok='$add[gbkey_ok]',fbkey_ok='$add[fbkey_ok]',newaddinfotime='$add[newaddinfotime]',classnavline='$add[classnavline]',classnavfh='".eaddslashes($add[classnavfh])."',sitekey='$add[sitekey]',siteintro='$add[siteintro]',docnewsnum='$add[docnewsnum]',dtcanbq='$add[dtcanbq]',dtcachetime='$add[dtcachetime]',regretime='$add[regretime]',regclosewords='$add[regclosewords]',regemailonly='$add[regemailonly]',repkeynum='$add[repkeynum]',getpasstime='$add[getpasstime]',acttime='$add[acttime]',regacttype='$add[regacttype]',acttext='".eaddslashes($add[acttext])."',getpasstext='".eaddslashes($add[getpasstext])."',acttitle='".eaddslashes($add[acttitle])."',getpasstitle='".eaddslashes($add[getpasstitle])."',opengetpass='$add[opengetpass]',hlistinfonum='$add[hlistinfonum]',qlistinfonum='$add[qlistinfonum]',dtncanbq='$add[dtncanbq]',dtncachetime='$add[dtncachetime]',readdinfotime='$add[readdinfotime]',qeditinfotime='$add[qeditinfotime]',ftpssl='$add[ftpssl]',ftppasv='$add[ftppasv]',ftpouttime='$add[ftpouttime]',onclicktype='$add[onclicktype]',onclickfilesize='$add[onclickfilesize]',onclickfiletime='$add[onclickfiletime]',closeqdt='$add[closeqdt]',settop='$add[settop]',qlistinfomod='$add[qlistinfomod]',gb_num='$add[gb_num]',member_num='$add[member_num]',space_num='$add[space_num]',opendoip='$add[opendoip]',closedoip='$add[closedoip]',doiptype='$doiptypes',infolday='$add[infolday]',filelday='$add[filelday]',baktempnum='$add[baktempnum]',dorepkey='$add[dorepkey]',dorepword='$add[dorepword]',onclickrnd='$add[onclickrnd]',indexpagedt='$add[indexpagedt]',keybgcolor='$add[keybgcolor]',keyfontcolor='$add[keyfontcolor]',keydistcolor='$add[keydistcolor]',closeqdtmsg='$add[closeqdtmsg]',openfileserver='$add[openfileserver]',closemods='$closemodss',fieldandtop='$add[fieldandtop]',fieldandclosetb='$add[fieldandclosetb]',firsttitlename='".eaddslashes($add[firsttitlename])."',isgoodname='".eaddslashes($add[isgoodname])."',closelisttemp='".eaddslashes($add[closelisttemp])."',chclasscolor='".eaddslashes($add[chclasscolor])."',timeclose='".eaddslashes($timecloses)."',timeclosedo='".eaddslashes($timeclosedos)."',ipaddinfonum='$add[ipaddinfonum]',ipaddinfotime='$add[ipaddinfotime]',closehmenu='$closehmenus',indexaddpage='$add[indexaddpage]',modmemberedittran='$add[modmemberedittran]',modinfoedittran='$add[modinfoedittran]';");
	DoSetFileServer($add);//远程附件更新
	GetConfig();
	//首页动态文件
	if($add['indexpagedt']!=$add['oldindexpagedt'])
	{
		if($add['indexpagedt'])
		{
			DelFiletext(ECMS_PATH.'index'.$add[indextype]);
			@copy(ECMS_PATH.'e/data/template/dtindexpage.txt',ECMS_PATH.'index.php');
		}
		else
		{
			DelFiletext(ECMS_PATH.'index.php');
			$indextemp=GetIndextemp();
			NewsBq(0,$indextemp,1,0);
		}
	}
	if($sql){
		insert_dolog("");//操作日志
		printerror("SetPublicSuccess","SetEnews.php".hReturnEcmsHashStrHref2(1));
	}
	else{
		printerror("DbError","history.go(-1)");
	}
}

//远程附件更新
function DoSetFileServer($add){
	global $empire,$dbtbpre;
	$update='';
	if($add['fs_ftppassword'])
	{
		$update=",ftppassword='$add[fs_ftppassword]'";
	}
	$add['fs_ftpmode']=(int)$add['fs_ftpmode'];
	$add['fs_ftpssl']=(int)$add['fs_ftpssl'];
	$add['fs_ftppasv']=(int)$add['fs_ftppasv'];
	$add['fs_ftpouttime']=(int)$add['fs_ftpouttime'];
	$sql=$empire->query("update {$dbtbpre}enewspostserver set purl='$add[fs_purl]',ftphost='$add[fs_ftphost]',ftpport='$add[fs_ftpport]',ftpusername='$add[fs_ftpusername]',ftppath='$add[fs_ftppath]',ftpmode='$add[fs_ftpmode]',ftpssl='$add[fs_ftpssl]',ftppasv='$add[fs_ftppasv]',ftpouttime='$add[fs_ftpouttime]'".$update." where pid='1'");
}

//测试远程附件FTP
function CheckFileServerFtp($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"public");
	$ftphost=$add[fs_ftphost];
	$ftpport=$add[fs_ftpport];
	$ftpusername=$add[fs_ftpusername];
	if($add[fs_ftppassword])
	{
		$ftppassword=$add[fs_ftppassword];
	}
	else
	{
		$fsr=$empire->fetch1("select ftppassword from {$dbtbpre}enewspostserver where pid='1' limit 1");
		$ftppassword=$fsr[ftppassword];
	}
	$ftppath=$add[fs_ftppath];
	$tranmode=(int)$add['fs_ftpmode'];
	$ftpssl=(int)$add['fs_ftpssl'];
	$pasv=(int)$add['fs_ftppasv'];
	$timeout=(int)$add['fs_ftpouttime'];
	CheckFtpConnect($ftphost,$ftpport,$ftpusername,$ftppassword,$ftppath,$ftpssl,$pasv,$tranmode,$timeout);
}

//测试远程发布FTP
function CheckPostServerFtp($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"public");
	$ftphost=$add[ftphost];
	$ftpport=$add[ftpport];
	$ftpusername=$add[ftpusername];
	if($add[ftppassword])
	{
		$ftppassword=$add[ftppassword];
	}
	else
	{
		$fsr=$empire->fetch1("select ftppassword from {$dbtbpre}enewspublic limit 1");
		$ftppassword=$fsr[ftppassword];
	}
	$ftppath=$add[ftppath];
	$tranmode=(int)$add['ftpmode'];
	$ftpssl=(int)$add['ftpssl'];
	$pasv=(int)$add['ftppasv'];
	$timeout=(int)$add['ftpouttime'];
	CheckFtpConnect($ftphost,$ftpport,$ftpusername,$ftppassword,$ftppath,$ftpssl,$pasv,$tranmode,$timeout);
}

$enews=$_POST['enews'];
if(empty($enews))
{
	$enews=$_GET['enews'];
}
if($enews)
{
	hCheckEcmsRHash();
	include LoadLang("pub/fun.php");
	include("../data/dbcache/class.php");
	include("../data/dbcache/MemberLevel.php");
}
if($enews=="SetEnews")//参数设置
{
	SetEnews($_POST,$logininid,$loginin);
}
elseif($enews=='CheckFileServerFtp')//测试附件FTP
{
	CheckFileServerFtp($_POST,$logininid,$loginin);
}
elseif($enews=='CheckPostServerFtp')//测试远程发布FTP
{
	CheckPostServerFtp($_POST,$logininid,$loginin);
}

$r=$empire->fetch1("select * from {$dbtbpre}enewspublic limit 1");
//文件类别
$filetype=substr($r[filetype],1,strlen($r[filetype]));
$filetype=substr($filetype,0,strlen($filetype)-1);
//投稿图片扩展名
$qaddimgtype=substr($r[qaddtranimgtype],1,strlen($r[qaddtranimgtype]));
$qaddimgtype=substr($qaddimgtype,0,strlen($qaddimgtype)-1);
//投稿附件扩展名
$qaddfiletype=substr($r[qaddtranfiletype],1,strlen($r[qaddtranfiletype]));
$qaddfiletype=substr($qaddfiletype,0,strlen($qaddfiletype)-1);
//反馈附件
$feedbackfiletype=substr($r[feedbackfiletype],1,strlen($r[feedbackfiletype])-2);
//会员表单
$memberimgtype=substr($r[memberimgtype],1,strlen($r[memberimgtype]));
$memberimgtype=substr($memberimgtype,0,strlen($memberimgtype)-1);
$memberfiletype=substr($r[memberfiletype],1,strlen($r[memberfiletype]));
$memberfiletype=substr($memberfiletype,0,strlen($memberfiletype)-1);
//----------会员组
$sql1=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($l_r=$empire->fetch($sql1))
{
	if($r[defaultgroupid]==$l_r[groupid])
	{$select=" selected";}
	else
	{$select="";}
	//搜索会员组
	if($r[searchgroupid]==$l_r[groupid])
	{$s_select=" selected";}
	else
	{$s_select="";}
	//查看资料权限
	if($r[showinfolevel]==$l_r[groupid])
	{$showinfo_select=" selected";}
	else
	{$showinfo_select="";}
	//会员列表查看权限
	if($r[memberlistlevel]==$l_r[groupid])
	{$memberlist_select=" selected";}
	else
	{$memberlist_select="";}
	$membergroup.="<option value=".$l_r[groupid].$select.">".$l_r[groupname]."</option>";
	$searchmembergroup.="<option value=".$l_r[groupid].$s_select.">".$l_r[groupname]."</option>";
	$showinfolevel.="<option value=".$l_r[groupid].$showinfo_select.">".$l_r[groupname]."</option>";
	$memberlistlevel.="<option value=".$l_r[groupid].$memberlist_select.">".$l_r[groupname]."</option>";
}
//远程附件
if($r['openfileserver']==1)
{
	$hiddenfileserver="<script>document.getElementById('setfileserver').style.display='';</script>";
}
else
{
	$hiddenfileserver="<script>document.getElementById('setfileserver').style.display='none';</script>";
}
$fsr=$empire->fetch1("select * from {$dbtbpre}enewspostserver where pid='1' limit 1");
//当前使用的模板组
$thegid=GetDoTempGid();
db_close();
$empire=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>参数设置</title>
<link rel="stylesheet" type="text/css" href="adminstyle/1/yecha/yecha.css" />
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/jquery.min.js"></SCRIPT>
<SCRIPT type="text/javascript" src="adminstyle/<?=$loginadminstyleid?>/js/ui.tab.js"></SCRIPT>
<script type="text/javascript">
		function foreColor(objf)
		{
		  if (!Error())	return;
		  var arr = showModalDialog("ecmseditor/fieldfile/selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
		  if (arr != null) objf.value=arr;
		  else objf.focus();
		}

</script>
</head>
<body>
<div class="container">
<div class="nynav"><div>当前位置： <a href="../main.php">后台首页</a> > <a href="SetEnews.php<?=$ecms_hashur['ehref']?>">参数设置</a></div></div>
<div class="kongbai"></div>
    <form name="form1" method="post" action="SetEnews.php">
<?=$ecms_hashur['form']?>
    <input type=hidden name=enews value=SetEnews>
    <div id="tab"  style="padding-bottom:50px;_margin-bottom:100px;">
	<div class="ui-tab-container">
	<ul class="clearfix ui-tab-list">
		<li class="ui-tab-active">基本属性</li>
		<li>用户设置</li>
		<li>文件设置</li>
        <li>JS设置</li>
        <li>分组生成</li>
        <li>搜索设置</li>
        <li>信息设置</li>
        <li>FTP/Email</li>
        <li>模型设置</li>
        <li>图片设置</li>
	</ul>
	<div class="ui-tab-bd">
		<div class="ui-tab-content">
			<h3><span>基本信息设置</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>站点名称</label><input name="sitename" type="text" id="sitename" value="<?=$r[sitename]?>" size="20"><font color="#666666">&nbsp;</font></li>
                <li class="jqui"><label>网站地址</label><input name="newsurl" type="text" id="newsurl4" value="<?=$r[newsurl]?>" size="20"><font color="#666666">(结尾需加"/"，如：/)</font></li>
                <li class="jqui"><label>附件地址</label><input name="fileurl" type="text" id="fileurl" value="<?=$r[fileurl]?>" size="20"><font color="#666666">&nbsp;</font></li>
                <li class="jqui"><label>管理员邮箱</label><input name="email" type="text" id="email" value="<?=$r[email]?>" size="20"><font color="#666666">&nbsp;</font></li>
                <li class="jqui"><label>网站关键字</label><input name="sitekey" type="text" id="sitekey" value="<?=$r[sitekey]?>" size="38"><font color="#666666">&nbsp;</font></li>
                <li class="jqui"><label>网站简介</label><textarea name="siteintro" cols="60" rows="5" id="siteintro"><?=$r[siteintro]?></textarea><font color="#666666">&nbsp;</font></li>
                <li class="jqui"><label>首页文件扩展名</label><input name="indextype" type="text" id="indextype" value="<?=$r[indextype]?>" size="20"><select name="select" onChange="document.form1.indextype.value=this.value">
              <option value=".html">扩展名</option>
              <option value=".html">.html</option>
              <option value=".htm">.htm</option>
              <option value=".php">.php</option>
              <option value=".shtml">.shtml</option>
            </select>
            <input name="oldindextype" type="hidden" id="oldindextype" value="<?=$r[indextype]?>">
            <font color="#666666">(如：.html,.htm,.xml,.php)</font></li>
                <li class="jqui"><label>首页模式</label><input type="radio" name="indexpagedt" value="0"<?=$r['indexpagedt']==0?' checked':''?>>
            静态首页
            <input type="radio" name="indexpagedt" value="1"<?=$r['indexpagedt']==1?' checked':''?>>
            动态首页 
            <input name="oldindexpagedt" type="hidden" id="oldindexpagedt" value="<?=$r[indexpagedt]?>"></li>
                <li class="jqui"><label>PHP超时时间设置</label><input name="php_outtime" type="text" id="php_outtime" value="<?=$r[php_outtime]?>" size="10">
            <font color="#666666">秒 (一般不需要设置)</font></li>
                <li class="jqui"><label>关闭前台所有动态页面</label><input type="radio" name="closeqdt" value="1"<?=$r[closeqdt]==1?' checked':''?>>
            是
            <input type="radio" name="closeqdt" value="0"<?=$r[closeqdt]==0?' checked':''?>>
            否<font color="#666666">(如果开启，前台所有动态文件都无法使用，但性能和安全性最高)</font></li>
                <li><label>关闭动态页面提示内容</label><textarea name="closeqdtmsg" cols="60" rows="5" id="closeqdtmsg"><?=ehtmlspecialchars($r[closeqdtmsg])?></textarea></li>
                <li class="jqui"><label>关闭前台模块相关功能</label><input name="closemods[]" type="checkbox" id="closemods[]" value="down"<?=strstr($r['closemods'],',down,')?' checked':''?>>
            下载 
            <input name="closemods[]" type="checkbox" id="closemods[]" value="movie"<?=strstr($r['closemods'],',movie,')?' checked':''?>>
            电影 
            <input name="closemods[]" type="checkbox" id="closemods[]" value="shop"<?=strstr($r['closemods'],',shop,')?' checked':''?>>
            商城 
            <input name="closemods[]" type="checkbox" id="closemods[]" value="pay"<?=strstr($r['closemods'],',pay,')?' checked':''?>>
            在线支付 
            <input name="closemods[]" type="checkbox" id="closemods[]" value="rss"<?=strstr($r['closemods'],',rss,')?' checked':''?>>
            RSS
            <input name="closemods[]" type="checkbox" id="closemods[]" value="search"<?=strstr($r['closemods'],',search,')?' checked':''?>>
            搜索
			<input name="closemods[]" type="checkbox" id="closemods[]" value="sch"<?=strstr($r['closemods'],',sch,')?' checked':''?>>
            全站搜索
            <input name="closemods[]" type="checkbox" id="closemods[]" value="member"<?=strstr($r['closemods'],',member,')?' checked':''?>>
            会员
			<input name="closemods[]" type="checkbox" id="closemods[]" value="pl"<?=strstr($r['closemods'],',pl,')?' checked':''?>>
            评论
			<input name="closemods[]" type="checkbox" id="closemods[]" value="print"<?=strstr($r['closemods'],',print,')?' checked':''?>>
            打印
            <input name="closemods[]" type="checkbox" id="closemods[]" value="mconnect"<?=strstr($r['closemods'],',mconnect,')?' checked':''?>>
外部登录
<input name="closemods[]" type="checkbox" id="closemods[]" value="fieldand"<?=strstr($r['closemods'],',fieldand,')?' checked':''?>>
            结合项</li>
                <li class="jqui"><label>不开启操作的时间点</label>
                <table><tr><td>
              <input name="timeclose[]" type="checkbox" value="0"<?=strstr($r['timeclose'],',0,')?' checked':''?>>
0点
              <input name="timeclose[]" type="checkbox" value="1"<?=strstr($r['timeclose'],',1,')?' checked':''?>>
1点
              <input name="timeclose[]" type="checkbox" value="2"<?=strstr($r['timeclose'],',2,')?' checked':''?>>
2点
              <input name="timeclose[]" type="checkbox" value="3"<?=strstr($r['timeclose'],',3,')?' checked':''?>>
3点
              <input name="timeclose[]" type="checkbox" value="4"<?=strstr($r['timeclose'],',4,')?' checked':''?>>
4点
              <input name="timeclose[]" type="checkbox" value="5"<?=strstr($r['timeclose'],',5,')?' checked':''?>>
5点
              <input name="timeclose[]" type="checkbox" value="6"<?=strstr($r['timeclose'],',6,')?' checked':''?>>
6点
              <input name="timeclose[]" type="checkbox" value="7"<?=strstr($r['timeclose'],',7,')?' checked':''?>>
7点
              <input name="timeclose[]" type="checkbox" value="8"<?=strstr($r['timeclose'],',8,')?' checked':''?>>
8点
              <input name="timeclose[]" type="checkbox" value="9"<?=strstr($r['timeclose'],',9,')?' checked':''?>>
9点
              <input name="timeclose[]" type="checkbox" value="10"<?=strstr($r['timeclose'],',10,')?' checked':''?>>
10点
              <input name="timeclose[]" type="checkbox" value="11"<?=strstr($r['timeclose'],',11,')?' checked':''?>>
11点
              <input name="timeclose[]" type="checkbox" value="12"<?=strstr($r['timeclose'],',12,')?' checked':''?>>
12点
</td></tr><tr><td>
              <input name="timeclose[]" type="checkbox" value="13"<?=strstr($r['timeclose'],',13,')?' checked':''?>>
13点
              <input name="timeclose[]" type="checkbox" value="14"<?=strstr($r['timeclose'],',14,')?' checked':''?>>
14点
              <input name="timeclose[]" type="checkbox" value="15"<?=strstr($r['timeclose'],',15,')?' checked':''?>>
15点
              <input name="timeclose[]" type="checkbox" value="16"<?=strstr($r['timeclose'],',16,')?' checked':''?>>
16点
              <input name="timeclose[]" type="checkbox" value="17"<?=strstr($r['timeclose'],',17,')?' checked':''?>>
17点
              <input name="timeclose[]" type="checkbox" value="18"<?=strstr($r['timeclose'],',18,')?' checked':''?>>
18点
              <input name="timeclose[]" type="checkbox" value="19"<?=strstr($r['timeclose'],',19,')?' checked':''?>>
19点
              <input name="timeclose[]" type="checkbox" value="20"<?=strstr($r['timeclose'],',20,')?' checked':''?>>
20点
              <input name="timeclose[]" type="checkbox" value="21"<?=strstr($r['timeclose'],',21,')?' checked':''?>>
21点
              <input name="timeclose[]" type="checkbox" value="22"<?=strstr($r['timeclose'],',22,')?' checked':''?>>
22点
              <input name="timeclose[]" type="checkbox" value="23"<?=strstr($r['timeclose'],',23,')?' checked':''?>>
23点
</td></tr></table></li>
                <li class="jqui"><label>限定操作时间的操作</label><input name="timeclosedo[]" type="checkbox" id="timeclosedo[]" value="reg"<?=strstr($r['timeclosedo'],',reg,')?' checked':''?>>
          注册会员
            <input name="timeclosedo[]" type="checkbox" id="timeclosedo[]" value="info"<?=strstr($r['timeclosedo'],',info,')?' checked':''?>>
投稿
<input name="timeclosedo[]" type="checkbox" id="timeclosedo[]" value="pl"<?=strstr($r['timeclosedo'],',pl,')?' checked':''?>>
评论
<input name="timeclosedo[]" type="checkbox" id="timeclosedo[]" value="gbook"<?=strstr($r['timeclosedo'],',gbook,')?' checked':''?>>
留言板</li>
                <li><label>远程保存忽略地址<br /><font color="#666666">(一行为一个地址)</font></label><textarea name="notsaveurl" cols="60" rows="8" id="notsaveurl"><?=$r[notsaveurl]?></textarea></li>
                <li><label>前台允许提交的来源地址<br>
            <font color="#666666">(一行为一个地址)</font></label><textarea name="canposturl" cols="60" rows="8" id="canposturl"><?=$r[canposturl]?></textarea></li>
                <li><label>验证码字符组成</label><select name="keytog" id="keytog">
              <option value="0"<?=$r[keytog]==0?' selected':''?>>数字</option>
              <option value="1"<?=$r[keytog]==1?' selected':''?>>字母</option>
              <option value="2"<?=$r[keytog]==2?' selected':''?>>数字+字母</option>
            </select></li>
                <li class="jqui"><label>验证码过期时间</label><input name="keytime" type="text" id="keytime" value="<?=$r[keytime]?>" size="10">
            分钟 </li>
                <li class="jqui"><label>验证码加密字符串</label><input name="keyrnd" type="text" id="keyrnd" value="<?=$r[keyrnd]?>" size="38"> 
            <font color="#666666">(10~60个任意字符，最好多种字符组合)</font></li>
                <li class="jqui"><label>验证码配色</label>背景颜色： 
            <input name="keybgcolor" type="text" id="keybgcolor" value="<?=$r[keybgcolor]?>" size="5"> 
            <a onClick="foreColor(document.form1.keybgcolor);"><img src="../data/images/color.gif" width="21" height="21" align="absbottom"></a>文字颜色：
            <input name="keyfontcolor" type="text" id="keyfontcolor" value="<?=$r[keyfontcolor]?>" size="5"> 
            <a onClick="foreColor(document.form1.keyfontcolor);"><img src="../data/images/color.gif" width="21" height="21" align="absbottom"></a> 干扰颜色：
            <input name="keydistcolor" type="text" id="keydistcolor" value="<?=$r[keydistcolor]?>" size="5"> 
            <a onClick="foreColor(document.form1.keydistcolor);"><img src="../data/images/color.gif" width="21" height="21" align="absbottom"></a></li>
             <div class="clear"></div>
            </ul>
            
        </div>
		<div class="ui-tab-content" style="display:none">
        	<h3><span>后台设置</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>后台登陆验证码</label><input type="radio" name="adminloginkey" value="0"<?=$r[adminloginkey]==0?' checked':''?>>
            开启 
            <input type="radio" name="adminloginkey" value="1"<?=$r[adminloginkey]==1?' checked':''?>>
            关闭</li>
            <li class="jqui"><label>后台登录次数限制</label><input name="loginnum" type="text" id="loginnum" value="<?=$r[loginnum]?>" size="10">次</li>
            <li class="jqui"><label>重新登录时间间隔</label><input name="logintime" type="text" id="logintime" value="<?=$r[logintime]?>" size="10">分钟</li>
            <li class="jqui"><label>登录超时限制</label><input name="exittime" type="text" id="exittime" value="<?=$r[exittime]?>" size="10">分钟</li>
            </ul>      
            <h3><span>前台设置</span></h3>
            <ul>
            <li class="jqui"><label>会员注册</label> 
              <input type="radio" name="register_ok" value="0"<?=$r[register_ok]==0?' checked':''?>>
              开启 
              <input type="radio" name="register_ok" value="1"<?=$r[register_ok]==1?' checked':''?>>
              关闭</li>
              
            <li><label>注册会员默认会员组</label><select name="defaultgroupid">
              <?=$membergroup?>
            </select>
            </li>
            <li class="jqui"><label>注册赠送点数</label><input name="reggetfen" type="text" id="reggetfen" value="<?=$r[reggetfen]?>" size="10"></li>
            <li class="jqui"><label>注册用户名限制</label><input name="min_userlen" type="text" id="min_userlen" value="<?=$r[min_userlen]?>" size="6">
            ~ 
            <input name="max_userlen" type="text" id="max_userlen" value="<?=$r[max_userlen]?>" size="6">
            个字节</li>
            <li class="jqui"><label>注册密码限制</label><input name="min_passlen" type="text" id="min_passlen" value="<?=$r[min_passlen]?>" size="6">
            ~ 
            <input name="max_passlen" type="text" id="max_passlen" value="<?=$r[max_passlen]?>" size="6">
            个字节</li>
            <li class="jqui"><label>会员邮箱唯一性检查:</label><input name="regemailonly" type="radio" value="1"<?=$r[regemailonly]==1?' checked':''?>>
            开启 
            <input name="regemailonly" type="radio" value="0"<?=$r[regemailonly]==0?' checked':''?>>
            关闭</li>
            <li class="jqui"><label>同一IP注册间隔限制:</label><input name="regretime" type="text" id="regretime" value="<?=$r[regretime]?>" size="10">
            个小时</li>
            <li class="jqui"><label>用户名保留关键字:</label><input name="regclosewords" type="text" id="repnum3" value="<?=$r[regclosewords]?>" size="38">
            <font color="#666666">(禁止包含字符,多个用&quot;|&quot;号隔开,支持多字验证)</font></li>
            <li class="jqui"><label>投稿功能</label><input type="radio" name="addnews_ok" value="0"<?=$r[addnews_ok]==0?' checked':''?>>
            开启 
            <input type="radio" name="addnews_ok" value="1"<?=$r[addnews_ok]==1?' checked':''?>>
            关闭</li>
            <li class="jqui"><label>会员空间</label><input type="radio" name="openspace" value="0"<?=$r[openspace]==0?' checked':''?>>
            开启 
            <input type="radio" name="openspace" value="1"<?=$r[openspace]==1?' checked':''?>>
            关闭</li>
            <li class="jqui"><label>会员登陆验证码</label><input type="radio" name="loginkey_ok" value="1"<?=$r[loginkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="loginkey_ok" value="0"<?=$r[loginkey_ok]==0?' checked':''?>>
            关闭</li>
            <li class="jqui"><label>会员注册验证码</label><input type="radio" name="regkey_ok" value="1"<?=$r[regkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="regkey_ok" value="0"<?=$r[regkey_ok]==0?' checked':''?>>
            关闭</li>
            
            <li><label>会员列表查看权限</label><select name="memberlistlevel" id="memberlistlevel">
              <option value=0>游客</option>
              <?=$memberlistlevel?>
            </select>
            </li>
            <li><label>查看会员资料权限</label><select name="showinfolevel" id="showinfolevel">
              <option value=0>游客</option>
              <?=$showinfolevel?>
            </select></li>
            <li class="jqui"><label>会员列表每页显示</label><input name="member_num" type="text" id="member_num" value="<?=$r[member_num]?>" size="10">
            个会员</li>
            <li class="jqui"><label>会员空间信息每页显示</label><input name="space_num" type="text" id="space_num" value="<?=$r[space_num]?>" size="10">
            个信息</li>
            <li class="jqui"><label>会员注册审核方式</label><input name="regacttype" type="radio" value="0"<?=$r[regacttype]==0?' checked':''?>>
            无 
            <input name="regacttype" type="radio" value="1"<?=$r[regacttype]==1?' checked':''?>>
            邮件激活</li>
            <li class="jqui"><label>激活帐号链接有效期</label><input name="acttime" type="text" id="acttime" value="<?=$r[acttime]?>" size="10">
            小时</li>
            
            <li><label>帐号激活邮件内容<br> <font color="#666666">[!--pageurl--]:激活地址 
            <br>
            [!--username--]:用户名<br>
            [!--email--]:邮箱地址<br>
            [!--date--]:发送时间<br>
            [!--sitename--]:网站名称<br>
            [!--news.url--]:网站地址</font></label><table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td>标题： 
                  <input name="acttitle" type="text" id="acttitle" value="<?=stripSlashes($r[acttitle])?>" size="38" class="text"></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">内容：<textarea name="acttext" cols="60" rows="12" id="acttext"><?=ehtmlspecialchars(stripSlashes($r[acttext]))?></textarea></td>
              </tr>
            </table></li>
            <li class="jqui"><label>开启取回密码功能</label><input type="radio" name="opengetpass" value="1"<?=$r[opengetpass]==1?' checked':''?>>
            开启 
            <input type="radio" name="opengetpass" value="0"<?=$r[opengetpass]==0?' checked':''?>>
            关闭</li>
            <li class="jqui"><label>取回密码链接有效期</label><input name="getpasstime" type="text" id="getpasstime" value="<?=$r[getpasstime]?>" size="10">
            小时</li>
            
            <li><label>取回密码邮件内容<br><font color="#666666">[!--pageurl--]:取回地址 
            <br>
            [!--username--]:用户名<br>
            [!--email--]:邮箱地址<br>
            [!--date--]:发送时间<br>
            [!--sitename--]:网站名称<br>
            [!--news.url--]:网站地址 </font></label><table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td>标题： 
                  <input name="getpasstitle" type="text" id="getpasstitle" value="<?=stripSlashes($r[getpasstitle])?>" size="38"></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">内容：<textarea name="getpasstext" cols="60" rows="12" id="textarea"><?=ehtmlspecialchars(stripSlashes($r[getpasstext]))?></textarea></td>
              </tr>
            </table></li>
            </ul>
            <h3><span>访问控制设置</span></h3>
            <ul>
            <li style="position:relative;"><label>禁止 
            IP 访问列表:<br /><font color="#666666">(前台及后台有效)</font></label><textarea name="closeip" cols="60" rows="8" id="closeip"><?=$r[closeip]?></textarea>
            <div class="tips ipfl"><span class="tip-arrow tip-arrow-left"><em>◆</em><b>◆</b></span>
    每个 IP 一行，既可输入完整地址，也可只输入 IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 192.168.0.0～192.168.255.255 
            范围内的所有地址，留空为不设置</div>
            </li>
            <li style="position:relative;"><label>允许 IP 访问列表:<br /><font color="#666666">(前台及后台有效)</font>
          </label><textarea name="openip" cols="60" rows="8" id="textarea2"><?=$r[openip]?></textarea>
          <div class="tips ipfl"><span class="tip-arrow tip-arrow-left"><em>◆</em><b>◆</b></span>
    只有当用户处于本列表中的 IP 地址时才可以访问网站，列表以外的地址访问将视为 IP 被禁止.每个 IP 一行，既可输入完整地址，也可只输入 
            IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 192.168.0.0～192.168.255.255 
            范围内的所有地址，留空为所有 IP 除明确禁止的以外均可访问</div></li>
            <li style="position:relative;"><label>允许后台 IP 访问列表:<br /><font color="#666666">(后台有效)</font>
          </label><textarea name="hopenip" cols="60" rows="8" id="textarea3"><?=$r[hopenip]?></textarea>
          <div class="tips ipfl"><span class="tip-arrow tip-arrow-left"><em>◆</em><b>◆</b></span>
    只有当管理员处于本列表中的 IP 地址时才可以访问后台，列表以外的地址访问将视为 IP 被禁止.每个 IP 一行，既可输入完整地址，也可只输入 
            IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 192.168.0.0～192.168.255.255 
            范围内的所有地址，留空为所有 IP 除明确禁止的以外均可访问</div></li>
            </ul>
            <h3><span>提交控制设置</span></h3>
            <ul>
            <li class="jqui"><label>控制动作</label>
<input name="doiptype[]" type="checkbox" id="doiptype[]" value="register"<?=strstr($r['doiptype'],',register,')?' checked':''?>>
            注册 
            <input name="doiptype[]" type="checkbox" id="doiptype[]" value="pl"<?=strstr($r['doiptype'],',pl,')?' checked':''?>>
            评论 
            <input name="doiptype[]" type="checkbox" id="doiptype[]" value="postinfo"<?=strstr($r['doiptype'],',postinfo,')?' checked':''?>>
            投稿 
            <input name="doiptype[]" type="checkbox" id="doiptype[]" value="gbook"<?=strstr($r['doiptype'],',gbook,')?' checked':''?>>
            留言 
            <input name="doiptype[]" type="checkbox" id="doiptype[]" value="downinfo"<?=strstr($r['doiptype'],',downinfo,')?' checked':''?>>
            下载
            <input name="doiptype[]" type="checkbox" id="doiptype[]" value="onlineinfo"<?=strstr($r['doiptype'],',onlineinfo,')?' checked':''?>>
            在线观看 
            <input name="doiptype[]" type="checkbox" id="doiptype[]" value="showinfo"<?=strstr($r['doiptype'],',showinfo,')?' checked':''?>>
            查看信息</li>
            
            <li style="position:relative;"><label>禁止 IP 提交列表:<br />
          </label><textarea name="closedoip" cols="60" rows="8" id="closedoip"><?=$r[closedoip]?></textarea>
          <div class="tips ipfl"><span class="tip-arrow tip-arrow-left"><em>◆</em><b>◆</b></span>
    每个 IP 一行，既可输入完整地址，也可只输入 IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 192.168.0.0～192.168.255.255 
            范围内的所有地址，留空为不设置</div></li>
            <li style="position:relative;"><label>允许 IP 提交列表:<br />
          </label><textarea name="opendoip" cols="60" rows="8" id="opendoip"><?=$r[opendoip]?></textarea>
          <div class="tips ipfl"><span class="tip-arrow tip-arrow-left"><em>◆</em><b>◆</b></span>
    只有当用户处于本列表中的 IP 地址时才可以提交数据，列表以外的地址提交将视为 IP 被禁止.每个 IP 一行，既可输入完整地址，也可只输入 
            IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 192.168.0.0～192.168.255.255 
            范围内的所有地址，留空为所有 IP 除明确禁止的以外均可访问</div></li>
            </ul>
        </div>
		<div class="ui-tab-content" style="display:none">
        	<h3><span>文件设置</span></h3>
            <div class="line"></div>
            <ul>
            	<li class="jqui"><label>附件存放目录</label>
            <input type="radio" name="fpath" value="0"<?=$r[fpath]==0?' checked':''?>>
            栏目目录
            <input type="radio" name="fpath" value="1"<?=$r[fpath]==1?' checked':''?>>
            /d/file/p目录 
            <input type="radio" name="fpath" value="2"<?=$r[fpath]==2?' checked':''?>>
            /d/file目录
            </li>
                <li><label>存放的子文件夹</label>
            <input name="filepath" type="text" id="filepath" value="<?=$r[filepath]?>" size="12"> 
            <select name="select6" onChange="document.form1.filepath.value=this.value">
              <option value="Y-m-d">选择</option>
              <option value="Y-m-d">2005-01-27</option>
              <option value="Y/m-d">2005/01-27</option>
              <option value="Y/m/d">2005/01/27</option>
              <option value="Ymd">20050127</option>
              <option value="">不设置目录</option>
            </select> <font color="#666666">(如Y-m-d，Y/m-d，Y/m/d，Ymd等形式)</font></li>
                <li class="jqui"><label>后台上传附件大小</label><input name="filesize" type="text" id="filesize" value="<?=$r[filesize]?>" size="10"></li>
                <li class="jqui"><label>后台允许上传文件的扩展名</label><input name="filetype" type="text" id="filetype" value="<?=$filetype?>" size="38"> 
            <font color="#666666">(多个请用"|"格开，如：.gif|.jpg)</font></li>
                <li class="jqui"><label>前台投稿附件设置</label><table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="qaddtran" type="checkbox" value="1"<?=$r[qaddtran]==1?' checked':''?>>
                  开启上传图片,最大图片： 
                  <input name="qaddtransize" type="text" id="qaddtransize" value="<?=$r[qaddtransize]?>" size="6">
                  KB</td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">图片扩展名: 
                  <input name="qaddtranimgtype" type="text" value="<?=$qaddimgtype?>" size="30"> 
                  <font color="#666666"> (多个用&quot;|&quot;格开) </font></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;"><input name="qaddtranfile" type="checkbox" value="1"<?=$r[qaddtranfile]==1?' checked':''?>>
                  开启上传附件,最大附件： 
                  <input name="qaddtranfilesize" type="text" value="<?=$r[qaddtranfilesize]?>" size="6">
                  KB</td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">附件扩展名: 
                  <input name="qaddtranfiletype" type="text" value="<?=$qaddfiletype?>" size="30"> 
                  <font color="#666666">(多个用&quot;|&quot;格开)</font></td>
              </tr>
            </table></li>
                <li class="jqui"><label>前台反馈附件设置</label><table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="feedbacktfile" type="checkbox" id="feedbacktfile" value="1"<?=$r[feedbacktfile]==1?' checked':''?>>
                  开启上传附件,最大附件： 
                  <input name="feedbackfilesize" type="text" value="<?=$r[feedbackfilesize]?>" size="6">
                  KB</td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">附件扩展名: 
                  <input name="feedbackfiletype" type="text" value="<?=$feedbackfiletype?>" size="30"> 
                  <font color="#666666">(多个用&quot;|&quot;格开)</font></td>
              </tr>
            </table></li>
                <li class="jqui">
                <label>会员表单附件设置</label>
                <table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="openmembertranimg" type="checkbox" id="openmembertranimg" value="1"<?=$r[openmembertranimg]==1?' checked':''?>>
                  开启上传图片,最大图片： 
                  <input name="memberimgsize" type="text" id="memberimgsize" value="<?=$r[memberimgsize]?>" size="6">
                  KB</td>
              </tr>
              <tr> 
                <td  style="padding-top:5px;">图片扩展名: 
                  <input name="memberimgtype" type="text" id="memberimgtype" value="<?=$memberimgtype?>" size="30"> 
                  <font color="#666666"> (多个用&quot;|&quot;格开) </font></td>
              </tr>
              <tr> 
                <td  style="padding-top:5px;"><input name="openmembertranfile" type="checkbox" id="openmembertranfile" value="1"<?=$r[openmembertranfile]==1?' checked':''?>>
                  开启上传附件,最大附件： 
                  <input name="memberfilesize" type="text" id="memberfilesize" value="<?=$r[memberfilesize]?>" size="6">
                  KB</td>
              </tr>
              <tr> 
                <td  style="padding-top:5px;">附件扩展名: 
                  <input name="memberfiletype" type="text" id="memberfiletype" value="<?=$memberfiletype?>" size="30"> 
                  <font color="#666666">(多个用&quot;|&quot;格开)</font></td>
              </tr>
            </table>
                </li>
<li class="jqui"><label>会员附件字段支持填写</label><input type="radio" name="modmemberedittran" value="1"<?=$r[modmemberedittran]==1?' checked':''?>>是
              <input type="radio" name="modmemberedittran" value="0"<?=$r[modmemberedittran]==0?' checked':''?>>否</li>
<li class="jqui"><label>投稿附件字段支持填写</label><input type="radio" name="modinfoedittran" value="1"<?=$r[modinfoedittran]==1?' checked':''?>>是
              <input type="radio" name="modinfoedittran" value="0"<?=$r[modinfoedittran]==0?' checked':''?>>否</li>
                <li class="jqui"><label>文件生成权限</label><input type="radio" name="filechmod" value="0"<?=$r[filechmod]==0?' checked':''?>>
            0777
            <input type="radio" name="filechmod" value="1"<?=$r[filechmod]==1?' checked':''?>>
            不限制</li>
                <li class="jqui"><label>广告JS文件前缀</label><input name="adfile" type="text" id="adfile" value="<?=$r[adfile]?>" size="38">
		  <iframe name="checkftpiframe" style="display: none" src="blank.php"></iframe></li>
          
          		</ul>
                <h3><span>备份设置</span></h3>
                <ul>
                <li class="jqui"><label>数据备份存放目录</label>admin/ebak/ 
            <input name="bakdbpath" type="text" id="bakdbpath" value="<?=$r[bakdbpath]?>"></li>
                <li class="jqui"><label>压缩包存放目录</label>admin/ebak/ 
            <input name="bakdbzip" type="text" id="bakdbzip" value="<?=$r[bakdbzip]?>"></li>
                <li class="jqui"><label>备份只选择当前数据库</label><input name="ebakthisdb" type="checkbox" id="ebakthisdb" value="1"<?=$r[ebakthisdb]==1?' checked':''?>>
            是</li>
                <li class="jqui"><label>空间不支持数据库列表</label><input name="ebakcanlistdb" type="checkbox" id="ebakcanlistdb" value="1"<?=$r[ebakcanlistdb]==1?' checked':''?>>
            是<font color="#666666">(如果空间不允许列出数据库,请打勾)</font></li>
                <li class="jqui"><label>支持MYSQL查询方式</label><input name="limittype" type="checkbox" id="limittype" value="1"<?=$r[limittype]==1?' checked':''?>>
            支持</li>
               
            </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>信息排行设置(JS)</span></h3>
            <div class="line"></div>
            <ul class="jqui">
                	<li><label>热门信息显示</label><input name="hotnum" type="text" id="hotnum" value="<?=$r[hotnum]?>" size="10">条信息</li>
                    <li><label>最新信息显示</label><input name="newnum" type="text" id="newnum" value="<?=$r[newnum]?>" size="10">条信息</li>
                    <li><label>推荐信息显示</label><input name="goodnum" type="text" id="goodnum" value="<?=$r[goodnum]?>" size="10">条信息</li>
                    <li><label>热门评论显示</label><input name="hotplnum" type="text" id="hotplnum" value="<?=$r[hotplnum]?>" size="10">条信息</li>
                    <li><label>头条信息显示</label><input name="firstnum" type="text" id="firstnum" value="<?=$r[firstnum]?>" size="10">条信息</li>
             
            </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>分组生成设置（依服务器配置设置大小）</span></h3>
            <div class="line"></div>
            <ul class="jqui">
            	<li><label>每组生成间隔</label><input name="realltime" type="text" id="realltime" value="<?=$r[realltime]?>" size="10">秒<font color="#666666">(0为连续生成)</font></li>
                <li><label>栏目生成每组</label><input name="relistnum" type="text" id="relistnum" value="<?=$r[relistnum]?>" size="10">个</li>
                <li><label>信息生成每组</label><input name="renewsnum" type="text" id="renewsnum" value="<?=$r[renewsnum]?>" size="10">个</li>
                <li><label>更新相关链接每组</label><input name="infolinknum" type="text" id="infolinknum" value="<?=$r[infolinknum]?>" size="10">个</li>
                <li><label>生成自定义JS每组</label><input name="reuserjsnum" type="text" id="reuserjsnum" value="<?=$r[reuserjsnum]?>" size="10">个</li>
                <li><label>生成自定义列表每组</label><input name="reuserlistnum" type="text" id="reuserlistnum" value="<?=$r[reuserlistnum]?>" size="10">个</li>
                <li><label>自定义页面每组</label><input name="reuserpagenum" type="text" id="reuserpagenum" value="<?=$r[reuserpagenum]?>" size="10">个</li>
                <li><label>投票JS每组</label><input name="revotejsnum" type="text" id="revotejsnum" value="<?=$r[revotejsnum]?>" size="10">个</li>
                <li><label>广告JS每组</label><input name="readjsnum" type="text" id="readjsnum" value="<?=$r[readjsnum]?>" size="10">个</li>
                <li><label>替换字段值每组</label><input name="dorepnum" type="text" id="dorepnum" value="<?=$r[dorepnum]?>" size="10">个</li>
                <li><label>替换地址权限每组</label><input name="dorepdlevelnum" type="text" id="dorepdlevelnum" value="<?=$r[dorepdlevelnum]?>" size="10">个</li>
                <li><label>批量删除信息每组</label><input name="delnewsnum" type="text" id="delnewsnum" value="<?=$r[delnewsnum]?>" size="10">个</li>
                <li><label>批量归档信息每组</label><input name="docnewsnum" type="text" id="docnewsnum" value="<?=$r[docnewsnum]?>" size="10">个</li>
                <li><label>导入栏目模板每组</label><input name="loadtempnum" type="text" id="loadtempnum" value="<?=$r[loadtempnum]?>" size="10">个</li>
                
             </ul>
             </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>搜索设置</span></h3>
            <div class="line"></div>
            <ul>	
                	<li><label>搜索用户组</label><select name="searchgroupid" id="searchgroupid">
              <option value=0>游客</option>
              <?=$searchmembergroup?>
            </select></li>
                    <li class="jqui"><label>搜索关键字</label>
            <input name="min_keyboard" type="text" id="min_keyboard" value="<?=$r[min_keyboard]?>" size="6">
            个字符与 
            <input name="max_keyboard" type="text" id="max_keyboard" value="<?=$r[max_keyboard]?>" size="6">
            个字符之间</li>
                    <li class="jqui"> <label>搜索时间间隔</label> 
            <input name="searchtime" type="text" id="searchtime" value="<?=$r[searchtime]?>" size="6">
            秒</li>
                    <li class="jqui"><label>页面显示</label>每页 
            <input name="search_num" type="text" id="search_num" value="<?=$r[search_num]?>" size="6">
            显示条记录， 
            <input name="search_pagenum" type="text" id="search_pagenum" value="<?=$r[search_pagenum]?>" size="6">
            个分页链接<font color="#666666">(为0的话，系统默认25条，12个链接)</font></li>
                    <li class="jqui"><label>支持公共模板变量</label><input type="radio" name="searchtempvar" value="0"<?=$r['searchtempvar']==0?' checked':''?>>
            不支持 
            <input type="radio" name="searchtempvar" value="1"<?=$r['searchtempvar']==1?' checked':''?>>
            支持<font color="#666666">(搜索模板及动态页面)</font></li>
                    
                    <li><label>高级搜索页扩展名</label><input name="searchtype" type="text" id="searchtype" value="<?=$r[searchtype]?>" size="10"> 
            <font color="#666666"> 
            <select name="select2" onChange="document.form1.searchtype.value=this.value">
              <option value=".html">扩展名</option>
              <option value=".html">.html</option>
              <option value=".htm">.htm</option>
              <option value=".php">.php</option>
              <option value=".shtml">.shtml</option>
            </select>
            (如：.html,.htm,.xml,.php)</font></li>
            </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>信息设置</span></h3>
            <div class="line"></div>
            <ul>
                	<li class="jqui"><label>后台管理信息</label>每页显示
            <input name="hlistinfonum" type="text" id="hlistinfonum" value="<?=$r[hlistinfonum]?>" size="10">
            个信息</li>
                    <li class="jqui"><label>前台结合项列表</label>每页显示 
            <input name="qlistinfonum" type="text" id="qlistinfonum" value="<?=$r[qlistinfonum]?>" size="10">
            个信息</li>
            	
                    <li><label>后台信息默认显示时间范围</label><select name="infolday" id="infolday">
              <option value="0"<?=$r['infolday']==0?' selected':''?>>全部显示</option>
              <option value="86400"<?=$r['infolday']==86400?' selected':''?>>1 
              天</option>
              <option value="172800"<?=$r['infolday']==172800?' selected':''?>>2 
              天</option>
              <option value="604800"<?=$r['infolday']==604800?' selected':''?>>一周</option>
              <option value="2592000"<?=$r['infolday']==2592000?' selected':''?>>1 
              个月</option>
              <option value="7948800"<?=$r['infolday']==7948800?' selected':''?>>3 
              个月</option>
              <option value="15897600"<?=$r['infolday']==15897600?' selected':''?>>6 
              个月</option>
              <option value="31536000"<?=$r['infolday']==31536000?' selected':''?>>1 
              年</option>
            </select></li>
                    <li><label>后台附件默认显示时间范围</label><select name="filelday" id="filelday">
              <option value="0"<?=$r['filelday']==0?' selected':''?>>全部显示</option>
              <option value="86400"<?=$r['filelday']==86400?' selected':''?>>1 
              天</option>
              <option value="172800"<?=$r['filelday']==172800?' selected':''?>>2 
              天</option>
              <option value="604800"<?=$r['filelday']==604800?' selected':''?>>一周</option>
              <option value="2592000"<?=$r['filelday']==2592000?' selected':''?>>1 
              个月</option>
              <option value="7948800"<?=$r['filelday']==7948800?' selected':''?>>3 
              个月</option>
              <option value="15897600"<?=$r['filelday']==15897600?' selected':''?>>6 
              个月</option>
              <option value="31536000"<?=$r['filelday']==31536000?' selected':''?>>1 
              年</option>
            </select></li>
                    <li><label>信息置顶设置</label><select name="settop" id="settop">
              <option value="0"<?=$r[settop]==0?' selected':''?>>不使用置顶</option>
              <option value="1"<?=$r[settop]==1?' selected':''?>>栏目列表置顶</option>
              <option value="2"<?=$r[settop]==2?' selected':''?>>标签调用置顶</option>
              <option value="3"<?=$r[settop]==3?' selected':''?>>JS调用置顶</option>
              <option value="4"<?=$r[settop]==4?' selected':''?>>栏目/标签/JS置顶</option>
              <option value="5"<?=$r[settop]==5?' selected':''?>>栏目/标签置顶</option>
              <option value="6"<?=$r[settop]==6?' selected':''?>>栏目/JS置顶</option>
              <option value="7"<?=$r[settop]==7?' selected':''?>>标签/JS置顶</option>
            </select></li>
                    <li class="jqui"><label>&nbsp;</label><input name="fieldandtop" type="checkbox" id="fieldandtop" value="1"<?=$r[fieldandtop]==1?' checked':''?>>
            结合项支持置顶</li>
                    <li class="jqui"><label>结合项不启用的表</label><input name="fieldandclosetb" type="text" id="fieldandclosetb" value="<?=substr($r[fieldandclosetb],1,strlen($r[fieldandclosetb])-2)?>" size="38"> 
            <font color="#666666">(多个表名用半角逗号隔开，如：news,download)</font></li>
                    <li class="jqui"><label>动态列表支持标签</label><input type="radio" name="dtcanbq" value="0"<?=$r[dtcanbq]==0?' checked':''?>>
            不支持 
            <input type="radio" name="dtcanbq" value="1"<?=$r[dtcanbq]==1?' checked':''?>>
            支持</li>
                    <li class="jqui"><label>动态列表变量缓存</label><input name="dtcachetime" type="text" id="dtcachetime" value="<?=$r[dtcachetime]?>" size="10">
            分钟</li>
                    <li class="jqui"><label>动态内容页支持标签</label><input type="radio" name="dtncanbq" value="0"<?=$r[dtncanbq]==0?' checked':''?>>
            不支持 
            <input type="radio" name="dtncanbq" value="1"<?=$r[dtncanbq]==1?' checked':''?>>
            支持</li>
                    <li class="jqui"><label>动态内容页变量缓存</label><input name="dtncachetime" type="text" id="dtncachetime" value="<?=$r[dtncachetime]?>" size="10">
            分钟</li>
                    <li class="jqui"><label>新会员投稿限制</label>最新注册会员必须过 
            <input name="newaddinfotime" type="text" id="newaddinfotime" value="<?=$r[newaddinfotime]?>" size="6">
            分钟才能投稿 <font color="#666666">(0为不限制)</font></li>
                    <li class="jqui"><label>投稿数量限制</label>同一个IP在
            <input name="ipaddinfotime" type="text" id="ipaddinfotime" value="<?=$r[ipaddinfotime]?>" size="6">
          个小时内最大只允许增加
          <input name="ipaddinfonum" type="text" id="ipaddinfonum" value="<?=$r[ipaddinfonum]?>" size="6">
          个信息<font color="#666666">(0为不限，且模型要增加infoip字段)</font></li>
                    <li class="jqui"><label>重复投稿时间限制</label><input name="readdinfotime" type="text" id="readdinfotime" value="<?=$r[readdinfotime]?>" size="10">
            秒</li>
                    <li class="jqui"><label>投稿信息修改时间限制：</label><input name="qeditinfotime" type="text" id="qeditinfotime" value="<?=$r[qeditinfotime]?>" size="10">
            分钟<font color="#666666">(0为不限制)</font></li>
                    <li class="jqui"><label>投稿管理信息显示方式：</label><input type="radio" name="qlistinfomod" value="0"<?=$r[qlistinfomod]==0?' checked':''?>>
            按表显示 
            <input type="radio" name="qlistinfomod" value="1"<?=$r[qlistinfomod]==1?' checked':''?>>
            按模型显示 <font color="#666666">(按模型显示影响效率)</font></li>
                    <li class="jqui"><label>栏目导航分隔字符</label><input name="classnavfh" type="text" id="navfh3" value="<?=ehtmlspecialchars($r[classnavfh])?>" size="38"></li>
                    <li class="jqui"><label>栏目导航显示个数</label><input name="classnavline" type="text" id="classnavline" value="<?=$r[classnavline]?>" size="10"> 
            <font color="#666666">(0为不限)</font></li>
                    <li class="jqui"><label>所在位置导航分隔字符</label><input name="navfh" type="text" id="navfh" value="<?=$r[navfh]?>" size="10"> 
            <font color="#666666">(如:"首页 &gt; 新闻"中的"&gt;")</font></li>
                    <li class="jqui"><label>信息简介截取</label><input name="smalltextlen" type="text" id="smalltextlen" value="<?=$r[smalltextlen]?>" size="10">
            个字<font color="#666666"> (简介为空时，截取信息内容)</font></li>
            
                    <li><label>相关链接依据</label><select name="newslink" id="newslink">
              <option value="0"<?=$r['newslink']==0?' selected':''?>>标题包含关键字</option>
              <option value="1"<?=$r['newslink']==1?' selected':''?>>关键字相同</option>
              <option value="2"<?=$r['newslink']==2?' selected':''?>>标题包含与关键字相同</option>
            </select></li>
                    <li class="jqui"><label>增加信息随机点击数范围</label><input name="onclickrnd" type="text" id="onclickrnd" value="<?=$r[onclickrnd]?>" size="10"> 
            <font color="#666666">(格式:"最小数,最大数"，空表示不使用。例子：20,100)</font></li>
                    <li class="jqui"><label>开启信息内容过滤字符替换</label><input type="radio" name="dorepword" value="0"<?=$r['dorepword']==0?' checked':''?>>
            生成页面时替换 
            <input type="radio" name="dorepword" value="1"<?=$r['dorepword']==1?' checked':''?>>
            增加信息时替换 
            <input type="radio" name="dorepword" value="2"<?=$r['dorepword']==2?' checked':''?>>
            关闭替换<font color="#666666"> (关闭替换效率高)</font></li>
                    <li class="jqui"><label>开启信息内容关键字替换</label><input type="radio" name="dorepkey" value="0"<?=$r['dorepkey']==0?' checked':''?>>
            生成页面时替换 
            <input type="radio" name="dorepkey" value="1"<?=$r['dorepkey']==1?' checked':''?>>
            增加信息时替换 
            <input type="radio" name="dorepkey" value="2"<?=$r['dorepkey']==2?' checked':''?>>
            关闭替换<font color="#666666"> (关闭替换效率高)</font></li>
                    <li class="jqui"><label>信息内容关键字重复替换</label><input name="repkeynum" type="text" id="repkeynum" value="<?=$r[repkeynum]?>" size="10">
            次<font color="#666666"> (0为不限,效率高；限制替换次数会影响生成效率。)</font></li>
                    <li class="jqui"><label>反馈验证码</label><input type="radio" name="fbkey_ok" value="1"<?=$r[fbkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="fbkey_ok" value="0"<?=$r[fbkey_ok]==0?' checked':''?>>
            关闭</li>
                    <li class="jqui"><label>留言验证码</label><input type="radio" name="gbkey_ok" value="1"<?=$r[gbkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="gbkey_ok" value="0"<?=$r[gbkey_ok]==0?' checked':''?>>
            关闭</li>
                    <li class="jqui"><label>重复留言时间限制</label><input name="regbooktime" type="text" id="regbooktime" value="<?=$r[regbooktime]?>" size="10">
            秒</li>
                    <li class="jqui"><label>重复投票时间限制</label><input name="revotetime" type="text" id="revotetime" value="<?=$r[revotetime]?>" size="10">
            秒</li>
                    <li class="jqui"><label>留言每页显示</label><input name="gb_num" type="text" id="gb_num" value="<?=$r[gb_num]?>" size="10">
            个留言</li>
                    <li class="jqui"><label>模板备份记录数</label><input name="baktempnum" type="text" id="baktempnum" value="<?=$r[baktempnum]?>" size="10"> 
            <font color="#666666">(0为不备份)</font></li>
            
                    <li class="jqui"><label>关闭动态使用的列表模板ID</label><input name="closelisttemp" type="text" id="closelisttemp" value="<?=$r[closelisttemp]?>" size="38">
            <input type="button" name="Submit6222" value="管理列表模板" onClick="window.open('template/ListListtemp.php?gid=<?=$thegid?>');" class="button"> 
            <font color="#666666">(多个ID用半角逗号","隔开)</font></li>
                    <li class="jqui"><label>模板支持程序代码</label><input type="radio" name="candocode" value="1"<?=$r[candocode]==1?' checked':''?>>
            开启 
            <input type="radio" name="candocode" value="0"<?=$r[candocode]==0?' checked':''?>>
            关闭</li>
                    <li class="jqui"><label>防采集</label><input type="radio" name="opennotcj" value="1"<?=$r[opennotcj]==1?' checked':''?>>
            开启 
            <input type="radio" name="opennotcj" value="0"<?=$r[opennotcj]==0?' checked':''?>>
            关闭</li>
                    <li class="jqui"><label>内容防复制</label><input type="radio" name="opencopytext" value="1"<?=$r[opencopytext]==1?' checked':''?>>
            开启 
            <input type="radio" name="opencopytext" value="0"<?=$r[opencopytext]==0?' checked':''?>>
            关闭<font color="#666666"> (内容随机字符)</font></li>
                    <li class="jqui"><label>列表分页函数(下拉)</label><input name="listpagefun" type="text" id="listpagefun" value="<?=$r[listpagefun]?>" size="38"> 
            <font color="#666666"> (可加到e/class/userfun.php文件里)</font></li>
                    <li class="jqui"><label>列表分页函数(列表)</label>
             <table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="listpagelistfun" type="text" id="listpagelistfun" value="<?=$r[listpagelistfun]?>" size="38"> 
            <font color="#666666">(可加到e/class/userfun.php文件里)</font></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">每页显示<input name="listpagelistnum" type="text" id="listpagelistnum" value="<?=$r[listpagelistnum]?>" size="6">
            个页码</td>
              </tr>
            </table></li>
                    <li><label>内容分页函数</label>
            <table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="textpagefun" type="text" id="textpagefun" value="<?=$r[textpagefun]?>" size="38"> 
            <font color="#666666">(可加到e/class/userfun.php文件里)</font></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">每页显示 
            <input name="textpagelistnum" type="text" id="textpagelistnum" value="<?=$r[textpagelistnum]?>" size="6">
            个页码</td>
              </tr>
            </table>
            </li>
                    <li class="jqui"><label>RSS/XML设置</label>显示最新 
            <input name="rssnum" type="text" id="rssnum" value="<?=$r[rssnum]?>" size="6">
            条记录，简介截取 
            <input name="rsssub" type="text" id="rsssub" value="<?=$r[rsssub]?>" size="6">
            个字</li>
            
                    <li><label>点击统计设置</label>
             <table border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="25">类型： 
                  <select name="onclicktype" id="onclicktype">
                    <option value="0"<?=$r[onclicktype]==0?' selected':''?>>实时统计</option>
                    <option value="1"<?=$r[onclicktype]==1?' selected':''?>>文本缓存</option>
                    <option value="2"<?=$r[onclicktype]==2?' selected':''?>>不统计</option>
                  </select></td>
              </tr>
              <tr> 
                <td height="25" class="jqui">文本缓存最大文件： 
                  <input name="onclickfilesize" type="text" id="onclickfilesize" value="<?=$r[onclickfilesize]?>" size="10"> 
                  KB</td>
              </tr>
              <tr> 
                <td height="25" class="jqui"> 文本缓存最长时间：
                
                  <input name="onclickfiletime" type="text" id="onclickfiletime" value="<?=$r[onclickfiletime]?>" size="10">
                  分钟</td>
              </tr>
            </table></li>
                    <li class="jqui"><label>信息外部链接设置</label><input type="radio" name="opentitleurl" value="0"<?=$r[opentitleurl]==0?' checked':''?>>
            统计点击 
            <input type="radio" name="opentitleurl" value="1"<?=$r[opentitleurl]==1?' checked':''?>>
            显示原链接</li>
                    <li class="jqui"><label>选择终极栏目的背景颜色</label><input name="chclasscolor" type="text" id="chclasscolor" value="<?=$r[chclasscolor]?>" size="38"></li>
              
                    <li><label>九级头条名称</label><textarea name="firsttitlename" cols="80" rows="8" id="firsttitlename"><?=str_replace("|","\r\n",$r[firsttitlename])?></textarea></li>
                    <li><label>九级推荐名称</label><textarea name="isgoodname" cols="80" rows="8" id="isgoodname"><?=str_replace("|","\r\n",$r[isgoodname])?></textarea></li>
            </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>发送邮件设置</span></h3>
            <div class="line"></div>
            <ul>
                	<li class="jqui"><label>邮件发送模式</label><input type="radio" name="sendmailtype" value="0"<?=$r[sendmailtype]==0?' checked':''?>>
            mail 函数发送 
            <input type="radio" name="sendmailtype" value="1"<?=$r[sendmailtype]==1?' checked':''?>>
            SMTP 模块发送</li>
                    <li class="jqui"><label>SMTP服务器</label><input name="smtphost" type="text" id="smtphost" value="<?=$r[smtphost]?>" size="38"></li>
                    <li class="jqui"><label>SMTP端口</label><input name="smtpport" type="text" id="smtpport" value="<?=$r[smtpport]?>" size="10"></li>
                    <li class="jqui"><label>发信人地址</label><input name="fromemail" type="text" id="fromemail" value="<?=$r[fromemail]?>" size="38"></li>
                    <li class="jqui"><label>发信人呢称</label><input name="emailname" type="text" id="emailname" value="<?=$r[emailname]?>" size="10"></li>
                    <li class="jqui"><label>是否需要登录验证</label><input type="radio" name="loginemail" value="1"<?=$r[loginemail]==1?' checked':''?>>
            是 
            <input type="radio" name="loginemail" value="0"<?=$r[loginemail]==0?' checked':''?>>
            否</li>
                    <li class="jqui"><label>邮箱登录用户名</label><input name="emailusername" type="text" id="emailusername" value="<?=$r[emailusername]?>" size="10"></li>
                    <li class="jqui"><label>邮箱登录密码</label><input name="emailpassword" type="password" id="emailpassword" value="<?=$r[emailpassword]?>" size="10"></li>
                     
                    </ul>
                    <h3><span>FTP设置(远程发布 / PHP运行于安全模式等情况下需设置以下选项)</span></h3>
                 	<ul>
                    <li class="jqui"><label>PHP运行于安全模式</label><input name="phpmode" type="checkbox" id="phpmode" value="1"<?=$r[phpmode]==1?' checked':''?>>
            是</li>
             
                    <li><label>安装形式</label><select name="install" id="select">
              <option value="0"<?=$r[install]==0?' selected':''?>>服务端</option>
              <option value="1"<?=$r[install]==1?' selected':''?>>客户端</option>
            </select> <font color="#666666">(如是远程发布，请选客户端，并且需配置FTP选项)</font></li>
                    <li class="jqui"><label>启用 SSL 连接</label><input type="radio" name="ftpssl" value="1"<?=$r[ftpssl]==1?' checked':''?>>
            是
            <input type="radio" name="ftpssl" value="0"<?=$r[ftpssl]==0?' checked':''?>>
            否</li>
                    <li class="jqui"><label>被动模式(pasv)连接</label><input type="radio" name="ftppasv" value="1"<?=$r[ftppasv]==1?' checked':''?>>
            是 
            <input type="radio" name="ftppasv" value="0"<?=$r[ftppasv]==0?' checked':''?>>
            否</li>
                    <li class="jqui"><label>FTP服务器地址</label><input name="ftphost" type="text" id="ftphost" value="<?=$r[ftphost]?>" size="10">
            端口： 
            <input name="ftpport" type="text" id="ftpport" value="<?=$r[ftpport]?>" size="4"></li>
                    <li class="jqui"><label>FTP用户名</label><input name="ftpusername" type="text" id="ftpusername" value="<?=$r[ftpusername]?>" size="10"> </li>
                    <li class="jqui"><label>FTP密码</label><input name="ftppassword" type="password" size="10">
            <font color="#666666">(不修改密码请留空) </font></li>
                    <li class="jqui"><label>传送模式</label><input type="radio" name="ftpmode" value="1"<?=$r[ftpmode]==1?' checked':''?>>
            ASCII 
            <input type="radio" name="ftpmode" value="0"<?=$r[ftpmode]==0?' checked':''?>>
            二进制</li>
                    <li class="jqui"><label>FTP 传输超时时间</label><input name="ftpouttime" type="text" id="ftpouttime" value="<?=$r[ftpouttime]?>" size="10">
            秒<font color="#666666">(0为服务器默认)</font></li>
                    <li class="jqui"><label>系统根目录(FTP)</label><input name="ftppath" type="text" value="<?=$r[ftppath]?>" size="10"> 
            <font color="#666666">(目录结尾不要加斜杠"/"，空为根目录)</font></li>
                    <li><label>测试FTP服务器</label><input type="submit" name="Submit32" value="测试FTP服务器" onClick="document.form1.enews.value='CheckPostServerFtp';document.form1.action='SetEnews.php';document.form1.target='checkftpiframe';" class="anniu">
            <font color="#666666">(无需保存设置即可测试，请在测试通过后再保存)</font></li>
                           
             </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>信息投稿屏蔽设置</span></h3>
            <div class="line"></div>
            <ul>
            		<li style="position:relative;"><label>屏蔽字段</label><textarea name="closewordsf" cols="60" rows="8"><?=$r[closewordsf]?></textarea>
                     <div class="tips ipfl"><span class="tip-arrow tip-arrow-left"><em>◆</em><b>◆</b></span>
多个用"|"格开，如"title|newstext"<br /><a href="db/ListTable.php" target="_blank">[点击查看字段]</a></div></li>
                    <li style="position:relative;"><label>屏蔽字符列表</label><textarea name="closewords" cols="60" rows="8"><?=$r[closewords]?></textarea>
                    <div class="tips ipfl"><span class="tip-arrow tip-arrow-left"><em>◆</em><b>◆</b></span>
(1)、多个用"|"隔开，如"字符1|字符2" 。<br />
            (2)、同时包含多字时屏蔽可用双"#"隔开，如"破##解|字符2" 。这样只要内容同时包含"破"和"解"字都会被屏蔽。</div></li>
            </ul>
            <h3><span>新闻/下载/电影/商城等模型设置</span></h3>
            <ul>
                	<li class="jqui"><label>关闭后台菜单</label><input name="closehmenu[]" type="checkbox" id="closehmenu[]" value="shop"<?=stristr($r['closehmenu'],',shop,')?' checked':''?>>
          商城</li>
                    <li class="jqui"><label>同一地址下载/观看超过</label><input name="redodown" type="text" id="redodown" value="<?=$r[redodown]?>" size="10">
            个小时 将重复扣点</li>
                    <li class="jqui"><label>同一信息查看超过</label><input name="redoview" type="text" id="redoview" value="<?=$r[redoview]?>" size="10">
            个小时 将重复扣点</li>
                    <li class="jqui"><label>下载验证码</label><input name="downpass" type="text" id="downpass" value="<?=$r[downpass]?>" size="10"> 
            <font color="#666666">(主要用于防盗链,请定期更新一次密码)</font></li>
                    <li class="jqui"><label>开启直接下载</label><input type="radio" name="opengetdown" value="1"<?=$r[opengetdown]==1?' checked':''?>>
            是 
            <input type="radio" name="opengetdown" value="0"<?=$r[opengetdown]==0?' checked':''?>>
            否</li>
                
            </ul>
        </div>
        <div class="ui-tab-content" style="display:none">
        	<h3><span>图片缩略图设置</span></h3>
            <div class="line"></div>
            	<ul>
                	<li class="jqui"><label>默认值</label>
             宽: 
            <input name="spicwidth" type="text" id="spicwidth" value="<?=$r[spicwidth]?>" size="6">
            ×高: 
            <input name="spicheight" type="text" id="spicheight" value="<?=$r[spicheight]?>" size="6"></li>
                    <li class="jqui"><label>超出部分是否截取</label><input type="radio" name="spickill" value="1"<?=$r['spickill']==1?' checked':''?>>
            是 
            <input type="radio" name="spickill" value="0"<?=$r['spickill']==0?' checked':''?>>
            否</li>
                </ul>
            <h3><span>图片水印设置(不想用图片水印，请留空)</span></h3>
            	<ul>
                	<li class="jqui"><label>水印位置</label>
                    <table width="200" border="0" cellpadding="1" cellspacing="1" class="sywz">
  <tr>
    <td rowspan="3"><div align="center"> 
                    <input type="radio" name="markpos" value="0"<?=$r[markpos]==0?' checked':'';?>>
                    随机 </div></td>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="1"<?=$r[markpos]==1?' checked':'';?>>
                  </div></td>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="2"<?=$r[markpos]==2?' checked':'';?>>
                  </div></td>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="3"<?=$r[markpos]==3?' checked':'';?>>
                  </div></td>
  </tr>
  <tr>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="4"<?=$r[markpos]==4?' checked':'';?>>
                  </div></td>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="5"<?=$r[markpos]==5?' checked':'';?>>
                  </div></td>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="6"<?=$r[markpos]==6?' checked':'';?>>
                  </div></td>
  </tr>
  <tr>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="7"<?=$r[markpos]==7?' checked':'';?>>
                  </div></td>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="8"<?=$r[markpos]==8?' checked':'';?>>
                  </div></td>
    <td><div align="center"> 
                    <input type="radio" name="markpos" value="9"<?=$r[markpos]==9?' checked':'';?>>
                  </div></td>
  </tr>
</table>
                    </li>
                    
                    <li class="jqui"><label>文字水印</label>
                    <table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td>文字内容 
            <input name="marktext" type="text" id="marktext" value="<?=$r[marktext]?>"> 
            <font color="#666666">(目前不支持中文)</font></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">文字字体 
            <input name="markfont" type="text" id="markfont" value="<?=$r[markfont]?>"> 
            <font color="#666666">(从后台开始算，如../data就是data目录)</font></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">文字颜色 
            <input name="markfontcolor" type="text" id="markfontcolor" value="<?=$r[markfontcolor]?>"> 
            <a onClick="foreColor(document.form1.markfontcolor);"><img src="../data/images/color.gif" width="21" height="21" align="absbottom"></a> </td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">文字大小 
            <input name="markfontsize" type="text" value="<?=$r[markfontsize]?>"> 
            <font color="#666666">(1~5之间的数字)</font></td>
              </tr>
            </table>
                    </li>
                    <li class="jqui"><label>图片水印</label>
                    <table border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td>图片文件
            <input name="markimg" type="text" id="markimg" value="<?=$r[markimg]?>"> 
            <font color="#666666">(从后台开始算，如../data就是data目录)</font></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">图片质量 
            <input name="jpgquality" type="text" id="jpgquality" value="<?=$r[jpgquality]?>"> 
            <font color="#666666">(该值决定 jpg 格式图片的质量，范围从 0 到 100)</font></td>
              </tr>
              <tr> 
                <td style="padding-top:5px;">水印透明度 
            <input name="markpct" type="text" id="markpct" value="<?=$r[markpct]?>" size="10"> 
            <font color="#666666">(该值决定图片水印清晰度，其值范围从 0 到 100)</font></td>
              </tr>
            </table></li>
                </ul>
 </div>
 <div class="line"></div>
  </div>
 <div class="sub jqui"><input type="submit" name="Submit" value=" 设置 " onClick="document.form1.enews.value='SetEnews';document.form1.action='SetEnews.php';document.form1.target='';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="reset" name="Submit2" value=" 重置 "></div>
 <!--[if lte IE 6]>
 <div class="floatsub"><input type="submit" name="Submit" value="提 交" onClick="document.form1.enews.value='SetEnews';document.form1.action='SetEnews.php';document.form1.target='';" class="shezhi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" name="Submit2" value="重 置" class="chongzhi"></div>
 <![endif]-->
 </div>
</div>
 </form>
 <div class="clear"></div>
<?=$hiddenfileserver?>
</div>
</body>
</html>
