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
 * Blog controller
 */

class Blog_Controller extends Controller
{
   /*
    * list of posts
    */
   
   public function index_action()
   {
      $page     = (int) $this->params->page; // page number
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
         
         $post->editHref   = '%/blog/edit/' .   $post->id . '?backTo=site';
         $post->deleteHref = '%/blog/trash/' . $post->id . '/' . base64_encode('#/');
         
         // URL to post
         
         $post->url = '$/' . date('Y/m', $post->published) . '/' . $post->name;
         
         // is draft
         
         $post->draft = ($post->status == 'draft');
         
         // post creation human-readable date
         
         $post->published_human = HumanDate($post->published, true, true);
         
         // comments counter - visible (approved) comments for users, all comments - for admin
         
         if(!Users::isLogged())
         {
            $approvedComments = $post->approvedCommentsCount;
            
            if($approvedComments > 0)
            {
               $post->comments  = ', ' . $approvedComments;
               $post->comments .= ' ' . pl_inflect($approvedComments, 'komentarzy', 'komentarz', 'komentarze');
            }
         }
         else
         {
            // counters
            
            $approvedComments   = $post->approvedCommentsCount;
            $allComments        = $post->commentsCount;
            
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
         
         // content
         
         if($post->summary === null)
         {
            $post->content = Textile::textile($post->content);
         }
         else
         {
            $post->summary = Textile::textile($post->summary . ' <em>[...]</em>');
         }
         
         //--
         
         $posts[] = $post;
      }
      
      // displaying
      
      $view = View('posts');
      $view->posts = $posts;
      $view->page  = $page;
      
      $view->anotherPage  = ($postsObj->rows == 11);                                // whether there is another page
      $view->previousPage = ($page == 2 ? '$/blog' : '$/blog?page=' . ($page - 1)); // URL for previous page
      $view->nextPage     = '$/blog?page=' . ($page + 1);                           // URL for next page
      
      $view->display();
   }
   
   /*
    * post
    */
   
   public function _post_action($name)
   {
      // getting post data
      
      $post = $this->model->postData_name($name);
      
      if(!$post)
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      // checking if published
      
      if($post->status !== 'published')
      {
         // displaying notice for admin, or 'not found'
         
         if(Users::isLogged())
         {
            $this->displayNotice('Ten wpis nie jest opublikowany. Tylko Ty go możesz zobaczyć.');
         }
         else
         {
            Watermelon::displayNoPageFoundError();
            return;
         }
      }
      
      // post
      
      $post->content = Textile::textile($post->content);
      $post->url     = '#/' . date('Y/m', $post->published) . '/' . $post->name;
      
      // displaying (if exists)
      
      $id = $post->id;
      
      $this->pageTitle         = $post->title;
      $this->noHeader = true;
      
      $view = View('post');
      $view->post         = $post;
      $view->commentsView = Comments::commentsView($id, 'blogpost', $post->url, (bool) $post->allowComments);

      $view->editHref     = '%/blog/edit/' . $id . '?backTo=post';
      $view->deleteHref   = '%/blog/trash/' . $id . '/' . base64_encode('#/');
      
      $view->published_human = HumanDate($post->published, true, true);
      
      $view->display();
   }
   
   /*
    * feed (accessible through '/feed.atom' URL)
    */
   
   public function feed_action()
   {
      header('Content-Type: application/atom+xml; charset=UTF-8');
      
      $this->plainOutput = true;
      
      echo file_get_contents(CachePath . 'feed.atom');
   }
}