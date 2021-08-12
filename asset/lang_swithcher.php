<?
    if($LANG['language']=='ru'){
      echo("<a href='{$SITE}?lang=en'>switch to English</a>");
    }
    else 
      echo("<a href='{$SITE}?lang=ru'>переключить на русский</a>");
	  echo("&nbsp;|&nbsp;<a href='{$SITE}?quit'>".$LANG['exit']."</a>");
?>