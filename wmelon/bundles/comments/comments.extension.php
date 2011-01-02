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

class Comments extends Extension
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
      $auth     = Loader::model('auth');
      
      $users = array();
      
      // comments
      
      $commentsObj = $model->commentsFor($id, $type);
      
      $approvedCount   = 0;   // counter of approved comments
      $unapprovedCount = 0;   // counter of unapproved comments
      
      // visibilityToken
      
      if(!Auth::isLogged() && isset($_SESSION['wmelon.comments.visibilityToken']))
      {
         $visibilityToken = $_SESSION['wmelon.comments.visibilityToken'];
      }
      
      // composing comments array
      
      foreach($commentsObj as $comment)
      {
         // tools
         
         $linkEnding = $comment->id . '/' . base64_encode($backPage . '#comment-' . $comment->id);
         
         $comment->editHref    = '%/comments/edit/'    . $linkEnding;
         $comment->deleteHref  = '%/comments/delete/'  . $comment->id . '/' . base64_encode($backPage . '#comments-link');
         $comment->approveHref = '%/comments/approve/' . $linkEnding;
         $comment->rejectHref  = '%/comments/reject/'  . $linkEnding;
         
         // visibility
         // (comment is visible if admin or comment is approved or comment visibility token match user's visibility token)
         
         $comment->visible = (Auth::isLogged() || !$comment->awaitingModeration || ($comment->visibilityToken == $visibilityToken && !empty($comment->visibilityToken)));
         
         // additionalInformation (for admin)
         
         if(Auth::isLogged() && $authorID === null)
         {
            $comment->additionalInfo = $comment->authorEmail . '; IP:' . $comment->authorIP;
         }
         
         // if commented as logged user
         
         $authorID = $comment->authorID;
         
         if($authorID !== null)
         {
            // if no user information in array, selecting from database
            
            if(empty($users[$authorID]))
            {
               $users[$authorID] = $auth->userData_id($authorID);
            }
            
            // CSS class (for admin comments distinction)
            
            $comment->cssClass = 'adminComment';
         }
         
         // gravatar url
         
         $gravatarEnding = '?s=64&d=' . urlencode(WM_BundlesURL) . 'watermelon/public/img/blank.png';
         
         if($authorID === null)
         {
            $comment->gravatarURL = 'http://gravatar.com/avatar/' . md5($comment->authorEmail) . $gravatarEnding;
         }
         else
         {
            $comment->gravatarURL = 'http://gravatar.com/avatar/' . md5($users[$authorID]->email) . $gravatarEnding;
         }
         
         // comments counter
         
         if($comment->awaitingModeration)
         {
            $unapprovedCount++;
         }
         else
         {
            $approvedCount++;
         }
         
         //--
         
         $comments[] = $comment;
      }
      
      // form
      
      $submitPage = 'comments/post/' . $id . '/' . $type . '/' . base64_encode($backPage);
      
      $form = new Form('wmelon.comments.addComment', $submitPage, $backPage . '#commentForm-link');
      $form->globalMessages = false;
      $form->submitLabel = 'Zapisz';
      
      // user data inputs (if not logged in)
      
      if(!Auth::isLogged())
      {
         // remembered user data
         
         $name    = $_SESSION['wmelon.comments.name'];
         $email   = $_SESSION['wmelon.comments.email'];
         $website = $_SESSION['wmelon.comments.website'];
         
         // inputs args
         
         $name    = array('value' => $name);
         $email   = array('value' => $email);
         $website = array('value' => $website, 'labelNote' => '(Opcjonalnie)');

         
         // adding inputs
         
         $form->addInput('text',  'name',    'Imię',   true,  $name);
         $form->addInput('email', 'email',   'Email',  true,  $email);
         $form->addInput('url',   'website', 'Strona', false, $website);
      }
      
      // content input
      
      $form->addInput('textarea', 'content', 'Treść komentarza');
      
      // comments counter
      
      $commentsCount = (Auth::isLogged() ? $commentsObj->rows : $approvedCount); // number of visible (approved) comments - for user and all comments - for admin
      
      $commentsCount = $commentsCount . ' ' . pl_inflect($commentsCount, 'komentarzy', 'komentarz', 'komentarze');
      
      if(Auth::isLogged() && $unapprovedCount > 0)
      {
         $commentsCount .= ' <span class="important">(' . $unapprovedCount . ' do sprawdzenia!)</span>';
      }
      
      // view
      
      $view = Loader::view('comments/comments', true);
      
      $view->comments      = $comments;
      $view->areComments   = $commentsObj->exists;
      $view->commentsCount = $commentsCount;
      $view->users         = $users;
      
      $view->visibilityToken = $visibilityToken;
      
      $view->id            = $id;
      $view->type          = $type;
      $view->backPage      = $backPage;
      
      $view->form          = $form->generate();
      
      return $view->display(true);
   }
   
   /*
    * posting comment
    * 
    * Don't call - it's called automatically
    */
   
   public static function postComment($id, $type, $backPage)
   {
      if(empty($id) || empty($type) || empty($backPage))
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      //--
      
      $model = Loader::model('comments');
      
      $backPage = base64_decode($backPage);
      
      $form = Form::validate('wmelon.comments.addComment', $backPage)->getAll();
      
      // testing for spam and adding
      
      if(!Auth::isLogged()) // if not logged in
      {
         // testing for spam
         
         $commentStatus = Sblam::test('content', 'name', 'email', 'website');
         
         // remembering user's data
         
         $_SESSION['wmelon.comments.name']    = $form->name;
         $_SESSION['wmelon.comments.email']   = $form->email;
         $_SESSION['wmelon.comments.website'] = $form->website;
         
         // assigning "visibility token" (token user needs to have in session to see his own comments, even if not approved)
         
         if(isset($_SESSION['wmelon.comments.visibilityToken']))
         {
            $visibilityToken = $_SESSION['wmelon.comments.visibilityToken'];
         }
         else
         {
            $visibilityToken = $form->name . $form->email . mt_rand();
            $visibilityToken = md5($visibilityToken);
            $visibilityToken = substr($visibilityToken, 16);
            
            $_SESSION['wmelon.comments.visibilityToken'] = $visibilityToken;
         }
         
         // adding comment
      
         switch($commentStatus)
         {
            case 0:
            case 1:
            case -1:
               $model->postComment($id, $type, $form->name, $form->email, $form->website, $form->content, true, $visibilityToken);
            
               Watermelon::addMessage('tick', 'Twój komentarz zostanie sprawdzony zanim zostanie publicznie pokazany');
            break;
         
            case -2:
               $commentID = $model->postComment($id, $type, $form->name, $form->email, $form->website, $form->content, false, $visibilityToken);
            
               Watermelon::addMessage('tick', 'Dodano komentarz');

               $backPage .= '#comment-' . $commentID;
            break;
         
            case 2:
               Watermelon::addMessage('warning', 'Filtr uznał twój komentarz za spam. ' . Sblam::reportLink());
            break;
         }
      }
      else // if logged in
      {
         $commentID = $model->postComment_logged($id, $type, $form->content);
         
         $backPage .= '#comment-' . $commentID;
      }
      
      SiteRedirect($backPage);
   }
}