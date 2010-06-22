<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
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

class Register extends Controller
{
   function Index()
   {
      setH1('Rejestracja');
      
      $this->addMeta('<style type="text/css">.registerform{text-align:left}.registerform label{float:left;width:130px;display:block}</style>');
      
      echo $this->load->view('register_form');
   }
   
   function Submit()
   {
      $login     = $_POST['login'];
      $pass      = $_POST['password'];
      $pass2     = $_POST['password2'];
      
      $this->user->Register($login, $pass, $pass2);
   }
}
?>