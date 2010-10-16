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
 * Pages model
 */

class Pages_Model extends Model
{
   /*
    * public DBResult pages()
    * 
    * List of pages
    */
   
   public function pages()
   {
      return $this->db->query("SELECT * FROM `__pages` ORDER BY `page_id` DESC");
   }
   
   /*
    * public object pageData_id(int $postID)
    * 
    * Data of a page (by ID) (or FALSE if doesn't exist)
    */
   
   public function pageData_id($id)
   {
      $id = (int) $id;
      
      $pageData = $this->db->query("SELECT * FROM `__pages` WHERE `page_id` = '%1'", $id);
      
      if($pageData->exists)
      {
         return $pageData->fetchObject();
      }
      else
      {
         return false;
      }
   }
   
   /*
    * public object pageData_name(string $pageName)
    * 
    * Data of a page (by name) (or FALSE if doesn't exist)
    */
   
   public function pageData_name($name)
   {
      $name = (string) $name;
      
      $pageData = $this->db->query("SELECT * FROM `__pages` WHERE `page_name` = '%1'", $name);
      
      if($pageData->exists)
      {
         return $pageData->fetchObject();
      }
      else
      {
         return false;
      }
   }
}