jQuery(document).ready( function($) {
$('#load-more').click(function(){

    var button = $(this),
        data = {
            'action': 'load_more',
            'posts' :load_more.posts,
            'current_page': load_more.current_page,
            'max_page': load_more.max_page
        };
        
    $.ajax({
        url : load_more.ajaxurl,
        data : data,
        type : 'POST',
        beforeSend : function (xhr) { 
            button.text('Loading...');
        },
        success : function( data ){
        if( data ){
            button.text('More posts').prev().before(data);
            load_more.current_page++;
            if( load_more.current_page == load_more.max_page )
                button.remove();
            }
            else{
                button.remove();
            }
        }
    });

});
});