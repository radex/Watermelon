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
   
   progressWidth = (1 / Installer_Steps * 100);
   
   $('#progress-bar-progress').css({width: progressWidth + '%'});
   $('#progress-bar-progress').attr('data-width', progressWidth); // just to make things easier
   
   // buttons
   
   $('#next-button').focus();
   $('#previous-button').attr('disabled', 'disabled');
   
   // trigger page change
   
   $('input[type!=button]').keyup(function(e)
   {
      if(e.keyCode == 13)
      {
         nextClick();
      }
   });
   
   $('#next-button').click(nextClick);
   
   $('#previous-button').click(previous);
   
   // form.submit() don't do anything
   
   $('form').submit(function(){ return false; });
   
   // "Show advanced" in db info form
   
   $('#dbinfo-advanced-hr a').click(showAdvanced);
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
   
   // validating or just moving to the next step
   
   switch($('.current').attr('id'))
   {
      case 'permissions':        permissionsValidator(); break;
      case 'dbinfo':             dbInfoValidator(); break;
      case 'userdata':           userDataValidator(); break;
      case 'sitename':           install(); break;
      case 'permissions_after':  permissions_afterValidator(); break;
      default:
         next();
      break;
   }
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