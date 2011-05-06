/*
 * Displays error(s) from array
 */

function displayErrors(messagesArray)
{
   // joining into HTML
   
   messages = '';
   
   $.each(messagesArray, function(index, value)
   {
      messages += '<div class="error">' + value + '</div>';
   });
   
   // displaying
   
   $('.current .messages').css({height: 'auto'});
   
   height_before = $('.current .messages').height();
   
   $('.current .messages').html(messages);
   
   height_after = $('.current .messages').height();
   
   // flash if height didn't change (contents are the same or other message but still the same height)
   // or animate height change
   
   if(height_before == height_after)
   {
      $('.current .messages').css({opacity: .5});
      $('.current .messages').animate({opacity: 1}, 150);
   }
   else
   {
      $('.current .messages').css({height: height_before});
      $('.current .messages').animate({height: height_after, marginBottom: 10}, 400); // paddingBottom: 0 to fix WebKit bug
   }
}

/**************************************************************************/

/*
 * Sets fixed height to the #content-inner based on current height of .content-box.current
 * (needed for animations)
 */

function fixHeight()
{
   $('.content-box').css({height: 'auto'})

   currentHeight = $('.content-box.current').innerHeight();

   $('#content-inner').css({height: currentHeight});
}

/*
 * Sets flexible height to the #container-inner while hiding .content-boxes other than .current.
 * (so that e.g. errors can appear)
 */

function flexHeight()
{
   $('.content-box').css({height: 0});
   $('.content-box.current').css({height: 'auto'});
   
   $('#content-inner').css({height: 'auto'});
}

/**************************************************************************/

var Installer_Dim = null;

/*
 * Dims container so that user can see something happens when AJAX request is made
 * 
 * Dim is delayed by .3s to avoid blink if network connection is fast
 */

function dim()
{
   Installer_Dim = setTimeout("$('.content-box.current').css({opacity: 0.7})", 300);
}

/*
 * Undims container after AJAX request is completed
 */

function undim()
{
   clearTimeout(Installer_Dim);
   $('.content-box.current').css({opacity: 1});
}