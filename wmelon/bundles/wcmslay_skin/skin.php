<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  

class WCMSLay_Skin extends Skin
{
   protected function drawBlockMenu($id)
   {
      foreach($this->blockMenus[$id] as $menu)
      {
         echo '<section>';
         
         list($title, $blockSet, $block, $parameters) = $menu;
         
         echo '<h2>' . $title . '</h2>';
         
         $blockObj = BlockSet($blockSet);
         
         call_user_func_array(array($blockObj, $block), $parameters);
         
         echo '</section>';
      }
   }
   
   public function display()
   {
      $skin = new View(WM_SkinPath . 'index_phptal.php');
      
      $skin->content           = $this->content;
      $skin->pageTitle         = $this->pageTitle;
      $skin->siteName          = $this->siteName;
      $skin->siteSlogan        = $this->siteSlogan;
      $skin->footer            = SiteLinks($this->footer);
      $skin->dontShowPageTitle = $this->dontShowPageTitle;
      $skin->additionalData    = $this->additionalData;
      $skin->skin              = $this;
      
      $skin->display();
   }
}