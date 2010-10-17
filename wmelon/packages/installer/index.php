<?defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?=WM_PackagesURL?>watermelon/public/basic.css" />
<link rel="stylesheet" href="<?=WM_PackagesURL?>watermelon/public/watermelon.css" />
<link rel="stylesheet" href="<?=WM_SkinURL?>installer.css" />
<title>Watermelon CMS</title>
<header></header>
<? if($additionalData != 'no-container'): ?>
<div id="content">
   <h1><?=$pageTitle?></h1>
   
   <? $this->drawMessages() ?>
   
   <?=$content?>

   <div id="status-bar">
      <?
      
      if(isset($additionalData->form))
      {
         echo '<button onclick="document.form.submit()">Dalej</button>';
      }
      else
      {
         echo '<form action="' . WM_SiteURL . $additionalData->next . '"><button>Dalej</button></form>';
      }
      
   
      if($additionalData->previous !== null)
      {
         echo '<form action="' . WM_SiteURL . $additionalData->previous . '"><button>Wróć</button></form>';
      }
      else
      {
         echo '<button disabled>Wróć</button>';
      }
   
      ?>

   
      <div id="progress-bar-container">
         <div id="progress-bar">
            <div id="progress-bar-progress" style="width:<?=$additionalData->progress?>%"></div>
         </div>
         Postęp instalacji
      </div>
   </div>
</div>
<? else: ?>
<?=$content?>
<? endif; ?>