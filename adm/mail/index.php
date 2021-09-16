<?php
include_once("../../config.php");

$orgComiteeSign="С уважением,\nОргкомитет конференции ФизикА.СПб/".YEAR;
$textform_example="\nЗдравствуйте %speaker%!
Тра-ля- ля
\nВаша работа %thesis_title%
\nУникальная ссылка на тезисы %thesislink%\n------\n".$orgComiteeSign;
/*
### Скрипт почтовой рассылки ###

# Исходные данные #

- группа рассылки (accepted, rejected, oral, poster ...)
- или id персоны (здесь или в отдельном скрипте)


# Что сделать #

- создать поля тема (subject) и текст письма (body) с тегами (%name%, %thesis_title%, ...)
- выбрать адресатов (thesis_id, speaker_email, ФИО, Заголовок тезисоы)
- в цикле по каждоому адресату:
	- транслировать теги и записать в таблицу YEAR_messages c id Тезиса
	- отправить письмо адресату черезз ajax-запрос


 */

/* 
# куски кода из других программ ../2019/*
include_once("../classes/phpmailer/PHPMailer.php");

$speaker=strip_tags($speaker);
$body=$_SESSION['msg_tpl'];
$link="http://reg.physica.spb.ru/thesis.php?uid=".$uid;
$link="<a href='$link'>$link</a>";
$search =array("%speaker%","%thesis_title%","%thesislink%");
$replace=array($speaker,"&laquo;".$thesis_title."&raquo;",$link);
$body=str_ireplace($search,$replace,$body);

 if (smtp_mail($to, $subj, $msg))
	{
		$now=date("Y-m-d H:i:s");
		$sql="UPDATE {$table} SET `lastmailed`='$now' WHERE `uid`='$uid'";
		if(mysql_query($sql))
			write_log("\n\nMail to $to \n----\n was sent at $now\n-------------\n".strip_tags($msg));
	}
	else write_log("\n ERROR> $now : Мail to $to did not send");
*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">

    <title>Mail service::PhysicA.SPB</title>

    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <script src="https://use.fontawesome.com/7b07b4d79c.js"></script>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="email.css" rel="stylesheet">

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/autoresize.jquery.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="email.js"></script>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-sm-12 border-red">
                <h1>Рассылка участникам конференции Физика.СПб/
                    <? echo YEAR?>
                </h1>
            </div>
        </div>

        <form id="mailform" name="mailform" enctype="multipart/form-data" method="post"  >
            <div class="row">
                <div class="col-lg-9 col-sm-12 border-red">
                    <div class="form-group ">
                        <legend for="subject" class="text-primary">Тема</legend>
                        <input type="text" class="form-control" name="subject" id="subject" value='PhysicA.SPb/<? echo YEAR?>' />
                    </div>
                    <div class="form-group">
                        <legend  class="text-primary">Кому:</legend>
                        <select type="checkbox" class="form-control" id="recipients" name="recipients">
                            <option value="-" value='none' > - </option>
                            <option value="all" value='all' selected="selected">ВСЕМ!</option>
                            <option value='accepted'>Принятым</option>
                            <option value='ioffe'>Принятым, ФТИ</option>
                            <option value='no_ioffe'>Принятым, кроме ФТИ</option>
                            <option value='oral'>Устным </option>
                            <option value='poster'>Стендовым </option>
                            <option value='invited'>Приглашенным </option>
                            <option value='rejected'>Отклонённым </option>
                            <option value='plagiat'>Плагиат!</option>
                        </select>
                    </div>
                    <div class="form-group flex-grow-1">
                        <legend for="message"  class="text-primary">Сообщение</legend>
                        <textarea name="message" class="form-control" id="message"
                            rows="10"><?=$textform_example?></textarea>
                    </div>
                    <div class="form-group text-center">
                        <button id="send" name="send" type="submit" class="btn btn-primary mb-2" disabled="disabled">Отправить</button>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-12 border-red">
                    <h1>Адресаты</h1>
                    <div class="col-12 p-0" id="recipients_list">
                    </div>
                </div>
            </div>
        </form>


    </div>
</body>

</div>
</body>