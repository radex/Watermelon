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
      return DBQuery::select('comments')->orderBy('id', true)->act();
   }
   
   /*
    * public object commentData(int $id)
    * 
    * $id comment data
    */
   
   public function commentData($id)
   {
      return DB::select('comments', (int) $id);
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
      
      return DBQuery::select('comments')->where('record', $id)->andWhere('type', $type)->orderBy('id')->act();
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
      
      $query = DBQuery::select('id', 'comments')->where('record', $id)->andWhere('type', $type);
      
      if(!$all)
      {
         $query = $query->andWhere('awaitingModeration', false);
      }
      
      return $query->act()->rows;
   }
   
   /*
    * public void deleteCommentsFor(int $id, string $type)
    * 
    * Deletes comments for $id record of $type type of content
    */
   
   public function deleteCommentsFor($id, $type)
   {
      $id   = (int) $id;
      $type = (string) $type;
      
      DBQuery::delete('comments')->where('record', $id)->andWhere('type', $type)->act();
   }
   
   /*
    * public int postComment(int $id, string $type, string $authorName, string $authorEmail, string $authorWebsite, string $content, bool $awaitingModeration)
    * 
    * Posts a comment (for $id record of $type type of content)
    * 
    * Returns its ID
    */
   
   public function postComment($id, $type, $authorName, $authorEmail, $authorWebsite, $content, $awaitingModeration)
   {
      return DB::insert('comments', array
         (
            'record'        => (int)    $id,
            'type'          => (string) $type,
            'authorName'    => (string) $authorName,
            'authorEmail'   => (string) $authorEmail,
            'authorWebsite' => (string) $authorWebsite,
            'content'       => (string) $content,
            'created'       => time(),
            'awaitingModeration' => (int) $awaitingModeration
         ));
   }
   
   /*
    * public int postComment(int $id, string $type, string $content)
    * 
    * Posts a comment as currently logged user (for $id record of $type type of content)
    * 
    * Returns its ID
    */
   
   public function postComment_logged($id, $type, $content)
   {
      return DB::insert('comments', array
         (
            'record'        => (int)    $id,
            'type'          => (string) $type,
            'authorID'      => Auth::userData()->id,
            'content'       => (string) $content,
            'created'       => time(),
            'awaitingModeration' => false
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
   }
   
   /*
    * public void approve(int $id)
    * 
    * Marks $id comment as approved (not awaiting moderation)
    */
   
   public function approve($id)
   {
      DBQuery::update('comments')->set('awaitingModeration', false)->where('id', (int) $id)->act();
   }
   
   /*
    * public void reject(int $id)
    * 
    * Marks $id comment as rejected (awaiting moderation)
    */
   
   public function reject($id)
   {
      DBQuery::update('comments')->set('awaitingModeration', true)->where('id', (int) $id)->act();
   }
}