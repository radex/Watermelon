<?php defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo WM_BundlesURL?>watermelon/public/basic.css">
<link rel="stylesheet" href="<?php echo WM_BundlesURL?>watermelon/public/watermelon.css">
<link rel="stylesheet" href="<?php echo WM_SkinURL?>installer.css">
<?php $this->drawHeadTags(); ?>
<header></header>
<?php

if(!$additionalData->noContainer)
{
   echo $additionalData->formOpen;

?>
<div id="content">
   <h1><?php echo $pageTitle?></h1>
   
   <?php  $this->drawMessages() ?>
   
   <?php echo $content?>

   <div id="status-bar">
      <?php 
      
      echo '<input type="submit" value="Dalej" autofocus>';
   
      if($additionalData->previous !== null)
      {
         echo '<input type="button" onclick="window.location=\'' . WM_SiteURL . $additionalData->previous . '\'" value="Wróć">';
      }
      else
      {
         echo '<input type="button" value="Wróć" disabled>';
      }
   
      ?>
      <div id="progress-bar-container">
         <div id="progress-bar">
            <?php if($additionalData->progress > 0): ?>
            <div id="progress-bar-progress" style="width:<?php echo $additionalData->progress?>%"></div>
            <?php endif; ?>
         </div>
         Postęp instalacji
      </div>
   </div>
</div>
<?php
   echo '</form>';
}
else
{
   echo $content;
}