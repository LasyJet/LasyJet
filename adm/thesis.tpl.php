<?
	if(!$print):
?>

<div class="row one_thesis m-4 p-4 border border-dark" id="thesis_<?=$TH['id']?>" data-thesisid="<?=$TH['thesis_id']?>">
<?
	endif;
?>	
	<div class="title_border col-12 border border-info" title='"<?=$TH['sect_id']?>"'><a name="<?=$TH['thesis_id']?>"></a>
		<h3 class="title" data-sect_id='"<?=$TH['sect_id']?>"' <?=$contenteditable?> >Секция: <?=$TH['sect_title']?></h3>
	</div>

	<div class="col-12" title="<?=$TH['thesis_id']?>">
	
	<h1 class="title" <?=$contenteditable?> ><a href="<? echo SITE?>/thesis.php?id=<?=$TH['thesis_id']?>"><i class="fa fa-eye fa-md"></i></a> <?=$TH['title']?></h1>
	</div>

	<div class='col-12' <?=$contenteditable?> >
		<?
		echo "<span class='speaker'>".strip_tags($TH['speaker'],"<sup>")."</span>";
		echo (strlen($TH['coauthors'])>2)?", ":"";
		echo strip_tags($TH['coauthors'],"<sup>");
		?>
	</div>

	<div class='affiliations col-12' <?=$contenteditable?> >
		<?=$TH['affiliations']?>
	</div>

	<div class="col-12">
		<p class='speaker_email'><b>e-mail</b>:<i> <?=$TH['email']?></i></p>
	</div>

	<div class='text col-12 mt-4' style="min-height:10rem"  <?=$contenteditable?> >
		<?
		// $empty_thesee="<p>Type or paste your abstract here</p>";
		// $empty_thesee="<p>Впишите или вставьте ваши тезисы здесь</p>";
		echo(strlen($TH['text'])<100)?"Текст не заполнен":$TH['text'];
		?>
	</div>


	<div class='literature col-12' <?=$contenteditable?> >
		<p class="font-weight-bold"><?=$LANG['liter_header']?></h>	
		<?=$TH['literature']?>
	</div>
<? if(allowRFBR):?>

	<div class="rfbr_title col-12">
		<p class="font-weight-bold">Наименование проект РФФИ</p><? //=$LANG['rfbr_title']?>
		<p class='text '  <?=$contenteditable?> > 
			<?=$TH['rfbr_title']?>
		</p>

		<p class='rfbr_num text'  <?=$contenteditable?> >
		<b><? //=$LANG['rfbr_num']?>Номер проекта РФФИ:</b> <?=$TH['rfbr']?>
		</p>
	</div>
<?endif; ?>
	<div class="col-12">
		<p><b>Дата подачи:</b> <?=$TH['date']?></p>
	</div>
	
	<div class="col-12 border border-info pb-1">
		<p class="font-weight-bold">Ваша оценка</p>
		<?
		if(allowGrade){
			echo "<div class='col-7 grade p-0' data-thesisid=\"{$TH['thesis_id']}\">";
			// var_dump($TH['grade']);
			$style="";
			for($i=1;$i<=10;$i++){
				if($TH['grade']!==NULL){
					if($TH['grade']!=$i) {
						$style="style='display: none'";
						$nograde="";
					}
					else{ 
						$style="";
					}
				}
				else $nograde="&larr; <span class='text-info'>поставьте оценку</span>";

				echo "<span class='m-1 p-1 grade".(11-$i)."' $style >";
				echo $i;
				echo "</span>";
				
			}
			
			echo "</div>";
			// echo "<div class='col-2'>$nograde/div>";
		}
		?>
	</div>
<? if(!$print): ?>
</div>
<? endif ?>