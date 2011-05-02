<?php die?>
<div class="content-box" id="permissions">
   <h1>Uprawnienia</h1>
   
   <div class="messages" />
   
   <p>Do poprawnego działania, Watermelon musi mieć możliwość zmiany zawartości kilku plików i folderów.</p>
   <p>Twój serwer wymaga do tego ręcznej zmiany ich <em>uprawnień</em>.</p>
   
   <p>Nadaj poniższym plikom i folderom uprawnienia do zapisu (uprawnienia <em>777</em>):</p>
   
   <ul class="fileslist">
      <li tal:repeat="file files">${file}</li>
   </ul>
   
   <p>(<a href="#" onclick="nextClick(); return false;">sprawdź ponownie</a>)</p>
   
   <p>W razie wątpliwości, zobacz dział pomocy na stronie dostawcy Twojego serwera lub zajrzyj do pomocy swojego klienta FTP.</p>
</div>