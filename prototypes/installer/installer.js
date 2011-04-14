var Installer_Animations = true;

window.onload = function()
{
   intro()
   
   $('#content').mousedown(next)
   $('#content').mouseup(previous)
}


function intro()
{
   // moving header to the center
   
   height = window.innerHeight
   headerPaddingTop = (height - 100) / 2
   
   $('header').css({display: 'block', paddingTop: headerPaddingTop + 'px'})
   $('header div').css({opacity: 0})
   
   // moving container
   
   containerHeight = $('#container').height()
   $('#container').css({display: 'block', top: (headerPaddingTop - containerHeight) + 'px'})
   
   // disabling box-shadow property
   
   boxShadow = $('#wrapper').css('box-shadow')
   
   $('#wrapper').css('box-shadow', 'none');
   
   // animating header
   
   $('header div').animate({opacity: 1}, Installer_Animations ? 2000 : 0, function()
   {
      // moving header and container on the proper positions
      
      $('header').animate({paddingTop: 0}, Installer_Animations ? 500 : 0)
      $('#container').animate({top: '100px'}, Installer_Animations ? 500 : 0, function()
      {
         // restoring box-shadow

         $('#wrapper').css('box-shadow', boxShadow)
         
         // making header transparent (so that box-shadow of container looks good)
         
         $('header').css('background', 'transparent')
         $('header div').css('background-color', 'transparent')
      })
   })
}

function next()
{
   $('#content-inner').animate({marginLeft: '-=750px'}, 300)
}

function previous()
{
   $('#content-inner').animate({marginLeft: '+=750px'}, 300)
}