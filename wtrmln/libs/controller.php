<?php
/********************************************************************

  Watermelon CMS

Copyright 2008-2009 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

class Controller
{
   static public $_user;
   
   public function Controller()
   {
      $this->url   = new URL();
      $this->db    = new DB();
      $this->load  = new Loader();
      $this->user  = new User();
      self::$_user = $this->user;
      
      if(defined('ADMIN_MODE'))
      {
         if(!$this->user->IsAdmin())
         {
            header('Location: ' . WTRMLN_MAINURL . 'login');
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