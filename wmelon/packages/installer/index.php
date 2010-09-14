<?defined('WM') or die?><!doctype html>
<html lang="pl">
<link rel="stylesheet" href="<?=WM_SkinURL?>installer.css" />
<title>Watermelon CMS</title>
<header></header>
<div id="content">
   <h1>Are you ready to go on a ride?</h1>
   
   <?=$content?>
   
   <div id="status-bar">
      <button>Dalej</button>
      <button>Wróć</button>
      
      <div id="progress-bar-container">
         <div id="progress-bar">
            <div id="progress-bar-progress">
            </div>
         </div>
         Postęp instalacji
      </div>
   </div>
</div>