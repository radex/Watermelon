<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
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
    * subnav config
    */
   
   function __construct()
   {
      parent::__construct();
      
      $subNav[] = array('Lista stron', null, 'pages/index');
      $subNav[] = array('Nowa strona', null, 'pages/new');
      
      $this->subNav = $subNav;
   }
   
   /*
    * pages table
    */
   
   function index_action()
   {
      $this->pageTitle = 'Lista stron';
      
      $pages = $this->model->pages();
      
      $commentsModel = Model('comments');
      
      // if no pages
      
      if($pages->empty)
      {
         echo '<p>Brak stron. <a href="$/pages/new">Utwórz pierwszą.</a></p>';
         return;
      }
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Tytuł', '<small>Napisana (uaktualniona)</small>', 'Komentarzy');
      $table->selectedActions[] = array('Usuń', 'pages/delete/');
      
      // adding pages
      
      foreach($pages as $page)
      {
         $id = $page->id;
         
         //--
         
         $name  = $page->name;
         $title = '<a href="$/pages/edit/' . $id . '" title="Edytuj stronę">' . $page->title . '</a>';
         
         //--
         
         $content = strip_tags($page->content);
         
         if(strlen($content) > 130)
         {
            $content = substr($content, 0, 130) . ' (...)';
         }
         
         //--
         
         $actions = '';
         $actions .= '<a href="#/' . $name . '" title="Obejrzyj na stronie">Zobacz</a> | ';
         $actions .= '<a href="$/pages/edit/' . $id . '" title="Edytuj stronę">Edytuj</a> | ';
         $actions .= '<a href="$/pages/delete/' . $id . '" title="Usuń stronę">Usuń</a>';
         
         //--
         
         $pageInfo = $title . '<br>';
         $pageInfo .= $content;
         $pageInfo .= '<div class="acp-actions">' . $actions . '</div>';
         
         //--
         
         $dates  = '<small>' . HumanDate($page->created, true, true);
         $dates .= '<br>(' . HumanDate($page->updated, true, true) . ')</small>'; //TODO: + by [author]
         
         //--
         
         $allComments        = $page->commentsCount;
         $unapprovedComments = $allComments - $page->approvedCommentsCount;
         
         $comments = $allComments;
         
         if($unapprovedComments > 0)
         {
            $comments .= ' <strong><a href="#/' . $name . '#comments-link">(' . $unapprovedComments . ' do sprawdzenia!)</a></strong>';
         }
         
         //--
         
         $cells = array($pageInfo, $dates, $comments);
         
         $table->addRow($id, $cells);
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
      
      // inputs args
      
      $contentArgs = array('style' => 'width: 100%; height:30em');
      $nameArgs    = array('labelNote' => 'Opcjonalnie - jeśli nie podasz, to zostanie wygenerowana automatycznie');
      
      // adding inputs
      
      $form->addInput('text', 'title', 'Tytuł', true);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      $form->addInput('text', 'name', 'Nazwa', false, $nameArgs);
      
      echo $form->generate();
   }
   
   /*
    * new page submit
    */
   
   function newSubmit_action()
   {
      $form = Form::validate('wmelon.pages.newPage', 'pages/new');
      $data = $form->get();
      
      $id = $this->model->postPage($data->title, $data->name, $data->content);
      
      $this->addMessage('tick', 'Dodano stronę!');
      
      SiteRedirect('pages/edit/' . $id);
   }
   
   /*
    * edit page
    */
   
   function edit_action($id)
   {
      $id = (int) $id;
      
      // getting data
      
      $data = $this->model->pageData_id($id);
      
      if(!$data)
      {
         SiteRedirect('pages');
      }
      
      // back to link
      
      if($this->parameters->backto == 'site')
      {
         $backTo = '/backTo:site';
         
         $backToLabel = ' lub <a href="#/' . $data->name . '">powróć do strony</a>';
      }
      else
      {
         $backToLabel = ', <a href="$/pages/">powróć do listy stron</a> lub <a href="#/' . $data->name . '">obejrzyj stronę</a>';
      }
      
      // form options
      
      $this->pageTitle = 'Edytuj stronę';
      
      $form = new Form('wmelon.pages.editPage', 'pages/editSubmit/' . $id . $backTo, 'pages/edit/' . $id . $backTo);
      
      $form->displaySubmitButton = false;
      
      // inputs labels
      
      $nameLabel = 'Nie zmieniaj, jeśli <em>naprawdę</em> nie wiesz co robisz';
      
      // inputs args
      
      $titleArgs   = array('value' => $data->title);
      $contentArgs = array('value' => $data->content, 'style' => 'width: 100%; height:30em');
      $nameArgs    = array('value' => $data->name, 'labelNote' => $nameLabel);
      
      // adding inputs
      
      $form->addInput('text', 'title', 'Tytuł', true, $titleArgs);
      $form->addInput('textarea', 'content', 'Treść', true, $contentArgs);
      $form->addInput('text', 'name',  'Nazwa', true, $nameArgs);
      
      $form->addHTML('<label><span></span><input type="submit" value="Zapisz">' . $backToLabel . '</label>');
      
      echo $form->generate();
   }
   
   /*
    * edit page submit
    */
   
   function editSubmit_action($id, $backPage)
   {
      $id = (int) $id;
      
      $backTo = ($this->parameters->backto == 'site') ? '/backTo:site' : '';
      
      // checking if exists
      
      if(!$this->model->pageData_id($id))
      {
         SiteRedirect('pages');
      }
      
      // editing
      
      $form = Form::validate('wmelon.pages.editPage', 'pages/edit/' . $id . $backTo);
      $data = $form->get();
      
      $this->model->editPage($id, $data->title, $data->name, $data->content);
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano stronę');
      
      SiteRedirect('pages/edit/' . $id . $backTo);
   }
   
   /*
    * delete page
    */

   function delete_action($ids, $backPage)
   {
      AdminQuick::bulkAction('delete', 'pages', $ids, $backPage,
         function($ids, $model)
         {
            return 'Czy na pewno chcesz usunąć ' . count($ids) . ' stron';
         });
   }

   /*
    * delete page submit
    */

   function delete_submit_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('pages', $ids, $backPage,
         function($ids, $model)
         {
            $model->deletePages($ids);
         },
         function($count)
         {
            return 'Usunięto ' . $count . ' stron';
         });
   }
}