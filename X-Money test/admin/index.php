<?php
mysql_connect("localhost", "", "")//параметры в скобках ("хост", "имя пользователя", "пароль")
or die("<p>Ошибка подключения к базе данных! " . mysql_error() . "</p>");
mysql_select_db("")//параметр в скобках ("имя базы, с которой соединяемся")
 or die("<p>Ошибка выбора базы данных! ". mysql_error() . "</p>");
mysql_query("SET NAMES utf8");
$sid = $_COOKIE["sid"];
$sql_select = "SELECT * FROM rvuti_users WHERE hash='$sid'";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
if($row)
{
	$prava = $row['prava'];
	$login = $row['login'];
}
if($prava == 1)
{
?>
<html xml:lang="ru" xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Админка</title>
<link href="../files/st.css" rel="stylesheet" type="text/css">
<script src="../files/js.cookie.js" type="text/javascript"></script>



<style type="text/css">

html, body, #container {height: 100%; background-color:#fbfbfb;}
body > #container { height: auto; min-height: 100%; background-color:#fbfbfb; } 

#footer {
clear: both;
position: relative;
z-index: 10;
height: 3em;
margin-top: -3em;
} 

#content { padding-bottom: 3em; } 

</style>
</head>

<body>
<div id="container">
<div id="content">


<table style="margin-top: 20px;" align="center" width="884" border="0" cellspacing="0" cellpadding="0">


<tbody><tr><td colspan="3" align="right" height="50">
	
	<a href="/admin/index.php?act=payout" class="nav">Выплаты</a>
	<a href="/admin/index.php?act=payment" class="nav">Пополнения</a>
	<a href="/admin/index.php" class="nav">Статистика</a>
	
	<a href="/admin/index.php?act=users" class="nav">Пользователи</a>
	<span style="padding-left: 100px; padding-right: 10px;"><?php echo $login = ucfirst($row['login']); $login; ?></span>
	<a class="nav" onclick="Cookies.set('sid', '');location.href = '/';" title="Выход">Выход</a>
	</td></tr></tbody></table>

<table style="margin-top:20px;" align="center" width="884" border="0" cellspacing="0" cellpadding="0">



