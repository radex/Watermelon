<?php if(!defined('WM_IS')) exit;
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  
?>

<a href="$/">Panel Admina</a> &gt; <a href="$/pages">Strony</a> &gt; Nowa

<?php
   Controller::addMeta(
   '<style type="text/css">.newpage_box label{float:left;width:100px;display:block}'.
   '.newpage_box #title, .newpage_box #name{width:60%}'.
   '.newpage_box #text{width: 100%; height:250px;}</style>');
?>

<script type="text/javascript">

function trim(str)
{
   // bazowane na http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_trim/
   // na licencji MIT
   // no i trochę edytowane przeze mnie ;)

   for(i = 0; i < str.length; i++)
   {
      if(str.charAt(i) != ' ')
      {
         str = str.substring(i);
         break;
      }
   }
   
   for(i = str.length - 1; i >= 0; i--)
   {
      if(str.charAt(i) != ' ')
      {
         str = str.substring(0, i + 1);
         break;
      }
   }
   
   return str.charAt(0) == ' ' ? '' : str;
}

function UpdatePageName()
{
   title = document.getElementById("title").value;
   title = trim(title);
   document.getElementById("title").value = title;
   
   name = document.getElementById("name");
   title = title.toLowerCase();
   title = title.replace(/ę/g, "e");
   title = title.replace(/ó/g, "o");
   title = title.replace(/ą/g, "a");
   title = title.replace(/ś/g, "s");
   title = title.replace(/ł/g, "l");
   title = title.replace(/ż/g, "z");
   title = title.replace(/ź/g, "z");
   title = title.replace(/ć/g, "c");
   title = title.replace(/ń/g, "n");
   title = title.replace(/ /g, "-");
   
   var res = [];
   
   while(title.length > 0)
   {
      res[res.length] = title.substring(0, 1);
      title = title.substring(1);
   }
   
   title2 = '';
   
   var j = 0;
   
   for(k in res) j++;
   
   for(i = 0; i < j; i++)
   {
      c = res[i].charCodeAt(0);
      if(c >= 97 && c <= 122) title2 += String.fromCharCode(c);
      if(c >= 48 && c <= 57) title2 += String.fromCharCode(c);
      if(c == 45) title2 += String.fromCharCode(c);
   }
   
   title2 = title2.replace(/-+/g, "-");
   name.value = title2;
}
</script>

<form action="$/pages/post/<$tkey>/<$tvalue>" method="POST">
   <fieldset class="newpage_box">
      <legend>Nowa strona</legend>
      
      <label for="title">Temat:</label>
      <input type="text" name="title" id="title" onchange="UpdatePageName()">
      
      <br>
      
      <label for="name">Nazwa:</label>
      <input type="text" name="name" id="name">
      
      <br>
      
      <label for="text">Treść:</label><br>
      
      <textarea name="text" id="text"></textarea>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>