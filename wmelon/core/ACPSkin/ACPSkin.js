function TableAction(tableID, link)
{
   checkboxes = document.getElementsByTagName('input');
   
   items = new Array();

   for(i = 0; i < checkboxes.length; i++)
   {
      checkboxRegExp = new RegExp("^table([0-9]+)-id([0-9]+)$");

      checkboxID = checkboxes[i].id;

      checkboxTableID = checkboxID.replace(checkboxRegExp, "$1");

      if(checkboxRegExp.test(checkboxID))
      {
         if(checkboxTableID == tableID && checkboxes[i].checked == true)
         {
            items.push(checkboxID.replace(checkboxRegExp, "$2"));
         }
      }
   }

   if(items.length > 0)
   {
      itemsString = items.join(',');

      document.location = link + itemsString;
   }
   else
   {
      alert('Nie zaznaczono żadnego elementu');
   }
}

function TableSelectAll(tableID)
{
   checkboxes = document.getElementsByTagName('input');

   for(i = 0; i < checkboxes.length; i++)
   {
      checkboxRegExp = new RegExp("^table([0-9]+)-id([0-9]+)$");

      checkboxID = checkboxes[i].id;

      checkboxTableID = checkboxID.replace(checkboxRegExp, "$1");

      if(checkboxRegExp.test(checkboxID))
      {
         if(checkboxTableID == tableID)
         {
            $('#' + checkboxID).attr('checked', 'true');
         }
      }
   }

   return false;
}

function TableUnselectAll(tableID)
{
   checkboxes = document.getElementsByTagName('input');

   for(i = 0; i < checkboxes.length; i++)
   {
      checkboxRegExp = new RegExp("^table([0-9]+)-id([0-9]+)$");

      checkboxID = checkboxes[i].id;

      checkboxTableID = checkboxID.replace(checkboxRegExp, "$1");

      if(checkboxRegExp.test(checkboxID))
      {
         if(checkboxTableID == tableID)
         {
            $('#' + checkboxID).removeAttr('checked');
         }
      }
   }

   return false;
}