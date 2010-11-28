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
 * Blog controller
 */

class Blog_Controller extends Controller
{
   /*
    * list of posts
    */
   
   public function index_action()
   {
      $postsObj = $this->model->posts();
      $posts    = array();
      
      // adding edit/delete links URL information
      
      foreach($postsObj as $post)
      {
         $post->editHref   = '%/blog/edit/' .   $post->id . '/backTo:site';
         $post->deleteHref = '%/blog/delete/' . $post->id . '/backTo:site';
         
         $posts[] = $post;
      }
      
      $view = View('posts');
      $view->posts = $posts;
      $view->display();
   }
   
   /*
    * post
    */
   
   public function _actionHandler($name)
   {
      if(empty($name))
      {
         Watermelon::displayNoPageFoundError(); //TODO: improve it, so that I can more concretely write what is not found
         return;
      }
      
      // getting post data
      
      $postData = $this->model->postData_name($name);
      
      if(!$postData)
      {
         Watermelon::displayNoPageFoundError(); // -||-
         return;
      }
      
      // displaying (if exists)
      
      $id = $postData->id;
      
      $postData->content = Textile::textile($postData->content);
      
      $this->pageTitle = $postData->title;
      $this->dontShowPageTitle = true;
      
      $view = View('post');
      $view->post = $postData;
      $view->commentsView = Comments::commentsView($id, 'blogpost', '#/blog/' . $name);
      
      $view->editHref = '%/blog/edit/' . $id . '/backTo:site';
      $view->deleteHref = '%/blog/delete/' . $id . '/backTo:site';
      
      $view->display();
   }
}