<?php
//变量名,变量值,工具条模式,编辑器目录,高度,宽度
function ECMS_ShowEditorVar($varname,$varvalue,$toolbar='Default',$basepath='',$height='300',$width='100%'){
global $filepass,$classid;
if ($toolbar=='Basic'){
	echo '<script type="text/javascript" src="/e/extend/UE/ueditor.memberconfig.js"></script>
';
	} else {
	echo '<script type="text/javascript" src="/e/extend/UE/ueditor.config.js"></script>
';
}

echo '
<script type="text/javascript" src="/e/extend/UE/ueditor.all.js"></script>
<script id="UEditor" type="text/plain" name="'.$varname.'">'.$varvalue.'</script>
<script type=text/javascript>
		UE.getEditor("UEditor",{initialFrameHeight:'.$height.',initialFrameWidth:"'.$width.'",Ext:\'{"classid":"'.$classid.'","filepass":"'.$filepass.'"}\'})
</script>
';
}
?>