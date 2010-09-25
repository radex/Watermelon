<?defined('WM') or die?>

<?=$errors?>

Podaj nick i hasło, które chcesz mieć dla siebie na swojej stronie.

<form action="$/6" method="post" name="form">
   
   <div>
      <label>Nazwa użytkownika:</label>
      <input type="text" name="user" value="<?=$form->user?>">
   </div>
   
   <div>
      <label>Hasło</label>
      <input type="password" name="pass" value="<?=$form->pass?>">
   </div>
</form>