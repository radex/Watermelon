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
   
   /*
    * public void postPage(string $title, string $name, string $content)
    * 
    * Posts a page with given data, as currently logged user and with current time
    */
   
   public function postPage($title, $name, $content)
   {
      $title   = (string) $title;
      $name    = (string) $name;
      $content = (string) $content;
      
      $this->db->query("INSERT INTO `__pages` (`page_author`, `page_created`, `page_title`, `page_name`, `page_content`) VALUES ('%1', '%2', '%3', '%4', '%5')", Auth::userData()->id, time(), $title, $name, $content);
   }
   
   /*
    * public void editPage(int $id, string $title, string $name, string $content)
    * 
    * Edits $id page, setting given data
    */
   
   public function editPage($id, $title, $name, $content)
   {
      $id      = (int)    $id;
      $title   = (string) $title;
      $name    = (string) $name;
      $content = (string) $content;
      
      $this->db->query("UPDATE `__pages` SET `page_title` = '%1', `page_name` = '%2', `page_content` = '%3' WHERE `page_id` = '%4'", $title, $name, $content, $id);
   }
   
   /*
    * public void deletePage(int $id)
    * 
    * Deletes a page with given ID
    */
   
   public function deletePage($id)
   {
      $id = (int) $id;
      
      $this->db->query("DELETE FROM `__pages` WHERE `page_id` = '%1'", $id);
   }
}