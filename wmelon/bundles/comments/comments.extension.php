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
 * Comments extension
 */

class Comments_Extension extends Extension
{
   /*
    * public string commentsView(int $id, string $type, string $backPage)
    * 
    * Returns output of comments view for $id record of $type type of content (blog post, page, etc.)
    * 
    * string $backPage - name of page (on the same website) on which comment view will be displayed (name of page to go back on after posting a comment), e.g.: 'blog/post/1'
    */
   
   public static function commentsView($id, $type, $backPage)
   {
      $id       = (int) $id;
      $type     = (string) $type;
      $backPage = (string) $backPage;
      
      $model    = Loader::model('comments');
      $comments = $model->commentsFor($id, $type);
      
      // form
      
      $submitPage = 'comments/post/' . $id . '/' . $type . '/' . base64_encode($backPage);
      
      $form = new Form('wmelon.comments.addComment', $submitPage, $backPage . '#commentForm-link');
      $form->globalMessages = false;
      $form->submitLabel = 'Zapisz';
      
      $form->addInput('text', 'name', 'Imię');
      $form->addInput('email', 'email', 'Email');
      $form->addInput('text', 'website', 'Strona', false, array('labelNote' => '(Opcjonalnie)'));
      $form->addInput('textarea', 'text', 'Treść komentarza');
      
      // view
      
      $view = Loader::view('comments/comments', true);
      
      $view->comments = $comments;
      $view->id = $id;
      $view->type = $type;
      $view->backPage = $backPage;
      $view->form = $form->generate();
      
      return $view->display(true);
   }
}

class Comments extends Comments_Extension{}