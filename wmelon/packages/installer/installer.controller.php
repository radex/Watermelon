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

class Installer_Controller extends Controller
{
   public function installer()
   {
      echo 'asd';
      // asdsad;
      /*
      define('WM_SiteURL',     $w['siteURL']);
      define('WM_SystemURL',   $w['systemURL']);
      */
      
      define('WM_SiteURL',     'http://localhost/w/index.php/');
      define('WM_SystemURL',   'http://localhost/w/wmelon/');
      
      define('WM_PackagesURL', WM_SystemURL . 'packages/');
      define('WM_UploadedURL', WM_SystemURL . 'uploaded/');
      
      define('WM_SkinPath', WM_Packages    . 'installer/');
      define('WM_SkinURL',  WM_PackagesURL . 'installer/');
      
      Watermelon::$config['skin'] = 'installer';
      
      // define('WM_Lang', $w['lang']);
      // define('WM_Algo', $w['algo']);
   }
}