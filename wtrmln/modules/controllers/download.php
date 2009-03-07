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

class Download extends Controller
{
   /*
    * lista grup
    */
   
   function Index()
   {
      setH1('Download');
      
      $groups = model('download')->GetGroups();
      
      echo $this->load->view('download_groups', array('groups' => $groups));
   }
   
   /*
    * lista plików w grupie
    */
   
   function Group()
   {
      $group = $this->url->segment(1);
      $files = model('download')->GetFiles($group);
      
      if(!$files->exists())
      {
         echo 'brak plików w kategorii';
         return;
      }
      
      $groupInfo = model('download')->GroupData($group)->to_obj();
      
      echo $this->load->view('download_files', array('files' => $files, 'group' => $groupInfo));
   }
   
   /*
    * ściąganie
    */
   
   function get()
   {
      $id = $this->url->segment(1);
      
      $data = model('download')->FileData($id);
      
      if(!$data->exists())
      {
         echo $this->load->view('download_nosuchfile');
         return;
      }
      
      model('download')->IncDownloads($id);
      
      redirect($data->to_obj()->url);
   }
}
?>