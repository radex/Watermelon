var Installer_Intro = false;
var Installer_Step = 1;

window.onload = function()
{
   // launch intro
   
   intro()
   
   // resizing first step's .content-box
   
   $('#content-inner').css({height: $('.content-box:first-of-type').innerHeight()})
   
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
   // if there is a form, first validate it
   
   if($('.current form').length == 1)
   {
            //TODO: make ajax request here
   }
   else
   {
      next()
   }
}

/**************************************************************************/
/* moves to the next step */

function next()
{
   // enable "previous" button
   
   $('#previous-button').removeAttr('disabled')
   
   // change .current class
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').removeClass('current')
   
   Installer_Step++
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current')
   
   // resize height of container and move
   
   nextStepHeight = $('.content-box:nth-of-type(' + Installer_Step + ')').innerHeight()
   
   $('#content-inner').animate({marginLeft: '-=750px', height: nextStepHeight}, 400, function()
   {
      // focus first input (or next button if no inputs)
      
      $('#next-button').focus()
      $('.content-box.current input:eq(1)').focus()
   })
}

/**************************************************************************/
/* moves to the previous step */

function previous(step)
{
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
      $('.content-box.current input:eq(1)').focus()
   })
}