<?php
/********************************************************************

  Watermelon CMS

Copyright 2009 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

class BenchmarkManager extends Controller
{
   function BenchmarkManager()
   {
      parent::Controller();
   }
   
   function index()
   {
      setH1('Benchmark Manager');
      
      // pobieramy listę benchmarków
      
      $benchmarksResult = $this->db->query("SELECT * FROM `__benchmark`");
      
      // jeśli brak benchmarków
      
      if($benchmarksResult->num_rows() == 0)
      {
         echo 'brak benchmarków';
         return;
      }
      
      // segregujemy wyniki dla danych benchmarków
      
      while($benchmark = $benchmarksResult->to_obj())
      {
         $benchmarks[$benchmark->name][] = (int) $benchmark->value;
      }
      
      // robimy listę
      
      foreach($benchmarks as $key => $var)
      {
         $values = array();
         
         echo '<br><br><h2>' . $key . ' [<a href="$/benchmarkmanager/delete/' . $key . '/">Usuń</a>]</h2>';
         
         echo '<table>';
         
         echo '<tr><th>Czas</th><th>Czas</th><th>Czas</th><th>Czas</th><th>Czas</th><th>Czas</th></tr>';
         
         $i = 0;
         
         foreach($var as $value)
         {
            if($i == 6)
            {
               $i = 0;
            }
            
            if($i == 0)
            {
               echo '<tr>';
            }
            
            echo '<td>' . $value . ' µs</td>';
            
            $i++;
            
            if($i == 6)
            {
               echo '</tr>';
            }
            
            $values[] = $value;
         }
         
         echo '</table>';
         
         echo '<strong>Średni wynik:</strong> ' . (int) (array_sum($values) / count($values)) . ' µs';
      }
   }
   
   function delete()
   {
      $this->db->query("DELETE FROM `__benchmark` WHERE `name` = '%1'", $this->url->segment(1));
      
      echo 'done, <a href="$/benchmarkmanager">wróć</a>';
   }
}
?>
