var Installer_Intro = true;

/*
 * shows nice intro
 */

function intro()
{
   // moving header to the center
   
   height = window.innerHeight;
   headerPaddingTop = (height - 100) / 2;
   
   $('header').css({display: 'block', paddingTop: headerPaddingTop + 'px', backgroundColor: '#ddd'});
   $('header div').css({opacity: 0});
   
   // moving container
   
   containerHeight = $('#container').outerHeight();
   $('#container').css({display: 'block', marginTop: (headerPaddingTop - containerHeight) + 'px'});
   
   // animating header
   
   $('header div').animate({opacity: 1}, Installer_Intro ? 2000 : 0, function()
   {
      // moving header and container on the proper positions
      
      $('header').animate({paddingTop: 0}, Installer_Intro ? 500 : 0);
      $('#container').animate({marginTop: '75px'}, Installer_Intro ? 500 : 0, function()
      {
         // making header transparent (so that box-shadow of container looks good)
         
         $('header').css('background', 'transparent');
      });
   });
}

/*
 * moves to the next step
 */

function next()
{
   // if that was last step, redirect to homepage
   
   if(Installer_Step == Installer_Steps)
   {
      window.location.href = SiteURL;
      return;
   }
   
   //---
   
   fixHeight();
   
   // if next step is .skip-box (permissions step after setting proper permissions), skip it
   
   skip = false;
   
   if($('.content-box:nth-of-type(' + (Installer_Step + 1) + ')').hasClass('skip-box'))
   {
      skip = true;
   }
   
   // change .current class
   
   $('.content-box.current').removeClass('current');
   
   skip ? Installer_Step += 2 : Installer_Step++;
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current');
   
   // buttons
   
   Installer_ButtonsDisabled = true;
   
   $('#previous-button').removeAttr('disabled');
   
   // resize height of container and move
   
   nextStepHeight = $('.content-box.current').innerHeight();
   
   marginChange = (skip ? '-=1500px' : '-=750px');
   animationDuration = (skip ? 600 : 400);
   
   $('#content-inner').animate({marginLeft: marginChange, height: nextStepHeight}, animationDuration, function()
   {
      afterMove();
   });
   
   // resize progress bar
   
   progressWidth = (Installer_Step / Installer_Steps * 100);
   
   if($('#progress-bar-progress').attr('data-width') < progressWidth)
   {
      $('#progress-bar-progress').animate({width: progressWidth + '%'}, 400);
      $('#progress-bar-progress').attr('data-width', progressWidth);
   }
}

/*
 * moves back to the previous step
 */

function previous(step)
{
   fixHeight();
   
   // if previous step is .skip-box (permissions step after setting proper permissions), skip it
   
   skip = false;
   
   if($('.content-box:nth-of-type(' + (Installer_Step - 1) + ')').hasClass('skip-box'))
   {
      skip = true;
   }
   
   // change .current class

   $('.content-box:nth-of-type(' + Installer_Step + ')').removeClass('current');
   
   skip ? Installer_Step -= 2 : Installer_Step--;
   
   $('.content-box:nth-of-type(' + Installer_Step + ')').addClass('current');
   
   // buttons
   
   Installer_ButtonsDisabled = true;
   
   if(Installer_Step == 1)
   {
      $('#previous-button').attr('disabled', 'disabled');
   }
   
   // resize height of container and move

   prevStepHeight = $('.content-box.current').innerHeight();
   
   marginChange = (skip ? '+=1500px' : '+=750px');
   animationDuration = (skip ? 600 : 400);
   
   $('#content-inner').animate({marginLeft: marginChange, height: prevStepHeight}, animationDuration, function()
   {
      afterMove();
   });
}

/**************************************************************************/
/*
 * Shows hidden inputs in database info step
 */

function showAdvanced()
{
   // showing contents
   
   $('#dbinfo-advanced').css({display: 'block'});
   
   height = $('#dbinfo-advanced').innerHeight();
   
   $('#dbinfo-advanced').css({height: 0});
   $('#dbinfo-advanced').animate({height: height}, 300);
   
   // changing "Show advanced" link to hr
   
   $('#dbinfo-advanced-hr').html('Zaawansowane');
   $('#dbinfo-advanced-hr').animate({borderBottomColor: '#bbb'}, 300)
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
      $('.content-box.current input')[0].focus();
   }
   else
   {
      $('#next-button').focus();
   }
   
   // hide other .content-boxes and flex height
   
   flexHeight();
   
   // enable buttons
   
   Installer_ButtonsDisabled = false;
}