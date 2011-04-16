var Installer_Intro = true;
var Installer_Step = 1;

window.onload = function()
{
   // load first installer step
   
   $('#content-inner').append('<div class="content-box">' + $('#installer-steps > div:nth-of-type(1)').html() + '</div>')
   
      // it seems that there's a bug, because $('.installer-step:nth-of-type()') didn't work.
      // I had to use $('#installer-steps > div:nth-of-type()')
   
   // launch intro
   
   intro()
   
   // hook page moving
   
   $('#next-button').click(function()
   {
      Installer_Step++
      next(Installer_Step)
   })
   
   $('#previous-button').click(function()
   {
      Installer_Step--
      previous(Installer_Step)
   })
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
/* moves to step with "next step" animation */

function next(step)
{
   // load requested step as next
   
   $('#content-inner').append('<div class="content-box">' + $('#installer-steps > div:nth-of-type(' + step + ')').html() + '</div>')
   
   // resize height of container and move
   
   nextStepHeight = $('.content-box:nth-of-type(2)').innerHeight()
   
   $('#content-inner').animate({marginLeft: '-750px', height: nextStepHeight}, 400, function()
   {
      // remove previously current step and change margin
      
      $('.content-box:nth-of-type(1)').remove()
      $('#content-inner').css({marginLeft: 0})
   })
}

/**************************************************************************/
/* moves to step with "previous step" animation */

function previous(step)
{
   // load requested step as previous (and change CSS so that it stays in place)
   
   $('#content-inner').prepend('<div class="content-box">' + $('#installer-steps > div:nth-of-type(' + step + ')').html() + '</div>')
   $('#content-inner').css({marginLeft: '-750px'})
   
               //FIXME: Webkit bug
   
   
   // resize height of container and move
   
   prevStepHeight = $('.content-box:nth-of-type(1)').innerHeight()
   
   $('#content-inner').animate({marginLeft: '0', height: prevStepHeight}, 400, function()
   {
      // remove previously current step
      
      $('.content-box:nth-of-type(2)').remove()
   })
}