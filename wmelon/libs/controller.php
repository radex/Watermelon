<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
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

class Controller
{
   static public $_user;
   
   public function __construct()
   {
      $this->url   = new URL();
      $this->db    = new DB();
      $this->load  = new Loader();
      $this->user  = new User();
      self::$_user = $this->user; // TODO: whut's that?
      
      if(defined('ADMIN_MODE'))
      {
         if(!$this->user->IsAdmin())
         {
            header('Location: ' . WM_MAINURL . 'login');
            exit;
         }
      }
   }

   /*
    * static public void addMeta(string $data);
    * 
    * dodaje element do sekcji <head>
    * 
    * string $data - element do wstawienia, np. '<style type="text/css">*{display:none}</style>'
    */

   static public function addMeta($data)
   {
      $metaSrc = Watermelon::$metaSrc;

      $metaSrc[] = $data;

      Watermelon::$metaSrc = $metaSrc;
   }
}

?>