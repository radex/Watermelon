<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * class ViewPreFilter
 * 
 * Filters PHPTAL views by replacing <? with <?php and <?= with <?php echo (to prevent failing on servers with disabled short_open_tags)
 */

class ViewPreFilter extends PHPTAL_PreFilter
{
   /*
    * filter
    */
   
   public function filter($view)
   {
      // short open tags
      
      $view = str_replace('<?=', '<?php echo ', $view);
      $view = preg_replace('/<\?([^px]{1})/', '<?php $1', $view);
      
      return $view;
   }
}