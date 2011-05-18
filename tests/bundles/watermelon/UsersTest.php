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
 * @covers Users
 */

class UsersTest extends PHPUnit_Framework_TestCase 
{
   public static function setUpBeforeClass() 
   {
      dbConnect();
      
      self::clearTables();
      
      Config::set('wmelon.admin', self::userData());
   }
   
   public static function tearDownAfterClass()
   {
      self::clearTables();
   }
   
   public static function clearTables()
   {
      DB::query('TRUNCATE TABLE wm_config');
   }
   
   public static function userData()
   {
      $user = (object) array();
      $user->login = 'Radex'; // and we'll call Users::login() with 'radex' to make sure case-insensitivity works fine
      $user->salt = '1234567890abcdef';
      $user->pass = sha1('qwerty' . $user->salt);
      $user->nick = 'Radex';
      
      return $user;
   }
   
   /**************************************************************************/
   
   /**
    * Tests whether isLogged is false by default
    */

   public function testIsLoggedFalse()
   {
      $this->assertFalse(Users::isLogged());
      $this->assertFalse(Users::isLogged());
   }
   
   /**
    * Tests if userData is null by default
    */

   public function testIfUserDataNull()
   {
      $this->assertNull(Users::userData());
   }
   
   /**
    * Tries logging in as a non-existing user
    */

   public function testNonExistingUser()
   {
      try
      {
         Users::login('non-existing', '');
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'users:doesNotExist')
         {
            return;
         }
      }
      
      $this->fail('Should raise [users:doesNotExist]');
      
      $this->assertFalse(Users::isLogged());
   }
   
   /**
    * Tries logging using wrong password
    */

   public function testWrongPassword()
   {
      try
      {
         Users::login('radex', 'bad');
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'users:wrongPassword')
         {
            return;
         }
      }
      
      $this->fail('Should raise [users:wrongPassword]');
      
      $this->assertFalse(Users::isLogged());
   }
   
   /**
    * Tries logging in
    */

   public function testLoggingIn()
   {
      $this->assertFalse(Users::isLogged());
      $this->assertTrue(Users::login('radex', 'qwerty'));
      $this->assertTrue(Users::isLogged());
      $this->assertTrue(Users::login('   radex   ', '   qwerty  ')); // checking if trimming correctly
      $this->assertTrue(Users::isLogged());
   }
   
   /**
    * Tries logging out and then logging back in
    */

   public function testLoggingOutAndIn()
   {
      $this->assertTrue(Users::isLogged());
      Users::logout();
      $this->assertFalse(Users::isLogged());
      
      $this->assertTrue(Users::login('radex', 'qwerty'));
      $this->assertTrue(Users::isLogged());
   }
   
   /**
    * Tries requesting user data when logged out
    */

   public function testUserDataWhenLoggedOut()
   {
      Users::logout();
      
      $this->assertNull(Users::userData());
   }
   
   /**
    * Tries requesting user data when logged in
    */

   public function testUserData()
   {
      Users::login('radex', 'qwerty');
      
      $this->assertEquals(self::userData(), Users::userData());
   }
   
   /**
    * Tests login case-insensitivity
    */

   public function testLoginCaseInsensitivity()
   {
      Users::logout();
      
      $this->assertTrue(Users::login('RADEX', 'qwerty'));
   }
   
   /**
    * Tests if user data session is cleared after logging out
    */

   public function testSessionClearedAfterLoggingOut()
   {
      $_SESSION['wmelon.users.login'] = 'foo';
      $_SESSION['wmelon.users.pass'] = 'bar';
      
      Users::logout();
      
      $this->assertNull($_SESSION['wmelon.users.login']);
      $this->assertNull($_SESSION['wmelon.users.pass']);
   }
   
   /**
    * Tests if user data session is set after logging in
    */

   public function testSessionAfterLoggingIn()
   {
      unset($_SESSION['wmelon.users.login'], $_SESSION['wmelon.users.pass']);
      
      Users::login('radex', 'qwerty');
      
      $this->assertSame('Radex', $_SESSION['wmelon.users.login']);            // 'Radex' and not 'radex' because of login's case-insensitivity (we're logging in as 'radex' but really it's 'Radex')
      $this->assertSame(self::userData()->pass, $_SESSION['wmelon.users.pass']);
   }
   
   /**
    * Tests auto-logging (from session data) with bad data
    * 
    * @runInSeparateProcess
    */

   public function testBadAutoLogging()
   {
      $_SESSION['wmelon.users.login'] = 'bad';
      $_SESSION['wmelon.users.pass'] = sha1('' . self::userData()->salt);
      $this->assertFalse(Users::isLogged());
      $this->assertNull(Users::userData());
      $this->assertFalse(Users::isLogged()); // checking if caching response works fine
      $this->assertNull(Users::userData());
   }
   
   /**
    * -||-, take two
    * 
    * @runInSeparateProcess
    */

   public function testBadAutoLogging2()
   {
      $_SESSION['wmelon.users.login'] = 'Radex';      // we were previously logging in as 'radex' to test case-insensitivity but in session actual login is stored when calling Users::login()
      $_SESSION['wmelon.users.pass'] = sha1('QWERTY' . self::userData()->salt);
      $this->assertFalse(Users::isLogged());
      $this->assertNull(Users::userData());
      $this->assertFalse(Users::isLogged());
      $this->assertNull(Users::userData());
   }
   
   /**
    * Tests successful auto-logging
    * 
    * @runInSeparateProcess
    */

   public function testSuccessfulAutoLogging()
   {
      $_SESSION['wmelon.users.login'] = 'Radex';
      $_SESSION['wmelon.users.pass'] = sha1('qwerty' . self::userData()->salt);
      $this->assertTrue(Users::isLogged());
      $this->assertEquals(self::userData(), Users::userData());
      $this->assertTrue(Users::isLogged());
      $this->assertEquals(self::userData(), Users::userData());
   }
   
   /**
    * -||-,take two (trying to call Users::userData() before calling ::isLogged() first)
    * 
    * @runInSeparateProcess
    */

   public function testSuccessfulAutoLogging2()
   {
      $_SESSION['wmelon.users.login'] = 'Radex';
      $_SESSION['wmelon.users.pass'] = sha1('qwerty' . self::userData()->salt);
      $this->assertEquals(self::userData(), Users::userData());
      $this->assertTrue(Users::isLogged());
      $this->assertEquals(self::userData(), Users::userData());
      $this->assertTrue(Users::isLogged());
   }
   
   /**
    * Tests fetching user's data by calling ::userData with UID argument
    * 
    * (currently it's hardcoded to always return admin's data, since there are no other users)
    */

   public function testUserDataMethodWithUID()
   {
      Users::logout();
      $this->assertEquals(self::userData(), Users::userData(0));
      Users::login('radex', 'qwerty');
      $this->assertEquals(self::userData(), Users::userData(1));
   }
}