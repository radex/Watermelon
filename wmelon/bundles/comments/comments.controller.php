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
      
      // data
      
      $authorName    = trim($_POST['name']);
      $authorEmail   = trim($_POST['email']);   //TODO: check whether email and website are valid
      $authorWebsite = trim($_POST['website']);
      $text          = trim($_POST['text']);
      $backPage      = base64_decode($backPage);
      
      // checking whether all required data is given
      
      if(empty($authorName) || empty($authorEmail) || empty($text))
      {
         echo 'Wszystkie pola są wymagane'; //TODO: improve it using messages
         
         return;
      }
      
      // posting
      
      $model = $this->load->model('comments');
      
      $model->postComment($id, $type, $authorName, $authorEmail, $authorWebsite, $text);
      
      // redirecting
      
      SiteRedirect($backPage);
   }
}