<?php if(!defined('WTRMLN_ADMIN_IS')) exit; ?>
<html>
   <head>
      <title>Logowanie</title>
      <meta http-equiv="Pragma" content="no-cache, private">
      <meta http-equiv="Expires" content="0">
      <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
      <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
      <meta name="robots" content="noindex,nofollow">
      <style>
         fieldset
         {
            width: 500px;
            margin: 50px auto;
            border:0;
         }

         h1
         {
            width: 100%;
            text-align: center;
            padding-left: 30px;
            color: #666;
            border-bottom: 3px solid #EEE;
            font-weight: bold;
            font-size: 1em;
         }

         input
         {
            border: 1px solid #AAA;
            margin: 5px;
            padding: 3px;
            text-align: center;
            width: 150px;
            margin-bottom: 10px;
         }

         input:focus
         {
            border: 1px dotted #777;
            background: #F8F8F8;
         }

         label, #label
         {
            display: block;
            float: left;
            text-align: right;
            width: 100px;
            margin-top: 8px;
            margin-left:100px;
         }
      </style>
   </head>
   <body>
      <form action="?check=true" method="POST" autocomplete="off">
         <fieldset>
            <?php echo $_C; ?>
         </fieldset>
      </form>
   </body>
</html>