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
      $posts = $this->model->posts();
      
      $view = View('posts');
      $view->posts = $posts;
      $view->display();
   }
   
   /*
    * post
    */
   
   public function post_action($id)
   {
      if(empty($id))
      {
         Watermelon::displayNoPageFoundError(); //TODO: improve it, so that I can more concretely write what is not found
         return;
      }
      
      // getting post data
      
      $postData = $this->model->postData($id);
      
      if(!$postData)
      {
         Watermelon::displayNoPageFoundError(); // -||-
         return;
      }
      
      // displaying (if exists)
      
      $this->pageTitle = $postData->blogpost_title;
      $this->dontShowPageTitle = true;
      
      $postData->blogpost_content = Textile_Extension::textile($postData->blogpost_content);
      
      $view = View('post');
      $view->post = $postData;
      $view->commentsView = Comments_Extension::commentsView($id, 'blogposts', 'blog/post/' . $id);
      $view->display();
   }
}