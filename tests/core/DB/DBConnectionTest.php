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

class DBConnectionTest extends PHPUnit_Framework_TestCase 
{
   /**
    * Tests connect() -- connection error
    * 
    * @runInSeparateProcess
    */

   public function testConnect_connectionError()
   {
      try
      {
         DB::connect('host', 'name', 'user', 'pass', 'prefix');
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'DB:connectError')
         {
            return;
         }
      }
      
      $this->fail('Should raise WMException[DB:connectError]');
   }
   
   /**
    * Tests connect() -- database selection error
    * 
    * @runInSeparateProcess
    */

   public function testConnect_selectionError()
   {
      try
      {
         DB::connect('localhost', 'badName', 'watermeloner', 'wtrmln123', 'prefix');
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'DB:selectError')
         {
            return;
         }
      }
      
      $this->fail('Should raise WMException[DB:selectError]');
   }
   
   /**
    * Tests connect() -- rest
    * 
    * @runInSeparateProcess
    */

   public function testConnect()
   {
      // connect
      
      dbConnect();
      
      // already connected
      
      $this->assertNull(DB::connect('','','','',''));
   }
}