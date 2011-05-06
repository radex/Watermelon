/*
 * Validates files/folders permissions
 */

function permissionsValidator()
{
   dim();
   
   // ask server
   
   $.ajax(
   {
      url: WM_SiteURL + 'permissions.json',
      dataType: 'json'
   })
   .error(ajaxErrorHandler)
   .success(permissionsValidatorSuccess);
}

/*
 * Validates database info form
 */

function dbInfoValidator()
{
   errors = [];
   
   // trim all
   
   name   = trim('#db-name');
   user   = trim('#db-user');
   pass   = trim('#db-pass');
   prefix = trim('#db-prefix');
   host   = trim('#db-host');
   
   // check if all required inputs are filled
   
   if(name.length == 0 || user.length == 0 || host.length == 0)
   {
      errors.push('Wypełnij wszystkie pola');
   }
   
   // check if database name and prefix are valid
   
   if(!name.match(/^[a-z0-9_]*$/i))
   {
      errors.push('Podana nazwa bazy danych jest niepoprawna — dozwolone są jedynie litery, cyfry oraz znak "_"');
   }
   
   if(!prefix.match(/^[a-z0-9_]*$/i))
   {
      errors.push('Podany prefiks nazw tabel jest niepoprawny — dozwolone są jedynie litery, cyfry oraz znak "_"');
   }
   
   // stop here if there are errors
   
   if(errors.length > 0)
   {
      displayErrors(errors);
      return;
   }
   
   // do some server-side validation
   
   dim();
   
   $.ajax(
   {
      url: WM_SiteURL + 'db.json',
      dataType: 'json',
      type: 'POST',
      data: {name: name, user: user, pass: pass, prefix: prefix, host: host}
   })
   .error(ajaxErrorHandler)
   .success(function(data)
   {
      undim();
      
      // add errors from response
      
      if(data[0] == 'error')
      {
         $.each(data[1], function(i, value)
         {
            errors.push(value);
         })
      }
      else
      {
         $('#db-prefix').val(data[1]);
      }

      // display errors or go forward

      displayErrors(errors);

      if(errors.length == 0)
      {
         next();
      }
   });
}

/*
 * Validates user data form
 */

function userDataValidator()
{
   errors = [];
   
   // trim all
   
   login = trim('#user-login');
   pass  = trim('#user-pass');
   pass2 = trim('#user-pass2');
   
   // check if all inputs are filled
   
   if(login.length == 0 || pass.length == 0 || pass2.length == 0)
   {
      errors.push('Wypełnij wszystkie pola');
   }
   
   // check if passwords are the same
   
   if(pass.length > 0 && pass2.length > 0 && pass != pass2)
   {
      errors.push('Podane hasła się różnią');
   }
   
   // display errors or go forward
   
   displayErrors(errors);
   
   if(errors.length == 0)
   {
      next();
   }
}

/*
 * Requests installer action
 */

function install()
{
   // disabling "back" button (so that you can't go back after it's installed)
   
   $('#previous-button').attr('disabled', 'disabled');
   
   // installation, part 1
   
   dim();
   
   $.ajax(
   {
      url: WM_SiteURL + 'install-1.json',
      dataType: 'json'
   })
   .error(function(jqXHR)
   {
      undim();
      
      console.log(jqXHR.responseText);
      
      Installer_PopupMessage = jqXHR.responseText;
      
      displayErrors(['Wystąpił jakiś dziwny błąd. <a href="#" onclick="popupError(); return false;">Zobacz błąd</a>']);
      
      // disable buttons (at this stage files are changed, so we can't just redo it)
      
      Installer_ButtonsDisabled = true;
      $('#next-button').attr('disabled', 'disabled');
   })
   .success(function()
   {
      // check if mod_rewrite works
      
      $.ajax(
      {
         url: WM_SystemURL + 'core/urltest.php',
         dataType: 'text'
      })
      .error(ajaxErrorHandler)
      .success(function(mod_rewrite)
      {
         // installation, part 2
         
         $.ajax(
         {
            url: WM_SiteURL + 'install-2.json',
            dataType: 'json',
            type: 'POST',
            data:
               {
                  dbname      : trim('#db-name'),
                  dbuser      : trim('#db-user'),
                  dbpass      : trim('#db-pass'),
                  dbprefix    : trim('#db-prefix'),
                  dbhost      : trim('#db-host'),

                  login       : trim('#user-login'),
                  pass        : trim('#user-pass'),
                  pass2       : trim('#user-pass2'),

                  sitename    : trim('#sitename-input'),

                  mod_rewrite : mod_rewrite
               }
         })
         .error(function(jqXHR)
         {
            undim();

            console.log(jqXHR.responseText);

            Installer_PopupMessage = jqXHR.responseText;

            displayErrors(['Wystąpił jakiś dziwny błąd. <a href="#" onclick="popupError(); return false;">Zobacz błąd</a>']);

            // disable buttons (at this stage files are changed, so we can't just redo it)
            
            Installer_ButtonsDisabled = true;
            $('#next-button').attr('disabled', 'disabled');
         })
         .success(function()
         {
            undim();
            
            next();
         });
      });
   });
}

/**************************************************************************/

/*
 * used by permissionsValidator() and permissions_afterValidator()
 */

function permissionsValidatorSuccess(files)
{
   undim();
   
   // clear errors

   displayErrors([]);
   
   // converting list of files that require write permissions to html
   
   files_html = '';

   $.each(files, function(index, value)
   {
      files_html += '<li>' + value + '</li>';
   });
   
   // displaying
   
   filesListSel = '.content-box.current .fileslist';
   
   $(filesListSel).css({height: 'auto'});

   height_before = $(filesListSel).height();

   $(filesListSel).html(files_html);

   height_after = $(filesListSel).height();

   // flash if height didn't change (contents are the same or other contents but still the same height)
   // or animate height change

   if(height_before == height_after)
   {
      $(filesListSel)
         .css({color: '#BBB'})
         .animate({color: '#666'}, 200);
   }
   else
   {
      $(filesListSel)
         .css({height: height_before})
         .animate({height: height_after}, 200);
   }
   
   // if everything's fine now
   
   if(files.length == 0)
   {
      $('.content-box.current').addClass('skip-box');
      next();
   }
}

/**************************************************************************/

/*
 * Callback for $.ajax(...).error()
 * 
 * Displays notice and logs error
 */

function ajaxErrorHandler(jqXHR)
{
   undim();

   console.log(jqXHR.responseText);

   displayErrors(['Wystąpił jakiś dziwny błąd. Spróbuj jeszcze raz.']);
}

var Installer_PopupMessage = '';

/*
 * Displays message (HTML) from Installer_PopupMessage in a pop-up
 */

function popupError()
{
   popup = window.open('','','width=600,height=400').document;
   
   html = 
   '<!DOCTYPE html>' +
   '<meta charset="UTF-8"/>' + 
   '<link rel="stylesheet" href="' + WM_SystemURL + 'bundles/installer/public/style.css"/>' +
   '<div id="popup-header">' + 
   'Wystąpił błąd, który przerwał instalację.<br>Oto komunikat (dla ekspertów), który się wyświetlił:' +
   '</div>' +
   '<div id="popup-message">' +
   Installer_PopupMessage + 
   '</div>';
   
   popup.write(html);
}