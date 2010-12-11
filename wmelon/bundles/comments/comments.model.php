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
      
      return $this->db->query("SELECT `__comments`.* FROM `__comments_records` JOIN `__comments` ON `__comments_records`.`comment` = `__comments`.`id` WHERE `__comments_records`.`record` = '%1' AND `__comments_records`.`type` = '%2' ORDER BY `__comments`.`id`", $id, $type);
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
    * public DBResult deleteCommentsFor(int $id, string $type)
    * 
    * Deletes comments for $id record of $type type of content
    */
   
   public function deleteCommentsFor($id, $type)
   {
      $id   = (int) $id;
      $type = (string) $type;
      
      // selecting comments ids
      
      $comments = DBQuery::select('comment', 'comments_records')->where('record', $id)->andWhere('type', $type)->execute();
      
      foreach($comments as $comment)
      {
         $ids[] = $comment->comment;
      }
      
      // deleting comments
      
      DB::delete('comments', $ids);
      
      foreach($ids as $comment)
      {
         DBQuery::delete('comments_records')->where('comment', $comment)->execute();
      }
   }
   
   /*
    * public void postComment(int $id, string $type, string $authorName, string $authorEmail, string $authorWebsite, string $content, bool $awaitingModeration)
    * 
    * Posts a comment (for $id record of $type type of content)
    */
   
   public function postComment($id, $type, $authorName, $authorEmail, $authorWebsite, $content, $awaitingModeration)
   {
      $commentID = DB::insert('comments', array
         (
            'authorName'    => (string) $authorName,
            'authorEmail'   => (string) $authorEmail,
            'authorWebsite' => (string) $authorWebsite,
            'content'       => (string) $content,
            'created'       => time(),
            'awaitingModeration' => (int) $awaitingModeration
         ));
      
      DB::insert('comments_records', array
         (
            'record'  => (int) $id,
            'comment' => $commentID,
            'type'    => (string) $type
         ));
      
      return $commentID;
   }
   
   /*
    * public void postComment(int $id, string $type, string $content)
    * 
    * Posts a comment as currently logged user (for $id record of $type type of content)
    */
   
   public function postComment_logged($id, $type, $content)
   {
      $commentID = DB::insert('comments', array
         (
            'authorID'      => Auth::userData()->id,
            'content'       => (string) $content,
            'created'       => time(),
            'awaitingModeration' => false
         ));
      
      DB::insert('comments_records', array
         (
            'record'  => (int) $id,
            'comment' => $commentID,
            'type'    => (string) $type
         ));
      
      return $commentID;
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
            'content' => $content
         ));
   }
   
   /*
    * public void deleteComments(int[] $ids)
    * 
    * Deletes comments with given ID-s
    */
   
   public function deleteComments(array $ids)
   {
      DB::delete('comments', $ids);
      
      foreach($ids as $id)
      {
         DBQuery::delete('comments_records')->where('comment', $id)->execute();
      }
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