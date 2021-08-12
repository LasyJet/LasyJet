<div class='card-header'><?=$LANG['account']?></div>
<div class='card-body'>
<?=account_data($dbh)?>
<div class="col p-0" >
    <form name='chPasswordFrom'>
    <div class="input-group" id="passwd_form">
    <input id="chPassword" type="password" class="col-11 border border-info " placeholder="click to change password"/>
        <span  class="eye-info text-dark"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
    </div>
    </form>
    <div id="pwdButtonGrp" tabindex="1">
        <button type="button" id="changePwd" class="btn-sm btn-success">Change</button>
        <button type="button" id="cancelPwd" class="btn-sm btn-secondary">Cancel</button>
        <p class='saved text-info'>Password saved</p>
    </div>
</div>
</div>