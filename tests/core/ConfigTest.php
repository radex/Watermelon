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

require_once dirname(__FILE__) . '/../autoloader.php';

/**
 * @covers Config
 */

class ConfigTest extends PHPUnit_Framework_TestCase 
{
   public function setUp() 
   {
      DB::connect('localhost', 'watermelon_tests', 'watermeloner', 'wtrmln123', 'wm_');
      
      $this->clearTables();
   }
   
   public function tearDown()
   {
      $this->clearTables();
   }
   
   public function clearTables()
   {
      DB::query('TRUNCATE TABLE wm_config');
   }
   
   /**************************************************************************/
   
   /*
    * Invalid field identificators
    */
   
   public function badNames()
   {
      return array
         (
            array(null),
            array(''),
            array('foo'),
            array('.'),
            array('..'),
         );
   }
   
   /**
    * @expectedException InvalidArgumentException
    * @dataProvider badNames
    * @test
    */
   
   public function testBadNames_get($badName)
   {
      Config::get($badName);
   }
   
   /**
    * @expectedException InvalidArgumentException
    * @dataProvider badNames
    * @test
    */
   
   public function testBadNames_set($badName)
   {
      Config::set($badName, null);
   }
   
   /**
    * @expectedException InvalidArgumentException
    * @dataProvider badNames
    * @test
    */
   
   public function testBadNames_delete($badName)
   {
      Config::delete($badName);
   }
   
   /**
    * @expectedException InvalidArgumentException
    * @dataProvider badNames
    * @test
    */
   
   public function testBadNames_exists($badName)
   {
      Config::exists($badName);
   }
   
   /**************************************************************************/
   
   /**
    * @test
    */
   
   public function testLocally()
   {
      $c = new Config;
      
      // non-existing
      
      $this->assertNull($c->get('test.field2'));
      $this->assertFalse($c->exists('test.field'));
      $this->assertNull($c->get('test.field'));
      $this->assertFalse($c->exists('test.field'));
      
      // null value
      
      $c->set('test.field', null);
      
      $this->assertTrue($c->exists('test.field'));
      $this->assertNull($c->get('test.field'));
      
      // string value
      
      $c->set('test.field', 'test');
      
      $this->assertSame('test', $c->get('test.field'));
      $this->assertSame('test', $c->get('TeSt.FIELd')); // case sensivity
      
      // deleting
      
      $c->delete('doesnt.exist');
      
      $c->delete('test.field');
      
      $this->assertFalse($c->exists('test.field'));
      $this->assertNull($c->get('test.field'));
      
      $c->set('test.field', 'sth');
      
      $this->assertTrue($c->exists('test.field'));
      $this->assertSame('sth', $c->get('test.field'));
   }
   
   /**
    * @test
    */

   public function testDatabase()
   {
      $c = new Config;
      
      // null value getting
      
      DB::insert('config', array('name' => 't1.test1', 'value' => serialize(null)));
      
      $this->assertTrue($c->exists('t1.test1'));
      $this->assertNull($c->get('t1.test1'));
      
      // complex value getting
      
      $complexValue = array(null, 234, 'foo', array(true));
      
      DB::insert('config', array('name' => 't1.test2', 'value' => serialize($complexValue)));
      
      $this->assertSame($complexValue, $c->get('t1.test2'));
      
      // and the other way round
      
      $c->set('t1.test3', $complexValue);
      
      $this->assertSame($complexValue,
         unserialize(DBQuery::select('config')->where('name', 't1.test3')->act()->fetchObject()->value));
      
      $c->set('t1.test3', null);
      
      $this->assertSame(null,
         unserialize(DBQuery::select('config')->where('name', 't1.test3')->act()->fetchObject()->value));
      
      // deleting
      
      $c->delete('t1.test3');
      
      $this->assertFalse(DBQuery::select('config')->where('name', 't1.test3')->act()->exists);
      
      // and recreating
      
      $c->set('t1.test3', $complexValue);
      
      $this->assertSame($complexValue,
         unserialize(DBQuery::select('config')->where('name', 't1.test3')->act()->fetchObject()->value));
      
      // deleting (2)
      
      $c->delete('t1.test4');
      
      DB::insert('config', array('name' => 't1.test5', 'value' => serialize('sth')));
      
      $c->delete('t1.test5');
      
      $this->assertFalse($c->exists('t.test5'));
      $this->assertSame(false,
         DBQuery::select('config')->where('name', 't1.test5')->act()->exists);
   }
   
   /**
    * @test
    */

