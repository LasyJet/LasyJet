<hr/>

<div class="row">
    <div class="col-12">
    <h5 class='card-title text-info'><?=$LANG['lift_upload']?> <?=$pdfIcon?>
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