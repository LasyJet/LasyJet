


<h2 class="title"> <sup></sup> <?=$TH['title']?></h2>
<p class="thesis_number" title="<?=$TH['thesis_id']?>">
	<a href="<? echo SITE?>/thesis.php?id=<?=$TH['thesis_id']?>"><?=$TH['thesis_id']?></a>
</p>
<p class=' authors'  >
	<?
	echo "<span class='speaker'>".strip_tags($TH['speaker'],"<sup>")."</span>";
	echo (strlen($TH['coauthors'])>2)?", ":"";
	echo strip_tags($TH['coauthors'],"<sup>");
	?>
</p>

<p class='affiliations '  >
	<?=$TH['affiliations']?>
</p>


	<p class='speaker_email'>e-mail:<i> <?=$TH['email']?></i></p>


	<?
	echo $TH['text'];
	?>


<?
if(strlen($TH['literature'])>20):
?>

	<p class="liter_header"><?=$LANG['liter_header']?></h>	
	<?=$TH['literature']?>

<?
endif;
?>

<?
if(strlen($TH['rfbr_title'])>5 || strlen($TH['rfbr'])>2):
?>
<p class="rfbr-header">Наименование проект РФФИ</p>
<p class='rfbr-title'><?=$TH['rfbr_title']?></p>
<p class='rfbr-num'><b>Номер проекта РФФИ:</b> <?=$TH['rfbr']?></p>
<?
endif;
?>