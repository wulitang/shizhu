<?php
require("../../class/connect.php");
require("../../class/q_functions.php");
require("../../class/db_sql.php");
require("../../member/class/user.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
eCheckCloseMods('member');//关闭模块
$user=islogin();
$r=ReturnUserInfo($user[userid]);
$addr=$empire->fetch1("select * from {$dbtbpre}enewsmemberadd where userid='$user[userid]' limit 1");
//生成头像
$rs = array();
$userid=$addr[userid];
$username=$user[username];
$filepass=0;

$result = array();
$result['success'] = false;
$msg = '';


//上传目录
$dir = ECMS_PATH."d/file/avator";
if (!is_dir($dir )) mkdir($dir ); 
$avatars = array("__avatar1");
$avatars_length = count($avatars);
for ( $i = 0; $i < $avatars_length; $i++ )
{ 
	$avatar = $_FILES[$avatars[$i]];
	$avatar_number = $i + 1;
	if ( $avatar['error'] > 0 )
	{
		$msg .= $avatar['error'];
	}
	else
	{
		$savePath = $dir."/user".$userid."_avator.jpg";
		move_uploaded_file($avatar["tmp_name"], $savePath);
    //写入数据库
		$avatorurl="/d/file/avator/user".$userid."_avator.jpg";
    $result['avatarUrls'][$i] = $avatorurl;
		$filename="user".$userid."_avator.jpg";
		$empire->query("update {$dbtbpre}enewsmemberadd set userpic='$avatorurl' where userid='$userid'");
		$filesize=abs(filesize($dir.'/user'.$userid.'_avator.jpg'));
		$tx=$empire->fetch1("select * from {$dbtbpre}enewsfile_member where filename='$filename' limit 1");
		$filetime=strtotime("now");
		if (empty($tx[fileid])){
			$empire->query("insert into {$dbtbpre}enewsfile_member(filename,filesize,adduser,path,filetime,no,type,id,cjid,onclick,fpath,modtype) values('$filename','$filesize','[EditInfo]$username','avator','$filetime','Member[userpic]','1','1','0','0','2','6')"); 
			} else{
			$empire->query("update {$dbtbpre}enewsfile_member set filesize='$filesize',filetime='$filetime' where filename='$filename'"); 
		}
		$success_num++;
	}
} 

$result['msg'] = $msg;
if ($success_num > 0)
{
	$result['success'] = true;
}
print json_encode($result);

function toVirtualPath($physicalPpath)
{
	$virtualPath = str_replace($_SERVER['DOCUMENT_ROOT'], "", $physicalPpath);
	$virtualPath = str_replace("\\", "/", $virtualPath);
	return $virtualPath;
}
?>
