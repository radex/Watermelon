<?defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?=WM_SkinURL?>style.css">
<title><?= empty($pageTitle) ? 'Panel Admina - ' . $siteName : $pageTitle . ' - Panel Admina - ' . $siteName ?></title>
<?=$this->drawHeadTags()?>

<header>
   <div>
      <div id="siteName"><a href="<?=WM_SiteURL?>admin/"><?=$siteName?></a></div>
      <div id="siteSlogan">Panel admina</div>
   </div>
</header>

<nav>
   <ul id="navLeft">
   </ul>
   <ul id="navRight">
   </ul>
</nav>
<div id="container">
   <nav id="subnav">
   </nav>
   <div id="content">
      <?= (empty($pageTitle) || $dontShowPageTitle) ? '' : '<h1>' . $pageTitle . '</h1>' ?>
      <?  $this->drawMessages() ?>
      <?= $content ?>
   </div>
</div>
<footer>
   powered by <strong>Watermelon CMS</strong>
</footer>

<?=$this->drawTailTags()?>
<script src="<?=WM_SkinURL?>ACPSkin.js"></script>