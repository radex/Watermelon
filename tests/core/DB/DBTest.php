<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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

require_once dirname(__FILE__) . '/../../autoloader.php';

/**
 * @covers DB
 */

class DBTest extends PHPUnit_Framework_TestCase 
{
   public function setUp()
   {
      dbConnect();
      
      $this->clearTables();
   }
   
   public function tearDown()
   {
      $this->clearTables();
   }
   
   public function clearTables()
   {
      DB::query('TRUNCATE TABLE wm_tests');
   }
   
   /**************************************************************************/
   
   /**
    * Tests insert,select,update,delete methods
    */

   public function testCRUD()
   {
      // inserting -- array
      
      $array = array('foo' => 'bar', 'bar' => 'foo');
      
      $id1 = DB::insert('tests', $array);
      
      $this->assertSame(mysql_insert_id(), $id1);
      
      // inserting -- object
      
      $obj = new stdClass;
      $obj->foo = 'bar';
      $obj->bar = 'foo';
      
      $id2 = DB::insert('tests', $obj);
      
      $this->assertSame(mysql_insert_id(), $id2);
      
      // ids should be consecutive
      
      $this->assertSame($id2, $id1 + 1);
      
      // selecting -- int id
      
      $obj1 = DB::select('tests', $id1);
      $obj2 = DB::select('tests', $id2);
      
      $this->assertSame('bar', $obj1->foo);
      $this->assertSame('foo', $obj1->bar);
      $this->assertSame('bar', $obj2->foo);
      $this->assertSame('foo', $obj2->bar);
      
      // inserting more
      
      $id3 = DB::insert('tests', array('foo' => '3'));
      $id4 = DB::insert('tests', array('foo' => '4'));
      $id5 = DB::insert('tests', array('foo' => '5', 'bar' => '_5_'));
      
      // selecting -- int[] ids
      
      $obj = DB::select('tests', array($id3, $id4));
      $obj3 = $obj->fetch();
      $obj4 = $obj->fetch();
      
      $this->assertSame('3', $obj3->foo);
      $this->assertSame('4', $obj4->foo);
      
      // selecting -- [key => val]
      
      $obj = DB::select('tests', array('bar' => '_5_'))->fetch();
      
      $this->assertSame('5', $obj->foo);
      $this->assertSame('_5_', $obj->bar);
      
      // selecting non-existing
      
      $this->assertFalse(DB::select('tests', PHP_INT_MAX));
      
      // updating -- int id -- array
      
      $array = array('foo' => 1, 'bar' => 2);
      
      $this->assertSame(1, DB::update('tests', $id1, $array));
      
      // updating -- int id -- object
      
      $obj = new stdClass;
      $obj->foo = 1;
      $obj->bar = 2;
      
      $this->assertSame(1, DB::update('tests', $id2, $obj));
      
      // updating -- test
      
      $obj1 = DB::select('tests', $id1);
      $obj2 = DB::select('tests', $id2);
      
      $this->assertSame('1', $obj1->foo);
      $this->assertSame('2', $obj1->bar);
      $this->assertSame('1', $obj2->foo);
      $this->assertSame('2', $obj2->bar);
      
      // updating -- int[] ids
      
      $updated = DB::update('tests', array($id1, $id2, PHP_INT_MAX), array('foo' => 'Foo!', 'bar' => 'Bar!'));
      
      $this->assertSame(2, $updated);
      
      $obj1 = DB::select('tests', $id1);
      $obj2 = DB::select('tests', $id2);
      
      $this->assertSame('Foo!', $obj1->foo);
      $this->assertSame('Foo!', $obj2->foo);
      $this->assertSame('Bar!', $obj1->bar);
      $this->assertSame('Bar!', $obj2->bar);
      
      // updating -- [key => val]
      
      $updated = DB::update('tests', array('bar' => '_5_'), array('foo' => '5!'));
      
      $this->assertSame(1, $updated);
      
      $obj5 = DB::select('tests', array('bar' => '_5_'))->fetch();
      
      $this->assertSame('5!', $obj5->foo);
      
      // deleting -- int id
      
      $this->assertSame(1, DB::delete('tests', $id1));
      
      $this->assertFalse(DB::select('tests', $id1));
      
      // deleting -- int[] ids
      
      $this->assertSame(2, DB::delete('tests', array($id2, $id3, PHP_INT_MAX)));
      
      $this->assertFalse(DB::select('tests', $id2));
      $this->assertFalse(DB::select('tests', $id3));
      
      // deleting -- [key => val]
      
      $this->assertSame(1, DB::delete('tests', array('bar' => '_5_')));
      
      $this->assertFalse(DB::select('tests', array('bar' => '_5_'))->fetch());
   }
   
