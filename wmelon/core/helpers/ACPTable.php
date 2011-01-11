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
    *    $action = array($label, $basePage[, $description])
    *       string $label       - label of a button
    *       string $basePage    - base of page of an action, e.g. 'pages/delete/'
    *       string $description - description (title="") of button
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
    * public array $rows
    * 
    * Table contents
    * 
    * $data = array($row, ...)
    *    $row = array([$id, ]$cells[, $attributes])
    *       int $id
    *          ID of item represented by row (only if ->isCheckbox is TRUE)
    *       
    *       string[] $cells = array(string $cell, ...)
    *          HTML contents of cells
    *          
    *       array $attributes = array(string $htmlAttribute => $value, ...)
    *          HTML attributes of a row
    */
   
   public $rows;
   
   /****************************************************/
   
   /*
    * public void addRow(int $id, string[] $cells[, $attributes])
    * public void addRow(string[] $cells[, $attributes])
    * 
    * Adds a row to the table
    * 
    * Attributes as in ->rows
    */
   
   public function addRow()
   {
      $this->rows[] = func_get_args();
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
            $pb .= '<a href="' . SiteURL($this->pageLink . '1') . '">Pierwsza</a> | ';
            $pb .= '<a href="' . SiteURL($this->pageLink . ($this->currentPage - 1)) . '">Poprzednia</a> | ';
         }
         
         $pb .= $this->currentPage . ' | ';
         
         if($this->currentPage < $this->lastPage)
         {
            $pb .= '<a href="' . SiteURL($this->pageLink . ($this->currentPage + 1)) . '">Następna</a> | ';
            $pb .= '<a href="' . SiteURL($this->pageLink . $this->lastPage) . '">Ostatnia</a>';
         }
         
         $pb .= '</div>';
      }
      
      // buttons
      
      if($this->isCheckbox)
      {
         $pb .= '<menu>';
      
         if(!empty($this->selectedActions))
         {
            $pb .= 'Zaznaczone: ';
         
            foreach($this->selectedActions as $action)
            {
               list($label, $basePage, $description) = $action;
               
               if(!empty($description))
               {
                  $description = ' title="' . htmlspecialchars($description) . '"';
               }
               
               $basePage = SiteURL($basePage);
            
               $pb .= '<input type="button" value="' . $label . '"' . $description . ' onclick="TableAction(' . $this->tableID . ',\'' . $basePage . '\')">' . "\n";
            }
         }
         
         $t .= '</menu>';
      }
         
      // header/footer of the table
      
         $h .= '<tr>';
         
         // select/unselect all
         
         if($this->isCheckbox)
         {
            $h .= '<th style="width: 15px" onclick="TableChangeSelection(' . $this->tableID . ', true)">';
            $h .= '<input type="checkbox" title="Zaznacz/odznacz wszystkie">';
            $h .= '</th>';
         }
         
         // header labels
         
         foreach($this->header as $headerLabel)
         {
            $h .= "<th>\n\t" . $headerLabel . "\n</th>\n";
         }
         
         //--
         
         $h .= "</tr>\n\n";
      
      // table beginning
      
      $t .= $pb;
      
      $t .= '<table id="acptable-' . $this->tableID . '" class="acptable">';
      
      $t .= '<thead>' . $h . '</thead>';
      $t .= '<tfoot>' . $h . '</tfoot>';
      
      // adding rows
      
      foreach($this->rows as $line)
      {
         // data
         
         if($this->isCheckbox)
         {
            list($id, $cells, $attributes) = $line;
         }
         else
         {
            list($cells, $attributes) = $line;
         }
         
         if(!isset($attributes))
         {
            $attributes = array();
         }
         
         // attributes (optionally)
         
         $t .= '<tr';
         
         foreach($attributes as $attribute => $value)
         {
            $t .= ' ' . $attribute . '="' . htmlspecialchars($value) . '"';
         }
         
         $t .= '>';
         
         // checkbox
         
         if($this->isCheckbox)
         {
            $t .= '<td title="ID: ' . $id . '" onclick="TableFlip(' . $this->tableID . ', ' . $id . ')">';
            $t .= '<input type="checkbox" id="table' . $this->tableID . '-id' . $id . '" onclick="TableFlip(' . $this->tableID . ', ' . $id . ')"></td>' . "\n";
         }
         
         // cells
         
         foreach($cells as $cell)
         {
            $t .= "<td>\n\t" . $cell . "\n</td>\n";
         }
         
         //--
         
         $t .= "</tr>\n\n";
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