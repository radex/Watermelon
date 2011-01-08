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
      // determining posts scope (all/trash/drafts/published)
      
      switch($this->parameters->scope)
      {
         case 'trash':
            $scopeLabel = ' (kosz)';
            $scope = 'trash';
         break;
         
         case 'drafts':
            $scopeLabel = ' (szkice)';
            $scope = 'drafts';
         break;
         
         case 'published':
            $scopeLabel = ' (opublikowane)';
            $scope = 'published';
         break;
         
         default:
            $scope = 'all';
         break;
      }
      
      // fetching posts
      
      $posts = $this->model->allPosts($scope);
      
      $commentsModel = Model('comments');
      
      // if no blog posts
      
      if($posts->empty)
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
      $table->header = array('Tytuł', '<small>Napisany (uaktualniony)</small>', 'Komentarzy');
      
      // actions for selected posts
      
      if($scope == 'trash')
      {
         $table->selectedActions[] = array('Usuń na zawsze', 'blog/delete/');
         $table->selectedActions[] = array('Przywróć',       'blog/untrash/');
      }
      else
      {
         $table->selectedActions[] = array('Usuń', 'blog/trash/');
      }
      
      // adding posts
      
      foreach($posts as $post)
      {
         $id = $post->id;
         
         //--
         
            $title = '<a href="$/blog/edit/' . $id . ' title="Edytuj wpis">' . $post->title . '</a>';
         
            // draft marker (only for 'all' scope)
         
            if($post->status == 'draft' && $scope == 'all')
            {
               $title = '(Szkic) ' . $title;
            }
         
         //--
         
            $content = strip_tags($post->content);
         
            if(strlen($content) > 130)
            {
               $content = substr($content, 0, 130) . ' (...)';
            }
         
         //--
         
            $linkTo = '#/' . date('Y/m', $post->created) . '/' . $post->name;
         
            $actions = '';
            $actions .= '<a href="' . $linkTo . '" title="Obejrzyj wpis na stronie">Zobacz</a>';
            $actions .= ' | <a href="$/blog/edit/' . $id . '" title="Edytuj wpis">Edytuj</a>';
            
            // deleting or moving to trash (depending on scope)
            
            if($scope == 'trash')
            {
               $actions .= ' | <a href="$/blog/delete/' . $id . '" title="Nieodwracalnie usuń wpis">Usuń na zawsze</a>';
            }
            else
            {
               $actions .= ' | <a href="$/blog/trash/' . $id . '" title="Przenieś wpis do kosza">Usuń</a>';
            }
            
            // moving posts from trash to drafts (in trash scope)
            
            if($scope == 'trash')
            {
               $actions .= ' | <a href="$/blog/untrash/' . $id . '" title="Przywróć wpis z kosza do szkiców">Przywróć</a>';
            }
            
         //--
         
            $postInfo  = $title . '<br>';
            $postInfo .= '<small>' . $content . '</small><br>';
            $postInfo .= '<div class="acp-actions">' . $actions . '</div>';
         
         //--
         
            $dates = '<small>' . HumanDate($post->created, true, true) . '<br>(' . HumanDate($post->updated, true, true) . ')</small>'; //TODO: + by [author]
         
         //--
         
            $allComments        = $post->commentsCount;
            $unapprovedComments = $allComments - $post->approvedCommentsCount;
         
            $comments = $allComments;
         
            if($unapprovedComments > 0)
            {
               $comments .= ' <strong><a href="' . $linkTo . '#comments-link">(' . $unapprovedComments . ' do sprawdzenia!)</a></strong>';
            }
         
         //--
         
         $table->addLine($id, $postInfo, $dates, $comments);
      }
      
      // displaying
      
      $this->pageTitle = 'Lista wpisów' . $scopeLabel;
      
      $view = View('admin/list');
      $view->counts = $this->model->counts();
      $view->posts  = $posts;
      $view->table  = $table->generate();
      
      $view->display();
   }
   
   /*
    * new post
    */
   
   function new_action()
   {
      // options
      
      $this->pageTitle = 'Nowy wpis';
      
      $form = new Form('wmelon.blog.newPost', 'blog/newSubmit', 'blog/new');
      
      $form->displaySubmitButton = false;
      
      // label notes
      
      $summary_note = 'Jeśli chcesz, napisz krótko wstęp lub streszczenie wpisu - zostanie ono pokazane na stronie głównej i w czytnikach kanałów';
      
      // input args
      
      $contentArgs       = array('style' => 'width: 100%; height:30em');
      $summaryArgs       = array('labelNote' => $summary_note);
      $allowCommentsArgs = array('value' => true);
      
      // adding inputs
      
      $form->addInput('text',     'title',           'Tytuł',                true);
      $form->addInput('textarea', 'content',         'Treść',                true,  $contentArgs);
      $form->addInput('textarea', 'summary',         'Streszczenie',         false, $summaryArgs);
      $form->addInput('checkbox', 'allowComments',   'Pozwól na komentarze', false, $allowCommentsArgs);
      
      // submit buttons (save and publish)
      
      $form->addHTML('<br><label><span></span>');
      $form->addHTML('<input type="submit" name="submit_save" value="Zapisz">');
      $form->addHTML('<input type="submit" name="submit_publish" value="Publikuj">');
      $form->addHTML('</label>');
      
      // displaying
      
      echo $form->generate();
   }
   
   /*
    * new post submit
    */
   
   function newSubmit_action()
   {
      $form = Form::validate('wmelon.blog.newPost', 'blog/new');
      $data = $form->getAll();
      
      // determining action - save or publish
      
      if(isset($_POST['submit_publish']))
      {
         $publish = true;
      }
      else
      {
         $publish = false;
      }
      
      // posting
      
      $id = $this->model->postPost($publish, $data->title, $data->content, $data->summary, $data->allowComments);
      
      // redirecting
      
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
      
      // options
      
      $this->pageTitle = 'Edytuj wpis';
      
      $form = new Form('wmelon.blog.editPost', 'blog/editSubmit/' . $id . $backTo, 'blog/edit/' . $id . $backTo);
      
      $form->displaySubmitButton = false;
      
      // label notes
      
      $summary_note = 'Jeśli chcesz, napisz krótko wstęp lub streszczenie wpisu - zostanie ono pokazane na stronie głównej i w czytnikach kanałów';
      
      // inputs args
      
      $titleArgs         = array('value' => $data->title);
      $contentArgs       = array('value' => $data->content, 'style' => 'width: 100%; height:30em');
      $summaryArgs       = array('value' => $data->summary, 'labelNote' => $summary_note);
      $allowCommentsArgs = array('value' => $data->allowComments);
      
      // adding inputs
      
      $form->addInput('text',     'title',           'Tytuł',                true,  $titleArgs);
      $form->addInput('textarea', 'content',         'Treść',                true,  $contentArgs);
      $form->addInput('textarea', 'summary',         'Streszczenie',         false, $summaryArgs);
      $form->addInput('checkbox', 'allowComments',   'Pozwól na komentarze', false, $allowCommentsArgs);
      
      // submit buttons
      
      $form->addHTML('<br><label><span></span>');
      $form->addHTML('<input type="submit" name="submit_save" value="Zapisz">');
      
      if($data->status == 'draft')
      {
         $form->addHTML('<input type="submit" name="submit_publish" value="Opublikuj">');
      }
      
      $form->addHTML($backToLabel);
      $form->addHTML('</label>');
      
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
      
      $this->model->editPost($id, $data->title, $data->content, $data->summary, $data->allowComments);
      
      // updating status (if 'Publish' button selected)
      
      if(isset($_POST['submit_publish']))
      {
         $this->model->changeStatus($id, 'published');
         
         $this->addMessage('tick', 'Opublikowano wpis');
      }
      
      // redirecting
      
      $this->addMessage('tick', 'Zaktualizowano wpis');
      
      SiteRedirect('blog/edit/' . $id . $backTo);
   }

   /*
    * move posts to trash
    */

   function trash_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('blog', $ids, $backPage,
         function($ids, $model)
         {
            $model->changeStatus($ids, 'trash');
         },
         function($count)
         {
            return 'Przeniesiono ' . $count . ' postów do kosza';
         });
   }
   
   /*
    * move posts from trash to drafts
    */

   function untrash_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('blog', $ids, $backPage,
         function($ids, $model)
         {
            $model->changeStatus($ids, 'draft');
         },
         function($count)
         {
            return 'Przywrócono ' . $count . ' postów z kosza';
         });
   }
   
   /*
    * delete post
    */

   function delete_action($ids, $backPage)
   {
      AdminQuick::bulkAction('delete', 'blog', $ids, $backPage,
         function($ids, $model)
         {
            return 'Czy na pewno chcesz usunąć ' . count($ids) . ' wpisów?';
         });
   }

   /*
    * delete post submit
    */

   function delete_submit_action($ids, $backPage)
   {
      AdminQuick::bulkActionSubmit('blog', $ids, $backPage,
         function($ids, $model)
         {
            $model->deletePosts($ids);
         },
         function($count)
         {
            return 'Usunięto ' . $count . ' wpisów';
         });
   }
}