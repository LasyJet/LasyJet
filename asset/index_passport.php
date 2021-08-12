<h5  class="card-title">В ФТИ им. А.Ф. Иоффе действует пропускной режим.<br>
					Для организации прохода, пожалуйста, укажите ниже следующие данные:</h5>

<p class="m-0 font-weight-light">Фамилия, имя, отчество на русском языке.</p>
<p class="m-0 font-weight-light">Дата рождения, место рождения</p>
<p class="m-0 font-weight-light">Серия и номер паспорта,</p>
<p class="m-0 font-weight-light">Когда и кем выдан паспорт</p>
<p class="m-0 font-weight-light">Адрес регистрации.</p>
<p class="text-danger mt-2">Если ваш доклад представляет другой человек впишите его паспортные данные!</p>
<p class="text-danger mt-2">Если у вас нет парспорта РФ оставтьте поле как есть и свяжитесь с Оргкомитетом.</p>
<p class="mt-2">Если вы не можете приехать и будете участвовать онлайн,<br> нажмите на кнопку &laquo;участвую&nbsp;онлайн&raquo;</p>

<!-- <div id="passport" tabindex="2" class="alert alert-warning mt-2	text-dark border border-danger" style="min-height:8rem"role="alert" contenteditable="true"> -->
<div id="passport" tabindex="2" class="alert alert-warning mt-2	text-dark border border-danger" style="min-height:8rem"role="alert" contenteditable="false">
<p class='text-primary'><b>Приём паспортных данных для оформления пропуска завершен.</b> <br>Если вы не сообщили данные ранее, значит вы участвуете онлайн</p>
<?=$passport?>
</div>

<div class="row mt-2">
    <div class="col-2 "><button type="button" id="savePassport" class="btn-sm btn-success">Save</button></div>
    <div class="col-7 text-left" id="passport_info"></div>
    <div class="col-3 text-right"><button type="button" id="online" class="btn-sm btn-info">Участвую онлайн</button></div>
</div>