   public function testSubKeys()
   {
      $c = new Config;
      
      // non-existing
      
      $this->assertFalse($c->exists('t2.test1.foo'));
      $this->assertNull($c->get('t2.test1.foo'));
      
      $c->set('t2.test1', null);
      
      $this->assertFalse($c->exists('t2.test1.foo'));
      $this->assertNull($c->get('t2.test1.foo'));
      
      $c->set('t2.test1', array('bar' => 'test'));
      
      $this->assertFalse($c->exists('t2.test1.foo'));
      $this->assertNull($c->get('t2.test1.foo'));
      
      $t1->bar = 'test';
      
      $this->assertFalse($c->exists('t2.test1.foo'));
      $this->assertNull($c->get('t2.test1.foo'));
      
      // setting and getting
      
      $c->set('t2.test2.foo', null);
      
      $t2->foo = null;
      
      $this->assertEquals($t2, $c->get('t2.test2'));
      $this->assertNull($c->get('t2.test2.foo'));
      $this->assertTrue($c->exists('t2.test2'));
      $this->assertTrue($c->exists('t2.test2.foo'));
      
      // deleting
      
      $t2 = new stdClass;
      
      $c->delete('t2.test2.foo');
      
      $this->assertFalse($c->exists('t2.test2.foo'));
      $this->assertNull($c->get('t2.test2.foo'));
      $this->assertTrue($c->exists('t2.test2'));
      $this->assertEquals($t2, $c->get('t2.test2'));
      
      // setting (as object)
      
      $t3->foo = 'bar';
      
      $c->set('t2.test3', $t3);
      
      $this->assertTrue($c->exists('t2.test3.foo'));
      $this->assertSame('bar', $c->get('t2.test3.foo'));
      
      // setting (as array)
      
      $t4['foo'] = 'bar';
      
      $c->set('t2.test4', $t4);
      
      $this->assertTrue($c->exists('t2.test4.foo'));
      $this->assertSame('bar', $c->get('t2.test4.foo'));
   }
   
   /**
    * @test
    */

   public function testComplexSubKeys()
   {
      $c = new Config;
      
      // 1
      
      $c->set('t3.test1.foo.bar.asd', false);
      
      $this->assertTrue($c->exists('t3.test1.foo.bar.asd'));
      $this->assertTrue($c->exists('t3.test1.foo.bar'));
      $this->assertTrue($c->exists('t3.test1.foo'));
      $this->assertTrue($c->exists('t3.test1'));
      
      // 2
      
      $t2a->a = 'b';
      $t2b->foo = 'bar';
      $t2b->bar = array('a' => $t2a, 'b' => false);
      $t2c = array('foo' => $t2b, 'bar' => 666);
      
      $c->set('t3.test2', $t2c);
      
      // 2 - getting
      
      $this->assertEquals($t2c, $c->get('t3.test2'));
      $this->assertEquals($t2b, $c->get('t3.test2.foo'));
      $this->assertSame(666, $c->get('t3.test2.bar'));
      $this->assertSame('bar', $c->get('t3.test2.foo.foo'));
      $this->assertEquals($t2b->bar, $c->get('t3.test2.foo.bar'));
      $this->assertFalse($c->get('t3.test2.foo.bar.b'));
      $this->assertSame($t2a, $c->get('t3.test2.foo.bar.a'));
      $this->assertSame('b', $c->get('t3.test2.foo.bar.a.a'));
      
      // 2 - exists
      
      $this->assertTrue($c->exists('t3.test2'));
      $this->assertTrue($c->exists('t3.test2.foo'));
      $this->assertTrue($c->exists('t3.test2.bar'));
      $this->assertTrue($c->exists('t3.test2.foo.foo'));
      $this->assertTrue($c->exists('t3.test2.foo.bar'));
      $this->assertTrue($c->exists('t3.test2.foo.bar.b'));
      $this->assertTrue($c->exists('t3.test2.foo.bar.a'));
      
      // 2 - setting
      
      $c->set('t3.test2.foo.bar.a.a', true);
      
      $this->assertTrue($c->get('t3.test2.foo.bar.a.a'));
      
      // 2 - deleting
      
      $c->delete('t3.test2.foo.bar.a');
      
      $this->assertFalse($c->exists('t3.test2.foo.bar.a'));
      $this->assertFalse($c->exists('t3.test2.foo.bar.a.a'));
      
      $c->delete('t3.test2.foo.bar');
      
      $this->assertFalse($c->exists('t3.test2.foo.bar'));
      $this->assertFalse($c->exists('t3.test2.foo.bar.b'));
      $this->assertFalse($c->exists('t3.test2.foo.bar.a'));
      
      $c->delete('t3.test2.nosuchkey');
   }
}