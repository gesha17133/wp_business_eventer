jQuery(document).ready(function($){
  
    jQuery.cookie=function(name,value,options){if(typeof value!='undefined'){options=options||{};if(value===null){value='';options.expires=-1}var expires='';if(options.expires&&(typeof options.expires=='number'||options.expires.toUTCString)){var date;if(typeof options.expires=='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000))}else{date=options.expires}expires='; expires='+date.toUTCString()}var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('')}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break}}}return cookieValue}};   
    
    $('#Capa_1').click(function(){

        $("#Capa_1").addClass('clicked');
        
        var pathToMyPage = window.location.pathname;        

        if ( !$.cookie("post-status") || $.cookie("post-status") == 'unliked'){

            $.cookie('post-status', 'liked' ,{ expires : 3, path: pathToMyPage });

            data = {
                'action': 'like_post',
                'count': ++like_post.count,
                'post_id': like_post.post_id,
            }

            $.ajax({
                url: like_post.ajaxurl,
                data: data,
                type: 'POST',
                success: function (data) {
                    $('#text-like').text(like_post.count)

                }

            });  
        }
        
        else if($.cookie("post-status") == 'liked'){
    
            $('#Capa_1').removeClass('liked');
    
            $('#Capa_1').removeClass('clicked');
    
            $.cookie('post-status', 'unliked' ,{ expires : 3, path: pathToMyPage });
            
            data = {
                'action': 'like_post',
                'count': --like_post.count,
                'post_id': like_post.post_id,
            }

            $.ajax({
                url: like_post.ajaxurl,
                data: data,
                type: 'POST',
                success: function (data){
                    $('#text-like').text(like_post.count)
                }
            });
        
        }

        $('#text-like').text(like_post.count);        
    
    });

    if ($.cookie('post-status') == 'liked') {
        $('#Capa_1').addClass('liked');
    }

    $('#text-like').text(like_post.count);
    
});