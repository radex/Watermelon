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
         
         $title = '<a href="#/' . date('Y/m', $post->created) . '/' . $post->name . '">' . $post->title . '</a>';
         
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
      //TODO: give option (for advanced users) to type name for themselves
      
      // options
      
      $this->pageTitle = 'Nowy wpis';
      
      $form = new Form('wmelon.blog.newPost', 'blog/newSubmit', 'blog/new');
      
      // input args
      
      $contentArgs = array('style' => 'width: 100%; height:30em');
      $summaryArgs = array('labelNote' => 'Jeśli chcesz, napisz krótko wstęp lub streszczenie wpisu - zostanie ono pokazane na stronie głównej i w czytnikach kanałów');
      
      // adding inputs
      
      $form->addInput('text', 'title', 'Tytuł', true);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      $form->addInput('textarea', 'summary', 'Streszczenie', false, $summaryArgs);
      
      echo $form->generate();
   }
   
   /*
    * new post submit
    */
   
   function newSubmit_action()
   {
      $form = Form::validate('wmelon.blog.newPost', 'blog/new');
      $data = $form->getAll();
      
      $id = $this->model->postPost($data->title, $data->content, $data->summary);
      
      $this->addMessage('tick', 'Dodano wpis!');
      
      SiteRedirect('blog/edit/' . $id);
   }
   
   /*
    * edit post
    */
   
   function edit_action($id)
   {
      $id = (int) $id;
      
      // getting data
      
      $data = $this->model->postData_id($id);
      
      if(!$data)
      {
         SiteRedirect('blog');
      }
      
      // back to link
      
      $backTo = isset($this->parameters->backto) ? '/backTo:' . $this->parameters->backto : '';
      
      $postURL = '#/' . date('Y/m', $data->created) . '/' . $data->name;
      
      switch($this->parameters->backto)
      {
         case 'post':
            $backToLabel = ' lub <a href="' . $postURL . '">powróć do wpisu</a>';
         break;
          
         case 'site':
            $backToLabel = ' lub <a href="#/#blogpost-' . $data->id . '">powróć do strony</a>';
            
            // TODO: and what if the post is not on first page?
         break;
          
         default:
            $backToLabel = ', <a href="$/blog/">powróć do listy wpisów</a> albo <a href="' . $postURL . '">obejrzyj wpis</a>';
         break;
      }
      
      // form options
      
      $this->pageTitle = 'Edytuj wpis';
      
      $form = new Form('wmelon.blog.editPost', 'blog/editSubmit/' . $id . $backTo, 'blog/edit/' . $id . $backTo);
      
      $form->displaySubmitButton = false;
      
      // inputs labels
      
      $sumarryLabel = 'Jeśli chcesz, napisz krótko wstęp lub streszczenie wpisu - zostanie ono pokazane na stronie głównej i w czytnikach kanałów';
      
      // inputs args
      
      $titleArgs   = array('value' => $data->title);
      $contentArgs = array('value' => $data->content, 'style' => 'width: 100%; height:30em');
      $summaryArgs = array('value' => $data->summary, 'labelNote' => $sumarryLabel);
      
      // adding inputs
      
      $form->addInput('text', 'title', 'Tytuł', true, $titleArgs);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      $form->addInput('textarea', 'summary', 'Streszczenie', false, $summaryArgs);
      
      $form->addHTML('<label><span></span><input type="submit" value="Zapisz">' . $backToLabel . '</label>');
      
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
      
      $this->model->editPost($id, $data->title, $data->content, $data->summary);
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano wpis');
      
      SiteRedirect('blog/edit/' . $id . $backTo);
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