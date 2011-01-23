<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010 Radosław Pietruszewski.
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
 * class EventCenter
 * 
 * Events management
 */

class EventCenter
{
   private static $eventHandlers;
   
   /*
    * public static void registerEventHandler(string $eventName, callback $callback)
    * 
    * Register specified function as handler for event with name $eventName
    * 
    * $callback may be:
    * - function name
    * - array(class name, function name)
    * - array(object, function name)
    * - anonymous function
    */
   
   public static function registerEventHandler($eventName, $callback)
   {
      self::$eventHandlers[$eventName][] = $callback;
   }
   
   /*
    * public static void triggerEvent(string $eventName[, $arg1[, $arg2]])
    * 
    * Calls all callbacks for event with name $eventName (optionally: with arguments passed further)
    */
   
   public static function triggerEvent($eventName)
   {
      $args = func_get_args();
      array_shift($args); // shifting event name off beggining of an array
      
      self::triggerEvent_array($eventName, $args);
   }
   
   /*
    * public static void triggerEvent_array(string $eventName, array $args)
    * 
    * Calls all callbacks for event with name $eventName with arguments passed in $args
    */
   
   public static function triggerEvent_array($eventName, $args)
   {
      foreach(self::$eventHandlers[$eventName] as $callback)
      {
         call_user_func_array($callback, $args);
      }
   }
}