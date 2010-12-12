<?die?><style>
#navOptionsForm input[type=text],
#navOptionsForm input[type=checkbox]
{
   width: 97%;
}
</style>
<script>

function navOptions_redirect(id, action)
{
   $('#navOptionsForm').attr('action', '<?=WM_AdminURL?>options/nav_save/' + id + '/' + action);
   $('#navOptionsForm').submit();
}

function navOptions_add()
{
   // determining ID of newly added item
   
   newItemID = $('#navOptionsForm').attr('data-items');
   newItemID = parseInt(newItemID);
   
   // updating items count
   
   $('#navOptionsForm').attr('data-items', newItemID + 1);
   
   // adding down/bottom options to last item
   
   $('#navOptions_bottomPlaceholder').replaceWith(navOptions_optionsPattern);
   
   $('#down_NEW').attr(  'onclick', 'navOptions_redirect(' + (newItemID - 1) + ', "down")');
   $('#bottom_NEW').attr('onclick', 'navOptions_redirect(' + (newItemID - 1) + ', "bottom")');
   
   $('#down_NEW').removeAttr('id');
   $('#bottom_NEW').removeAttr('id');
   
   // appending new row (content is pattern from main view)
   
   $('#navOptionsForm tbody').append('<tr>' + navOptions_rowPattern + '</tr>');
   
   $('#top_NEW').attr(   'onclick', 'navOptions_redirect(' + newItemID + ', "top")');
   $('#up_NEW').attr(    'onclick', 'navOptions_redirect(' + newItemID + ', "up")');
   $('#delete_NEW').attr('onclick', 'navOptions_redirect(' + newItemID + ', "delete")');
   
   $('#name_NEW').attr(    'name', 'name_' + newItemID);
   $('#url_NEW').attr(     'name', 'url_' + newItemID);
   $('#relative_NEW').attr('name', 'relative_' + newItemID);
   $('#title_NEW').attr(   'name', 'title_' + newItemID);
   
   $('#name_NEW').removeAttr('id');
   $('#url_NEW').removeAttr('id');
   $('#relative_NEW').removeAttr('id');
   $('#title_NEW').removeAttr('id');
   $('#top_NEW').removeAttr('id');
   $('#up_NEW').removeAttr('id');
   $('#delete_NEW').removeAttr('id');
}

</script>