<?php if(!defined('WTRMLN_IS')) exit;

function getMenu()
{
   $haveToUpdate = false;
   $addedMenu    = false;
   $deletedMenu  = false;
   
   $menus_list = Config::getConf('PA_menu');
   $menus_list = unserialize($menus_list);
   
   // robimy listę AC_menus
   
   foreach(glob(WTRMLN_ACINFO . 'menu_*.php') as $filename)
   {
      $acname = preg_replace("#" . WTRMLN_ACINFO . "menu_([^.]+)\.php#", "\\1", $filename);
      
      if(!in_array($acname, $menus_list))
      {
         $haveToUpdate = true;
         $addedMenu    = true;
         $menus_list[] = $acname;
      }
   }
   
   // sprawdzamy, czy któryś z menów nie istnieje jako AC_menu
   
   foreach($menus_list as $menu)
   {
      if(!file_exists(WTRMLN_ACINFO . 'menu_' . $menu . '.php'))
      {
         $haveToUpdate = true;
         $deletedMenu  = true;
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
      Config::setConf('PA_menu', serialize($menus_list));
   }
   
   if($addedMenu)
   {
      Watermelon::$PA_Events[] = array(null, null, 'Dodano menu');
   }
   
   if($deletedMenu)
   {
      Watermelon::$PA_Events[] = array(null, null, 'Usunięto menu');
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
      
      $echoBuffer .= '<a href="' . site_url($header_link) . '">' . $header . '</a>';
      
      if(is_array($menus))
      {
         $echoBuffer .= '<div><ul>';
         foreach($menus as $menu)
         {
            $echoBuffer .= '<li><a href="' . site_url($menu[0]) . '">' . $menu[1] . '</a></li>';
         }
         $echoBuffer .= '</ul></div>';
      }
   }
   
   return $echoBuffer;
}

function getEvents()
{
   $preEvents = Watermelon::$PA_Events;
   
   $anyEvents = false;
   
   // robimy listę ACEventów
   
   foreach(glob(WTRMLN_ACINFO . 'event_*.php') as $filename)
   {
      $acname = preg_replace("#" . WTRMLN_ACINFO . "event_([^.]+)\.php#", "\\1", $filename);
      
      $event_list[] = $acname;
      
      $anyEvents = true;
   }
   
   if(count($preEvents) > 0)
   {
      $anyEvents = true;
   }
   
   if($anyEvents)
   {
      // odpalamy pre-eventy
      
      foreach($preEvents as $event)
      {
         list($link, $int, $description) = $event;
         
         if(is_int($int))
         {
            $int = '<div class="intbox">' . $int . '</div> ';
         }
         else
         {
            $int = '';
         }
         
         if(is_string($link))
         {
            $description = '<a href="' . site_url($link) . '">' . $description . '</a>';
         }
         
         echo '<li>' . $int . $description . '</li>';
      }
      
      // ładujemy eventy
      
      foreach($event_list as $event)
      {
         include WTRMLN_ACINFO . 'event_' . $event . '.php';
         $classname = 'ACEvent_' . $event;
         $event_objects[] = new $classname;
      }
      
      // odpalamy eventy
      
      foreach($event_objects as $eventObj)
      {
         list($link, $int, $description) = $eventObj->getEvent();
         
         if(is_int($int))
         {
            $int = '<div class="intbox">' . $int . '</div> ';
         }
         else
         {
            $int = '';
         }
         
         if(is_string($link))
         {
            $description = '<a href="' . site_url($link) . '">' . $description . '</a>';
         }
         
         echo '<li>' . $int . $description . '</li>';
      }
   }
}

include WTRMLN_THEMEPATH . 'index.php';
