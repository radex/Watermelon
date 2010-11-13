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
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Komentarz', 'Napisany', 'Status', 'Akcje');
      $table->selectedActions[] = array('Usuń', 'comments/delete/');
      
      //TODO: link to blog post/page where post was written
      
      // adding comments
      
      foreach($comments as $comment)
      {
         $id = $comment->id;
         
         //--
         
         $content = strip_tags($comment->text);
         
         if(strlen($content) > 500)
         {
            $content = substr($content, 0, 500) . ' (...)';
         }
         
         $content = nl2br($content);
         
         //--
         
         $created = HumanDate($comment->created); //TODO: + by [author]
         
         //--
         
         $status = $comment->awaitingModeration ? 'Niesprawdzony' : '';
         
         //TODO: <td> and <tr> attributes in Form
         
         //--
         
         $actions = '';
         $actions .= '<a href="$/comments/edit/' . $id . '">Edytuj</a>&nbsp;|&nbsp;';
         $actions .= '<a href="$/comments/delete/' . $id . '">Usuń</a> | ';
         
         if($comment->awaitingModeration)
         {
            $actions .= '<a href="$/comments/approve/' . $id . '">Zatwierdź</a>';
         }
         else
         {
            $actions .= '<a href="$/comments/reject/' . $id . '">Odrzuć</a>';
         }
         
         //TODO: mark as spam
         
         //--
         
         $table->addLine($id, $content, $created, $status, $actions);
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
      
      $form->addInput('textarea', 'content', 'Treść', true, array('style' => 'width: 100%; height:30em', 'value' => $data->text));
      
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
      $data = $form->getAll();
      
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
      AdminQuick::delete($ids, $backPage, 'comments',
         function($ids, $model)
         {
            return 'Czy na pewno chcesz usunąć ' . count($ids) . ' komentarzy?';
         });
      
   }
   
   /*
    * delete comment submit
    */
   
   function deleteSubmit_action($ids, $backPage)
   {
      AdminQuick::deleteSubmit($ids, $backPage, 'comments',
         function($id, $model)
         {
            $model->deleteComment($id);
         },
         function($count)
         {
            return 'Usunięto ' . $count . ' komentarzy';
         });
   }
   
   /*
    * approve comment
    */
   
   function approve_action($id, $backPage)
   {
      $this->model->approve($id);
      
      $backPage = base64_decode($backPage);
      $backPage = empty($backPage) ? 'comments' : $backPage;
      
      SiteRedirect($backPage);
   }
   
   /*
    * reject comment
    */
   
   function reject_action($id, $backPage)
   {
      $this->model->reject($id);
      
      $backPage = base64_decode($backPage);
      $backPage = empty($backPage) ? 'comments' : $backPage;
      
      SiteRedirect($backPage);
   }
}