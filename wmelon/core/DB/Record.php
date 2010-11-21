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
 * class DBRecord
 * 
 * Representation of database record
 */

// TODO: make it working for tables, where no auto-incremented ID is used

class DBRecord implements IteratorAggregate
{
   /*
    * public string $_tableName
    * 
    * Table of name containing this record
    */
   
   public $_tableName;
   
   /*
    * public object $_fields
    * 
    * Column names and field values of this record
    */
   
   public $_fields;
   
   /*
    * public bool $_upToDate
    * 
    * Whether record fields have been SELECT-ed
    * 
    * Used when object is created by DB::select() - record isn't actually SELECT-ed until __get() or update() is called
    */
   
   public $_upToDate = false;
   
   /*******************************************************/
   
   /*
    * __construct(string $table)
    * 
    * Constructs object representing record in $table
    * 
    * It doesn't actually create it - to INSERT it to database, populate fields you want, and call ->save()
    * 
    * Calling ->save() for the first time will set ->id, so that next time ->save() is called, UPDATE is made instead of INSERT
    * 
    * Note that (currently) it works only for tables with auto-incremented ID - for other tables DBRecord will work only as data representation, and ->save/delete/update() won't work
    */
   
   public function __construct($table)
   {
      $this->_tableName = $table;
   }
   
   /*
    * public void save()
    * 
    * Saves record to database - INSERTs it or UPDATEs, depending on whether ->id is filled
    */
   
   public function save()
   {
      if(isset($this->_fields->id))
      {
         $fields = $this->_fields;

         DBQuery::update($this->_tableName)->set($fields)->where('id', $this->_fields->id)->execute();
         
         $this->_upToDate = true;
      }
      else
      {
         $fields = $this->_fields;
         
         $id = DBQuery::insert($this->_tableName)->set($fields)->execute();
         
         $this->_fields->id = $id;
         $this->_upToDate   = true;
      }
   }
   
   /*
    * public void delete()
    * 
    * Deletes record from the database
    * 
    * Does nothing if there's no ->id
    * 
    * It doesn't destroy record itself apart from unsetting ->id
    */
   
   public function delete()
   {
      // if no id
      
      if(!isset($this->_fields->id))
      {
         return;
      }
      
      // deleting
      
      DBQuery::delete($this->_tableName)->where('id', $this->_fields->id)->execute();
      
      unset($this->_fields->id);
   }
   
   /*
    * public void update()
    * 
    * Retrieves this record contents from database, and updates fields in this object
    * 
    * So it's actually SELECT, and *not* UPDATE
    * 
    * Does nothing if there's no ->id
    */
   
   public function update()
   {
      // if no id
      
      if(!isset($this->_fields->id))
      {
         return;
      }
      
      // updating
      
      $data = DBQuery::select($this->_tableName)->where('id', $this->_fields->id)->execute()->fetchPure();
      
      foreach($data as $column => &$field)
      {
         $this->_fields->$column = $field;
      }
      
      $this->_upToDate = true;
   }
   
   /*******************************************************/
   
   /*
    * __get
    */
   
   public function __get($field)
   {
      // updating if not up-to-date
      
      if(!$this->_upToDate && isset($this->_fields->id))
      {
         $this->update();
      }
      
      // returning
      
      return $this->_fields->$field;
   }
   
   /*
    * __set
    */
   
   public function __set($field, $value)
   {
      $this->_fields->$field = $value;
   }
   
   /*
    * __isset
    */
   
   public function __isset($field)
   {
      // updating if not up-to-date
      
      if(!$this->_upToDate && isset($this->_fields->id))
      {
         $this->update();
      }
      
      // returning
      
      return isset($this->_fields->$field);
   }
   
   /*
    * __unset
    */
   
   public function __unset($field)
   {
      unset($this->_fields->$field);
   }
   
   /*
    * Iterator
    */
   
   public function getIterator()
   {
      return new ArrayIterator($this->_fields);
   }
}
