<?php

class WCMSLay_Skin extends Skin
{
   protected function drawTextMenu($id)
   {
      parent::drawTextMenu($id);
      
      echo 'test';
   }
   
   protected function drawHeadTags()
   {
      foreach($this->headTags as $tag)
      {
         echo '   ' . $tag . "\n\n";
      }
   }
}