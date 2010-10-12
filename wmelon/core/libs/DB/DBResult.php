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

/*
 * class DBResult
 * 
 * Class representing database query result
 * 
 * You can iterate through its rows using foreach (yay!)
 */

class DBResult implements Iterator
{
   /*
    * public int $rows
    * 
    * Number of rows in result set
    * 
    * Note that it is valid only for QUERY statement (and other returning result set), for other types of queries (like INSERT) it is always 0. Use DB::affectedRows() for them instead.
    */
   
   public $rows = 0;
   
   /*
    * public bool $exists
    * 
    * Whether number of rows in result set is greater than 0
    * 
    * Handy shortcut for ($x->rows > 0)
    * 
    * Note that it is valid only for QUERY statement (and other returning result set), for other types of queries (like INSERT) it is always 0
    */
   
   public $exists = false;
   
   /*
    * public mysql_result $res
    * 
    * Query result resource (returned by DB::query())
    */
   
   public $res;
   
   private $index = 0;
   
   private $valid = false;
   
   /*
    * constructor
    */
   
   public function __construct($res)
   {
      $this->res = $res;
      
      if(is_resource($res))
      {
         $this->rows = mysql_num_rows($res);
         $this->exists = ($this->rows > 0);
      }
   }
   
   /*
    * public object fetchObject()
    * 
    * Fetches a row as an object
    */
   
   public function fetchObject()
   {
      return mysql_fetch_object($this->res);
   }
   
   /*
    * public array fetchArray()
    * 
    * Fetches a row as an array
    */
   
   public function fetchArray()
   {
      return mysql_fetch_array($this->res);
   }
   
   /*
    * Iterator interface methods
    */
   
   public function current()
   {
      $this->moveIndex($this->index);
      
      return $this->fetchObject();
   }
   
   public function key()
   {
      return $this->index;
   }
   
   public function next()
   {
      $this->index++;
      
      $this->valid = ($this->index < $this->rows);
   }
   
   public function rewind()
   {
      $this->index = 0;
      $this->valid = $this->moveIndex(0);
   }
   
   public function valid()
   {
      return $this->valid;
   }
   
   private function moveIndex($index)
   {
      if($this->rows == 0)
      {
         return false;
      }
      else
      {
         return mysql_data_seek($this->res, $index);
      }
   }
}