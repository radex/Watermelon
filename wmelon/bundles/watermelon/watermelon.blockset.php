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

class Watermelon_Blockset extends Blockset
{
   public static function debugInfo()
   {
      if(!defined('WM_Debug'))
      {
         return;
      }
      
      $view = View('watermelon/debuginfo', true);
      
      // stats
      
      $view->generationTime = round(Benchmark::executionTime(), -2) / 1000;
      $view->peakMemory     = round((memory_get_peak_usage() - WM_StartMemory) / 1000, 1);
      $view->currentMemory  = round((memory_get_usage() - WM_StartMemory) / 1000, 1);
      
      // queries
      
      $view->queries = DB::$queriesArray;
      
      $view->queriesCount = count($view->queries);
      
      foreach($view->queries as &$query)
      {
         if(strlen($query) > 165)
         {
            $query = substr($query, 0, 165) . ' (...)';
         }

         $query = htmlspecialchars($query);

         $query = preg_replace('/([A-Z]+)/', '<em>$1</em>', $query);
      }
      
      // returning
      
      return $view->generate();
   }
}