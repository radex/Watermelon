<?defined('WM') or die?>

<div id="langChooser">
   <? foreach($langs as $lang): ?>
      <a href="$/2/<?=$lang[0]?>">
         <img src="<?=WM_SkinURL . 'img/' . $lang[0] . '.png'?>" alt="<?=$lang[1]?>">
         <?=$lang[1]?>
      </a>
   <? endforeach; ?>
</div>
            