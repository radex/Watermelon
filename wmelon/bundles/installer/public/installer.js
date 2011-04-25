var Installer_Intro = false;
var Installer_Step = 1;
var Installer_Steps;
var Installer_ButtonsDisabled = false;

window.onload = function()
{
   // launch intro
   
   intro();
   
   // give .current to the first step and hide other boxes
   
   $('.content-box:first-of-type').addClass('current');
   
   flexHeight();
   
   // resizing progress bar
   
   Installer_Steps = $('.content-box').length;
   
   progressWidth = (1 / (Installer_Steps + 1) * 100);
   
   $('#progress-bar-progress').css({width: progressWidth + '%'});
   $('#progress-bar-progress').attr('data-width', progressWidth); // just to make things easier
   
   // buttons
   
   $('#next-button').focus();
   $('#previous-button').attr('disabled', 'disabled');
   
   // trigger page change
   
   $('input[type!=button]').keyup(function(e)
   {
      if(e.keyCode == 13 && Installer_Step < Installer_Steps)
      {
         nextClick();
      }
   });
   
   $('#next-button').click(nextClick);
   
   $('#previous-button').click(previous);
   
   // form.submit() don't do anything
   
   $('form').submit(function(){ return false; });
}

/*
 * action after "Next" button is clicked
 */

function nextClick()
{
   // ignoring if disabled (it's disabled during animations)
   
   if(Installer_ButtonsDisabled)
   {
      return;
   }
   
   // validating (if there's a form) or just moving to the next step
   
   if($('.current form').length == 1)
   {
      switch($('.current').attr('id'))
      {
         case 'dbinfo':   dbInfoValidator(); break;
         case 'userdata': userDataValidator(); break;
         case 'sitename': siteNameValidator(); break;
      }
   }
   else
   {
      next();
   }
}

/**************************************************************************/

/*
 * Displays error(s) from array
 */

function displayErrors(messagesArray)
{
   // joining into HTML
   
   messages = '';
   
   $.each(messagesArray, function(index, value)
   {
      messages += '<div class="error">' + value + '</div>';
   });
   
   // displaying
   
   $('.current .messages').css({height: 'auto'});
   
   height_before = $('.current .messages').height();
   
   $('.current .messages').html(messages);
   
   height_after = $('.current .messages').height();
   
   // flash if height didn't change (contents are the same or other message but still the same height)
   // or animate height change
   
   if(height_before == height_after)
   {
      $('.current .messages').css({opacity: .5});
      $('.current .messages').animate({opacity: 1}, 150);
   }
   else
   {
      $('.current .messages').css({height: height_before});
      $('.current .messages').animate({height: height_after, marginBottom: 10}, 400); // paddingBottom: 0 to fix WebKit bug
   }
}

/**************************************************************************/

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
      errors.push('Wszystkie pola muszą być wypełnione');
   }
   
   // check if database name and prefix are valid
   
   if(!name.match(/^[a-z0-9_]*$/i))
   {
      errors.push('Nazwa bazy danych jest niepoprawna — dozwolone są jedynie litery, cyfry oraz znak "_"');
   }
   
   if(!prefix.match(/^[a-z0-9_]*$/i))
   {
      errors.push('Prefiks nazw tabel jest niepoprawny — dozwolone są jedynie litery, cyfry oraz znak "_"');
   }
   
   // stop here if there are errors
   
   if(errors.length > 0)
   {
      displayErrors(errors);
      return;
   }
   
   // container dim (so that user can see something happens) -- in .5s delay to avoid blink if network connection is fast
   
   dim = setTimeout("$('.content-box.current').css({opacity: 0.7})", 500);
   
   // do some server-side validation
   
   $.ajax(
   {
      url: 'db.json',
      dataType: 'json',
      type: 'POST',
      data: {name: name, user: user, pass: pass, prefix: prefix, host: host}
   })
   .success(function(data)
   {
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
      
      // css
      
      clearTimeout(dim);
      $('.content-box.current').css({opacity: 1});

      // display errors or go forward

      displayErrors(errors);

      if(errors.length == 0)
      {
         next();
      }
   })
   .error(function(jqXHR)
   {
      console.log(jqXHR.responseText);
      
      clearTimeout(dim);
      $('.content-box.current').css({opacity: 1});
      
      displayErrors(['Wystąpił jakiś dziwny błąd. Spróbuj jeszcze raz.']);
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
      errors.push('Wszystkie pola muszą być wypełnione');
   }
   
   // check if passwords are the same
   
   if(pass.length > 0 && pass2.length > 0 && pass != pass2)
   {
      errors.push('Podane hasła nie pasują do siebie');
   }
   
   // display errors or go forward
   
   displayErrors(errors);
   
   if(errors.length == 0)
   {
      next();
   }
}

/*
 * Validates site name form
 */

function siteNameValidator()
{
   errors = [];
   
   // trim
   
   siteName = trim('#sitename-input');
   
   // check if filled
   
   if(siteName.length == 0)
   {
      errors.push('Podaj nazwę dla swojej strony');
   }
   
   // display errors or go forward
   
   displayErrors(errors);
   
   if(errors.length == 0)
   {
      next();
   }
}

/**************************************************************************/

/*
 * Sets fixed height to the #content-inner based on current height of .content-box.current
 * (needed for animations)
 */

function fixHeight()
{
   $('.content-box').show();

   currentHeight = $('.content-box.current').innerHeight();

   $('#content-inner').css({height: currentHeight});
   $('.content-box.current').css({marginLeft: 0});
}

/*
 * Sets flexible height to the #container-inner while hiding .content-boxes other than .current.
 * (so that e.g. errors can appear)
 */

function flexHeight()
{
   $('.content-box').hide();
   $('.content-box.current')
      .show()                                        // unhide current one
      .css({marginLeft: (Installer_Step - 1) * 750}); // and change margin so that content stays in place
   
   $('#content-inner').css({height: 'auto'});
}

/**************************************************************************/

/*
 * string trim(string selector)
 * 
 * Trims value of $(selector) and returns it
 */

function trim(selector)
{
   val = $.trim($(selector).val());
   
   $(selector).val(val);
   
   return val;
}