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

class MenuEdit extends Controller
{
   /*
    * lista menu
    */
   
   function index()
   {
      Watermelon::addmsgs('menuedit_deleted', 'menuedit_posted', 'menuedit_edited', 'menuedit_poschanged');
      
      // pobieramy listę menu
      
      $viewMenus = model('menuedit')->GetMenus();
      
      // sprawdzamy, czy są jakieś menu
      
      if(!$viewMenus->exists())
      {
         echo $this->load->view('menuedit_nomenus');
         return;
      }
      
      // skoro są, to je wyświetlamy
      
      echo $this->load->view('menuedit_table', array('menus' => $viewMenus));
   }
   
   /*
    * formularz nowego menu
    */
   
   function _new()
   {
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('newmenu', time() + 3600);
      
      echo $this->load->view('menuedit_new', array('tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * stworzenie menu
    */
   
   function Post()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['name']) OR empty($_POST['text']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'newmenu'))
      {
         echo $this->load->view('error');
         return;
      }
      
      // wysyłamy
      
      model('menuedit')->addMenu(htmlspecialchars($_POST['name']), $_POST['condition'], $_POST['text']);
      
      redirect(site_url('msg:menuedit_posted/menuedit'));
   }
   
   /*
    * formularz edycji menu
    */
   
   function edit()
   {
      $id = $this->url->segment(1);
      
      $data = model('menuedit')->GetMenuData($id);
      
      // sprawdzamy, czy w ogóle takie istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('menuedit_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = $this->load->model('tempkeys')->MakeKey('edit:' . $id, time() + 3600);
      
      echo $this->load->view('menuedit_edit', array('data' => $data->to_obj(), 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * submit: edycja menu
    */
   
   function editSubmit()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $menuID       = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'edit:' . $menuID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to edytujemy
      
      model('menuedit')->Edit(htmlspecialchars($_POST['name']), $_POST['condition'], $_POST['text'], $menuID);
      
      redirect(site_url('msg:menuedit_edited/menuedit'));
   }
   
   /*
    * (samo potwierdznie) usunięcia menu
    */
   
   function delete()
   {
      $id = $this->url->segment(1);
      
      $data = model('menuedit')->GetMenuData($id);
      
      // sprawdzamy, czy w ogóle takie istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('menuedit_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('delete:' . $id);
      
      echo $this->load->view('menuedit_deletequestion', array('id' => $id, 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * usuwanie menu
    */
   
   function delete_ok()
   {
      $tempKey = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $menuID = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('tempkeys')->CheckKey($tempKey, $tempKeyValue, 'delete:' . $menuID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to usuwamy
      
      model('menuedit')->DeleteMenu($menuID);
      
      redirect(site_url('msg:menuedit_deleted/menuedit'));
   }
   
   /*
    * zmiana pozycji menu
    */
   
   function setpos()
   {
      $menu = $this->url->segment(1);
      $pos  = $this->url->segment(2);
      
      model('menuedit')->SetMenuPosition($menu, $pos);
      
      siteredirect('msg:menuedit_poschanged/menuedit');
   }
   
   /*
    * lista górnych menu
    */
   
   function top()
   {
      Watermelon::addmsgs('menuedit_deleted', 'menuedit_posted', 'menuedit_edited', 'menuedit_boosted', 'menuedit_sinked');
      
      $menus = model('menuedit')->GetTopMenus();
      
      echo $this->load->view('menuedit_toptable', array('menus' => $menus));
   }
   
   /*
    * obniżanie pozycji menu górnego (de facto przesuwanie w prawo)
    */
   
   function top_down()
   {
      $menuID = $this->url->segment(1);
      
      $menus = model('menuedit')->GetTopMenus();
      
      $menu = $menus[$menuID];
      $menu_next = $menus[$menuID + 1];
      
      $menus[$menuID] = $menu_next;
      $menus[$menuID + 1] = $menu;
      
      model('menuedit')->UpdateTopMenus($menus);
      siteredirect('msg:menuedit_boosted/menuedit/top');
   }
   
   /*
    * podwyższanie pozycji menu górnego (de facto przesuwanie w lewo)
    */
   
   function top_up()
   {
      $menuID = $this->url->segment(1);
      
      $menus = model('menuedit')->GetTopMenus();
      
      $menu = $menus[$menuID];
      $menu_before = $menus[$menuID - 1];
      
      $menus[$menuID] = $menu_before;
      $menus[$menuID - 1] = $menu;
      
      model('menuedit')->UpdateTopMenus($menus);
      siteredirect('msg:menuedit_sinked/menuedit/top');
   }
   
   /*
    * formularz nowego menu górnego
    */
   
   function topnew()
   {
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('newtopmenu', time() + 3600);
      
      echo $this->load->view('menuedit_topnew', array('tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * stworzenie górnego menu
    */
   
   function top_post()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['name']) OR empty($_POST['condition']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'newtopmenu'))
      {
         echo $this->load->view('error');
         return;
      }
      
      // wysyłamy
      
      model('menuedit')->addTopMenu(htmlspecialchars($_POST['name']), $_POST['link'], $_POST['condition']);
      
      siteredirect('msg:menuedit_posted/menuedit/top');
   }
   
   /*
    * formularz edycji górnego menu
    */
   
   function topedit()
   {
      $id = $this->url->segment(1);
      
      $data = model('menuedit')->GetTopMenus();
      
      // sprawdzamy, czy w ogóle takie istnieje
      
      if(!isset($data[$id]))
      {
         echo $this->load->view('menuedit_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('topmenuedit:' . $id, time() + 3600);
      
      echo $this->load->view('menuedit_topedit', array('id' => (int) $id, 'name' => $data[$id][0], 'link' => $data[$id][1], 'condition' => $data[$id][2], 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * submit: edycja menu górnego
    */
   
   function topEditSubmit()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $menuID       = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'topmenuedit:' . $menuID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to edytujemy
      
      model('menuedit')->TopEdit(htmlspecialchars($_POST['name']), $_POST['link'], $_POST['condition'], $menuID);
      
      siteredirect('msg:menuedit_edited/menuedit/top');
   }
   
   /*
    * (samo potwierdznie) usunięcia menu górnego
    */
   
   function topDelete()
   {
      $id = $this->url->segment(1);
      
      $data = model('menuedit')->GetTopMenus();
      
      // sprawdzamy, czy w ogóle takie istnieje
      
      if(!isset($data[$id]))
      {
         echo $this->load->view('menuedit_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('topmenudelete:' . $id);
      
      echo $this->load->view('menuedit_topdeletequestion', array('id' => (int) $id, 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * usuwanie menu górnego
    */
   
   function topDelete_ok()
   {
      $tempKey = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $menuID = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('tempkeys')->CheckKey($tempKey, $tempKeyValue, 'topmenudelete:' . $menuID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to usuwamy
      
      model('menuedit')->DeleteTopMenu($menuID);
      
      siteredirect('msg:menuedit_deleted/menuedit/top');
   }
   
   /*
    * lista menu PA
    */
   
   function pa()
   {
      Watermelon::addmsgs('menuedit_pasinked', 'menuedit_paboosted');
      
      $viewMenus = $this->getMenus();
      
      echo $this->load->view('menuedit_patable', array('menus' => $viewMenus));
   }
   
   /*
    * podwyższanie pozycji menu PA
    */
   
   function pa_up()
   {
      $menuToBoost = $this->url->segment(1);
      
      $menus = $this->getMenus(true);
      
      $position = array_search($menuToBoost, $menus);
      
      $menu = $menus[$position];
      $menu_before = $menus[$position - 1];
      
      $menus[$position] = $menu_before;
      $menus[$position - 1] = $menu;
      
      model('menuedit')->UpdatePAMenus($menus);
      
      redirect(site_url('msg:menuedit_pasinked/menuedit/pa'));
   }
   
   /*
    * obniżanie pozycji menu PA
    */
   
   function pa_down()
   {
      $menuToSink = $this->url->segment(1);
      
      $menus = $this->getMenus(true);
      
      $position = array_search($menuToSink, $menus);
      
      $menu = $menus[$position];
      $menu_next = $menus[$position + 1];
      
      $menus[$position] = $menu_next;
      $menus[$position + 1] = $menu;
      
      model('menuedit')->UpdatePAMenus($menus);
      
      redirect(site_url('msg:menuedit_paboosted/menuedit/pa'));
   }
   
   /*
    * pobieranie listy menu
    */
   
   private function getMenus($onel = false)
   {
      $menus_list = model('menuedit')->GetPAMenus();
      
      $haveToUpdate = false;
      
      // robimy listę plików acinfo/menu_*.php
      
      foreach(glob(WTRMLN_ACINFO . 'menu_*.php') as $filename)
      {
         $acname = preg_replace("#" . WTRMLN_ACINFO . "menu_([^.]+)\.php#", "\\1", $filename);
         
         if(!in_array($acname, $menus_list))
         {
            $haveToUpdate = true;
            $menus_list[] = $acname;
         }
      }
      
      // sprawdzamy, czy któreś menu nie istnieje w acinfo/
      
      foreach($menus_list as $menu)
      {
         if(!file_exists(WTRMLN_ACINFO . 'menu_' . $menu . '.php'))
         {
            $haveToUpdate = true;
         }
         else
         {
            $menus_list2[] = $menu;
            include_once WTRMLN_ACINFO . 'menu_' . $menu . '.php';
            $classname = 'ACMenu_' . $menu;
            $class = new $classname;
            $menu_objects[] = array($menu, $class);
         }
      }
      
      $menus_list = $menus_list2;
      
      // aktualizujemy w bazie danych menu, jeśli trzeba
      
      if($haveToUpdate)
      {
         model('menuedit')->UpdatePAMenus($menus_list);
      }
      
      // odpalamy menu
      
      foreach($menu_objects as $menuObj)
      {
         $menu_name = $menuObj[0];
         $menuObj = $menuObj[1];
         
         list($header, $menus) = $menuObj->getMenu(URL::$class, str_replace('_new', 'new', URL::$method), URL::$segments);
         
         if(is_array($header))
         {
            $header_link = $header[0];
            $header = $header[1];
         }
         else
         {
            $header_link = $menu_name;
         }
         
         if($onel)
         {
            $viewMenus[] = $menu_name;
         }
         else
         {
            $viewMenus[] = array($menu_name, $header);
         }
      }
      
      return $viewMenus;
   }
}
?>