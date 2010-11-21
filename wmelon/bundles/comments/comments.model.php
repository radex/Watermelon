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
      $commentID = DB::insert('comments', array
         (
            'authorName'    => htmlspecialchars($authorName),
            'authorEmail'   => htmlspecialchars($authorEmail),
            'authorWebsite' => htmlspecialchars($authorWebsite),
            'text'          => (string) $text,
            'created'       => time(),
            'awaitingModeration' => (int) $awaitingModeration
         ));
      
      DB::insert('comments_records', array
         (
            'record'  => (int) $id,
            'comment' => $commentID,
            'type'    => (string) $type
         ));
   }
   
   /*
    * public void editComment(int $id, string $content)
    * 
    * Edits $id comment, setting given data
    */
   
   public function editComment($id, $content)
   {
      DB::update('comments', (int) $id, array
         (
            'text' => $content
         ));
      
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
      
      DB::delete('comments', $id);
      
      DBQuery::delete('comments_records')->where('comment', $id)->execute();
   }
   
   /*
    * public void approve(int $id)
    * 
    * Marks $id comment as approved (not awaiting moderation)
    */
   
   public function approve($id)
   {
      DBQuery::update('comments')->set('awaitingModeration', false)->where('id', (int) $id)->execute();
   }
   
   /*
    * public void reject(int $id)
    * 
    * Marks $id comment as rejected (awaiting moderation)
    */
   
   public function reject($id)
   {
      DBQuery::update('comments')->set('awaitingModeration', true)->where('id', (int) $id)->execute();
   }
}