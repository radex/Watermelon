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
 * Pages management
 */

class Pages_Controller extends Controller
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
      // determining posts scope (published/trash)
      
      switch($this->parameters->scope)
      {
         case 'trash':
            $scopeLabel = ' (kosz)';
            $scope = 'trash';
         break;
         
         default:
            $scope = 'published';
         break;
      }
      
      // fetching pages
      
      $pages = $this->model->pages($scope);
      
      // if no pages
      
      if($pages->empty)
      {
         $view = View('admin/list');
         $view->counts = $this->model->counts();
         $view->table  = null;
         
         $view->display();
         
         return;
      }
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Strona', 'Data', 'Komentarzy');
      
      // actions for selected posts
      
      if($scope == 'trash')
      {
         $table->selectedActions[] = array('Usuń na zawsze', 'pages/delete/', 'Nieodwracalnie usuń zaznaczone strony');
         $table->selectedActions[] = array('Przywróć',       'pages/untrash/', 'Przywróć zaznaczone strony z kosza');
      }
      else
      {
         $table->selectedActions[] = array('Usuń', 'pages/trash/', 'Przenieś zaznaczone strony do kosza');
      }
      
      // adding pages
      
      foreach($pages as $page)
      {
         $id = $page->id;
         
         //--
            
            $title = '<strong>' . $page->title . '</strong>';
            
            // title as link to edit (not in trash)
            
            if($scope != 'trash')
            {
               $title = '<a href="$/pages/edit/' . $id . '" title="Edytuj stronę">' . $title . '</a>';
            }
            
         //--
         
            $content = nl2br(strip_tags($page->content));
         
            if(mb_strlen($content) > 130)
            {
               $content = mb_substr($content, 0, 130) . ' (...)';
            }
         
         //--
         
            $actions = '';
            
            if($scope == 'published')
            {
               $actions .= '<a href="#/' . $page->name . '" title="Obejrzyj na stronie">Zobacz</a> | ';
               $actions .= '<a href="$/pages/edit/' . $id . '" title="Edytuj stronę">Edytuj</a> | ';
               $actions .= '<a href="$/pages/trash/' . $id . '" title="Przenieś stronę do kosza">Usuń</a>';
            }
            else
            {
               $actions .= '<a href="$/pages/delete/' . $id . '" title="Nieodwracalnie usuń stronę">Usuń na zawsze</a>';
               $actions .= ' | <a href="$/pages/untrash/' . $id . '" title="Przywróć stronę z kosza">Przywróć</a>';
            }
            
         
         //--
         
            $pageInfo = $title . '<br>';
            $pageInfo .= '<small>' . $content . '</small><br>';
            $pageInfo .= '<div class="acp-actions">' . $actions . '</div>';
         
         //--
         
            $dates  = '<small>' . HumanDate($page->created, true, true);
            $dates .= '<br>(' . HumanDate($page->updated, true, true) . ')</small>'; //TODO: + by [author]
            
            $dates = '<small>';
            
            $dates .= 'utworzona ' . HumanDate($page->created, true, true);
            
            if($page->updated > $page->created)
            {
               $dates .= ', <br>zmieniona ' . HumanDate($page->updated, true, true);
            }
            
            $dates .= '</small>';
         
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
      
      $this->pageTitle = 'Lista stron' . $scopeLabel;
      
      $view = View('admin/list');
      $view->counts = $this->model->counts();
      $view->pages  = $pages;
      $view->table  = $table->generate();
      
      $view->display();
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
      
      $this->displaySuccessNotice('Dodano stronę!');
      
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
      
      $this->displaySuccessNotice('Zaktualizowano stronę');
      
      SiteRedirect('pages/edit/' . $id . $backTo);
   }
   
   /**************************************************************************/
   
   /*
    * move posts to trash
    */

   function trash_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('pages', $ids, $backPage,
         function($ids, $model)
         {
            $model->changeStatus($ids, 'trash');
         },
         function($count)
         {
            return 'Przeniesiono ' . $count . ' stron do kosza'; //TODO: undo
         });
   }
   
   /*
    * move posts back from the trash
    */

   function untrash_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('pages', $ids, $backPage,
         function($ids, $model)
         {
            $model->changeStatus($ids, 'published');
         },
         function($count)
         {
            return 'Przywrócono ' . $count . ' stron z kosza';
         });
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