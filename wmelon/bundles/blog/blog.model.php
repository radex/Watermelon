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
    * public DBResult allPosts()
    * 
    * List of all posts
    */
   
   public function allPosts()
   {
      return DBQuery::select('blogposts')->orderBy('id', true)->execute();
   }
   
   /*
    * public DBResult posts(int $page)
    * 
    * List of posts (11 posts, starting from $page)
    * 
    * There are 10 posts for page, but 11 are selected, so that we know if there's another page
    */
   
   public function posts($page)
   {
      $page = (int) $page - 1;
      
      return DBQuery::select('blogposts')->orderBy('id', true)->limit($page * 10, 11)->execute();
   }
   
   /*
    * public object postData_id(int $postID)
    * 
    * Data of a post (or FALSE if doesn't exist)
    */
   
   public function postData_id($id)
   {
      return DB::select('blogposts', (int) $id);
   }
   
   /*
    * public object postData_name(int $name)
    * 
    * Data of a post (or FALSE if doesn't exist)
    */
   
   public function postData_name($name)
   {
      return DBQuery::select('blogposts')->where('name', (string) $name)->execute()->fetchObject();
   }
   
   /*
    * public void postPost(string $title, string $name, string $content)
    * 
    * Posts a post with given data, as currently logged user and with current time
    */
   
   public function postPost($title, $name, $content)
   {
      DB::insert('blogposts', array
         (
            'name'    => (string) $name,
            'title'   => (string) $title,
            'content' => (string) $content,
            'author'  => Auth::userData()->id,
            'created' => time()
         ));
   }
   
   /*
    * public void editPost(int $id, string $title, string $name, string $content)
    * 
    * Edits $id post, setting given data
    */
   
   public function editPost($id, $title, $name, $content)
   {
      DB::update('blogposts', (int) $id, array
         (
            'name'    => (string) $name,
            'title'   => (string) $title,
            'content' => (string) $content
         ));
   }
   
   /*
    * public void deletePost(int[] $ids)
    * 
    * Deletes posts with given ID-s
    */
   
   public function deletePosts(array $ids)
   {
      DB::delete('blogposts', $ids);
      
      foreach($ids as $id)
      {
         Model('comments')->deleteCommentsFor($id, 'blogpost');
      }
   }
}