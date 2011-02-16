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
 * @covers DBQuery
 */

class DBQueryTest extends PHPUnit_Framework_TestCase 
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
    * Tests ::insert/select/update/delete() constructors
    * 
    * @test
    */

   public function testConstructors()
   {
      $this->assertSame("INSERT INTO wm_foo", (string) DBQuery::insert('foo'));
      $this->assertSame("SELECT * FROM wm_foo", (string) DBQuery::select('foo'));
      $this->assertSame("SELECT foo, bar FROM wm_foo", (string) DBQuery::select('foo, bar', 'foo'));
      $this->assertSame("UPDATE wm_foo", (string) DBQuery::update('foo'));
      $this->assertSame("DELETE FROM wm_foo", (string) DBQuery::delete('foo'));
   }
   
   /**
    * Tests set() method
    * 
    * @test
    */

   public function testSet()
   {
      // setting by array
      
      $fieldsArr = array('a' => 'foo', 'b' => 'bar');
      
      $this->assertSame($fieldsArr, DBQuery::insert('')->set($fieldsArr)->fields);
      
      // setting by object
      
      $fieldsObj = new stdClass;
      $fieldsObj->a = 'foo';
      $fieldsObj->b = 'bar';
      
      $this->assertSame($fieldsArr, DBQuery::insert('')->set($fieldsObj)->fields);
      
      // appending by array
      
      $fields1 = array('a' => 'foo', 'b' => 'bar');
      $fields2 = array('a' => 'baz', 'c' => true);
      $fieldsExpected = array('a' => 'baz', 'b' => 'bar', 'c' => true);
      
      $this->assertSame($fieldsExpected, DBQuery::insert('')->set($fields1)->set($fields2)->fields);
      
      // appending by object
      
      $fields1 = new stdClass;
      $fields2 = new stdClass;
      $fields1->a = 'foo';
      $fields1->b = 'bar';
      $fields2->a = 'baz';
      $fields2->c = true;
      
      $this->assertSame($fieldsExpected, DBQuery::insert('')->set($fields1)->set($fields2)->fields);
      
      // setting by consecutive args
      
      $fieldsArr = array('a' => 'foo', 'b' => 'bar');
      
      $this->assertSame($fieldsArr, DBQuery::insert('')->set('a', 'foo', 'b', 'baz', 'b', 'bar')->fields);
      
      // appending by consecutive args
      
      $fieldsExpected = array('a' => 'baz', 'b' => 'bar', 'c' => true);
      
      $this->assertSame($fieldsExpected, DBQuery::insert('')->set('a', 'bar', 'b', 'bar')->set('a', 'baz', 'c', true)->fields);
   }
   
   /**
    * Tests generation of fields to insert/update
    * 
    * @test
    */

   public function testFieldsGeneration()
   {
      // insert
      
      $this->assertSame("INSERT INTO wm_foo (foo, bar, baz) VALUES('don\'t', 3.1415, null)",
         (string) DBQuery::insert('foo')->set('foo', "don't", 'bar', 3.1415, 'baz', null));
      
      // update
      
      $this->assertSame("UPDATE wm_foo SET foo = 'don\'t', bar = 3.1415, baz = null",
         (string) DBQuery::update('foo')->set('foo', "don't", 'bar', 3.1415, 'baz', null));
   }
   
   /**
    * Tests WHERE
    * 
    * @test
    */

   public function testWhere()
   {
      // where(1)
      
      $this->assertSame("foo", trim(DBQuery::insert('')->where('foo')->where));
      $this->assertSame("foo = true OR foo = null",
         trim(DBQuery::insert('')->where('foo', true)->where('OR foo = null')->where));
      
      // where(2)
      
      $this->assertSame("foo = 'don\'t'", trim(DBQuery::insert('')->where('foo', 'don\'t')->where));
      $this->assertSame("foo = true", trim(DBQuery::insert('')->where('foo', true)->where));
      $this->assertSame("foo = false", trim(DBQuery::insert('')->where('foo', false)->where));
      $this->assertSame("foo = null", trim(DBQuery::insert('')->where('foo', null)->where));
      $this->assertSame('foo = 5', trim(DBQuery::insert('')->where('foo', 5)->where));
      $this->assertSame('foo = 3.1415', trim(DBQuery::insert('')->where('foo', 3.1415)->where));
      
      // where(3)
      
      $this->assertSame("foo < 3.1415",
         trim(DBQuery::insert('')->where('foo', '<', 3.1415)->where));
      
      $this->assertSame("foo IN('don\'t', true, false, null, 5, 3.1415)",
         trim(DBQuery::insert('')->where('foo', 'IN', array('don\'t', true, false, null, 5, 3.1415))->where));
      
      // andWhere(2/3)
      
      $this->assertSame("foo = 5 AND bar < 14 AND baz IN(true, 3.1415)",
         trim(DBQuery::insert('')
         ->where('foo', 5)->andWhere('bar', '<', 14)->andWhere('baz', 'in', array(true, 3.1415))->where));
      
      // andWhere, orWhere
      
      $this->assertSame("name = 'foo' AND name = 'bar' OR name = 'baz'",
         trim(DBQuery::insert('')->where('name', 'foo')->andWhere('name', 'bar')->orWhere('name', 'baz')->where));
      
      // generation
      
      $this->assertSame("SELECT * FROM wm_foo WHERE foo",
         (string) DBQuery::select('foo')->where('foo'));
   }
   
   /**
    * Tests ORDER BY
    * 
    * @test
    */

   public function testOrder()
   {
      $this->assertSame('foo',
         trim(DBQuery::insert('')->order('foo')->order));
      
      $this->assertSame('foo DESC, bar ASC',
         trim(DBQuery::insert('')->order('foo DESC')->order('bar ASC')->order));
      
      // generation
      
      $this->assertSame('SELECT * FROM wm_foo ORDER BY foo DESC, bar ASC',
         (string) DBQuery::select('foo')->order('foo DESC')->order('bar ASC'));
   }
   
   /**
    * Tests LIMIT
    * 
    * @test
    */

   public function testLimit()
   {
      $this->assertSame('SELECT * FROM wm_foo LIMIT 20',
         (string) DBQuery::select('foo')->limit(20));
      
      $this->assertSame('SELECT * FROM wm_foo LIMIT 50, 20',
         (string) DBQuery::select('foo')->limit(20)->offset(50));
   }
   
   /**
    * Tests generation of queries using multiple features
    * 
    * @test
    */

   public function testComplex()
   {
      $this->assertSame("SELECT foo, bar FROM wm_foo WHERE foo IN(5, true, 'foo') OR bar < 15 ORDER BY foo, bar ASC LIMIT 100, 10",
         (string) DBQuery::select('foo, bar', 'foo')
         ->where('foo', 'in', array(5, true, 'foo'))->orWhere('bar', '<', 15)
         ->order('foo')->order('bar ASC')
         ->limit(10)->offset(100));
   }
   
   /**
    * Tests act()
    * 
    * @test
    */

   public function testAct()
   {
      // inserting
      
      $inserted_id = DBQuery::insert('tests')->set('foo', 'bar', 'bar', 'foo')->act();
      
      $this->assertSame(mysql_insert_id(), $inserted_id);
      
      // selecting
      
      $foo = DBQuery::select('tests')->where('id', $inserted_id)->act()->fetch();
      
      $this->assertEquals($inserted_id, $foo->id);
      $this->assertEquals('bar', $foo->foo);
      $this->assertEquals('foo', $foo->bar);
      
      // updating
      
      $this->assertSame(1, DBQuery::update('tests')->set('foo', 1, 'bar', 2)->where('id', $inserted_id)->act());
      
      // deleting
      
      $this->assertSame(1, DBQuery::delete('tests')->where('id', $inserted_id)->act());
      
      $this->assertFalse(DBQuery::select('tests')->where('id', $inserted_id)->act()->exists);
   }
}