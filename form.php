<?
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(isset($_POST["submit"])) exit; // защита от попытки подтвердить форму без js

include_once "config.php";

$today = date('Y-m-d');

$_SESSION['backdoor'] = (!empty($_GET['bd']) && in_array($_GET['bd'], $bd)); //bd array set in config.php

if ($today > DEADLINE && !$_SESSION['backdoor']) {
    echo "Registration finished";
    exit;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>PhysicA.SPb/<?=$YEAR?></title>
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/shutter.css">
    <link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.min.css">

    <style>
      #wrapper {min-width:30em; max-width: 60em; margin:auto}
      datalist {display: none;overflow: auto; max-height: 100px}
    </style>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/jquery.maskedinput.min.js"></script>

    <script>
      var userExist="<?=$LANG['user_exist']?>";
      var SITE="<?=$SITE?>";
      var this_ip="<?=$_SERVER['REMOTE_ADDR']?>";
    </script>

    <script src="./js/form.js"></script>

  </head>

  <body>
  <div id="wrapper">
  <p class="text-right"><?include "asset/lang_swithcher.php";?> </p>
  <h1 class="font-weight-light"><?=$LANG['reg_conference']?>/<?=$YEAR?></h1>

  <h3 class="text-center display-4"><?=$LANG['conference']?>/<?=$YEAR?></h3>

     <form enctype="multipart/form-data" method="post"  name="userdata" id="userdata"> <!--action="reg.php" -->
      <div class="form-group row">

        <div class="col-4">
    <!-- <label for="fname" class="col-form-label">Фамилия</label>  -->
          <input id="firstname" name="familyname" placeholder="<?=$LANG['familyname']?>" type="text" maxlength="32" autocomplete="family-name" required="required" class="form-control">
        </div>

        <div class="col-4">
    <!-- <label for="name" class="col-form-label">Имя</label>  -->
          <input id="lastname" name="givenname" placeholder="<?=$LANG['givenname']?>" type="text" maxlength="32" autocomplete="given-name" required="required" class="form-control">
        </div>

        <div class="col-4">
    <!-- <label for="additional-name" class=" col-form-label">Отчество</label>  -->
          <input id="parentname" name="parentname" placeholder="<?=$LANG['parentname']?>"  maxlength="32" autocomplete="additional-name" type="text"  class="form-control">
        </div>

    <div id="passportHelpBlock" class="col-12 form-text text-danger font-italic d-flex justify-content-center"><?=$LANG['Name_as_in_passport']?></div>

      </div>

      <div class="form-group row">
        <label for="gender" class="col-3 col-form-label"><?=$LANG['gender']?></label>
        <div class="col-3">
          <?=$LANG['gender_m']?> <input name="gender"  type="radio" value="m" required="required"  >
          <?=$LANG['gender_f']?> <input name="gender"  type="radio" value="w" required="required" >
        </div>
      </div>

      <div class="form-group row">
        <label for="bday" class="col-3 col-form-label"><?=$LANG['birthday']?></label>
        <div class="col-3">
          <input id="bday" name="birthday"  type="date" required="required" data-format=""
          pattern="^\d{2}.\d{2}.\d{4}$" placeholder="dd.mm.yyyy"
          autocomplete="birth-day" class="form-control"
          maxlength="10"
          >
        </div>
      </div>
      <div class="form-group row">
        <label for="email" class="col-3 col-form-label"><?=$LANG['email']?></label>
        <div class="col-9">
          <input id="email" name="email" placeholder="email" type="email" data-format="^[a-z0-9_.-]+@([a-z0-9]+.)+[a-z]{2,6}$" required="required" class="form-control">
          <div id="emailAlertBlock" class="col-12 form-text text-danger d-flex justify-content-center"></div>
        </div>
      </div>
      <div class="form-group row">
        <label for="phone" class="col-3 col-form-label"><?=$LANG['phone']?></label>
        <div class="col-9">
          <input id="phone" name="phone" placeholder="+7 999 999-99-99" type="tel"  required="required" class="form-control" maxlength="14">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-3 col-form-label" for="country"><?=$LANG['country']?></label>
        <div class="col-9">
          <input id="country" name="country" type="text" required="required" autocomplete="country-name" maxlength="64" placeholder="<?=$LANG['country_ph']?>" class="form-control">
        </div>
      </div>
      <div class="form-group row">
        <label for="city" class="col-3 col-form-label"><?=$LANG['city']?></label>
        <div class="col-9">
          <input id="city" name="city" type="text" required="required" class="form-control" autocomplete="city address-level2"  maxlength="64"  placeholder="<?=$LANG['city_ph']?>" list="city_list">
          <datalist id="city_list">
<?
  include_once "asset/city_list_" . $LANG['language'] . ".php";
?>
          </datalist>
        </div>
      </div>
      <div class="form-group row">
        <label for="affiliation" class="col-3 col-form-label"><?=$LANG['affiliation']?></label>
        <div class="col-9">
          <input id="company" name="company" type="text" required="required" placeholder="<?=$LANG['affiliation_ph']?>"  maxlength="255" autocomplete="organization" class="form-control" list="affiliation_list">
          <datalist id="affiliation_list">
<?
  include_once "asset/company_list_" . $LANG['language'] . ".php";
?>
      </datalist>
        </div>
      </div>

      <div class="form-group row">
        <label for="position" class="col-3 col-form-label"><?=$LANG['position']?></label>
        <div class="col-9">
          <input id="position" name="position" type="text" aria-describedby="positionHelpBlock" placeholder="<?=$LANG['position_ph']?>"  maxlength="32" required="required" class="form-control" list="position_list">
          <datalist id="position_list">
          <?=$LANG['position_list']?>
          </datalist>
        </div>
      </div>
      <div class="form-group row">
        <label for="degree" class="col-3 col-form-label"><?=$LANG['degree']?></label>
        <div class="col-9">
          <input id="degree" name="degree" type="text" aria-describedby="degreeHelpBlock" maxlength="64" class="form-control">
          <span id="degreeHelpBlock" class="form-text text-muted"><?=$LANG['degree_help']?></span>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-3"></div>
        <div class="col-9">
          <div class="custom-control custom-checkbox custom-control-inline">
            <input name="ioffe_pass" id="ioffe_pass" type="checkbox" class="custom-control-input" value="Есть пропуск ФТИ">
            <label for="ioffe_pass" class="custom-control-label"><?=$LANG['ioffe_pass']?></label>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-3"></div>
        <div class="col-9">
          <div class="custom-control custom-checkbox custom-control-inline">
            <input name="foreign" id="foreign" type="checkbox" aria-describedby="foreignHelpBlock" class="custom-control-input" value="foreign">
            <label for="foreign" class="custom-control-label"><?=$LANG['foreign']?></label>
          </div>
          <span id="foreignHelpBlock" class="form-text text-muted"><?=$LANG['foreign_help']?></span>
        </div>
      </div>
      <!-- <div class="form-group row">
        <label for="passport" class="col-3 col-form-label">Паспорт</label>
        <div class="col-9">
          <textarea id="passport" name="passport" cols="40" rows="5" aria-describedby="passportHelpBlock" required="required" disabled="disabled" class="form-control"></textarea>
          <span id="passportHelpBlock" class="form-text text-warning">Необходим для прохода не территорию ФТИ.<br>Заполняется после проведения экспертной оценки тезисов.</span>
        </div>
      </div> -->
      <div class="form-group row">
        <label for="show_hide_password" class="col-3 col-form-label"><?=$LANG['password']?></label>
        <div class="col-6">
          <div class="input-group" id="show_hide_password">
            <input id="password" name="password" placeholder="Password" type="password" autocomplete="new-password" class="form-control">
            <div class="input-group-append">
              <div class="input-group-text">
                <a href="" class="text-dark"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      </div>

    </div>
      <div class="form-group row">
      <div id="submitAlert" class="col-12 form-text text-danger d-flex justify-content-center"></div>
        <div class="offset-5 col-7">
          <img id="loader" src="img/ajax-loader.gif">
          <button id="submit" name="submit" type="submit" class="btn btn-primary"><?=$LANG['submit']?></button>
        </div>
      </div>
    </form>
  </div>
  
<div id="shutter">
  <div id="popup"><img src="./img/ajax-loader.gif"  style="border:0px"/>
  <br>Please wait.<br><i>Your data is processed</i></div>
</div>
<!-- Yandex.Metrika counter -->
<!-- <script src="js/yandex.metrika.js" type="text/javascript" ></script> -->
<!-- <noscript><div><img src="https://mc.yandex.ru/watch/30705528" style="position:absolute; left:-9999px;" alt="" /></div></noscript> -->
<!-- /Yandex.Metrika counter -->
  </body>
</html>
