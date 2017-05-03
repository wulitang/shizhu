<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: taoqili
     * Date: 12-1-16
     * Time: 上午11:44
     * To change this template use File | Settings | File Templates.
     */
    header("Content-Type: text/html; charset=utf-8");
    error_reporting( E_ERROR | E_WARNING );
	require('../../../class/connect.php'); //引入数据库配置文件和公共函数文件
    require('../../../class/db_sql.php'); //引入数据库操作文件
    require("../../../data/dbcache/class.php"); 
	require("../../../class/functions.php");
	$link=db_connect(); //连接MYSQL
	$empire=new mysqlquery();
	$classid=htmlspecialchars($_POST["classid"], ENT_QUOTES);
	$isadmin=getcvar('loginlevel',1);
	$fstb=$public_r['filedeftb']; //附件表的id
	if($isadmin=='1')
	{
		$username=getcvar('loginusername',1);
	} else {
		$username ='[Member]'.getcvar('mlusername');
	}
	$fspath=ReturnFileSavePath($classid);
	$webpath=$public_r[newsurl];
	$path=ECMS_PATH.$fspath['filepath'];
     if ($isadmin!='0') {
		$fp="ue_separate_ue";
		$sql=$empire->query("select * from {$dbtbpre}enewsfile_".$fstb." where adduser='$username'");
		while($r=$empire->fetch($sql))
		{
			$fspath=ReturnFileSavePath($r[classid],$r[fpath]);
			$filepath=$r[path].'/';
			$file=$fspath['fileurl'].$filepath.$r[filename];
			$str.=$file.$fp;
		}
        echo $str;
    }
db_close();
$empire=null;
?>
