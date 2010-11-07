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
    * public bool $isNewButton = true
    * 
    * Whether 'New' button shall be displayed
    */
   
   public $isNewButton = true;
   
   /*
    * public string $newButtonPage
    * 
    * Page, 'New' button shall point to
    */
   
   public $newButtonPage;
   
   /*
    * public string $newButtonLabel
    * 
    * Label of 'New' button
    */
   
   public $newButtonLabel;
   
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
}