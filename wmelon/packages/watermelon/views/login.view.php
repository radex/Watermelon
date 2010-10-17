<?die?>
<tal:block>
   <!-- TODO: make better form -->
   
   <form action="${php: SiteURI('auth/loginSubmit')}" method="post">
      
      <label>
         Login: <input name="login" />
      </label>
      <br />
      
      <label>
         Hasło: <input type="password" name="pass" />
      </label>
      <br />
      
      <input type="submit" value="Zaloguj" />
      
   </form>
</tal:block>