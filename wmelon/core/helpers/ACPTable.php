<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * Admin Control Panel tables generator
 */

class ACPTable
{
   /*
    * private static int $maxID;
    * 
    * Biggest ID of table on given page
    */
   
   private static $maxID = 1;
   
   /*
    * public int $tableID
    * 
    * ID of the table. Don't change that.
    */
   
   public $tableID;
   
   /****************************************************/
   
   /*
    * public bool $isCheckbox = true
    * 
    * Whether checkboxes, (un)select buttons and selectedActions buttons shall be dispalyed
    * 
    * If true, you have to pass ID at the beginning of every item in ->data
    */
   
   public $isCheckbox = true;
   
   /*
    * public array $selectedActions
    * 
    * Actions (that will be displayed as buttons) for selected items
    * 
    * $selectedActions = array($action, ...)
    *    $action = array($label, $basePage)
    *       string $label    - label of a button, e.g. 'Delete'
    *       string $basePage - base of page of an action, e.g. 'pages/delete/'
    */
   
   public $selectedActions = array();
   
   /****************************************************/
   
   /*
    * public bool $isPagination = true
    * 
    * Whether pages bar shall be displayed
    */
   
   public $isPagination = true;
   
   /*
    * public int $currentPage
    * 
    * Number of currently selected page
    */
   
   public $currentPage;
   
   /*
    * public int $lastPage
    * 
    * Number of last page
    */
   
   public $lastPage;
   
   /*
    * public string $pageLink
    * 
    * Base page for given table page, e.g. 'pages/index/page:'
    */
   
   public $pageLink;
   
   /****************************************************/
   
   /*
    * public string[] $header
    * 
    * List of header labels
    */
   
   public $header;
   
   /*
    * public array $data
    * 
    * Table contents
    * 
    * If ->isCheckbox is true, there shall be ID at the beginning of every item
    */
   
   public $data;
   
   /****************************************************/
   
   /*
    * public void addLine(int $id, string $cell[, ...])
    * public void addLine(int $id, string[] $cells)
    * public void addLine(string $cell[, ...])
    * public void addLine(string[] $cells)
    * 
    * Adds a line to the table
    * 
    * If ->isCheckbox is true, you have to pass ID of item represented by this line before actual cells
    * 
    * You can pass cell contents either by passing them in consecutive strings or as strings array
    */
   
   public function addLine()
   {
      $args = func_get_args();
      $line = array();
      
      if(is_array($args[0])) // array
      {
         $line = $args[0];
      }
      elseif(is_string($args[0])) // string, ...
      {
         $line = $args;
      }
      elseif(is_int($args[0]) && is_string($args[1])) // int, string, ...
      {
         $line = $args;
      }
      elseif(is_int($args[0]) && is_array($args[1])) // int, array
      {
         $line = $args[1];
         array_unshift($line, $args[0]);
      }
      
      $this->data[] = $line;
   }
   
   /*
    * public void generate()
    * 
    * Generates and returns table HTML
    */
   
   public function generate()
   {
      // pagination
      
      if($this->isPagination)
      {
         //TODO: better pagination
         
         $pb .= '<div class="acp-tablePages">';
         
         $pb .= 'Strona: ';
         
         if($this->currentPage > 1)
         {
            $pb .= '<a href="' . SiteURI($this->pageLink . '1') . '">Pierwsza</a> | ';
            $pb .= '<a href="' . SiteURI($this->pageLink . ($this->currentPage - 1)) . '">Poprzednia</a> | ';
         }
         
         $pb .= $this->currentPage . ' | ';
         
         if($this->currentPage < $this->lastPage)
         {
            $pb .= '<a href="' . SiteURI($this->pageLink . ($this->currentPage + 1)) . '">Następna</a> | ';
            $pb .= '<a href="' . SiteURI($this->pageLink . $this->lastPage) . '">Ostatnia</a>';
         }
         
         $pb .= '</div>';
      }
      
      // buttons
      
      if($this->isCheckbox)
      {
         $pb .= '<menu>';
      
         $pb .= '<input type="button" value="Zaznacz wszystkie" onclick="return TableSelectAll(' . $this->tableID . ')">';
         $pb .= '<input type="button" value="Odznacz wszystkie" onclick="return TableUnselectAll(' . $this->tableID . ')">';
      
         if(!empty($this->selectedActions))
         {
            $pb .= ', Zaznaczone: ';
         
            foreach($this->selectedActions as $action)
            {
               list($label, $basePage) = $action;
            
               $basePage = SiteURI($basePage);
            
               $pb .= '<input type="button" value="' . $label . '" onclick="return TableAction(' . $this->tableID . ',\'' . $basePage . '\')"> ';
            }
         }
         
         $t .= '</menu>';
      }
         
      // header/footer of the table
      
      $h .= '<tr>';
      
      if($this->isCheckbox)
      {
         $h .= '<th></th>';
      }
      
      foreach($this->header as $headerLabel)
      {
         $h .= '<th>' . $headerLabel . '</th>';
      }
      
      $h .= '</tr>';
      
      // table itself
      
      $t .= $pb;
      
      $t .= '<table>';
      
      $t .= '<thead>' . $h . '</thead>';
      $t .= '<tfoot>' . $h . '</tfoot>';
      
      foreach($this->data as $line)
      {
         $t .= '<tr>';
         
         // checkbox
         
         if($this->isCheckbox)
         {
            $id = $line[0];
            
            array_shift($line); // shifting ID out of an array
            
            $t .= '<td title="ID: ' . $id . '"><input type="checkbox" id="table' . $this->tableID . '-id' . $id . '"></td>';
         }
         
         // cells
         
         foreach($line as $cell)
         {
            $t .= '<td>' . $cell . '</td>';
         }
         
         //--
         
         $t .= '</tr>';
      }
      
      $t .= '</table>';
      
      $t .= $pb;
      
      // returning
      
      return $t;
   }
   
   /*
    * constructor
    */
   
   public function __construct()
   {
      $this->tableID = self::$maxID;
      
      self::$maxID++;
   }
}