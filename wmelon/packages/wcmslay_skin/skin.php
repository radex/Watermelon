<?php

class WCMSLay_Skin extends Skin
{
   protected function drawBlockMenu($id)
   {
      foreach($this->blockMenus[$id] as $menu)
      {
         list($title, $blockSet, $block, $parameters) = $menu;
         
         echo '<h2>' . $title . '</h2>';
         
         $blockObj = BlockSet($blockSet);
         
         call_user_func_array(array($blockObj, $block), $parameters);
      }
   }
}