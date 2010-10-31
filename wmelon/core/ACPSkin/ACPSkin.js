function TableDeleteSelected(tableID, deleteLink)
{
   checkboxes = document.getElementsByTagName('input');

   toDelete = new Array();

   for(i = 0; i < checkboxes.length; i++)
   {
      checkboxRegExp = new RegExp("^table([0-9]+)-id([0-9]+)$");

      checkboxID = checkboxes[i].id;

      checkboxTableID = checkboxID.replace(checkboxRegExp, "$1");

      if(checkboxRegExp.test(checkboxID))
      {
         if(checkboxTableID == tableID && checkboxes[i].checked == true)
         {
            toDelete.push(checkboxID.replace(checkboxRegExp, "$2"));
         }
      }
   }

   if(toDelete.length > 0)
   {
      toDeleteString = toDelete.join(',');

      document.location = deleteLink + toDeleteString;
   }
   else
   {
      alert('Nie zaznaczono żadnego elementu do skasowania');
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