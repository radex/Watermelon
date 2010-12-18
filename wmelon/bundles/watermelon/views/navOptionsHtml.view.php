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

</script>