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
 * Blog Model
 */

class Blog_Model extends Model
{
   /*
    * public DBResult posts()
    * 
    * List of posts
    */
   
   public function posts()
   {
      return $this->db->query("SELECT * FROM `__blogposts` ORDER BY `blogpost_id` DESC");
   }
   
   /*
    * public object postData(int $postID)
    * 
    * Data of a post (or FALSE if doesn't exist)
    */
   
   public function postData($id)
   {
      $id = (int) $id;
      
      $postData = $this->db->query("SELECT * FROM `__blogposts` WHERE `blogpost_id` = '%1'", $id);
      
      if($postData->exists)
      {
         return $postData->fetchObject();
      }
      else
      {
         return false;
      }
   }
   
   /*
    * public DBResult commentsForPost(int $postID)
    * 
    * Comments for a post
    * 
    * Note that existence of a post isn't checked
    */
   
   public function commentsForPost($id)
   {
      $id = (int) $id;
      
      $comments = $this->load->model('comments');
      
      return $comments->commentsFor($id, 'blogpost');
   }
}