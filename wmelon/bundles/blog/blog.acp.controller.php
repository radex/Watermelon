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
 * Blog management
 */

class Blog_Controller extends Controller
{
   /*
    * blog posts table
    */
   
   function index_action()
   {
      $this->pageTitle = 'Lista wpisów';
      
      $posts = $this->model->posts();
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Tytuł', 'Treść', 'Utworzono', 'Akcje');
      $table->selectedActions[] = array('Usuń', 'blog/delete/');
      
      // adding posts
      
      foreach($posts as $post)
      {
         // content
         
         $content = strip_tags($post->blogpost_content);
         
         if(strlen($content) > 100)
         {
            $content = substr($content, 0, 100) . ' (...)';
         }
         
         // created
         
         $created = HumanDate($post->blogpost_created); //TODO: + by [author]
         
         //--
         
         $actions = '';
         $actions .= '<a href="$/blog/edit/' . $post->blogpost_id . '">Edytuj</a> | ';
         $actions .= '<a href="$/blog/delete/' . $post->blogpost_id . '">Usuń</a>';
         
         $table->addLine($post->blogpost_id, $post->blogpost_title, $content, $created, $actions);
      }
      
      // displaying
      
      echo $table->generate();
   }
   
   /*
    * new post
    */
   
   function new_action()
   {
      $this->pageTitle = 'Nowy wpis';
      
      $form = new Form('wmelon.blog.newPost', 'blog/newSubmit', 'blog/new');
      $form->submitLabel = 'Zapisz';
      
      $form->addInput('text', 'title', 'Tytuł', true, array('style' => 'width: 500px'));
      $form->addInput('textarea', 'content', 'Treść', true, array('style' => 'width: 100%; height:30em'));
      
      echo $form->generate();
   }
   
   /*
    * new post submit
    */
   
   function newSubmit_action()
   {
      $form = Form::validate('wmelon.blog.newPost', 'blog/new');
      $data = $form->getAll();
      
      $this->model->postPost($data->title, $data->content);
      
      $this->addMessage('tick', 'Dodano wpis!');
      
      SiteRedirect('blog');
   }
   
   /*
    * edit post
    */
   
   function edit_action($id)
   {
      $id = (int) $id;
      
      // getting data
      
      $data = $this->model->postData($id);
      
      if(!$data)
      {
         SiteRedirect('blog');
      }
      
      // displaying form
      
      $this->pageTitle = 'Edytuj wpis';
      
      $form = new Form('wmelon.blog.editPost', 'blog/editSubmit/' . $id, 'blog/edit/' . $id);
      $form->submitLabel = 'Zapisz';
      
      $form->addInput('text', 'title', 'Tytuł', true, array('style' => 'width: 500px', 'value' => $data->blogpost_title));
      $form->addInput('textarea', 'content', 'Treść', true, array('style' => 'width: 100%', 'value' => $data->blogpost_content));
      
      echo $form->generate();
   }
   
   /*
    * edit post submit
    */
   
   function editSubmit_action($id)
   {
      $id = (int) $id;
      
      // checking if exists
      
      if(!$this->model->postData($id))
      {
         SiteRedirect('blog');
      }
      
      // editing
      
      $form = Form::validate('wmelon.blog.editPost', 'blog/edit/' . $id);
      $data = $form->getAll();
      
      $this->model->editPost($id, $data->title, $data->content);
      
      $this->addMessage('tick', 'Zaktualizowano wpis');
      
      SiteRedirect('blog');
   }
   
   /*
    * delete post
    */
   
   function delete_action($ids, $backPage)
   {
      AdminQuick::delete($ids, $backPage, 'blog',
         function($ids, $cnt)
         {
            return 'Czy na pewno chcesz usunąć ' . count($ids) . ' postów?';
         });
      
   }
   
   /*
    * delete post submit
    */
   
   function deleteSubmit_action($ids, $backPage)
   {
      AdminQuick::deleteSubmit($ids, $backPage, 'blog',
         function($id, $model)
         {
            $model->deletePost($id);
         },
         function($count)
         {
            return 'Usunięto ' . $count . ' postów';
         });
   }
}