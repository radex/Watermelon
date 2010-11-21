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
      return DBQuery::select('blogposts')->orderBy('id', true)->execute();
   }
   
   /*
    * public object postData(int $postID)
    * 
    * Data of a post (or FALSE if doesn't exist)
    */
   
   public function postData($id)
   {
      return DB::select('blogposts', (int) $id);
   }
   
   /*
    * public void postPost(string $title, string $content)
    * 
    * Posts a post with given data, as currently logged user and with current time
    */
   
   public function postPost($title, $content)
   {
      DB::insert('blogposts', array
         (
            'author'  => Auth::userData()->id,
            'created' => time(),
            'title'   => (string) $title,
            'content' => (string) $content
         ));
   }
   
   /*
    * public void editPost(int $id, string $title, string $content)
    * 
    * Edits $id post, setting given data
    */
   
   public function editPost($id, $title, $content)
   {
      DB::update('blogposts', (int) $id, array
         (
            'title'   => (string) $title,
            'content' => (string) $content
         ));
   }
   
   /*
    * public void deletePost(int $id)
    * 
    * Deletes a post with given ID
    */
   
   public function deletePost($id)
   {
      DB::delete('blogposts', (int) $id);
   }
}