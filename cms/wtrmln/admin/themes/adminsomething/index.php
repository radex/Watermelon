<?php if(!defined('WTRMLN_ADMIN_IS')) exit; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Panel Admina</title>
<link rel="stylesheet" type="text/css" href="http://localhost/wtrmln3/wtrmln/admin/themes/adminsomething/Style.css">
<meta http-equiv="Pragma" content="no-cache, private">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
<meta name="robots" content="noindex,nofollow">
</head>
<body>
<div id="bg"><img src="<?php echo WTRMLN_ADMIN.'themes/'.WTRMLN_ADMIN_THEME ?>/images/adminbg.jpg" alt=""></div>
<div id="header">
<span class="h1"><?php echo WTRMLN_SITENAME ?> &raquo; Panel Admina</span> (<a href="<?php echo WTRMLN_SITEURL;
?>">zobacz</a>)
<div>
Zalogowany jako <span><?php echo $_SESSION['WTRMLN_ADMIN_LOGIN'] ?></span>.
<a href="<?php echo WTRMLN_ADMIN ?>index.php/logout">Wyloguj</a>
</div>
</div>
<?php echo $menu; ?>
<div id="hr"></div>
<div id="content">
<?php echo $content; ?>
</div>
</body>
</html>