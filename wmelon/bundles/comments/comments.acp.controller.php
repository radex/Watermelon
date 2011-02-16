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
 * Comments management
 */

class Comments_Controller extends Controller
{
   /*
    * comments table
    */
   
   function index_action()
   {
      $this->pageTitle = 'Lista komentarzy';
      
      $comments = $this->model->comments();
      
      // if no comments
      
      if(!$comments->exists)
      {
         echo '<p>Brak komentarzy</p>';
         return;
      }
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Komentarz', 'Napisany', 'Autor');
      $table->selectedActions[] = array('Usuń', 'comments/delete/');
      $table->selectedActions[] = array('Zatwierdź', 'comments/approve/');
      $table->selectedActions[] = array('Odrzuć', 'comments/reject/');
      
      // users, posts, pages
      
      $users = array();
      $posts = array();   // in all three arrays data about certain uesr/blog post/page is stored, in [id] key
      $pages = array();
      
      // adding comments
      
      foreach($comments as $comment)
      {
         $id = $comment->id;
         
         //--
         
            $content = strip_tags($comment->content);
         
            if(mb_strlen($content) > 500)
            {
               $content = mb_substr($content, 0, 500) . ' (...)';
            }
         
            $content = nl2br($content);
         
         //--
         
            $actions = '';
            
            // previewing
            
            if($comment->type == 'blogpost')
            {
               // fetching data
               
               if(!isset($posts[$comment->record]))
               {
                  $posts[$comment->record] = Blog_Model::postData_id($comment->record);
               }
               
               $post = &$posts[$comment->record];
               
               //--
               
               $actions .= '<a href="#/' . date('Y/m', $post->published) . '/' . $post->name . '#comment-' . $id . '" title="Obejrzyj komentarz na stronie">Zobacz</a> | ';
            }
            elseif($comment->type = 'page')
            {
               // fetching data
               
               if(!isset($pages[$comment->record]))
               {
                  $pages[$comment->record] = Pages_Model::pageData_id($comment->record);
               }
               
               $page = &$pages[$comment->record];
               
               //--
               
               $actions .= '<a href="#/' . $page->name . '#comment-' . $id . '" title="Obejrzyj komentarz na stronie">Zobacz</a> | ';
            }
         
            $actions .= '<a href="$/comments/edit/' . $id . '" title="Edytuj komentarz">Edytuj</a> | ';
            $actions .= '<a href="$/comments/delete/' . $id . '" title="Usuń komentarz">Usuń</a>';
            
            // approve/reject (only if comment wasn't written by admin)
            
            if($comment->authorID === null)
            {
               if($comment->approved)
               {
                  $actions .= ' | <a href="$/comments/reject/' . $id . '" title="Oznacz komentarz jako oczekujący na moderację">Odrzuć</a>';
               }
               else
               {
                  $actions .= ' | <a href="$/comments/approve/' . $id . '" title="Oznacz komentarz jako sprawdzony">Zatwierdź</a>';
               }
            }
         
         //--
            
            $commentInfo = '';
            
            if(!$comment->approved)
            {
               $commentInfo .= '[<strong>Niesprawdzony!</strong>] ';
            }
            
            $commentInfo .= $content;
            $commentInfo .= '<div class="acp-actions">' . $actions . '</div>';
         
         //--
         
            $created = HumanDate($comment->created);
         
         //--
         
            if($comment->authorID !== null)
            {
               // fetching data
            
               if(!isset($users[$comment->authorID]))
               {
                  $users[$comment->authorID] = Users_Model::userData_id($comment->authorID);
               }
            
               $user = &$users[$comment->authorID];
            
               //--
            
               $author = htmlspecialchars($user->nick) . ' (admin)';
            }
            else
            {
               $author = htmlspecialchars($comment->authorName);
            
               if($comment->authorWebsite !== null)
               {
                  $author = '<a href="' . htmlspecialchars($comment->authorWebsite) . '" target="_blank">' . $author . '</a>';
               }
            
               $author .= '<br>' . htmlspecialchars($comment->authorEmail);
            }
         
         //--
         
            if(!$comment->approved)
            {
               $rowAttributes = array('style' => 'background-color:#F5E8D9');
            }
         
            $cells = array($commentInfo, $created, $author);
         
            $table->addRow($id, $cells, $rowAttributes);
      }
      
      // displaying
      
      echo $table->generate();
   }
   
   /*
    * edit comment
    */
   
   function edit_action($id, $backPage)
   {
      $id = (int) $id;
      
      // getting data
      
      $data = $this->model->commentData($id);
      
      if(!$data)
      {
         SiteRedirect('comments');
      }
      
      // displaying form
      
      $this->pageTitle = 'Edytuj wpis';
      
      $form = new Form('wmelon.comments.editComment', 'comments/editSubmit/' . $id . '/' . $backPage, 'comments/edit/' . $id . '/' . $backPage);
      
      $form->addInput('textarea', 'content', 'Treść', true, array('style' => 'width: 100%; height:30em', 'value' => $data->content));
      
      echo $form->generate();
   }
   
   /*
    * edit comment submit
    */
   
   function editSubmit_action($id, $backPage)
   {
      $id = (int) $id;
      
      // checking if exists
      
      if(!$this->model->commentData($id))
      {
         SiteRedirect('comments');
      }
      
      // editing
      
      $form = Form::validate('wmelon.comments.editComment', 'comments/edit/' . $id . '/' . $backPage);
      $data = $form->get();
      
      $this->model->editComment($id, $data->content);
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano komentarz');
      
      $backPage = base64_decode($backPage);
      $backPage = empty($backPage) ? 'comments' : $backPage;
      
      SiteRedirect($backPage);
   }
   
   /*
    * delete comment
    */

   function delete_action($ids, $backPage)
   {
      AdminQuick::bulkAction('delete', 'comments', $ids, $backPage,
         function($ids, $model)
         {
            return 'Czy na pewno chcesz usunąć ' . count($ids) . ' komentarzy?';
         });
   }

   /*
    * delete comment submit
    */

   function delete_submit_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('comments', $ids, $backPage,
         function($ids, $model)
         {
            $model->deleteComments($ids);
         },
         function($count)
         {
            return 'Usunięto ' . $count . ' komentarzy';
         });
   }
   
   /*
    * approve comment
    */

   function approve_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('comments', $ids, $backPage,
         function($ids, $model)
         {
            $model->approve($ids);
         });
   }
   
   /*
    * reject comment
    */
   
   function reject_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('comments', $ids, $backPage,
         function($ids, $model)
         {
            $model->reject($ids);
         });
   }
}