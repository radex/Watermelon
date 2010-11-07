function TableAction(tableID, link)
{
   checkboxes = document.getElementsByTagName('input');
   
   items = new Array();
   
   regexp = new RegExp("^table([0-9]+)-id([0-9]+)$");

   for(i in checkboxes)
   {
      id = checkboxes[i].id;
      
      if(regexp.test(id) && id.replace(regexp, "$1") == tableID && checkboxes[i].checked == true)
      {
         items.push(id.replace(regexp, "$2"));
      }
   }

   if(items.length > 0)
   {
      document.location = link + items.join(',');
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
   
   regexp = new RegExp("^table([0-9]+)-id([0-9]+)$");
   
   for(i in checkboxes)
   {
      id = checkboxes[i].id;

      if(regexp.test(id) && id.replace(regexp, "$1") == tableID)
      {
         if(selection)
         {
            $('#' + id).attr('checked', 'true');
         }
         else
         {
            $('#' + id).removeAttr('checked');
         }
      }
   }
}