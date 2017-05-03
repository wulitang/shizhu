<?php
	/**
    yecha整合至帝国cms
	参数设置都与帝国整合.包括上传附件格式等等
     */
    header("Content-Type: text/html; charset=utf-8");
    error_reporting(E_ERROR | E_WARNING);
    include "Uploader.class.php";
    require('../../../class/connect.php'); //引入数据库配置文件和公共函数文件
    require('../../../class/db_sql.php'); //引入数据库操作文件
    require("../../../data/dbcache/class.php"); 
	require("../../../class/functions.php");
	$link=db_connect(); //连接MYSQL
	$empire=new mysqlquery();
	$classid=$_POST["classid"];
	$isadmin=getcvar('loginlevel',1);
	$pz=$empire->fetch1("select filetype,filesize,qaddtranimgtype,qaddtransize from {$dbtbpre}enewspublic");
	$fstb=$public_r['filedeftb']; //附件表的id
	if($isadmin=='1')
	{
		$username=getcvar('loginusername',1);
		$filesize=$pz['filesize'];
	} else {
		$username ='[Member]'.getcvar('mlusername');
		$filesize=$pz['qaddtransize'];
	}
    //上传图片框中的描述表单名称，
    $title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
    $path = htmlspecialchars($_POST['dir'], ENT_QUOTES);
	$r=$empire->fetch1("select classpath from {$dbtbpre}enewsclass where classid='".$classid."'");
	$filepath=$r['classpath']; //获取附件路径
    //上传配置
    $config = array(
        "savePath" => '../../../../d/file/'.$filepath, 
        "maxSize" => $filesize, //单位KB
        "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp"  ) //请手动设置上传的图片格式
    );
    //生成上传实例对象并完成上传
    $up = new Uploader("upfile", $config);

    /**
     * 得到上传文件所对应的各个参数,数组结构
     * array(
     *     "originalName" => "",   //原始文件名
     *     "name" => "",           //新文件名
     *     "url" => "",            //返回的地址
     *     "size" => "",           //文件大小
     *     "type" => "" ,          //文件类型
     *     "state" => ""           //上传状态，上传成功时必须返回"SUCCESS"
     * )
     */
    $info = $up->getFileInfo();

    /**
     * 向浏览器返回数据json数据
     * {
     *   'url'      :'a.jpg',   //保存后的文件路径
     *   'title'    :'hello',   //文件描述，对图片来说在前端会添加到title属性上
     *   'original' :'b.jpg',   //原始文件名
     *   'state'    :'SUCCESS'  //上传状态，成功时返回SUCCESS,其他任何值将原样返回至图片上传框中
     * }
     */
	//写入数据库
	$url=explode("d/file",$info["url"]);
	$picurl="/d/file".$url[1];
	$filetime=strtotime(date("Y-m-d"));
	$sql=$empire->query("insert into {$dbtbpre}enewsfile_".$fstb."(filename,filesize,adduser,path,filetime,classid,no,type,id,cjid) values('$info[name]','$info[size]','$username','".date("Y-m-d H:i:s")."','$filetime','$classid','$info[name]','0','$filetime','0')");
	if($sql)
	{
    echo "{'url':'" .$picurl. "','title':'" . $title . "','original':'" . $info["originalName"] . "','state':'" . $info["state"] . "'}";
	}

