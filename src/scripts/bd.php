<?
$sender = "birthdaynotify@recnredit.ru";
$email = "akrotov@rencredit.ru";
$email = "#developers@rencredit.ru";
$url = "http://intranet.rccf.ru/bis/ad_birthday.php";
$f = @fopen($url, "r");
$str = fread($f, 2048);
fclose($f);
$str = iconv  ("utf-8", "windows-1251", $str);
$headers = "From: ".$sender . "\n".
            "Content-Type: text/html; charset=windows-1251\n".
            "X-Mailer: PHP/" . phpversion();
mail($email, "Дни рождения сотрудников ".date("d.m.Y"), $str, $headers);
echo "ok";
?>
