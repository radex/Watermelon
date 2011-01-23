<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
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
   
   public $type = 1;
   
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
   
   public $fields;
   
   //TODO: inserting multiple records
   
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
    * __construct(enum $type, string $table[, string $selectedFields])
    * 
    * enum $type = {'insert', 'select', 'update', 'delete'}
    * 
    * string $table          - table name
    * string $selectedFields - fields to be selected (only for SELECT query)
    */
   
   public function __construct($type, $table, $selectedFields = null)
   {
      $type = strtolower($type);
      
      switch($type)
      {
         case 'insert':
            $type = self::insert;
         break;
         
         case 'select':
         default:
            $type = self::select;
         break;
         
         case 'update':
            $type = self::update;
         break;
         
         case 'delete':
            $type = self::delete;
         break;
      }
      
      $this->type  = $type;
      $this->table = $table;
      $this->selectedFields = $selectedFields;
   }
   
   /*
    * public static DBQuery insert(string $table)
    * public static DBQuery select([string $selectedFields,] string $table)
    * public static DBQuery update(string $table)
    * public static DBQuery delete(string $table)
    * 
    * Returns new object of DBQuery of insert/select/update/delete type
    * 
    * string $selectedFields - fields to be selected (only for SELECT query)
    * string $table          - table name
    */
   
   public static function insert($table)
   {
      return new DBQuery('insert', $table);
   }
   
   public static function select($a, $b = null)
   {
      if($b === null)
      {
         $table = $a;
      }
      else
      {
         $table = $b;
         $selectedFields = $a;
      }
      
      return new DBQuery('select', $table, $selectedFields);
   }
   
   public static function update($table)
   {
      return new DBQuery('update', $table);
   }
   
   public static function delete($table)
   {
      return new DBQuery('delete', $table);
   }
   
   /*******************************************************/
   
   /*
    * public DBQuery set(array/object $fields)
    * public DBQuery set(string $column, mixed $value[, $column, $value[, ...]])
    * 
    * Adds given fields names and values to ->fields
    * 
    * array/object $fields - array/object of field names and values
    * string       $column - field name
    * string       $value  - field value
    * 
    * Examples:
    * 
    * set(array('x' => 'foo', 'x' => true, 'z' => 5))
    * set('x', 'foo',
    *     'x', true,
    *     'z', 5)
    * 
    * Which produces:
    * 
    * (x,y,z) VALUES('foo', true, 5)        for INSERT query, or
    * 
    * SET x = 'foo', y = true, z = 5        for UPDATE query
    */
   
   public function set()
   {
      $args = func_get_args();
      
      // adding
      
      if(is_array($args[0]))
      {
         $this->fields = $args[0];
      }
      elseif(is_object($args[0]))
      {
         $this->fields = (array) $args[0];
      }
      else
      {
         for($i = 0, $j = count($args); $i < $j; $i += 2)
         {
            $this->fields[$args[$i]] = $args[$i + 1];
         }
      }
      
      //--
      
      return $this;
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
         $this->where .= $args[0] . ' ';
         
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
      
      if(strtolower($op) == 'in') // 'in' operator - for IN(...)
      {
         // if not array
         
         if(!is_array($value))
         {
            $value = array($value);
         }
         
         // converting to string
         
         foreach($value as &$item)
         {
            $item = DB::sqlValue($item);
         }
         
         $value = implode(', ', $value);
         
         // query
         
         $q = $field . ' IN(' . $value . ')' . ' ';
      }
      else
      {
         $q = $field . ' ' . $op . ' ' . DB::sqlValue($value) . ' ';
      }
      
      if(!$and)
      {
         $this->where = 'WHERE ' . $q;
      }
      else
      {
         $this->where .= 'AND ' . $q;
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
      $this->orderBy = substr($this->orderBy, 0, -1); // deleting last space char to achieve nice separation with comma
      
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
      
      return $this;
   }
   
   /*******************************************************/
   
   /*
    * public string toSQL()
    * public string __toString()
    * 
    * Returns SQL representation of DBQuery object
    */
   
   public function toSQL()
   {
      // type
      
      switch($this->type)
      {
         case self::insert:
            $q .= 'INSERT INTO';
         break;
         
         case self::select:
         default:
            $selectedFields = empty($this->selectedFields) ? '*' : $this->selectedFields;
            $q .= 'SELECT ' . $selectedFields . ' FROM';
         break;
         
         case self::update:
            $q .= 'UPDATE';
         break;
         
         case self::delete:
            $q .= 'DELETE FROM';
         break;
      }
      
      // table
      
      $q .= ' ' . DB::$prefix . $this->table . ' ';
      
      // fields
      
      $fields = $this->fields;
      
      if(!empty($fields))
      {
         if($this->type == self::insert)
         {
            // column names
            
            $columns = array_keys($fields);
            $columns = implode(', ', $columns);
            
            // field values
            
            foreach($fields as &$field)
            {
               $field = DB::sqlValue($field);
            }
            
            $fields = implode(', ', $fields);
            
            // adding
            
            $q .= '(' . $columns . ') VALUES(' . $fields . ') ';
         }
         elseif($this->type == self::update)
         {
            // converting (key => val) to 'key = val'
            
            foreach($fields as $column => &$value)
            {
               $value = $column . ' = ' . DB::sqlValue($value);
            }
            
            // adding
            
            $q .= 'SET ' . implode(', ', $fields) . ' ';
         }
      }
      
      // WHERE, ORDER BY and LIMIT clauses
      
      $q .= $this->where;
      $q .= $this->orderBy;
      $q .= $this->limit;
      
      // returning
      
      return substr($q, 0, -1);
   }
   
   public function __toString()
   {
      return $this->toSQL();
   }
   
   /*
    * public mixed act()
    * 
    * Executes query, and returns:
    *    DBResult object     - for SELECT
    *    affected rows (int) - for DELETE and UPDATE
    *    inserted id   (int) - for INSERT
    */
   
   public function act()
   {
      $result = DB::query(true, $this->toSQL());
      
      switch($this->type)
      {
         case self::select:
            return $result;
         
         case self::delete:
         case self::update:
            return DB::affectedRows();
         
         case self::insert:
            return DB::insertedID();
      }
   }
}