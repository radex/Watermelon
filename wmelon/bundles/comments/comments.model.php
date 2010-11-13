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
    * public DBResult comments()
    * 
    * List of all comments
    */
   
   public function comments()
   {
      return $this->db->query("SELECT * FROM `__comments_records` JOIN `__comments` ON `__comments_records`.`comment` = `__comments`.`id` ORDER BY `__comments`.`id` DESC");
      
      //TODO: use improved ->query() when done here
   }
   
   /*
    * public object commentData(int $id)
    * 
    * $id comment data (with ID of record, that comment belongs to)
    */
   
   public function commentData($id)
   {
      $id = (int) $id;
      
      return $this->db->query("SELECT * FROM `__comments_records` JOIN `__comments` ON `__comments_records`.`comment` = `__comments`.`id` WHERE `__comments`.`id` = '%1'", $id)->fetchObject();
   }
   
   /*
    * public DBResult commentsFor(int $id, string $type)
    * 
    * List of comments for $id record of $type type of content
    */
   
   public function commentsFor($id, $type)
   {
      $id   = (int) $id;
      $type = (string) $type;
      
      return $this->db->query("SELECT `__comments`.* FROM `__comments_records` JOIN `__comments` ON `__comments_records`.`comment` = `__comments`.`id` WHERE `__comments_records`.`record` = '%1' AND `__comments_records`.`type` = '%2'", $id, $type);
   }
   
   /*
    * public int countCommentsFor(int $id, string $type, bool $all)
    * 
    * Number of comments for $id record of $type type of content
    * 
    * bool $all - whether all comments shall be counted (true) or only approved ones (false)
    */
   
   public function countCommentsFor($id, $type, $all)
   {
      $id   = (int) $id;
      $type = (string) $type;
      $all  = (bool) $all;
      
      if(!$all)
      {
         $notAll = ' AND `__comments`.`awaitingModeration` = 0';
      }
      
      return $this->db->query("SELECT `__comments`.`id` FROM `__comments_records` JOIN `__comments` ON `__comments_records`.`comment` = `__comments`.`id` WHERE `__comments_records`.`record` = '%1' AND `__comments_records`.`type` = '%2'" . $notAll, $id, $type)->rows;
   }
   
   /*
    * public void postComment(int $id, string $type, string $authorName, string $authorEmail, string $authorWebsite, string $text, bool $awaitingModeration)
    * 
    * Posts a comment (for $id record of $type type of content)
    */
   
   public function postComment($id, $type, $authorName, $authorEmail, $authorWebsite, $text, $awaitingModeration)
   {
      $id   = (int) $id;
      $type = (string) $type;
      $awaitingModeration = (int) $awaitingModeration;
      
      $authorName    = htmlspecialchars($authorName);
      $authorEmail   = htmlspecialchars($authorEmail);
      $authorWebsite = htmlspecialchars($authorWebsite);
      $text          = $text;
      
      $this->db->query("INSERT INTO `__comments` SET `authorName` = '%1', `authorEmail` = '%2', `authorWebsite` = '%3', `text` = '%4', `created` = '%5', `awaitingModeration` = '%6'", $authorName, $authorEmail, $authorWebsite, $text, time(), $awaitingModeration);
      
      $commentID = DB::insertedID();
      
      $this->db->query("INSERT INTO `__comments_records` (`record`, `comment`, `type`) VALUES ('%1', '%2', '%3')", $id, $commentID, $type);
   }
   
   /*
    * public void editComment(int $id, string $content)
    * 
    * Edits $id comment, setting given data
    */
   
   public function editComment($id, $content)
   {
      $id      = (int)    $id;
      $content = (string) $content;
      
      $this->db->query("UPDATE `__comments` SET `text` = '%1' WHERE `id` = '%2'", $content, $id);
      
      //TODO: text  -->  content
   }
   
   /*
    * public void deleteComment(int $id)
    * 
    * Deletes $id comment
    */
   
   public function deleteComment($id)
   {
      $id = (int) $id;
      
      $this->db->query("DELETE FROM `__comments` WHERE `id` = '%1'", $id);
      $this->db->query("DELETE FROM `__comments_records` WHERE `comment` = '%1'", $id);
   }
   
   /*
    * public void approve(int $id)
    * 
    * Marks $id comment as approved (not awaiting moderation)
    */
   
   public function approve($id)
   {
      $id = (int) $id;
      
      $this->db->query("UPDATE `__comments` SET `awaitingModeration` = 0 WHERE `id` = '%1'", $id);
   }
   
   /*
    * public void reject(int $id)
    * 
    * Marks $id comment as rejected (awaiting moderation)
    */
   
   public function reject($id)
   {
      $id = (int) $id;
      
      $this->db->query("UPDATE `__comments` SET `awaitingModeration` = 1 WHERE `id` = '%1'", $id);
   }
}