</table>
<?php
if($_GET['act'] == "user")
{
$user = $_GET['user_id'];
$sql_select = "SELECT * FROM rvuti_users WHERE id='$user'";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
$login = ucfirst($row['login']);
$ref = $row['referer'];
$idref = $ref;
$sql_select221 = "SELECT * FROM rvuti_users WHERE referer='$idref' ORDER BY `data_reg` DESC";
$result221 = mysql_query($sql_select221);
$row221 = mysql_fetch_array($result221);

$sql_select4 = "SELECT COUNT(*) FROM rvuti_users WHERE referer='$idref'";
$result4 = mysql_query($sql_select4);
$row4 = mysql_fetch_array($result4);	
$count = $row4['COUNT(*)'];
do
{
$sql_select423 = "SELECT SUM(suma) FROM rvuti_payments WHERE user_id=".$row221['id'];
$result423 = mysql_query($sql_select423);
$row423 = mysql_fetch_array($result423);
do
{   //$sumapey = $row423['SUM(suma)'];
	$sumapey = $sumapey + $row423['SUM(suma)'];
}
while($row423 = mysql_fetch_array($result423));

$sumapeys = ($sumapey / 100) * 10;
}
while($row221 = mysql_fetch_array($result221));

if($ref == '')
{
	$ref = "Нету реферера!";
	$refurl = "1";
}
?>
<table style="margin-top:20px;" align="center" width="884" border="0" cellspacing="0" cellpadding="0">

<tbody><tr><td><div class="zag2">Личная информация пользователя <strong><?php echo $login; ?></strong></div><table width="100%" border="0" cellspacing="0" cellpadding="5" class="tbl" style="border: 1px solid #ccc; border-radius: 4px; padding: 10px; background-color: #fff;">
			
			<tbody>
			
			<tr><td width="80" style="border-bottom:1px solid#f2f2f2;">Имя</td>
			<td style="border-bottom:1px solid#f2f2f2;"><?php echo $login; ?></td></tr>
			
			
			
			
			<tr><td width="80" style="border-bottom:1px solid#f2f2f2;">Рефералов</td>
			<td style="border-bottom:1px solid#f2f2f2;"><?php echo $count; ?></td></tr>	
						<tr><td width="80" style="border-bottom:1px solid#f2f2f2;">Доход от рефералов</td>
			<td style="border-bottom:1px solid#f2f2f2;"><?php echo $sumapey; ?></td></tr>	
			<tr><td width="80" style="border-bottom:1px solid#f2f2f2;">Реферер</td>
			<td style="border-bottom:1px solid#f2f2f2;"><a href="/admin/index.php?act=user&amp;user_id=<?php echo $refurl; ?>"><?php echo $ref; ?></a></td></tr>	
		    <tr><td width="80" style="border-bottom:1px solid#f2f2f2;">Баланс:</td>
			<td style="border-bottom:1px solid#f2f2f2;"><b><?php echo $row['balance']; ?></b> руб</td></tr>		    
			
			</tbody></table><br></td></tr>




</tbody></table>
<?php
}
?>
<?php
if($_GET['act'] == "users")
{
?>
<table class="tbl" align="center" style="border: 1px solid #cccccc; border-radius: 4px; padding: 10px; margin-top: 5px; font-size: 15px; background-color: #fff;" width="875" cellpadding="5" cellspacing="0">

<tbody><tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">ID</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Логин</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Пароль</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Почта</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Баланс</td><td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Дата Регистрации</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">IP[REG]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">IP</td>
</tr>

<?php
$sql_select = "SELECT * FROM rvuti_users ORDER BY `id` DESC";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
do
{
$login = ucfirst($row['login']);
	echo <<<HERE
<tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="50">$row[id]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300"><a href="/admin/index.php?act=user&amp;user_id=$row[id]">$login</a></td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="200">$row[password]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="200">$row[email]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300">$row[balance]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300">$row[data_reg]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300">$row[ip_reg]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300">$row[ip]</td>
</tr>
HERE;
}
while($row = mysql_fetch_array($result));
?>
</tbody></table>
<?php
}
?>
<?php
if($_GET['act'] == "")
{
	$sql_select = "SELECT MAX(id) FROM rvuti_games";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);

$games = $row['MAX(id)'];

	$sql_select = "SELECT SUM(balance) FROM rvuti_users";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
$summoney = $row['SUM(balance)'];
$summoney = round($summoney,2);

	$sql_select = "SELECT SUM(suma) FROM rvuti_payout";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);

$sumpayout = $row['SUM(suma)'];
$sumpayout = round($sumpayout,2);

	$sql_select = "SELECT SUM(suma) FROM rvuti_payments";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);

$sumpayments = $row['SUM(suma)'];
$sumpayments = round($sumpayments,2);
	$sql_select = "SELECT COUNT(*) FROM rvuti_users WHERE balance > 10";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);

$cont = $row['COUNT(*)'];

