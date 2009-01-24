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

class Config
{
   public static $siteName;
   public static $siteSlogan;
   public static $hashAlgo;
   public static $defaultHashAlgo;
   public static $defaultController;
   public static $theme;
   private static $superusers = null;
   
   public static function setSuperusers(array $superusers)
   {
      if(self::$superusers === NULL)
      {
         self::$superusers = $superusers;
      }
   }
   
   public static function getSuperusers()
   {
      return self::$superusers;
   }
}

?>
