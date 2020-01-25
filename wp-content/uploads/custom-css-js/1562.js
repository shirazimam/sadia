<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
/* Default comment here */ 


jQuery(document).ready(function( $ ){
  	
 /* $.getJSON("http://jsonip.com?callback=?", function (data) {
		$.getJSON("https://cors-anywhere.herokuapp.com/http://www.geoplugin.net/json.gp?ip="+data.ip,function(response){
    	let country = response.geoplugin_countryName;
        if(country == "India"){
          console.log(country);
          $('.woocs_auto_switcher').find('li:nth-child(2)').find('span').click();
        }else{
          $('.woocs_auto_switcher').find('li:nth-child(1)').find('span').clik();
        }
     });
  });
  */
// the request will be to http://www.geoplugin.net/json.gp?ip=my.ip.number but we need to avoid the cors issue, and we use cors-anywhere api.

  
  
  	$('.show-register-button button').on('click',function(e){
      	e.preventDefault();
    	$('.register-col').toggleClass('d-none');
      $('.login-col').toggleClass('d-none');
    });
  $('.show-login-button button').on('click',function(e){
      	e.preventDefault();
    	$('.register-col').toggleClass('d-none');
      $('.login-col').toggleClass('d-none');
    });
    $('.open-search').on('click',function(){
    	$('.search-bar-wrapper').toggleClass('d-none');
    });
  
  $('.close-search').click(function(){
  $('.search-bar-wrapper').toggleClass('d-none');
  });
  
  $('.product a').on('click',function(e){
  //e.preventDefault();
   // window.location.href ="/sadia/index.php/contact-us/"
  })
  
  $('.left-arrow').on('click',function(){
  $('.slick-prev').click();
    $('.active-dot').addClass('add-ripple');
    setTimeout(function(){
            $('.active-dot').removeClass('add-ripple');
    }, 1000);
  });
  
   $('.right-arrow').on('click',function(){
  $('.slick-next').click();
     $('.active-dot').addClass('add-ripple');
     setTimeout(function(){
            $('.active-dot').removeClass('add-ripple');
    }, 1000);
  });
  
  
    
       
});
</script>
<!-- end Simple Custom CSS and JS -->
