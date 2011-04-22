/*
 * shows nice intro
 */

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

/*
 * moves to the next step
 */

function next()
{
   fixHeight()
   
   // change .current class
   
   $('.content-box.current').removeClass('current')
   
   Installer_Step++
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current')
   
   // buttons
   
   Installer_ButtonsDisabled = true;
   
   if(Installer_Step == Installer_Steps)
   {
      $('#next-button').attr('disabled', 'disabled')
   }
   
   $('#previous-button').removeAttr('disabled')
   
   // resize height of container and move
   
   nextStepHeight = $('.content-box.current').innerHeight()
   
   $('#content-inner').animate({marginLeft: '-=750px', height: nextStepHeight}, 400, function()
   {
      afterMove()
   })
   
   // resize progress bar
   
   progressWidth = (Installer_Step / (Installer_Steps + 1) * 100)
   
   if($('#progress-bar-progress').attr('data-width') < progressWidth)
   {
      $('#progress-bar-progress').animate({width: progressWidth + '%'}, 400)
      $('#progress-bar-progress').attr('data-width', progressWidth)
   }
}

/*
 * moves back to the previous step
 */

function previous(step)
{
   fixHeight()
   
   // change .current class

   $('.content-box:nth-of-type(' + Installer_Step + ')').removeClass('current')
   
   Installer_Step--
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current')
   
   // buttons
   
   Installer_ButtonsDisabled = true;
   
   if(Installer_Step == 1)
   {
      $('#previous-button').attr('disabled', 'disabled')
   }
   
   $('#next-button').removeAttr('disabled')
   
   // resize height of container and move

   prevStepHeight = $('.content-box.current').innerHeight()

   $('#content-inner').animate({marginLeft: '+=750px', height: prevStepHeight}, 400, function()
   {
      afterMove()
   })
}

/**************************************************************************/

/*
 * does some stuff common to both next() and previous() after animation is complete
 */

function afterMove()
{
   // focus first input (or next button if no inputs)
   
   if($('.content-box.current input')[0])
   {
      $('.content-box.current input')[0].focus()
   }
   else
   {
      $('#next-button').focus()
   }
   
   // hide other .content-boxes and flex height
   
   flexHeight()
   
   // enable buttons
   
   Installer_ButtonsDisabled = false;
}