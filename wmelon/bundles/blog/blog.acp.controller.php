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
    * subnav config
    */
   
   function __construct()
   {
      parent::__construct();
      
      $subNav[] = array('Lista wpisów', null, 'blog/index');
      $subNav[] = array('Nowy wpis', null, 'blog/new');
      
      $this->subNav = $subNav;
   }
   
   /*
    * blog posts table
    */
   
   function index_action()
   {
      $this->pageTitle = 'Lista wpisów';
      
      $posts = $this->model->allPosts();
      
      $commentsModel = Model('comments');
      
      // if no blog posts
      
      if($posts->empty)
      {
         echo '<p>Brak wpisów. <a href="$/blog/new">Napisz pierwszy.</a></p>';
         return;
      }
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Tytuł', 'Treść', '<small>Napisany (uaktualniony)</small>', 'Komentarzy', 'Akcje');
      $table->selectedActions[] = array('Usuń', 'blog/delete/');
      
      // adding posts
      
      foreach($posts as $post)
      {
         $id = $post->id;
         
         //--
         
         $title = '<a href="#/blog/' . $post->name . '">' . $post->title . '</a>';
         
         //--
         
         $content = strip_tags($post->content);
         
         if(strlen($content) > 100)
         {
            $content = substr($content, 0, 100) . ' (...)';
         }
         
         //--
         
         $dates = '<small>' . HumanDate($post->created, true, true) . ' (' . HumanDate($post->updated, true, true) . ')</small>'; //TODO: + by [author]
         
         //--
         
         $comments = $commentsModel->countCommentsFor($id, 'blogpost', true);
         
         //--
         
         $actions = '';
         $actions .= '<a href="$/blog/edit/' . $id . '">Edytuj</a> | ';
         $actions .= '<a href="$/blog/delete/' . $id . '">Usuń</a>';
         
         //--
         
         $table->addLine($id, $title, $content, $dates, $comments, $actions);
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
      
      //TODO: give option (for advanced users) to type name for themselves
      
      $titleArgs   = array('style' => 'width: 500px');
      $contentArgs = array('style' => 'width: 100%; height:30em');
      
      $form->addInput('text', 'title', 'Tytuł', true, $titleArgs);
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
      
      $backTo = isset($this->parameters->backto) ? '/backTo:' . $this->parameters->backto : '';
      
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
      $contentArgs = array('style' => 'width: 100%; height:30em', 'value' => $data->content);
      
      $form->addInput('text', 'title', 'Tytuł', true, $titleArgs);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      
      echo $form->generate();
   }
   
   /*
    * edit post submit
    */
   
   function editSubmit_action($id)
   {
      $id = (int) $id;
      
      $backTo = isset($this->parameters->backto) ? '/backTo:' . $this->parameters->backto : '';
      
      // checking if exists
      
      $postData = $this->model->postData_id($id);
      
      if(!$postData)
      {
         SiteRedirect('blog');
      }
      
      // editing
      
      $form = Form::validate('wmelon.blog.editPost', 'blog/edit/' . $id . $backTo);
      $data = $form->getAll();
      
      $this->model->editPost($id, $data->title, $data->content);
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano wpis');
      
      switch($this->parameters->backto)
      {
         case 'post':
            $backPage = '#/blog/' . $postData->name;
         break;
         
         case 'site':
            $backPage = '#/'; //TODO: + #id
         break;
         
         default:
            $backPage = '';
         break;
      }
      
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
         function($ids, $model)
         {
            $model->deletePosts($ids);
         },
         function($count)
         {
            return 'Usunięto ' . $count . ' postów';
         });
   }
}