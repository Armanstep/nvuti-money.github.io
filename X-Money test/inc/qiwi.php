<?php
require("bd.php");

$qiwi_num = "+79153114610";
$qiwi_pass = "395zed674qqqQ";
$qiwi_check = "1";//просто переменная для взаимодействия файлов!

$sql_select = "SELECT * FROM rvuti_users";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
do
{
	$time = time();
	if($time > $row['online_time'])
	{
$update_sql1 = "Update rvuti_users set online='0' WHERE id=".$row['id'];
mysql_query($update_sql1) or die("" . mysql_error());
	}
}
while($row = mysql_fetch_array($result));
	class qiwiPaymentClass
	{
		public function __construct ()
	        {
			$this -> curl = curl_init ();
			$this -> fileCookies = 'cookies.txt';
			$this -> ticket = '';
		}
		public function auth ($login, $password)
		{
			curl_setopt ($this -> curl, CURLOPT_URL, 'https://sso.qiwi.com/cas/tgts');
			curl_setopt ($this -> curl, CURLOPT_HEADER, 0);
			curl_setopt ($this -> curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($this -> curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($this -> curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($this -> curl, CURLOPT_COOKIEFILE, $this -> fileCookies);
			curl_setopt ($this -> curl, CURLOPT_COOKIEJAR, $this -> fileCookies);
			curl_setopt ($this -> curl, CURLOPT_POST, 1);
			curl_setopt ($this -> curl, CURLOPT_POSTFIELDS, '{"login":"'.$login.'","password":"'.$password.'"}');
			curl_setopt ($this -> curl, CURLOPT_HTTPHEADER,
				array (
					'User-Agent Mozilla/5.0 (Windows NT 5.1; rv:38.0) Gecko/20100101 Firefox/38.0',
					'Accept: application/vnd.qiwi.sso-v1+json',
					'Accept-Language: ru;q=0.8,en-US;q=0.6,en;q=0.4',
					'Accept-Encoding: gzip, deflate',
					'Content-Type: application/json; charset=UTF-8',
					'Referer: https://qiwi.com/',
					'Origin: https://qiwi.com',
					'Connection: keep-alive',
					'Pragma: no-cache',
					'Cache-Control: no-cache'
				)
			);
			$cont = curl_exec ($this -> curl);
			$jsonCont = json_decode ($cont);
			if (!isset ($jsonCont -> entity -> ticket))
			{
				return 0;
			}
			$this -> ticket = $jsonCont -> entity -> ticket;
			curl_setopt ($this -> curl, CURLOPT_URL, 'https://sso.qiwi.com/cas/sts');
			curl_setopt ($this -> curl, CURLOPT_POSTFIELDS, '{"ticket":"'.$this -> ticket.'","service":"https://qiwi.com/j_spring_cas_security_check"}');
			curl_setopt ($this -> curl, CURLOPT_HTTPHEADER,
				array (
					'User-Agent Mozilla/5.0 (Windows NT 5.1; rv:38.0) Gecko/20100101 Firefox/38.0',
					'Accept: application/vnd.qiwi.sso-v1+json',
					'Accept-Language: ru;q=0.8,en-US;q=0.6,en;q=0.4',
					'Accept-Encoding: deflate',
					'Content-Type: application/json; charset=UTF-8',
					'Referer: https://sso.qiwi.com/app/proxy?v=1',
					'Connection: keep-alive',
					'Pragma: no-cache',
					'Cache-Control: no-cache'
				)
			);
			$cont = curl_exec ($this -> curl);
			$jsonCont = json_decode ($cont);
			if (!isset ($jsonCont -> entity -> ticket))
			{
				return 0;
			}
			$this -> ticket = $jsonCont -> entity -> ticket;
			curl_setopt ($this -> curl, CURLOPT_URL, 'https://qiwi.com/j_spring_cas_security_check?ticket='.$this -> ticket);
			curl_setopt ($this -> curl, CURLOPT_POST, 0);
			curl_setopt ($this -> curl, CURLOPT_HTTPHEADER,
				array (
					'User-Agent Mozilla/5.0 (Windows NT 5.1; rv:38.0) Gecko/20100101 Firefox/38.0',
					'Accept: application/json, text/javascript, */*; q=0.01',
					'Accept-Language: en-US,en;q=0.5',
					'Accept-Encoding: deflate',
					'X-Requested-With: XMLHttpRequest',
					'Referer https://qiwi.com/',
					'Connection: keep-alive'
				)
			);
			$cont = curl_exec ($this -> curl);
			$jsonCont = json_decode ($cont);
			if (!isset ($jsonCont -> code -> value))
			{
				return 0;
			}
			return 1;
		}
		//1 today 2 yesterday 3 week
		public function history ($type)
		{
			curl_setopt ($this -> curl, CURLOPT_URL, 'https://qiwi.com/user/report/list.action');
			curl_setopt ($this -> curl, CURLOPT_POST, 1);
			curl_setopt ($this -> curl, CURLOPT_POSTFIELDS, 'type='.$type);
			curl_setopt ($this -> curl, CURLOPT_HTTPHEADER,
				array (
					'User-Agent Mozilla/5.0 (Windows NT 5.1; rv:38.0) Gecko/20100101 Firefox/38.0',
					'Accept:"text/html, */*; q=0.01"',
					'Accept-Language:"en-US,en;q=0.5"',
					'Accept-Encoding: deflate',
					'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
					'X-Requested-With: XMLHttpRequest',
					'Referer: https://qiwi.com/report/list.action?type='.$type,
					'Connection: keep-alive'
				)
			);
			$cont = curl_exec ($this -> curl);
			if (preg_match_all ('|<div class="DateWithTransaction">.*<span class="date">(.*)</span>.*<span class="time">(.*)</span>.*<div class="transaction">(.*)</div>.*</div>|Usi', $cont, $dateWithTransaction) &&
			preg_match_all ('|<div class="IncomeWithExpend (.*)">.*<div class="cash">(.*)</div>|Usi', $cont, $incomeWithExpend) &&
			preg_match_all ('|<div class="ProvWithComment">.*<div class="provider">.*<span class="opNumber">(.*)</span>.*</div>.*<div class="comment">(.*)</div>|Usi', $cont, $provWithComment))
			{
				for ($i = 0; $i< 10; $i++)
				{
					if($incomeWithExpend [1][$i] == "income")
					{
						$transaction = trim ($dateWithTransaction [3][$i]);
						
						 $sql_select1 = "SELECT COUNT(*) FROM `rvuti_payments` WHERE transaction='".trim ($dateWithTransaction [3][$i])."'";
$result1 = mysql_query($sql_select1);
$row1 = mysql_fetch_array($result1);
if($row1['COUNT(*)'] == 0)
{
	$idmoney = str_replace('-RvutiPay', '', $provWithComment [2][$i]);
		if (is_numeric($idmoney))
		{
									 $sql_select1 = "SELECT COUNT(*) FROM `rvuti_payments` WHERE transaction='".trim ($dateWithTransaction [3][$i])."'";
$result1 = mysql_query($sql_select1);
$row1 = mysql_fetch_array($result1);
if($row1['COUNT(*)'] == 0)
{
		$sql_select = "SELECT * FROM rvuti_users WHERE id='$idmoney'";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
if($row)
{	
$balance = $row['balance'];
$ref = $row['referer'];
}
	$suma = trim (str_replace('руб.',' ',$incomeWithExpend [2][$i]));
	$qiwi = trim ($provWithComment [1][$i]);
	$transaction = trim ($dateWithTransaction [3][$i]);
	$data1 = trim ($dateWithTransaction [1][$i]);
	$data2 = trim ($dateWithTransaction [2][$i]);
	$sumaref = ($suma / 100) * 10;
if($ref >= 1)
{	
			$sql_select = "SELECT * FROM rvuti_users WHERE id='$ref'";
$result = mysql_query($sql_select);
$row = mysql_fetch_array($result);
if($row)
{	
$balanceref = $row['balance'];
$balancerefs = $balanceref + $sumaref;
$update_sql1 = "Update rvuti_users set balance='$balancerefs' WHERE id='$ref'";
    mysql_query($update_sql1) or die("" . mysql_error());
}
}

	$data = "$data1 $data2";
	$suma = str_replace(',', '.', $suma);
$balancenew = $row['balance'] + $suma;
$update_sql1 = "Update rvuti_users set balance='$balancenew' WHERE id='$idmoney'";
    mysql_query($update_sql1) or die("" . mysql_error());
			$insert_sql1 = "
			INSERT INTO `rvuti_payments` (`user_id`, `suma`, `data`, `qiwi`, `transaction`) 
			VALUES ('{$idmoney}', '{$suma}', '{$data}', '{$qiwi}', '{$transaction}')
			";
mysql_query($insert_sql1);
}

}
}
					}
					$balancerefs = "";
$balanceref = "";
$sumaref = "";
$ref = "";
					$balance = "";
					$balancenew = "";
					$idmoney = "";
					$suma = "";
	$qiwi = "";
	$transaction = "";
	$data1 = "";
	$data2 = "";
	$data = "";
				}
				//return $history;
			} else {
				//return 0;
			}
		}
	}
	$qiwi = new qiwiPaymentClass;
	if ($qiwi -> auth ("79153114610", "395zed674qqqQ") == 1)
	{
		//echo 'authorized!';
		//echo json_encode($qiwi -> history (3));
	} else {
		//echo 'error!';
	}

?>