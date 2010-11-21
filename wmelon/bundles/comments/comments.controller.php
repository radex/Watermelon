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
 * Comments controller
 */

class Comments_Controller extends Controller
{
   /*
    * posting a comment
    */
   
   public function post_action($id, $type, $backPage)
   {
      if(empty($id) || empty($type) || empty($backPage))
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      //--
      
      $backPage = base64_decode($backPage);
      
      $form = Form::validate('wmelon.comments.addComment', $backPage)->getAll();
      
      // testing for spam and adding
      
      if(!Auth::isLogged()) // if not logged in
      {
         $commentStatus = Sblam::test('content', 'name', 'email', 'website');
      
         // adding comment
      
         switch($commentStatus)
         {
            case 0:
            case 1:
            case -1:
               $this->model->postComment($id, $type, $form->name, $form->email, $form->website, $form->content, true);
            
               $this->addMessage('tick', 'Twój komentarz zostanie sprawdzony, zanim zostanie pokazany');
            break;
         
            case -2:
               $commentID = $this->model->postComment($id, $type, $form->name, $form->email, $form->website, $form->content, false);
            
               $this->addMessage('tick', 'Dodano komentarz');

               $backPage .= '#comment-' . $commentID;
            break;
         
            case 2:
               $this->addMessage('warning', 'Filtr uznał twój komentarz za spam. ' . Sblam::reportLink());
            break;
         }
      }
      else
      {
         $commentID = $this->model->postComment_logged($id, $type, $form->content);
         
         $backPage .= '#comment-' . $commentID;
      }
      
      SiteRedirect($backPage); //TODO: redirect to newest comment (if successfully added)
   }
}