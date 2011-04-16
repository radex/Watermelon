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
      
      // input values
      
      $config = Watermelon::$config;
      
      $siteName       = $config->siteName;
      $siteSlogan     = $config->siteSlogan;
      $footer         = $config->footer;
      $head           = $config->headTags;
      $tail           = $config->tailTags;
      
      $userData = Users::userData();
      
      $login          = $userData->login;
      $nick           = $userData->nick;
      $email          = $userData->email;
      
      $sblamKey       = Config::get('wmelon.sblam.apiKey');
      
      // label notes
      
      $siteSlogan_label     = 'W kilku słowach opisz o czym jest ta strona';
      $footer_label         = 'Możesz używać HTML<br>oraz <em>$/</em> dla linków na stronie';
      $head_label           = '"sekcja &lt;head&gt;"; skrypty, arkusze stylów itp.';
      $tail_label           = 'Skrypty, dodane na końcu strony';
      
      $login_label          = 'Nazwa użyta podczas logowania. Uważaj, żeby przypadkiem nie zmienić.';
      $nick_label           = 'Nazwa pokazywana przy Twoich komentarzach itp.';
      $email_label          = 'Zostanie użyty obok Twoich komentarzy do pokazania <a href="http://gravatar.com/" target="_blank">gravatara</a>';
      
      if(!empty($email))
      {
         $email_label .= '<br>Podgląd: ';
         
         $email_label .= '<img src="http://gravatar.com/avatar/' . md5($email) . '?s=64&amp;d=mm" style="vertical-align:middle" />';
      }
      
      $sblamKey_label       = '';
      
      // input args
      
      $siteName       = array('value' => $siteName);
      $siteSlogan     = array('value' => $siteSlogan,     'labelNote' => $siteSlogan_label);
      $footer         = array('value' => $footer,         'labelNote' => $footer_label);
      $head           = array('value' => $head,           'labelNote' => $head_label);
      $tail           = array('value' => $tail,           'labelNote' => $tail_label);
      
      $login          = array('value' => $login,          'labelNote' => $login_label);
      $nick           = array('value' => $nick,           'labelNote' => $nick_label);
      $email          = array('value' => $email,          'labelNote' => $email_label);
      
      $sblamKey       = array('value' => $sblamKey,       'labelNote' => $sblamKey_label);
      
      // adding inputs
      
      $form->addHTML('<fieldset id="siteOptions"><legend>Strona</legend>');
      $form->addInput('text',     'siteName',       'Nazwa strony',                   true,  $siteName);
      $form->addInput('text',     'siteSlogan',     'Slogan strony',                  false, $siteSlogan);
      $form->addInput('textarea', 'footer',         'Stopka',                         false, $footer);
      $form->addInput('textarea', 'head',           'Własne tagi na początek strony', false, $head);
      $form->addInput('textarea', 'tail',           'Własne tagi na koniec strony',   false, $tail);
      $form->addHTML('</fieldset>');
      
      $form->addHTML('<fieldset id="adminOptions"><legend>Ty</legend>');
      $form->addInput('text',     'login',          'Twój login',                     true,  $login);
      $form->addInput('text',     'nick',           'Twój nick',                      false, $nick);
      $form->addInput('email',    'email',          'Twój email',                     false, $email);
      $form->addInput('password', 'pass',           'Twoje hasło',                    false);
      $form->addInput('password', 'pass2',          'Twoje hasło (powtórz)',          false);
      $form->addHTML('</fieldset>');
      
      $form->addHTML('<fieldset id="sblamOptions"><legend>Sblam!</legend>');
      $form->addHTML('<p>Sblam! to filtr antyspamowy użyty w Watermelonie. Aby działał poprawnie należy ustalić dla niego <em>klucz API</em>. Własny klucz możesz <a href="http://sblam.com/key.html">wygenerować na stronie Sblam!</a></p>');
      $form->addInput('text',     'sblamKey',       'Klucz API',                      false, $sblamKey);
      $form->addHTML('</fieldset>');
      
      // rendering
      
      echo $form->generate();
   }
   
   /*
    * General - submit
    */
   
   function general_save_action()
   {
      $form = Form::validate('wmelon.options.general', 'options/general');
      $data = $form->get();
      
      // saving data - wmelon config
      
      $config = Watermelon::$config;
      
      $config->siteName       = $data->siteName;
      $config->siteSlogan     = $data->siteSlogan;
      $config->footer         = $data->footer;
      $config->headTags       = $data->head;
      $config->tailTags       = $data->tail;
      
      Config::set('wmelon.wmelon', $config);
      
      // verifying data - passwords
      
      if(!empty($data->pass) && $data->pass !=  $data->pass2)
      {
         $form->addError('Podane hasła nie są identyczne');
         $form->fallBack();
      }
      
      // saving data - userdata
      
      $fields = array
         (
            'login' => $data->login,
            'nick'  => $data->nick,
            'email' => $data->email,
         );
      
      if(!empty($data->pass))
      {
         $fields['password'] = sha1($data->pass . Users::userData()->salt);
         $_SESSION['wmelon.user.pass'] = $data->pass;
      }
      
      DB::update('users', 1, $fields);
      
      $_SESSION['wmelon.user.login'] = $data->login;
      
      // saving data - sblam!
      
      Config::set('wmelon.sblam.apiKey', $data->sblamKey);
      
      // redirecting
      
      $this->displaySuccessNotice('Zaktualizowano ustawienia');
      
      SiteRedirect('options/general');
   }
   
   /*
    * Navigation (menu) options
    */
   
   function nav_action()
   {
      $this->pageTitle = 'Ustawienia nawigacji';
      
      // getting menu data
      
      $menu_orig = Watermelon::$config->textMenus[0];
      
      // if no menu items
      
      if(empty($menu_orig))
      {
         View('noNavItems')->display();
         return;
      }
      
      // recomposing array
      
      foreach($menu_orig as $i => $item)
      {
         $menuItem = new stdClass;
         
         $menuItem->name       = $item[0];
         $menuItem->url        = $item[1];
         $menuItem->relative   = $item[2];
         $menuItem->title      = $item[3];
         
         $menu[] = $menuItem;
      }
      
      // rendering
      
      $view = View('navOptions');
      $view->menu = $menu;
      
      $iconsPath = BundlesURL . 'watermelon/public/';
      
      $view->topIcon    = $iconsPath . 'options-img/top.png';
      $view->upIcon     = $iconsPath . 'options-img/up.png';
      $view->downIcon   = $iconsPath . 'options-img/down.png';
      $view->bottomIcon = $iconsPath . 'options-img/bottom.png';
      $view->deleteIcon = $iconsPath . 'img/delete.png';
      
      $view->display();
      
      Watermelon::$headTags[] = View('navOptionsHtml')->generate();
   }
   
   /*
    * Navigation - submit
    */
   
   function nav_save_action($id, $action)
   {
      $id     = (int) $id;
      $action = strtolower($action);
      
      // validating action
      
      if(!in_array($action, array('top', 'up', 'down', 'bottom', 'delete', 'add')))
      {
         $action = false;
      }
      
      // composing $nav
      
      foreach($_POST as $key => $value)
      {
         list($key, $i) = explode('_', $key);
         
         $nav_post[$i]->$key = $value;
      }
      
      // filtering $nav
      
      foreach($nav_post as $item)
      {
         $item->name     = trim($item->name);
         $item->url      = trim($item->url);
         $item->relative = isset($item->relative);
         $item->title    = trim($item->title);
         
         if(empty($item->title))
         {
            $item->title = null;
         }
         
         $nav[] = $item;
      }
      
      // performing action if necessary
      
      if($action == 'top' || $action == 'bottom')
      {
         // recreating array, omiting given item
         
         foreach($nav as $i => $item)
         {
            if($i != $id)
            {
               $nav2[] = $item;
            }
         }
         
         // adding given item on top or bottom
         
         if($action == 'top')
         {
            array_unshift($nav2, $nav[$id]);
         }
         else
         {
            array_push($nav2, $nav[$id]);
         }
         
         $nav = $nav2;
      }
      elseif($action == 'up')
      {
         // swapping items
         
         $item = $nav[$id];
         
         $nav[$id]     = $nav[$id - 1];
         $nav[$id - 1] = $item;
      }
      elseif($action == 'down')
      {
         // swapping items
         
         $item = $nav[$id];
         
         $nav[$id]     = $nav[$id + 1];
         $nav[$id + 1] = $item;
      }
      elseif($action == 'delete')
      {
         // recreating array, omiting given item
         
         foreach($nav as $i => $item)
         {
            if($i != $id)
            {
               $nav2[] = $item;
            }
         }
         
         $nav = $nav2;
      }
      elseif($action == 'add')
      {
         $item = new stdClass;
         
         $item->name     = '';
         $item->url      = '';
         $item->relative = true;
         $item->title    = '';
         
         $nav[] = $item;
      }
      
      // saving and redirecting
      
      $textMenu = array();
      
      foreach($nav as $item)
      {
         $textMenu[] = array($item->name, $item->url, $item->relative, $item->title);
      }
      
      Watermelon::$config->textMenus = array($textMenu);
      
      Config::set('wmelon.wmelon', Watermelon::$config);
      
      SiteRedirect('options/nav');
   }
}