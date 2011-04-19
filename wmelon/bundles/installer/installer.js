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
   
   progressWidth = (1 / (Installer_Steps + 1) * 100) + '%'
   
   $('#progress-bar-progress').css({width: progressWidth})
   
   // buttons
   
   $('#next-button').focus()
   $('#previous-button').attr('disabled', 'disabled')
   
   // trigger page change
   
   $('input[type!=button]').keyup(function(e)
   {
      if(e.keyCode == 13)
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
/* shows nice intro */

function intro()
{
   // moving header to the center
   
   height = window.innerHeight
   headerPaddingTop = (height - 100) / 2
   
   $('header').css({display: 'block', paddingTop: headerPaddingTop + 'px', backgroundColor: '#ddd'})
   $('header div').css({opacity: 0})
   
   // moving container
   
   containerHeight = $('#container').height()
   $('#container').css({display: 'block', marginTop: (headerPaddingTop - containerHeight) + 'px'})
   
   // animating header
   
   $('header div').animate({opacity: 1}, Installer_Intro ? 2000 : 0, function()
   {
      // moving header and container on the proper positions
      
      $('header').animate({paddingTop: 0}, Installer_Intro ? 500 : 0)
      $('#container').animate({marginTop: '75px'}, Installer_Intro ? 500 : 0, function()
      {
         // making header transparent (so that box-shadow of container looks good)
         
         $('header').css('background', 'transparent')
      })
   })
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
/* moves to the next step */

function next()
{
   // disable buttons (so that it can't be clicked during animating)
   
   Installer_ButtonsDisabled = true;
   
   // enable "previous" button
   
   $('#previous-button').removeAttr('disabled')
   
   // unhide other boxes, restore fixed height of #content-boxes, delete .current's margin
   
   $('.content-box').show()
   
   currentHeight = $('.content-box.current').innerHeight()
   
   $('#content-inner').css({height: currentHeight})
   $('.content-box.current').css({marginLeft: 0})
   
   // change .current class
   
   $('.content-box.current').removeClass('current')
   
   Installer_Step++
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current')
   
   // resize height of container and move
   
   nextStepHeight = $('.content-box.current').innerHeight()
   
   $('#content-inner').animate({marginLeft: '-=750px', height: nextStepHeight}, 400, function()
   {
      // focus first input (or next button if no inputs)
      
      $('#next-button').focus()
      $('.content-box.current input')[0].focus()
      
      // hide other .content-boxes and flex height
      
      $('.content-box').hide()
      $('.content-box.current')
         .show()                                        // unhide current one
         .css({marginLeft: (Installer_Step - 1) * 750}) // and change margin so that content stays in place
      
      $('#content-inner').css({height: 'auto'})
      
      // enable buttons
      
      Installer_ButtonsDisabled = false;
   })
   
   // resize progress bar
   
   progressWidth = (Installer_Step / (Installer_Steps + 1) * 100) + '%'
   
   $('#progress-bar-progress').animate({width: progressWidth}, 400)
}

/**************************************************************************/
/* moves to the previous step */

function previous(step)
{
   // unhide other boxes, restore fixed height of #content-boxes, delete .current's margin

   $('.content-box').show()

   currentHeight = $('.content-box.current').innerHeight()

   $('#content-inner').css({height: currentHeight})
   $('.content-box.current').css({marginLeft: 0})
   
   // change .current class

   $('.content-box:nth-of-type(' + Installer_Step + ')').removeClass('current')
   
   Installer_Step--
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current')
   
   // disable "previous" button (if moved back to the first step)
   
   if(Installer_Step == 1)
   {
      $('#previous-button').attr('disabled', 'disabled')
   }
   
   // resize height of container and move

   prevStepHeight = $('.content-box:nth-of-type(' + Installer_Step + ')').innerHeight()

   $('#content-inner').animate({marginLeft: '+=750px', height: prevStepHeight}, 400, function()
   {
      // focus first input (or next button if no inputs)
      
      $('#next-button').focus()
      $('.content-box.current input')[0].focus()
      
      // hide other .content-boxes and flex height
      
      $('.content-box').hide()
      $('.content-box.current')
         .show()                                        // unhide current one
         .css({marginLeft: (Installer_Step - 1) * 750}) // and change margin so that content stays in place
      
      $('#content-inner').css({height: 'auto'})
   })
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