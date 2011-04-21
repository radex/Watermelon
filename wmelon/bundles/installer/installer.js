var Installer_Intro = false;
var Installer_Step = 1;
var Installer_Steps;
var Installer_ButtonsDisabled = false;

window.onload = function()
{
   // launch intro
   
   intro()
   
   // resizing first step's .content-box
   
   $('#content-inner').css({height: $('.content-box:first-of-type').innerHeight()})
   
   // resizing progress bar
   
   Installer_Steps = $('.content-box').length
   
   progressWidth = (1 / (Installer_Steps + 1) * 100)
   
   $('#progress-bar-progress').css({width: progressWidth + '%'})
   $('#progress-bar-progress').attr('data-width', progressWidth) // just to make things easier
   
   // buttons
   
   $('#next-button').focus()
   $('#previous-button').attr('disabled', 'disabled')
   
   // trigger page change
   
   $('input[type!=button]').keyup(function(e)
   {
      if(e.keyCode == 13 && Installer_Step < Installer_Steps)
      {
         nextClick()
      }
   })
   
   $('#next-button').click(nextClick)
   
   $('#previous-button').click(previous)
   
   // hooking up validators
   
   $('#userdata form').submit(userdataValidator)
}

/**************************************************************************/
/* "Next" button clicked */

function nextClick()
{
   // ignoring if disabled (it's disabled during animations)
   
   if(Installer_ButtonsDisabled)
   {
      return
   }
   
   // validating (if there's a form) or just moving to the next step
   
   /*if($('.current form').length == 1)
   {
      $('.current form').submit()
   }
   else*/
   {
      next()
   }
}

/**************************************************************************/
/* shows error(s) */

function displayErrors(messagesArray)
{
   // joining into HTML
   
   messages = '<div class="messages">'
   
   $.each(messagesArray, function(index, value)
   {
      messages += '<div class="error">' + value + '</div>'
   })
   
   messages += '</div>'
   
   // displaying and animating
   
   $('.current h1').after(messages)
   
   height = $('.current .messages').height();
   
   $('.current .messages').css({height: 0})
   $('.current .messages').animate({height: height}, 200)
}

/**************************************************************************/
/* validates userdata form */

function userdataValidator()
{
   displayErrors(['Błąd', 'Błąd 2   '])
   
   return false;
}