<?
$date = A::$api->date(LANG, $com["data"], 'sql', 'datetimetext');
?>
<a name="comment<?=$com["id"]?>"></a>
<div style="border-radius: 5px;   border:1px dashed #ddd; padding:10px;  margin:0px 0px 10px 0px; margin-left:<?=$num?>px">
	<p style="margin: 0px; padding: 0px; margin-bottom: 1rem; font-size: 12px;">
		<strong><?=$com["name"]?></strong> | Добавлено: <span><?=$date?></span>
	</p>
	<p style="margin: 0px; padding: 0px; font-size: 14px;"><?=nl2br(stripslashes($com["com"]))?></p>
	<?=($num==150?'':'<a href="#review-form" onclick="replay_to('.$com["id"].');" style="float:right; font-size:12px;">Ответить</a><br  style="clear:left" />')?>
</div>