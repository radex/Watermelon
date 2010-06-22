<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  

class BenchmarkManager extends Controller
{
   function index()
   {
      setH1('Benchmark Manager');
      
      // pobieramy listę benchmarków
      
      $benchmarksResult = $this->db->query("SELECT * FROM `__benchmark`");
      
      // jeśli brak benchmarków
      
      if(!$benchmarksResult->exists())
      {
         echo 'brak benchmarków';
         return;
      }
      
      // segregujemy wyniki dla danych benchmarków
      
      while($benchmark = $benchmarksResult->to_obj())
      {
         $benchmarks[$benchmark->name][] = array($benchmark->value, $benchmark->id);
      }
      
      // robimy listę
      
      foreach($benchmarks as $key => $var)
      {
         echo '<br><br><h2>' . $key . ' [<a href="$/benchmarkmanager/delete/' . $key . '/">Usuń</a>]</h2>';
         
         echo '<table>';
         
         echo '<tr><th>Czas</th><th>Czas</th><th>Czas</th><th>Czas</th><th>Czas</th><th>Czas</th></tr>';
         
         $i = 0;
         
         $values = 0;
         $valuesCounter = 0;
         
         foreach($var as $valuer)
         {
            $value = $valuer[0];
            if($i == 6)
            {
               $i = 0;
            }
            
            if($i == 0)
            {
               echo '<tr>';
            }
            
            if($value > bcmul(bcdiv($values, ($valuesCounter > 0 ? $valuesCounter : 1)), '1.2') AND $valuesCounter > 0)
            {
               $d = true;
            }
            else
            {
               $d = false;
            }
            
            echo '<td>' . ($d ? '<font color=red>' : '') . $value . ($d ? '</font>' : '') . ' µs <a href="$/benchmarkmanager/delete_entry/' . $valuer[1] . '">[x]</a></td>';
            
            $i++;
            
            if($i == 6)
            {
               echo '</tr>';
            }
            
            $valuesCounter++;
            $values = bcadd($values, $value);
         }
         
         echo '</table>';
         
         echo '<strong>Średni wynik:</strong> ' . bcdiv($values, $valuesCounter) . ' µs';
      }
   }
   
   function delete()
   {
      $this->db->query("DELETE FROM `__benchmark` WHERE `name` = '%1'", $this->url->segment(1));
      $this->db->query("OPTIMIZE TABLE `__benchmark`");
      
      echo 'done, <a href="$/benchmarkmanager">wróć</a>';
   }
   
   function delete_entry()
   {
      $this->db->query("DELETE FROM `__benchmark` WHERE `id` = '%1'", $this->url->segment(1));
      $this->db->query("OPTIMIZE TABLE `__benchmark`");
      
      echo 'done, <a href="$/benchmarkmanager">wróć</a>';
   }
}
?>
