<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>test</title>
<script type="text/javascript" src="/js/artDialog.js"></script>
<script type="text/javascript" src="/js/plugins/iframeTools.js"></script>
</head>

<body style="margin:0">
<button id="reload">刷新主页面</button>
<script>
// 刷新主页面
document.getElementById('reload').onclick = function () {
	art.dialog.data('iframeTools', '我知道你刷新了页面～哈哈'); // plugin.iframe.html可以收到
	var win = art.dialog.open.origin;//来源页面
	// 如果父页面重载或者关闭其子对话框全部会关闭
	win.location.reload();
	return false;
};
</script>
</body>
</html>
