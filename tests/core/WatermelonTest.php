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
 * @covers Watermelon
 */

class WatermelonTest extends PHPUnit_Framework_TestCase 
{
   /**
    * Tests getRequestURL() method
    */

   public function testGetRequestURL()
   {
      $method = new ReflectionMethod('Watermelon', 'getRequestURL');
      $method->setAccessible(true);
      
      // array($REQUEST_URI, $SCRIPT_NAME, $expectedResult, ...)
      
      $tests = array
         (
            '/foo/bar', '/index.php', 'foo/bar',
            '/index.php/foo/bar', '/index.php', 'foo/bar',
            '/', '/index.php', '',
            '/index.php', '/index.php', '',
            '/index.php/', '/index.php', '',
            '/path/foo/bar', '/path/index.php', 'foo/bar',
            '/path/index.php/foo/bar', '/path/index.php', 'foo/bar',
         );
      
      // testing
      
      for($i = 0; $i < count($tests); $i+=3)
      {
         $_SERVER['REQUEST_URI'] = $tests[$i];
         $_SERVER['SCRIPT_NAME'] = $tests[$i + 1];
         
         $this->assertSame($tests[$i + 2], $method->invoke(null));
      }
   }
   
   /**
    * Tests parseRequestURL() method
    */

   public function testParseRequestURL()
   {
      $method = new ReflectionMethod('Watermelon', 'parseRequestURL');
      $method->setAccessible(true);
      
      // array($URL => $expected, ...)
      
      $tests = array
         (
            '' => array(
               array(),
               null,
               (object) array()
            ),
            
            '//' => array(
               array(),
               null,
               (object) array()
            ),
            
            
            'foo/bar' => array(
               array('foo', 'bar'),
               null,
               (object) array()
            ),
            
            'foo/bar/' => array(
               array('foo', 'bar'),
               null,
               (object) array()
            ),
            
            '/foo//bar//' => array(
               array('foo', 'bar'),
               null,
               (object) array()
            ),
            
            
            'foo/bar.ext' => array(
               array('foo', 'bar'),
               'ext',
               (object) array()
            ),
            
            '.ext' => array(
               array('.ext'),
               null,
               (object) array()
            ),
            
            'foo.' => array(
               array('foo.'),
               null,
               (object) array()
            ),
            
            '.' => array(
               array('.'),
               null,
               (object) array()
            ),
            
            
            '?foo=bar' => array(
               array(),
               null,
               (object) array('foo' => 'bar')
            ),
            
            'foo?' => array(
               array('foo?'),
               null,
               (object) array()
            ),
            
            '?' => array(
               array('?'),
               null,
               (object) array()
            ),
            
            'foo/bar?foo=bar&foo2=bar2&flag' => array(
               array('foo', 'bar'),
               null,
               (object) array('foo' => 'bar', 'foo2' => 'bar2', 'flag' => null)
            ),
            
            
            'foo//bar/.xml?foo=bar&flag' => array(
               array('foo', 'bar'),
               'xml',
               (object) array('foo' => 'bar', 'flag' => null)
            ),
            
            '/.?' => array(
               array(),
               '?',
               (object) array()
            )
         );
      
      foreach($tests as $url => $expectedResult)
      {
         $this->assertEquals($expectedResult, $method->invoke(null, $url));
      }
   }
}