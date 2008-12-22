<?php if(!defined('WTRMLN_IS')) die;

//TODO: zrobić porządek

/* meta (poładniamy source)
**************************/

function getMeta()
{
   $meta = '';
   
   $_w_metaSrc = Watermelon::$metaSrc;
   
   foreach($_w_metaSrc as $metaItem)
   {
      $meta .= '   ' . $metaItem . "\n";
   }
   
   return $meta;
}

/* menu
***************************/

if(!defined('NOMENU'))
{
	$menus = DB::query("SELECT * FROM `__menu`");
   
	$menulist = array();
	
	while($menu_item = $menus->to_obj())
	{
	   if(!empty($menu_item->condition))
		{
		   $condition = $menu_item->condition;
		   $toEval = '
		   if(' . $condition . ')
		   {
		   $menulist[$menu_item->position] = \'<div class="h1">\' . $menu_item->capt . \'</div>\' . $menu_item->content;
		   }
		   ';
		   
		   eval($toEval);
		}
		else
		{
		   $menulist[$menu_item->position] = '<div class="h1">' . $menu_item->capt . '</div>' . $menu_item->content;
		}
	}
	
	$menu = '';
	
	for($i = 0, $j = count($menulist); $i < $j; $i++)
	{
	   if(!isset($menulist[$i]))
	   {
	      $j++;
	      continue;
	   }
	   $menu .= $menulist[$i];
	   unset($menulist[$i]);
	}
   
   //przetwarzanie
   
   $menu = str_replace('<?=', '<?php echo ', $menu);
   
   ob_start();
   $menu = ViewTags::Process($menu);
   $menu = eval('?>' . $menu . '<?php ');
   $menu = ob_get_contents();
   @ob_end_clean();
}


/* takie tam...
*********************************/

$siteheader = WTRMLN_SITENAME;
$siteslogan = WTRMLN_SITESLOGAN;

include WTRMLN_THEMEPATH . 'index.php';
