<?php
/*
echo '<pre>';
print_r($_SESSION['cart']);
echo '</pre>';
sleep(1);
if  ((strlen($_REQUEST['email'])>0)&&((strlen($_REQUEST['name'])>0)))
{
    $user = new UserIndex($docid, $_REQUEST['lng']);
    $password = $user->saverequest(fromutf($_POST['name']) , fromutf($_POST['email']));
    $message = "Уважаемый ".fromutf($_POST['name'])."!\n".
        "Благодарим Вас за интерес к нашему ресурсу\n".
        "Ваш логин -".fromutf($_POST['email'])." , пароль - ".$password."\n".
        "Вопросы и предложения Вы можете присылать на webmaster@timmson.ru \n";
    sendmail($_POST['email'], "Благодарим Вас за подписку", $message);
    sendmail("webmaster@timmson.ru", "Подписан новый пользователь - ".fromutf($_POST['name']), "");
    echo "Ваша завяка принята. Спасибо!";
} else {
    echo "Ваша завяка не принята. Спасибо!";
}*/
?>
