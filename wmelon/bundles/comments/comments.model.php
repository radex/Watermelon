<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
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
    * public int postComment(int $id, string $type, string $authorName, string $authorEmail, string $authorWebsite, string $content, bool $approved, string $visibilityToken)
    * 
    * Posts a comment (for $id record of $type type of content) and updates comments counters
    * 
    * Returns its ID
    */
   
   public function postComment($id, $type, $authorName, $authorEmail, $authorWebsite, $content, $approved, $visibilityToken)
   {
      $id            = (int)  $id;
      $approved      = (bool) $approved;
      
      $authorWebsite = (string) $authorWebsite;
      $authorWebsite = (empty($authorWebsite) ? null : $authorWebsite); // NULL if empty
      
      //--
      
      $commentID = DB::insert('comments', array
         (
            'record'             =>          $id,
            'type'               => (string) $type,
            'authorName'         => (string) $authorName,
            'authorEmail'        => (string) $authorEmail,
            'authorWebsite'      =>          $authorWebsite,
            'authorIP'           =>          ClientIP(),
            'content'            => (string) $content,
            'created'            =>          time(),
            'approved'           =>          $approved,
            'visibilityToken'    => (string) $visibilityToken,
         ));
      
      // update counters
      
      DB::query('UPDATE __? SET commentsCount = commentsCount + 1 WHERE id = ?', $type . 's', $id);
      
      if(!$approved)
      {
         DB::query('UPDATE __? SET approvedCommentsCount = approvedCommentsCount + 1 WHERE id = ?', $type . 's', $id);
      }
      
      //--
      
      return $commentID;
   }
   
   /*
    * public int postComment(int $id, string $type, string $content)
    * 
    * Posts a comment as currently logged user (for $id record of $type type of content) and updates comments counters
    * 
    * Returns its ID
    */
   
   public function postComment_logged($id, $type, $content)
   {
      $id = (int) $id;
      
      //--
      
      $commentID = DB::insert('comments', array
         (
            'record'        =>          $id,
            'type'          => (string) $type,
            'authorID'      =>          Users::userData()->id,
            'content'       => (string) $content,
            'created'       =>          time(),
            'approved'      =>          true
         ));
      
      // update counters
      
      DB::query('UPDATE __? SET commentsCount = commentsCount + 1 WHERE id = ?', $type . 's', $id);
      DB::query('UPDATE __? SET approvedCommentsCount = approvedCommentsCount + 1 WHERE id = ?', $type . 's', $id);
      
      //--
      
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
    * Deletes comments with given ID-s and updates comments counters
    */
   
   public function deleteComments(array $ids)
   {
      foreach($ids as $id)
      {
         $comment = DB::select('comments', $id);
         
         // updating counters
         
         $tableName = $comment->type . 's';
         $recordID  = $comment->record;
         
         DB::query('UPDATE __? SET commentsCount = commentsCount - 1 WHERE id = ?', $tableName, $recordID);
         
         if($comment->approved)
         {
            DB::query('UPDATE __? SET approvedCommentsCount = approvedCommentsCount - 1 WHERE id = ?', $tableName, $recordID);
         }
      }
      
      // deleting
      
      DB::delete('comments', $ids);
   }
   
   /*
    * public void approve(int[] $ids)
    * 
    * Marks $ids comments as approved (not awaiting moderation)
    */
   
   public function approve(array $ids)
   {
      foreach($ids as $id)
      {
         $comment = DB::select('comments', $id);
         
         // updating counters
         
         $tableName = $comment->type . 's';
         $recordID  = $comment->record;
         
         if(!$comment->approved)
         {
            DB::query('UPDATE __? SET approvedCommentsCount = approvedCommentsCount + 1 WHERE id = ?', $tableName, $recordID);
         }
         
         // approving

         DBQuery::update('comments')->set('approved', true)->where('id', $id)->act();
      }
   }
   
   /*
    * public void reject(int[] $ids)
    * 
    * Marks $ids comments as not approved (awaiting moderation)
    */
   
   public function reject(array $ids)
   {
      foreach($ids as $id)
      {
         $comment = DB::select('comments', $id);
         
         // can't reject admin's comment
         
         if($comment->authorID !== null)
         {
            continue;
         }
         
         // updating counters
         
         $tableName = $comment->type . 's';
         $recordID  = $comment->record;
         
         if($comment->approved)
         {
            DB::query('UPDATE __%1 SET approvedCommentsCount = approvedCommentsCount - 1 WHERE id = %2', $tableName, $recordID);
         }
         
         // rejecting

         DBQuery::update('comments')->set('approved', false)->where('id', $id)->act();
      }
   }
}