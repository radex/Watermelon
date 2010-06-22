<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  

class Users extends Controller
{
   function Index()
   {
      SetH1('Lista użytkowników');
   }
   
   function User()
   {
      // sprawdzamy, czy podano nazwę użytkownika
      
      $username = $this->url->segment(1);
      
      if(empty($username))
      {
         echo $this->load->view('nosuchuser');
         return;
      }
      
      // sprawdzamy, czy takowy użytkownik istnieje
      
      $this->userModel = $this->load->model('user');
      
      $userData = $this->userModel->UserData($username);
      
      if(!$userData->exists())
      {
         echo $this->load->view('nosuchuser');
         return;
      }
      
      // skoro istnieje...
      
      $userData = $userData->to_obj();
      
      setH1('Profil ' . $userData->nick);
      
      echo $this->load->view('users_profile', objectToArray($userData));
   }
}
?>
