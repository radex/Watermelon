<?php defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo WM_BundlesURL?>watermelon/public/basic.css">
<link rel="stylesheet" href="<?php echo WM_BundlesURL?>watermelon/public/watermelon.css">
<link rel="stylesheet" href="<?php echo WM_SkinURL?>installer.css">
<header></header>
<?php  if(!$additionalData->noContainer): ?>
<div id="content">
   <h1><?php echo $pageTitle?></h1>
   
   <?php  $this->drawMessages() ?>
   
   <?php echo $content?>

   <div id="status-bar">
      <?php 
      
      if(isset($additionalData->form))
      {
         echo '<button onclick="if(document.form.checkValidity()) document.form.submit()">Dalej</button>';
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
            <div id="progress-bar-progress" style="width:<?php echo $additionalData->progress?>%"></div>
         </div>
         Postęp instalacji
      </div>
   </div>
</div>
<?php  else: ?>
<?php echo $content?>
<?php  endif; ?>