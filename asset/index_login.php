<div class='row justify-content-md-center'>
<form enctype="multipart/form-data" method="post" name="login" id="login" ><!-- action="login.php" -->
	<?
    if(isset($_GET['success'])) {
            echo "<p class='m-4'>{$LANG['you_registered']}</p>";
        }

    if(date('Y-m-d')<=DEADLINE): //backdoor form.php?bd=element_from_config:$bd
    ?>
        <div class="col-12 text-center">
            <button type="button" class="btn btn-info m-4 btn-lg" onclick="window.location.assign('<?echo SITE?>/form.php?')"><?=$LANG['to_register']?></button>
        </div>
        <p class="text-center"><?=$LANG['or']?></p>
    <?
    else:
    ?>
        <div class="col-12 text-center">
            <p class="text-danger">
                <?
                echo $LANG['registeration_closed'];
                echo (regAnnouncedLater)?"<br>".$LANG['regAnnouncedLater']:"";
                ?>

            </p>
        </div>

    <?
    endif; // today < deadline
    ?>

    <h4 class="text-center"><?=$LANG['Login']?></h4>
    <div class="border border-muted p-4 m-1">
        <div class="form-group row mb-4">
            <label for="email">Email:</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" required >
        </div>
        <div class="form-group row bm-4" id="password-block">
            <input type='hidden' name='forgot' value=true disabled=disabled>
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd" name="password" placeholder="Enter password" autocomplete="new-password">
        </div>
    </div>
    <div class="col-12 text-center">
        <button id="submit" name="submit" type="submit" class="btn btn-success"><?=$LANG['Submit_enter']?></button>
        <p><a id="i_foret_password" href="#"><?=$LANG['forgot_password']?></a></p>
        <!-- <p><?=$infoIcon?> Функция восстановления пароля временно не работает.</br>Если вы забыли пароль пишите <a href="mailto:mail@physica.spb.ru?subject=I forgot password&body=Я забыл пароль!">mail@physica.spb.ru</a></p> -->
        <div id="infoBlock" class="alert alert-primary" role="alert"></div>
    </div>
</form>
</div>
