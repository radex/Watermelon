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
      $page     = (int) $this->parameters->page; // page number
      $page     = ($page < 1 ? 1 : $page);       // page=1, if specified page<1
      $postsObj = $this->model->posts($page);
      $posts    = array();
      
      // if page is not 1, and there are no posts (page is invalid), redirect to 0 page
      
      if($page != 1 && $postsObj->empty)
      {
         SiteRedirect('blog');
      }
      
      // adding data to posts
      
      foreach($postsObj as $i => $post)
      {
         // omiting 11th post
         
         if($i == 10)
         {
            continue;
         }
         
         // edit/delete links
         
         $post->editHref   = '%/blog/edit/' .   $post->id . '/backTo:site';
         $post->deleteHref = '%/blog/delete/' . $post->id . '/' . base64_encode('#/');
         
         // URL to post
         
         $post->url = '$/' . date('Y/m', $post->created) . '/' . $post->name;
         
         // post creation human date
         
         $post->created_human = HumanDate($post->created, true, true);
         
         // comments counter - visible (approved) comments for users, all comments - for admin
         
         if(!Auth::isLogged())
         {
            $approvedComments = Model('comments')->countCommentsFor($post->id, 'blogpost', false);
            
            if($approvedComments > 0)
            {
               $post->comments  = ', ' . $approvedComments;
               $post->comments .= ' ' . pl_inflect($approvedComments, 'komentarzy', 'komentarz', 'komentarze');
            }
         }
         else
         {
            // counters
            
            $approvedComments   = Model('comments')->countCommentsFor($post->id, 'blogpost', false);
            $allComments        = Model('comments')->countCommentsFor($post->id, 'blogpost', true);
            
            $unapprovedComments = $allComments - $approvedComments;
            
            // all comments counter
            
            if($allComments > 0)
            {
               $post->comments = ', ' . $allComments . ' ' . pl_inflect($allComments, 'komentarzy', 'komentarz', 'komentarze');
            }
            
            // unapproved comments counter
            
            if($unapprovedComments > 0)
            {
               $post->comments .= ' <a href="' . $post->url . '#comments-link" class="important">(' . $unapprovedComments . ' do sprawdzenia!)</a>';
            }
         }
         
         //--
         
         $posts[] = $post;
      }
      
      // displaying
      
      $view = View('posts');
      $view->posts = $posts;
      $view->page  = $page;
      
      $view->anotherPage  = ($postsObj->rows == 11);                                // whether there is another page
      $view->previousPage = ($page == 2 ? '$/blog' : '$/blog/page:' . ($page - 1)); // URL for previous page
      $view->nextPage     = '$/blog/page:' . ($page + 1);                           // URL for next page
      
      $view->display();
   }
   
   /*
    * post
    */
   
   public function _post_action($name)
   {
      if(empty($name))
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      // getting post data
      
      $postData = $this->model->postData_name($name);
      
      if(!$postData)
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      // post
      
      $postData->content = Textile::textile($postData->content);
      $postData->url     = '#/' . date('Y/m', $postData->created) . '/' . $postData->name;
      
      // displaying (if exists)
      
      $id = $postData->id;
      
      $this->pageTitle         = $postData->title;
      $this->dontShowPageTitle = true;
      
      $view = View('post');
      $view->post         = $postData;
      $view->commentsView = Comments::commentsView($id, 'blogpost', $postData->url, (bool) $postData->commentsAllowed);

      $view->editHref     = '%/blog/edit/' . $id . '/backTo:post';
      $view->deleteHref   = '%/blog/delete/' . $id . '/' . base64_encode('#/blog');
      
      $view->display();
   }
   
   /*
    * feed (accessible through '/feed.atom' URL)
    */
   
   public function feed_action()
   {
      header('Content-Type: application/atom+xml; charset=UTF-8');
      
      $this->outputType = self::Plain_OutputType;
      
      echo file_get_contents(WM_Cache . 'feed.atom');
   }
}