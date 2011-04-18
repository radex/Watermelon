var Installer_Intro = false;
var Installer_Step = 1;

window.onload = function()
{
   // launch intro
   
   intro()
   
   // hook page moving
   
   $('#next-button').click(function()
   {
      next()
   })
   
   $('#previous-button').click(function()
   {
      previous()
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
/* moves to the next step */

function next()
{
   Installer_Step++
   
   // resize height of container and move
   
   nextStepHeight = $('.content-box:nth-of-type(' + Installer_Step + ')').innerHeight()
   
   $('#content-inner').animate({marginLeft: '-=750px', height: nextStepHeight}, 400)
}

/**************************************************************************/
/* moves to the previous step */

function previous(step)
{
   Installer_Step--

   // resize height of container and move

   prevStepHeight = $('.content-box:nth-of-type(' + Installer_Step + ')').innerHeight()

   $('#content-inner').animate({marginLeft: '+=750px', height: prevStepHeight}, 400)
}