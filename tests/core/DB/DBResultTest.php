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
 * @covers DBResult
 */

class DBResultTest extends PHPUnit_Framework_TestCase 
{
   public static function setUpBeforeClass()
   {
      dbConnect();
      
      self::clearTables();
   }
   
   public static function tearDownAfterClass()
   {
      self::clearTables();
   }
   
   public static function clearTables()
   {
      DB::query('TRUNCATE TABLE wm_tests');
   }
   
   /**************************************************************************/
   
   /**
    * Tests fetching
    */

   public function testFetching()
   {
      // adding data
      
      $id1 = DB::insert('tests', array('foo' => 'a', 'bar' => '1'));
      $id2 = DB::insert('tests', array('foo' => 'b', 'bar' => '2'));
      
      // fetching none
      
      $obj = DB::select('tests', array(PHP_INT_MAX));
      
      $this->assertSame(0, $obj->rows);
      $this->assertSame(0, count($obj));
      $this->assertFalse($obj->exists);
      $this->assertTrue($obj->empty);
      $this->assertFalse($obj->fetch());
      
      // fetching all
      
      $obj = DB::select('tests', array($id1, $id2, PHP_INT_MAX));
      
      $this->assertSame(2, $obj->rows);
      $this->assertSame(2, count($obj));
      $this->assertTrue($obj->exists);
      $this->assertFalse($obj->empty);
      
      $obj1 = $obj->fetch();
      $obj2 = $obj->fetch();
      
      $this->assertEquals($id1, $obj1->id);
      $this->assertEquals($id2, $obj2->id);
      $this->assertSame('a', $obj1->foo);
      $this->assertSame('1', $obj1->bar);
      $this->assertSame('b', $obj2->foo);
      $this->assertSame('2', $obj2->bar);
   }
   
   /**
    * Tests looping with foreach
    */

   public function testForeach()
   {
      // fetching
      
      $id1 = DB::select('tests', array('foo' => 'a'))->fetch()->id;
      $id2 = DB::select('tests', array('foo' => 'b'))->fetch()->id;
      
      $obj = DB::select('tests', array($id1, $id2));
      
      // expected
      
      $expected = array();
      $expected[] = array('id' => (string) $id1, 'foo' => 'a', 'bar' => '1');
      $expected[] = array('id' => (string) $id2, 'foo' => 'b', 'bar' => '2');
      
      // looping
      
      foreach($obj as $i => $item)
      {
         foreach($item as $key => $val)
         {
            $arr1[$i][$key] = $val;
         }
      }
      
      // looping (again)
      
      foreach($obj as $i => $item)
      {
         foreach($item as $key => $val)
         {
            $arr2[$i][$key] = $val;
         }
      }
      
      // testing looped
      
      $this->assertSame($expected, $arr1);
      $this->assertSame($expected, $arr2);
      
      // looping over empty
      
      $obj = DB::select('tests', array('id' => PHP_INT_MAX));
      
      $looped = false;
      
      foreach($obj as $item)
      {
         $looped = true;
      }
      
      $this->assertFalse($looped);
   }
}