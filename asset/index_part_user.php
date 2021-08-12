<div class='row'>
    <div class="card border-primary col-12 col-md-3 mr-2 p-0">
        <div class='card-header'>
            <?=$LANG['account']?>
        </div>
        <div class='card-body'>
            <?=account_data($dbh)?>
                <div class="col p-0">
                    <div class="input-group" id="passwd_form">
                        <input id="chPassword" type="password" class="col-11 border border-info " placeholder="click to change password" />
                        <span class="eye-info text-dark"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                    </div>
                    <div id="pwdButtonGrp" tabindex="1">
                        <button type="button" id="changePwd" class="btn-sm btn-success">Change</button>
                        <button type="button" id="cancelPwd" class="btn-sm btn-secondary">Cancel</button>
                        <p class='saved text-info'>Password saved</p>
                    </div>
                </div>
        </div>
    </div>



    <? // right column ?>
    <div class="card border-primary col-12 col-md-8 p-0">
        
        <div class="card-header">
            <?=$LANG['information']?>
        </div>

        <div class="card-body">
            <?=info_block($dbh)['info']?>
                <div class="mt-3">
                <?=$LANG['SgnInfo']?>
                    <hr/>
                    <?
                        $accepted=array('accepted', 'poster','oral','invited');
                        $this_report_type=info_block($dbh)['report_type'];
                        $this_poster_file=info_block($dbh)['poster_file'];
                        $_SESSION['this_report_type']=$this_report_type;

                        if(in_array($this_report_type, $accepted)){
                            echo $LANG['ExtThesisInfo'];
                            echo "<hr/>";
                            $allow_if_poster_uploaded= ($this_report_type=='poster' && strlen($this_poster_file)>2 && $_SESSION['fee']!='---');
                            $allow_sertificate=($this_report_type=='oral' || $this_report_type=='invited' || $allow_if_poster_uploaded);
                            echo (accessInvitation)?$LANG['Invitation']:"";
                            echo (accessSertificate && $allow_sertificate)?"<hr/>".$LANG['Sertificate']:"";
                        }

                    ?>
                </div>

            <? if(accessLift && in_array($_SESSION['this_report_type'], ['poster','review'])): ?>
                <hr/>

                <div class="row">
                    <div class="col-12">
                        <h5 class='card-title text-info'>
                            <?=$LANG['lift_upload']?>
                                <?=$pdfIcon?>
                                    <span data-toggle="modal" data-target="#lift_about" style="cursor:help"><?=$infoIcon?></i></span></h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 pr-1"><input id="lift" type="file" name="lift" /></div>
                    <div class="col-6"><button id="liftupload" class="btn-sm btn-success">Upload</button></div>
                </div>

                <div class="row">
                    <div class="col-12 text-success text-center" id="lift_info">
                        <a href="<? echo SITE.getLift($dbh)?>">Your previous uploaded file <?=$imageIcon?></a>
                    </div>
                </div>
            <? endif; // загрузка презентации в лифте ?>


        <? if(in_array($_SESSION['this_report_type'], ['poster','review']) && AllowUploadPoster): ?>
            <hr/>
            <div class="row">
                <div class="col-12">
                    <h5 class='card-title text-info'>Загрузить файл постера для публикации онлайн (только pdf)
                        <?=$pdfIcon?>
                    </h5>
                </div>
            </div>

            <div class="row">
                <div class="col-6 pr-1"><input id="poster" type="file" name="poster" /></div>
                <div class="col-6"><button id="poster_upload" class="btn-sm btn-success">Upload</button></div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-success text-center " id="poster_info">
                    <a href="<? echo SITE.getPoster($dbh)?>">Your previous uploaded file <?=$imageIcon?></i></a>
                </div>
            </div>

            <hr/>
            <? endif;  // Загрузка постера ?> 

        <?  $passport=getPassport($dbh);
        #if($passport!="!Есть пропуск ФТИ"):
        ?>
        <!-- <h5  class="card-title">В ФТИ им. А.Ф. Иоффе действует пропускной режим.<br>
        Для организации прохода, пожалуйста, укажите ниже следующие данные:</h5> -->

                                    <!-- <p class="m-0 font-weight-light">Фамилия, имя, отчество на русском языке.</p>
        <p class="m-0 font-weight-light">Дата рождения, место рождения</p>
        <p class="m-0 font-weight-light">Серия и номер паспорта,</p>
        <p class="m-0 font-weight-light">Когда и кем выдан паспорт</p>
        <p class="m-0 font-weight-light">Адрес регистрации.</p>
        <p class="text-danger mt-2">Если ваш доклад представляет другой человек впишите его паспортные данные!</p>
        <p class="text-danger mt-2">Если у вас нет парспорта РФ оставтьте поле как есть и свяжитесь с Оргкомитетом.</p>
        <p class="mt-2">Если вы не можете приехать и будете участвовать онлайн,<br> нажмите на кнопку &laquo;участвую&nbsp;онлайн&raquo;</p> -->

        <!-- <div id="passport" tabindex="2" class="alert alert-warning mt-2	text-dark border border-danger" style="min-height:8rem"role="alert" contenteditable="true"> -->
        <!-- <div id="passport" tabindex="2" class="alert alert-warning mt-2	text-dark border border-danger" style="min-height:8rem"role="alert" contenteditable="false"> -->

        <!-- <p class='text-primary'><b>Приём паспортных данных для оформления пропуска завершен.</b> <br>Если вы не сообщили данные ранее, значит вы участвуете онлайн</p> -->
        <?#=$passport?>
        <!-- </div> -->

        <!-- <div class="row mt-2">
        <div class="col-2 "><button type="button" id="savePassport" class="btn-sm btn-success">Save</button></div>
        <div class="col-7 text-left" id="passport_info"></div>
        <div class="col-3 text-right"><button type="button" id="online" class="btn-sm btn-info">Участвую онлайн</button></div>
        </div> -->
        <? #endif;?>
        </div>
    </div>

</div>
<? if(info_block($dbh)['count'] < allowedThesisNum){
    echo '<div class="d-flex justify-content-center  mt-4">
        <button id="addThesises" type="button" class="btn btn-info">'.$LANG['addThesises'].'</button>
    </div>';
}
 

