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

class DBResult
{
   public $res; // query result resource (returned by DB::query)
   
   public function __construct($res)
   {
      $this->res = $res;
   }
   
   /*
    * public int numRows()
    * 
    * Returns number of rows in result
    */
   
   public function numRows()
   {
      return mysql_num_rows($this->res);
   }
   
   /*
    * public object toObj()
    * 
    * Returns data as an object
    */
   
   public function toObj()
   {
      return mysql_fetch_object($this->res);
   }
   
   /*
    * public array toArray()
    * 
    * Returns data as an array
    */
   
   public function toArray()
   {
      return mysql_fetch_array($this->res);
   }
   
   /*
    * public bool exists()
    * 
    * Returns TRUE if searched record exists (num_rows > 0), or FALSE otherwise
    */
   
   public function exists()
   {
      return (mysql_num_rows($this->res) == 0) ? false : true;
   }
}