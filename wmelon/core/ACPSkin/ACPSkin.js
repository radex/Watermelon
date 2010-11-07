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

function TableChangeSelection(tableID, selection)
{
   // top and bottom checkboxes
   
   $('#acptable-' + tableID + ' thead input').attr('checked', selection);
   $('#acptable-' + tableID + ' tfoot input').attr('checked', selection);
   
   revSelectionString = (selection) ? 'false' : 'true';
   onclickStr = 'TableChangeSelection(' + tableID + ',' + revSelectionString + ')';
   
   $('#acptable-' + tableID + ' thead input').attr('onclick', onclickStr);
   $('#acptable-' + tableID + ' tfoot input').attr('onclick', onclickStr);
   
   // item checkboxes
   
   checkboxes = document.getElementsByTagName('input');

   for(i = 0; i < checkboxes.length; i++)
   {
      checkboxRegExp = new RegExp("^table([0-9]+)-id([0-9]+)$");

      checkboxID = checkboxes[i].id;

      checkboxTableID = checkboxID.replace(checkboxRegExp, "$1");

      if(checkboxRegExp.test(checkboxID) && checkboxTableID == tableID)
      {
         if(selection)
         {
            $('#' + checkboxID).attr('checked', 'true');
         }
         else
         {
            $('#' + checkboxID).removeAttr('checked');
         }
      }
   }
}