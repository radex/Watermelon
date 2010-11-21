var ACP_sidebarHandle_is = false;

$(document).ready(function()
{
   $('#sidebar-handle').mousedown(function(e)
   {
      ACP_sidebarHandle_is = true;
   });
   
   $(document).mouseup(function()
   {
      ACP_sidebarHandle_is = false;
   });
   
   $(document).mousemove(function(e)
   {
      if(ACP_sidebarHandle_is)
      {
         width = e.pageX;
         
         if(width < 175)
         {
            width = 175;
         }
         else if(width > 400)
         {
            width = 400;
         }
         else if(width > 240 && width < 260)
         {
            width = 250;
         }
         
         $('#bottombarLeft').css('width', width - 10);
         $('#sidebar').css('width', width);
         $('#content').css('left', width);
      }
   });
});