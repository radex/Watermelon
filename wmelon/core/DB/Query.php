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
 * class DBQuery (a.k.a Query)
 * 
 * Representation of database query
 */

class Query extends DBQuery{}

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
    */
   
   public $where;
   
   /*
    * public string $order
    * 
    * ORDER BY clause of the query
    */
   
   public $order;
   
   /*
    * public int $limit
    * 
    * Maximum number of items in result
    */
   
   public $limit;
   
   /*
    * public int $offset
    * 
    * Offset of selected items
    * 
    * Note that you must use limit to use offset (it's a MySQL restriction)
    */
   
   public $offset;
   
   /**************************************************************************/
   
   /*
    * private __construct()
    * 
    * Use DBQuery::insert/select/update/delete() instead
    */
   
   private function __construct(){}
   
   /*
    * public static DBQuery insert(string $table)
    * 
    * Returns new object of DBQuery of INSERT type
    * 
    * string $table - table name
    */
   
   public static function insert($table)
   {
      $q = new DBQuery;
      
      $q->type  = self::insert;
      $q->table = $table;
      
      return $q;
   }
   
   /*
    * public static DBQuery select([string $selectedFields,] string $table)
    * 
    * Returns new object of DBQuery of SELECT type
    * 
    * string $selectedFields - fields to be selected
    * string $table          - table name
    */
    
   public static function select()
   {
      // variable args
      
      $args = func_get_args();
      
      if(count($args) == 1)
      {
         list($table) = $args;
      }
      else
      {
         list($selectedFields, $table) = $args;
      }
      
      // creating object
      
      $q = new DBQuery;
      
      $q->type           = self::select;
      $q->table          = $table;
      $q->selectedFields = $selectedFields;
      
      return $q;
   }
   
   /*
    * public static DBQuery update(string $table)
    * 
    * Returns new object of DBQuery of UPDATE type
    * 
    * string $table - table name
    */
   
   public static function update($table)
   {
      $q = new DBQuery;
      
      $q->type  = self::update;
      $q->table = $table;
      
      return $q;
   }
   
   /*
    * public static DBQuery delete(string $table)
    * 
    * Returns new object of DBQuery of DELETE type
    * 
    * string $table - table name
    */
   
   public static function delete($table)
   {
      $q = new DBQuery;
      
      $q->type  = self::delete;
      $q->table = $table;
      
      return $q;
   }
   
   /**************************************************************************/
   
   /*
    * public DBQuery set(array/object $fields)
    * public DBQuery set(string $column, mixed $value[, $column, $value[, ...]])
    * 
    * Sets given fields names with values to be inserted or updated
    * 
    * If method has been already called, fields are appended to the previous ones
    * 
    * array/object $fields - array/object of field names and values
    * 
    * string $column - field name
    * string $value  - field value
    * 
    * Examples:
    * 
    *    set(array('x' => 'foo', 'x' => true, 'z' => 5))
    *    set('x', 'foo',
    *        'x', true,
    *        'z', 5)
    * 
    * Both produce:
    * 
    *    (x, y, z) VALUES('foo', true, 5)        for INSERT query, or
    *    
    *    SET x = 'foo', y = true, z = 5          for UPDATE query
    */
   
   public function set()
   {
      $args = func_get_args();
      
      // object -> array
      
      if(is_object($args[0]))
      {
         $args[0] = (array) $args[0];
      }
      
      // adding
      
      if(is_array($args[0]))
      {
         foreach($args[0] as $field => $value)
         {
            $this->fields[$field] = $value;
         }
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
    * public DBQuery andWhere( -||- )
    * public DBQuery orWhere( -||- )
    * 
    * Adds condition to WHERE clause in query
    * 
    * Use where() for the first query and andWhere/orWhere() for next ones
    * 
    * string $field - name of field to compare with $value
    * string $op    - comparison operator, e.g. '=', '<', etc. Default is '='. There's also special operator IN, which takes array as value (see example below)
    * string $value - some value to be compared with $field value
    * 
    * Examples:
    * 
    *    where('id', '5')        --> WHERE id = 5
    *    where('name', "don't")  --> WHERE name = 'don\'t'
    *    where('rate', '>', 5)   --> WHERE rate > 5
    *    
    *    where('foo', 'IN', array(5, 'foo', true)) -->
    *       WHERE foo IN(5, 'foo', true)
    *    
    *    where('name', 'foo')->andWhere('name', 'bar')->orWhere('name', 'baz') -->
    *       WHERE name = 'foo' AND name = 'bar' OR name = 'baz'
    */
   
   /* 
    * public DBQuery where(string $condition)
    * 
    * Appends $condition to WHERE clause in query
    * 
    * You must prepend condition with AND/OR/etc. Don't prepend it with 'WHERE'
    */
   
   public function where()
   {
      $args = func_get_args();
      
      if(count($args) == 1)
      {
         $this->where .= ' ' . $args[0];
      }
      else
      {
         $this->where = ' ' . $this->_where($args);
      }
      
      return $this;
   }
   
   public function andWhere()
   {
      $args = func_get_args();
      
      $this->where .= ' AND ' . $this->_where($args);
      
      return $this;
   }
   
   public function orWhere()
   {
      $args = func_get_args();
      
      $this->where .= ' OR ' . $this->_where($args);
      
      return $this;
   }
   
   /*
    * auxillary method for generating SQL comparison expression from array(field[, operator], value)
    */
   
   protected function _where($args)
   {
      // for two args, implicit operator is '='
      
      if(count($args) == 2)
      {
         list($field, $value) = $args;
         
         $op = '=';
      }
      else
      {
         list($field, $op, $value) = $args;
      }
      
      // adding
      
      if(strtolower($op) == 'in') // 'in' operator - for IN(...)
      {
         // if array, converting to string
         
         if(is_array($value))
         {
            // converting to string

            foreach($value as &$item)
            {
               $item = DB::sqlValue($item);
            }

            $value = implode(', ', $value);
         }
         else
         {
            $value = DB::sqlValue($value);
         }
         
         // query
         
         $q = $field . ' IN(' . $value . ')';
      }
      else
      {
         $q = $field . ' ' . $op . ' ' . DB::sqlValue($value);
      }
      
      return $q;
   }
   
   /*
    * public DBQuery order(string $expression)
    * 
    * Adds epression to ORDER BY clause in query
    * 
    * For descending/ascending ordering just append 'DESC' or 'ASC' (as in SQL), e.g.:
    *    ->order('id DESC')
    */
   
   public function order($expression)
   {
      if(empty($this->order))
      {
         $this->order = ' ' . $expression;
      }
      else
      {
         $this->order .= ', ' . $expression;
      }
      
      return $this;
   }
   
   /*
    * public DBQuery limit(int $count)
    * 
    * Sets count of items to be selected
    */
   
   public function limit($count)
   {
      $this->limit = $count;
      
      return $this;
   }
   
   /*
    * public DBQuery offset(int $offset)
    * 
    * Sets offset of selected items
    */
   
   public function offset($offset)
   {
      $this->offset = $offset;
      
      return $this;
   }
   
   /*******************************************************/
   
   /*
    * public string __toString()
    * 
    * Returns SQL representation of DBQuery object
    */
   
   public function __toString()
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
      
      // WHERE
      
      if(!empty($this->where))
      {
         $q .= 'WHERE' . $this->where . ' ';
      }
      
      // ORDER BY
      
      if(!empty($this->order))
      {
         $q .= 'ORDER BY' . $this->order . ' ';
      }
      
      // limit and offset
      
      if($this->limit !== null)
      {
         if($this->offset !== null)
         {
            $q .= 'LIMIT ' . $this->offset . ', ' . $this->limit . ' ';
         }
         else
         {
            $q .= 'LIMIT ' . $this->limit . ' ';
         }
      }
      
      // returning
      
      return substr($q, 0, -1);
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
      $result = DB::pureQuery((string) $this);
      
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