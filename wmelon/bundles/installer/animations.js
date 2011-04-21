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
   // disable buttons (so that it can't be clicked during animating)
   
   Installer_ButtonsDisabled = true;
   
   // unhide other boxes, restore fixed height of #content-boxes, delete .current's margin
   
   $('.content-box').show()
   
   currentHeight = $('.content-box.current').innerHeight()
   
   $('#content-inner').css({height: currentHeight})
   $('.content-box.current').css({marginLeft: 0})
   
   // change .current class
   
   $('.content-box.current').removeClass('current')
   
   Installer_Step++
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current')
   
   // disable "next" button (if moved to the last step), enable "previous" button
   
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
   // unhide other boxes, restore fixed height of #content-boxes, delete .current's margin

   $('.content-box').show()

   currentHeight = $('.content-box.current').innerHeight()

   $('#content-inner').css({height: currentHeight})
   $('.content-box.current').css({marginLeft: 0})
   
   // change .current class

   $('.content-box:nth-of-type(' + Installer_Step + ')').removeClass('current')
   
   Installer_Step--
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current')
   
   // disable "previous" button (if moved back to the first step), enable "next" button
   
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
   
   $('.content-box').hide()
   $('.content-box.current')
      .show()                                        // unhide current one
      .css({marginLeft: (Installer_Step - 1) * 750}) // and change margin so that content stays in place
   
   $('#content-inner').css({height: 'auto'})
   
   // enable buttons
   
   Installer_ButtonsDisabled = false;
}