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
 * class DBQuery
 * 
 * Representation of database query
 */

class DBQuery
{
   /*
    * public enum $type
    * 
    * Type of query:
    *    DBQuery::insert - [C]reate
    *    DBQuery::select - [R]etrieve
    *    DBQuery::update - [U]pdate
    *    DBQuery::delete - [D]elete
    */
   
   public $type = 0;
   
   const insert = 0;
   const select = 1;
   const update = 2;
   const delete = 3;
   
   /*
    * public string $selectedFields
    * 
    * Names of fields (as string) to be selected (only for SELECT query)
    * 
    * Leave NULL for * (for all fields)
    * 
    * Example: 'id, name, text'
    */
   
   public $selectedFields;
   
   /*
    * public string $table
    * 
    * Name of table to perform query on
    */
   
   public $table;
   
   /*
    * public array $fields
    * 
    * Fields names and values to be inserted/updated (only for INSERT and UPDATE queries)
    * 
    * Example: array('name' => 'foo', 'text' => 'bar')
    */
   
   public $fields = array();
   
   /*
    * public string $where
    * 
    * WHERE clause of the query
    * 
    * Leave NULL for no WHERE clause
    */
   
   public $where;
   
   /*
    * public string $orderBy
    * 
    * ORDER BY clause of the query
    * 
    * Leave NULL for no ORDER BY clause
    */
   
   public $orderBy;
   
   /*
    * public string $limit
    * 
    * LIMIT clause of the query
    * 
    * Leave NULL for no LIMIT clause
    */
   
   public $limit;
   
   /*******************************************************/
   
   /*
    * public DBQuery from(string $table)
    * public DBQuery into(string $table)
    * 
    * Sets ->table to $table
    */
   
   public function from($table)
   {
      $this->table = $table;
      
      return $this;
   }
   
   public function into($table)
   {
      $this->table = $table;
      
      $return $this;
   }
   
   /*******************************************************/
   
   /*
    * public DBQuery where(string $field[, string $op = '='], string $value)
    * public DBQuery andWhere(string $field[, string $op = '='], string $value)
    * 
    * Adds WHERE clause to query
    * 
    * string $field - name of field to compare with $value
    * string $op    - comparison operator, e.g. '=', '<', etc. Default is '='
    * string $value - some value to be compared with $field value
    * 
    * Use ->where() for first condition, and ->andWhere() for next ones
    * 
    * Examples:
    * 
    * where('id', '5')        --> WHERE id = 5
    * where('name', 'don\'t') --> WHERE name = 'don\'t'
    * where('rate', '>', 5)   --> WHERE rate > 5
    * 
    * ---
    * 
    * public DBQuery where(string $whereClause)
    * 
    * Sets ->where to $whereClause
    * 
    * For example: 'id = 5' in argument produces 'WHERE id = 5' in ->where
    * 
    * ---
    * 
    * public DBQuery andWhere(string $condition)
    * 
    * Appends $condition to ->where (remember to precede it with AND/OR)
    */
   
   public function where()
   {
      $this->_where(false, func_get_args());
      
      return $this;
   }
   
   public function andWhere()
   {
      $this->_where(true, func_get_args());
      
      return $this;
   }
   
   protected function _where($and, $args)
   {
      $argc = count($args);
      
      // where(1)
      
      if($argc == 1 && !$and)
      {
         $this->where = 'WHERE ' . $args[0] . ' ';
         
         return;
      }
      
      // andWhere(1)
      
      if($argc == 1 && $and)
      {
         $this->where .= $args . ' ';
         
         return;
      }
      
      //--
      
      if($argc == 2)
      {
         // where/andWhere(2)
         
         list($field, $value) = $args;
         
         $op = '=';
      }
      elseif($argc == 3)
      {
         // where/andWhere(3)
         
         list($field, $op, $value) = $args;
      }
      
      // adding
      
      $q = ' ' . $field . ' ' . $op . ' ' . $this->sqlValue($value) . ' ';
      
      if(!$and)
      {
         $this->where = 'WHERE' . $q;
      }
      else
      {
         $this->where .= 'AND' . $q;
      }
   }
   
   /*
    * public DBQuery orderBy(string $field[, $desc = false])
    * public DBQuery andBy(string $field[, $desc = false])
    * 
    * Adds ORDER BY clause to query
    * 
    * Use ->orderBy() for first field, and ->andBy() for next ones
    */
   
   public function orderBy($field, $desc = false)
   {
      $desc = ($desc == true) ? ' DESC' : '';
      
      $this->orderBy = 'ORDER BY ' . $field . $desc . ' ';
      
      return $this;
   }
   
   public function andBy($field, $desc = false)
   {
      $desc = ($desc == true) ? ' DESC' : '';
      
      $this->orderBy .= ', ' . $field . $desc . ' ';
      
      return $this;
   }
   
   /*
    * public DBQuery limit(int $count)
    * public DBQuery limit(int $offset, int $count)
    * 
    * Adds LIMIT clause to query
    * 
    * Order of parameters is the same as in SQL
    */
   
   public function limit($a, $b = null)
   {
      if($b === null)
      {
         $this->limit = 'LIMIT ' . $a . ' ';
      }
      else
      {
         $this->limit = 'LIMIT ' . $a . ', ' . $b . ' ';
      }
   }
   
   /*******************************************************/
   
   /*
    * protected mixed sqlValue(mixed $value)
    * 
    * Returns SQL representation of $value:
    *    if string:    adds apostrophes before and after escapes string
    *    if int/float: returns the same
    *    if bool:      converts to string
    */
   
   protected function sqlValue($value)
   {
      if(is_string($value))
      {
         return "'" . mysql_real_escape_string($value) . "'";
      }
      elseif(is_bool($value))
      {
         return $value ? 'true' : 'false';
      }
      else
      {
         return $value;
      }
   }
}