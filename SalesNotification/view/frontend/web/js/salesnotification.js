require(['jquery'], function($){ 

  (function( $ ){

    $.fn.getSalesNotification = function() {
      
      if(window.salesnotification_current_catID){
        var current_catid = window.salesnotification_current_catID;
      } else{
        var current_catid = 0;
      }

      var url = '/salesnotification/api/SalesNotifcation';
      jQuery.ajax({
        url: url,
        showLoader: true,
        dataType:'json',
        type: 'POST',
        showLoader:false,
        data: {'current_catid' : current_catid},
    
        success: function(result){    
         var sn = sessionStorage.getItem("sn");
         if(sn != result.sn || sn == 0){
       
            sessionStorage.setItem("sn", result.sn);
            $('.notify_product_name').text(result.product_name);
            $('.purchase_ago').text(result.purchase_ago);
            $('#notifiy_product_img').attr('src',result.product_img);
            $('.notify_product_url').attr("href",result.product_url);
            $('#contentNotification').delay(1000).fadeIn();
            $('#contentNotification').delay(4000).fadeOut();    
         }
        }          
  
        
      });
       return this;
    }; 
 })( jQuery );
  


  $(document).ready(function(){
   
     
    if(window.salesnotification_enable === 1){ 
      $('#getSalesData').getSalesNotification();
     
      setInterval(function()
        { 
          $('#getSalesData').getSalesNotification();
        }, window.getHowLong);//time in milliseconds 
    }
  })



})



 
 