?>
<table class="tbl" align="center" style="border: 1px solid #ccc; border-radius: 4px; padding: 10px; background-color: #fff;" width="875" cellpadding="5" cellspacing="0"><tbody><tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Параметр</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Значение</td></tr>
<tr>
</tr>
<tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">Кол-во игр</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300"><?php echo $games; ?></td>
</tr>
<tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">Денег на счетах</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300"><?php echo $summoney; ?> руб</td>
</tr>
<tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">Пользователей с балансом &gt;=10 руб</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300"><?php echo $cont; ?></td>
</tr>
<tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">Выплат на сумму</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300"><?php echo $sumpayout; ?> руб</td>
</tr>
<tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">Пополнений на сумму</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300"><?php echo $sumpayments; ?> руб</td>
</tr>
</tbody></table>
<?php
}
?>
<?php
if($_GET['act'] == "payment")
{
?>
<table class="tbl" align="center" style="border: 1px solid #cccccc; border-radius: 4px; padding: 10px; margin-top: 5px; font-size: 15px; background-color: #fff;" width="875" cellpadding="5" cellspacing="0">

<tbody><tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">ID</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Сумма</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Пользователь</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Кошелек</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Транзакция</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Дата</td>
</tr>
<?php
$sql_select = "SELECT * FROM rvuti_payments ORDER BY `id` DESC";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
do
{
		$sql_select1 = "SELECT * FROM rvuti_users WHERE id='".$row['user_id']."'";
$result1 = mysql_query($sql_select1);
$row1 = mysql_fetch_array($result1);
$login = ucfirst($row1['login']);
	echo <<<HERE
	<tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="50">$row[id]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="200">$row[suma] руб</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300"><a href="/admin/index.php?act=user&amp;user_id=$row[user_id]">$login</a></td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="200">$row[qiwi]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="200">$row[transaction]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300">$row[data]</td>
</tr>
HERE;
}
while($row = mysql_fetch_array($result));
?>
</tbody></table>
<?php
}
?>
<?php
if($_GET['act'] == "payout")
{
?>
<table align="center" style="border: 1px solid #cccccc; border-radius: 4px; padding: 10px; margin-top: 5px; font-size: 15px; background-color: #fff;" width="875" class="tbl" cellpadding="5" cellspacing="0">

<tbody><tr>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">ID</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Кошелек</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Сумма</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Пользователь</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Статус</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">Дата</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; border-right: 1px solid #cccccc;" bgcolor="#ffffff" class="sm2" align="center">IP</td>
</tr>
<?php
$sql_select = "SELECT * FROM rvuti_payout ORDER BY `id` DESC";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
do
{
		$sql_select1 = "SELECT * FROM rvuti_users WHERE id=".$row['user_id'];
$result1 = mysql_query($sql_select1);
$row1 = mysql_fetch_array($result1);
$login = ucfirst($row1['login']);
	echo <<<HERE
	<tr>
	<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="50">$row[id]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="200">$row[qiwi]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">$row[suma] руб</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300"><b><a href="/admin/index.php?act=user&id=$row[user_id]">$login</a></b></td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">$row[status]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc;" align="center" width="300">$row[data]</td>
<td style="border-bottom: 1px solid #cccccc; border-left: 1px solid #cccccc; border-right: 1px solid #cccccc;" align="center" width="300">$row[ip]</td>
</tr>
HERE;
}
while($row = mysql_fetch_array($result));
?>
</tbody></table>
<?php
}
?>



</div>
</div>


</body></html>
<?php
}
else
{
include("http://rvuti.cf/error404.php");
}
?>

	<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
					<script src="assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
					<script src="assets/plugins/modernizr.custom.js" type="text/javascript"></script>
					<script src="assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
					<script src="assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
					<script src="assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
					<script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
					<script src="assets/plugins/jquery-bez/jquery.bez.min.js"></script>
					<script src="assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
					<script src="assets/plugins/jquery-actual/jquery.actual.min.js"></script>
					<script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
					<script type="text/javascript" src="assets/plugins/bootstrap-select2/select2.min.js"></script>
					<script type="text/javascript" src="assets/plugins/classie/classie.js"></script>
					<script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/lib/d3.v3.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/nv.d3.min.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/src/utils.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/src/tooltip.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/src/interactiveLayer.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/src/models/axis.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/src/models/line.js" type="text/javascript"></script>
					<script src="assets/plugins/nvd3/src/models/lineWithFocusChart.js" type="text/javascript"></script>
					<script src="assets/plugins/mapplic/js/hammer.js"></script>
					<script src="assets/plugins/mapplic/js/jquery.mousewheel.js"></script>
					<script src="assets/js/datatables.min.js"></script>
					<script src="assets/plugins/mapplic/js/mapplic.js"></script>
					<script src="assets/plugins/jquery-metrojs/MetroJs.min.js" type="text/javascript"></script>
					<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
					<script src="assets/js/main.js" type="text/javascript"></script>
					<script src="assets/js/main-modals.js" type="text/javascript"></script>
					<script src="assets/js/notifications.js" type="text/javascript"></script>
					<script src="assets/plugins/skycons/skycons.js" type="text/javascript"></script>
					<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
					<!-- END VENDOR JS -->
					<!-- BEGIN CORE TEMPLATE JS -->
					<script src="pages/js/pages.min.js"></script>
					<!-- END CORE TEMPLATE JS -->
					<!-- BEGIN PAGE LEVEL JS -->
					<script src="assets/js/dashboard.js" type="text/javascript"></script>
					<script src="assets/js/scripts.js" type="text/javascript"></script>
					<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  				<script>tinymce.init({ selector:'.tinymce' });</script>