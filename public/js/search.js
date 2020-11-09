jQuery(document).ready(function ($) {
    //SELECTORS TO WORK WITH
    let icon_search = $('.search_ico');
    let ico_close = $('.close_modal');
    let window_selected = $('.overlay_search');
    let input_field = $('#site_search_field');
    let search_staff = $('.wrapper_data_importance');
    let range_field = $('.range_date_field');
    let search_form = $('.search_form');
    let loader_ico = $('.search_results_preloader');
    let results_div = $(".results_div");
    let search_apply_filter = $('.search_start_button');
    let taxonomy_fields = $('input:checked');
    let info_block = $('.block_info');

    function openWindow() {
        window_selected.fadeIn(400);
    }

    function hideWindow() {
        window_selected.fadeOut(200);
    }
    /*******************************_RETURN_RESULTS_*******************************************/

    function ReturnResult(){
        posts_data = {
            'action': 'search_section',
            'link': search_section.site_link,
        }

         
        let selected = [];
            $('#checkboxes input:checked').each(function() {
            selected.push($(this).attr('name'));
        });

        posts_link = `${posts_data.link}wp/v2/events/required_posts?search=${input_field.val()}&from=${$("#amount").val()}&to=${$('#end_date').val()}&taxonomy=${selected}`;
        
        /*********_OUTPUT SECTION_*************/
        $.getJSON(posts_link,function(postList){
            postList.length < 10 ? 
            results_div.html(
                `<ul class="events_list">
                    ${ postList.map(data =>`
                    <li class="full_post_info"><a  href="${data.link}">${data.title}</a>
                        <div class="block_info">
                            <ul class="info_post_meta">
                                <p>Persons</p>
                                ${data.personas ? data.personas.map(person => `<li><a href="${person.link}">${person.name ? person.name : " "} ${person.surname ? person.surname : " " }</a></li>`).join(" ") : `<span>No Persons here Sir</span>`}
                            </ul>
                            <ul class="info_post_meta">
                                <p>Related Posts</p>
                                ${data.related_posts ? data.related_posts.map(related_post => `<li><a href="${related_post.link}">${related_post.title}</a></li>`).join(" ") : `<span>Related Posts Not Found</span>`}
                            </ul>
                        </div>
                    </li>`).join(' ')}
                </ul>`

                ) : results_div.html(`<h1 class="not_found_posts">Not found posts</h1>`);
        })

        .done(function(e) {
            console.log("second success");
        })
        .fail(function(e) {
            results_div.html("<p>POSTS NOT FOUND</p>")
        })
        .always(function() {
            console.log("complete");
        })
    }


    /***************************_ANIMATED OVERLAY_***********************************/

    function strecthScroll() {
        let current_scroll = window.pageYOffset;

        
        if (current_scroll > 300) {
            search_staff.fadeOut(200);
            range_field.fadeOut(350);
            window_selected.css("position", "fixed");
            window_selected.css("padding-top", "100px");
            search_form.css('border-bottom', 'none');
            window_selected.css('padding-bottom', '0');
        }

        if (current_scroll < 300) {
            window_selected.css("position", "absolute");
            search_staff.fadeIn(200);
            range_field.fadeIn(350);
            window_selected.css("padding-top", "20px");
            search_form.css('border-bottom', '1px solid gray');
            window_selected.css('padding-bottom', '30px');
        }

    }

    /********************************_PICK-DATES_***********************************/    
    $(function () {
        $("#slider-range").slider({
            range: true,
            min: new Date('2010-01-01').getTime() / 1000,
            max: new Date('2020-01-01').getTime() / 1000,
            step: 86400,
            values: [new Date('2010-01-02').getTime() / 1000, new Date('2013-02-01').getTime() / 1000],
            slide: function (event, ui) {
                $("#amount").val((new Date(ui.values[0] * 1000).toLocaleDateString()));
                $("#end_date").val((new Date(ui.values[1] * 1000).toLocaleDateString()));
            }
        });
        $("#amount").val((new Date($("#slider-range").slider("values", 0) * 1000).toLocaleDateString()));
        $('#end_date').val((new Date($("#slider-range").slider("values", 1) * 1000)).toLocaleDateString());
    });

    //events
    $(window).on('scroll', window, strecthScroll);
    icon_search.on('click', window_selected, openWindow);
    ico_close.on('click', window_selected, hideWindow);
    search_apply_filter.on("click", ReturnResult); 
    
});