   /**************************************************************************/
   
   /**
    * Tests pureQuery()
    * 
    * @runInSeparateProcess
    */

   public function testPureQuery()
   {
      // logging queries
      
      $q1 = 'SELECT * FROM wm_tests WHERE id = 1';
      
      DB::pureQuery($q1);
      
      $this->assertSame(array(), DB::$queriesArray);
      
      define('WM_Debug', '');
      
      DB::pureQuery($q1);
      
      $this->assertSame(array($q1), DB::$queriesArray);
      
      // purity
      
      $q2 = 'SELECT * FROM wm_tests WHERE foo = "__ foo ? \'\' bar"';
      
      DB::pureQuery($q2);
      
      $this->assertSame(array($q1, $q2), DB::$queriesArray);
      
      // inserting
      
      DB::pureQuery('INSERT INTO wm_tests (foo, bar) VALUES(1,2)');
      
      $id = mysql_insert_id();
      
      // selecting
      
      $obj = DB::pureQuery('SELECT * FROM wm_tests WHERE id = ' . $id)->fetch();
      
      $this->assertEquals($id, $obj->id);
      $this->assertSame('1', $obj->foo);
      $this->assertSame('2', $obj->bar);
      
      // error
      
      try
      {
         DB::pureQuery('bad query');
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'DB:queryError')
         {
            return;
         }
      }
      
      $this->fail('Should raise WMException[DB:queryError]');
   }
   
   /**
    * Tests query()
    * 
    * @runInSeparateProcess
    */

   public function testQuery()
   {
      // query 1
      
      define('WM_Debug', '');
      
      $arg1 = 'foo ? bar ? __baz';
      $arg2 = "don't?";
      $q1 = "SELECT * FROM __tests WHERE foo = '?' OR bar = '?'";
      $q1_expected = "SELECT * FROM wm_tests WHERE foo = 'foo ? bar ? __baz' OR bar = 'don\'t?'";
      
      DB::query($q1, $arg1, $arg2);
      
      $this->assertSame($q1_expected, end(DB::$queriesArray));
      
      // query 2
      
      DB::query("INSERT INTO __tests (foo,bar) VALUES('?','?')", "just don't", "__seriously__");
      
      $obj = DB::query("SELECT * FROM __tests WHERE id = ?", mysql_insert_id())->fetch();
      
      $this->assertSame("just don't", $obj->foo);
      $this->assertSame("__seriously__", $obj->bar);
   }
   
   /**************************************************************************/
   
   /**
    * Tests insertedID()
    */

   public function testInsertedID()
   {
      $this->assertSame(mysql_insert_id(), DB::insertedID());
   }
   
   /**
    * Tests affectedRows()
    */

   public function testAffectedRows()
   {
      $this->assertSame(mysql_affected_rows(), DB::affectedRows());
   }
   
   /**************************************************************************/
   
   /**
    * Tests sqlValue()
    */

   public function testSqlValue()
   {
      $this->assertSame("'don\\'t'", DB::sqlValue("don't"));
      $this->assertSame('true', DB::sqlValue(true));
      $this->assertSame('false', DB::sqlValue(false));
      $this->assertSame('null', DB::sqlValue(null));
      $this->assertSame('3', DB::sqlValue(3));
      $this->assertSame('3.1415', DB::sqlValue(3.1415));
   }
}