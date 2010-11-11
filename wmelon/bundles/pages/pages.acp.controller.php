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
 * Pages management
 */

class Pages_controller extends Controller
{
   /*
    * pages table
    */
   
   function index_action()
   {
      $this->pageTitle = 'Lista stron';
      
      $pages = $this->model->pages();
      
      $commentsModel = Model('comments');
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Tytuł', 'Nazwa', 'Treść', 'Utworzono', 'Komentarzy', 'Akcje');
      $table->selectedActions[] = array('Usuń', 'pages/delete/');
      
      // adding pages
      
      foreach($pages as $page)
      {
         $id = $page->page_id;
         
         //--
         
         $name  = $page->page_name;
         $title = '<a href="#/pages/' . $name . '">' . $page->page_title . '</a>';
         $name  = '<a href="#/pages/' . $name . '">' . $name . '</a>';
         
         //--
         
         $content = strip_tags($page->page_content);
         
         if(strlen($content) > 100)
         {
            $content = substr($content, 0, 100) . ' (...)';
         }
         
         //--
         
         $created = HumanDate($page->page_created); //TODO: + by [author]
         
         //--
         
         $comments = $commentsModel->countCommentsFor($id, 'page');
         
         //--
         
         $actions = '';
         $actions .= '<a href="$/pages/edit/' . $id . '">Edytuj</a> | ';
         $actions .= '<a href="$/pages/delete/' . $id . '">Usuń</a>';
         
         //--
         
         $table->addLine($id, $title, $name, $content, $created, $comments, $actions);
      }
      
      // displaying
      
      echo $table->generate();
   }
   
   /*
    * new page
    */
   
   function new_action()
   {
      $this->pageTitle = 'Nowa strona';
      
      $form = new Form('wmelon.pages.newPage', 'pages/newSubmit', 'pages/new');
      
      $form->addInput('text', 'title', 'Tytuł', true, array('style' => 'width: 500px'));
      $form->addInput('text', 'name', 'Nazwa', true,  array('style' => 'width: 500px', 'labelNote' => '(Część URL-u)'));
      $form->addInput('textarea', 'content', 'Treść', true, array('style' => 'width: 100%; height:30em'));
      
      echo $form->generate();
   }
   
   /*
    * new page submit
    */
   
   function newSubmit_action()
   {
      $form = Form::validate('wmelon.pages.newPage', 'pages/new');
      $data = $form->getAll();
      
      //TODO: check whether name already exists, propose alternative if so, preferably also via AJAX
      
      $this->model->postPage($data->title, $data->name, $data->content);
      
      $this->addMessage('tick', 'Dodano stronę!');
      
      SiteRedirect('pages');
   }
   
   /*
    * edit page
    */
   
   function edit_action($id, $backPage)
   {
      $id = (int) $id;
      
      // getting data
      
      $data = $this->model->pageData_id($id);
      
      if(!$data)
      {
         SiteRedirect('pages');
      }
      
      // displaying form
      
      $this->pageTitle = 'Edytuj stronę';
      
      $form = new Form('wmelon.pages.editPage', 'pages/editSubmit/' . $id . '/' . $backPage, 'pages/edit/' . $id . '/' . $backPage);
      
      $titleArgs   = array('style' => 'width: 500px', 'value' => $data->page_title);
      $nameArgs    = array('style' => 'width: 500px', 'labelNote' => '(Część URL-u)', 'value' => $data->page_name);
      $contentArgs = array('style' => 'width: 100%; height:30em', 'value' => $data->page_content);
      
      $form->addInput('text', 'title', 'Tytuł', true, $titleArgs);
      $form->addInput('text', 'name',  'Nazwa', true, $nameArgs);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      
      echo $form->generate();
   }
   
   /*
    * edit page submit
    */
   
   function editSubmit_action($id, $backPage)
   {
      $id = (int) $id;
      
      // checking if exists
      
      if(!$this->model->pageData_id($id))
      {
         SiteRedirect('pages');
      }
      
      // editing
      
      $form = Form::validate('wmelon.pages.editPage', 'pages/edit/' . $id . '/' . $backPage);
      $data = $form->getAll();
      
      $this->model->editPage($id, $data->title, $data->name, $data->content);
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano stronę');
      
      $backPage = base64_decode($backPage);
      $backPage = empty($backPage) ? 'pages' : $backPage;
      
      SiteRedirect($backPage);
   }
   
   /*
    * delete page
    */

   function delete_action($ids, $backPage)
   {
      AdminQuick::delete($ids, $backPage, 'pages',
         function($ids, $model)
         {
            return 'Czy na pewno chcesz usunąć ' . count($ids) . ' stron';
         });
      
   }

   /*
    * delete page submit
    */

   function deleteSubmit_action($ids, $backPage)
   {
      AdminQuick::deleteSubmit($ids, $backPage, 'pages',
         function($id, $model)
         {
            $model->deletePage($id);
         },
         function($count)
         {
            return 'Usunięto ' . $count . ' stron';
         });
   }
}