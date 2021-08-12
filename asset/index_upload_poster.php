
<hr/>
<div class="row">
    <div class="col-12">
    <h5 class='card-title text-info'>Загрузить файл постера для публикации онлайн (только pdf) <?=$pdfIcon?></h5>
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