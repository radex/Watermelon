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
 * Options
 */

class Options_Controller extends Controller
{
   /*
    * subnav config
    */
   
   function __construct()
   {
      parent::__construct();
      
      $subNav[] = array('Ustawienia ogólne', null, 'options/general');
      $subNav[] = array('Menu', null, 'options/nav');
      
      $this->subNav = $subNav;
   }
   
   /*
    * redirection to general options
    */
   
   function index_action()
   {
      SiteRedirect('options/general');
   }
   
   /*
    * General options
    */
   
   function general_action()
   {
      $this->pageTitle = 'Ustawienia ogólne';
      
      $form = new Form('wmelon.options.general', 'options/general_save', 'options/general');
      
      // label notes
      
      $siteSlogan_label     = 'W kilku słowach opisz o czym jest ta strona';
      $footer_label         = 'Możesz używać HTML';
      $head_label           = '"sekcja &lt;head&gt;"; skrypty, arkusze stylów itp.';
      $tail_label           = 'Skrypty, dodane na końcu strony';
      
      // input values
      
      $config = Watermelon::$config;
      
      $siteName       = $config->siteName;
      $siteSlogan     = $config->siteSlogan;
      $footer         = $config->footer;
      $head           = $config->headTags;
      $tail           = $config->tailTags;
      
      // input args
      
      $siteName       = array('value' => $siteName);
      $siteSlogan     = array('value' => $siteSlogan, 'labelNote' => $siteSlogan_label);
      $footer         = array('value' => $footer, 'style' => 'width: 70%',   'labelNote' => $footer_label);
      $head           = array('value' => $head, 'style' => 'width: 70%',   'labelNote' => $head_label);
      $tail           = array('value' => $tail, 'style' => 'width: 70%',   'labelNote' => $tail_label);
      
      // adding inputs
      
      $form->addInput('text',     'siteName',       'Nazwa strony',                   true,  $siteName);
      $form->addInput('text',     'siteSlogan',     'Slogan strony',                  false, $siteSlogan);
      $form->addInput('textarea', 'footer',         'Stopka',                         false, $footer);
      $form->addInput('textarea', 'head',           'Własne tagi na początek strony', false, $head);
      $form->addInput('textarea', 'tail',           'Własne tagi na koniec strony',   false, $tail);
      
      // rendering
      
      echo $form->generate();
   }
   
   /*
    * General - submit
    */
   
   function general_save_action()
   {
      $form = Form::validate('wmelon.options.general', 'options/general');
      $data = $form->getAll();
      
      // saving data
      
      $config = Watermelon::$config;
      
      $config->siteName       = $data->siteName;
      $config->siteSlogan     = $data->siteSlogan;
      $config->footer         = $data->footer;
      $config->headTags       = $data->head;
      $config->tailTags       = $data->tail;
      
      $this->registry->set('wmelon', $config);
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano ustawienia');
      
      SiteRedirect('options/general');
   }
   
   /*
    * Navigation (menu) options
    */
   
   function nav_action()
   {
      $this->pageTitle = 'Ustawienia nawigacji';
      
      // getting menu data
      
      $menuObj = Watermelon::$config->textMenus[0];
      
      foreach($menuObj as $i => $item)
      {
         $menuItem = new stdClass;
         
         $menuItem->name       = htmlspecialchars($item[0]);
         $menuItem->url        = htmlspecialchars($item[1]);
         $menuItem->relative   = !$item[2];
         $menuItem->title      = htmlspecialchars($item[3]);
         
         $menu[] = $menuItem;
      }
      
      // rendering
      
      $view = View('navOptions');
      $view->menu = $menu;
      
      $iconsPath = WM_BundlesURL . 'watermelon/public/';
      
      $view->topIcon    = $iconsPath . 'options-img/top.png';
      $view->upIcon     = $iconsPath . 'options-img/up.png';
      $view->downIcon   = $iconsPath . 'options-img/down.png';
      $view->bottomIcon = $iconsPath . 'options-img/bottom.png';
      $view->deleteIcon = $iconsPath . 'img/delete.png';
      
      $view->display();
      
      Watermelon::$headTags[] = View('navOptionsHtml')->display(true);
   }
   
   /*
    * Navigation - submit
    */
   
   function nav_save_action($id, $action)
   {
      $id     = (int) $id;
      $action = strtolower($action);
      
      // validating action
      
      switch($action)
      {
         case 'top':
         case 'up':
         case 'down':
         case 'bottom':
         case 'delete':
         break;
         
         default:
            $action = false;
         break;
      }
      
      // composing $nav
      
      foreach($_POST as $key => $value)
      {
         list($key, $i) = explode('_', $key);
         
         $nav[$i]->$key = $value;
      }
      
      // filtering $nav
      
      foreach($nav as $item)
      {
         $item->name     = trim($item->name);
         $item->url      = trim($item->url);
         $item->relative = isset($item->relative);
         $item->title    = trim($item->title);
         
         if(empty($item->title))
         {
            $item->title = null;
         }
         
         $nav2[] = $item;
      }
      
      $nav = $nav2; //TODO: fix
      unset($nav2);
      
      // performing action if necessary
      
      //TODO: simplify code
      
      if($action == 'top' || ($action == 'up' && $id == 1))
      {
         $nav2[] = $nav[$id];
         
         foreach($nav as $i => $item)
         {
            if($i != $id)
            {
               $nav2[] = $item;
            }
         }
         
         $nav = $nav2;
      }
      elseif($action == 'bottom' || ($action == 'down' && $id == count($nav) - 2))
      {
         foreach($nav as $i => $item)
         {
            if($i != $id)
            {
               $nav2[] = $item;
            }
         }
         
         $nav2[] = $nav[$id];
         
         $nav = $nav2;
      }
      elseif($action == 'up')
      {
         foreach($nav as $i => $item)
         {
            if($i == ($id - 1))
            {
               $nav2[] = $nav[$id];
            }
            elseif($i == $id)
            {
               $nav2[] = $nav[$id - 1];
            }
            else
            {
               $nav2[] = $item;
            }
         }
         
         $nav = $nav2;
      }
      elseif($action == 'down')
      {
         foreach($nav as $i => $item)
         {
            if($i == ($id + 1))
            {
               $nav2[] = $nav[$id];
            }
            elseif($i == $id)
            {
               $nav2[] = $nav[$id + 1];
            }
            else
            {
               $nav2[] = $item;
            }
         }
         
         $nav = $nav2;
      }
      elseif($action == 'delete')
      {
         foreach($nav as $i => $item)
         {
            if($i != $id)
            {
               $nav2[] = $item;
            }
         }
         
         $nav = $nav2;
      }
      
      // saving and redirecting
      
      foreach($nav as $item)
      {
         $textMenu[] = array($item->name, $item->url, !$item->relative, $item->title);
      }
      
      Watermelon::$config->textMenus = array($textMenu);
      
      $this->registry->set('wmelon', Watermelon::$config);
      
      SiteRedirect('options/nav');
   }
}