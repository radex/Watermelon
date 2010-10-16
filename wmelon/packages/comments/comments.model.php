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

/*
 * Comments model
 */

class Comments_Model extends Model
{
   /*
    * public DBResult comments([string $type])
    * 
    * List of comments for $type type of content (blog post, page, etc.) if specified, or all comments otherwise
    */
   
   public function comments($type = null){}
   
   /*
    * public DBResult commentsFor(int $id, string $type)
    * 
    * List of comments for $id record of $type type of content (blog post, page, etc.)
    */
   
   public function commentsFor($id, $type)
   {
      $id   = (int) $id;
      $type = (string) $type;
      
      return $this->db->query("SELECT `__comments`.* FROM `__comments_records` JOIN `__comments` ON `commrecord_comment` = `comment_id` WHERE `commrecord_record` = '%1' AND `commrecord_type` = '%2'", $id, $type);
   }
}