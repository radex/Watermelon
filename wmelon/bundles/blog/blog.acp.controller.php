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
      
      $commentsModel = Model('comments');
      
      // if no blog posts
      
      if(!$posts->exists)
      {
         echo '<p>Brak wpisów. <a href="$/blog/new">Napisz pierwszy.</a></p>';
         return;
      }
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Tytuł', 'Nazwa', 'Treść', 'Napisana', 'Komentarzy', 'Akcje');
      $table->selectedActions[] = array('Usuń', 'blog/delete/');
      
      // adding posts
      
      foreach($posts as $post)
      {
         $id = $post->id;
         
         //--
         
         $title = '<a href="#/blog/' . $post->name . '">' . $post->title . '</a>';
         $name  = '<a href="#/blog/' . $post->name . '">' . $post->name . '</a>';
         
         //--
         
         $content = strip_tags($post->content);
         
         if(strlen($content) > 100)
         {
            $content = substr($content, 0, 100) . ' (...)';
         }
         
         //--
         
         $created = HumanDate($post->created); //TODO: + by [author]
         
         //--
         
         $comments = $commentsModel->countCommentsFor($id, 'blogpost', true);
         
         //--
         
         $actions = '';
         $actions .= '<a href="$/blog/edit/' . $id . '">Edytuj</a> | ';
         $actions .= '<a href="$/blog/delete/' . $id . '">Usuń</a>';
         
         //--
         
         $table->addLine($id, $title, $name, $content, $created, $comments, $actions);
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
      
      //TODO: JavaScripted autogeneration of name
      //TODO: make sure not to use already reserved name (preferably using AJAX)
      
      $titleArgs   = array('style' => 'width: 500px');
      $nameArgs    = array('style' => 'width: 500px', 'labelNote' => '(Część URL-u)');
      $contentArgs = array('style' => 'width: 100%; height:30em');
      
      $form->addInput('text', 'title', 'Tytuł', true, $titleArgs);
      $form->addInput('text', 'name',  'Nazwa', true, $nameArgs);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      
      echo $form->generate();
   }
   
   /*
    * new post submit
    */
   
   function newSubmit_action()
   {
      $form = Form::validate('wmelon.blog.newPost', 'blog/new');
      $data = $form->getAll();
      
      $this->model->postPost($data->title, $data->name, $data->content);
      
      $this->addMessage('tick', 'Dodano wpis!');
      
      SiteRedirect('blog');
   }
   
   /*
    * edit post
    */
   
   function edit_action($id)
   {
      $id = (int) $id;
      
      $backTo = ($this->parameters->backto == 'site') ? '/backTo:site' : '';
      
      // getting data
      
      $data = $this->model->postData_id($id);
      
      if(!$data)
      {
         SiteRedirect('blog');
      }
      
      // displaying form
      
      $this->pageTitle = 'Edytuj wpis';
      
      $form = new Form('wmelon.blog.editPost', 'blog/editSubmit/' . $id . $backTo, 'blog/edit/' . $id . $backTo);
      
      $titleArgs   = array('style' => 'width: 500px', 'value' => $data->title);
      $nameArgs    = array('style' => 'width: 500px', 'value' => $data->name, 'labelNote' => '(Część URL-u)');
      $contentArgs = array('style' => 'width: 100%; height:30em', 'value' => $data->content);
      
      $form->addInput('text', 'title', 'Tytuł', true, $titleArgs);
      $form->addInput('text', 'name',  'Nazwa', true, $nameArgs);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      
      echo $form->generate();
   }
   
   /*
    * edit post submit
    */
   
   function editSubmit_action($id)
   {
      $id = (int) $id;
      
      $backTo = ($this->parameters->backto == 'site') ? '/backTo:site' : '';
      
      // checking if exists
      
      if(!$this->model->postData_id($id))
      {
         SiteRedirect('blog');
      }
      
      // editing
      
      $form = Form::validate('wmelon.blog.editPost', 'blog/edit/' . $id . $backTo);
      $data = $form->getAll();
      
      $this->model->editPost($id, $data->title, $data->name, $data->content);
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano wpis');
      
      $backPage = ($backTo == '') ? 'blog' : '#/blog/' . $data->name;
      
      SiteRedirect($backPage);
   }
   
   /*
    * delete post
    */
   
   function delete_action($ids, $backPage)
   {
      AdminQuick::delete($ids, $backPage, 'blog',
         function($ids, $model)
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