jQuery(document).ready(function($){
    var currentMonth = 0,
    currentYear = 2015,
    monthMap = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
    $dayList = $('.days');
    

    var addDayElement = function(date, $container) {
    var element = $(document.createElement('a')).addClass('date');
    
    if (date.getMonth()  !== currentMonth) {element.addClass('out-of-scope');}
    
    
    element.text(date.getDate());
    
    $container.append(element);
    };
    var getFirstLastDates = function(date) {
    var startDate, endDate;
    
    startDate = new Date(date.getFullYear(), date.getMonth(), 1);
    while (startDate.getDay() !== 1) { startDate.setDate(startDate.getDate() - 1); }
    
    endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    while (endDate.getDay() !== 0) { endDate.setDate(endDate.getDate() + 1); }
    return [startDate, endDate];
    };

    var renderDays = function(dateRange) {
    $dayList.empty();
    var startDate = dateRange[0],
        endDate = dateRange[1],
        currentDate = startDate;

    while (currentDate <= endDate){
        addDayElement(currentDate, $dayList);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    
    }

    var loadCalendar = function(date){

    $('.headline .month').text(monthMap[currentMonth]);
    $('.headline .year').text(currentYear);
    renderDays(getFirstLastDates(date));
    };

    loadCalendar(new Date());


    var dates = picker.available_posts; 
    
    $('.date').each(function(){
        if ($(this).text() < 9){
            d = '0' + $(this).text();
        } else {
            d = $(this).text();
        }
            
        if (currentMonth < 9) {
            m = '0' + (currentMonth + 1);
        } else {
            m = currentMonth + 1;
        }

        full_date = `${currentYear}-${m}-${d}`;
        
    })

    $('.days').on('click', '.date', function(e){

        $('.date').removeClass('selected');    
        $(this).addClass('selected');

        this_day = $(this).text();
        if (currentMonth < 9) {
            mounth = '0' + (currentMonth + 1);
        } else {
            mounth = currentMonth + 1;
        }


        if (this_day < 9) {
            this_day = '0' + (this_day);
        } else {
            this_day = this_day;
        }    
            
        dataq = {
            'action' : 'picker',
            'mounth' : mounth,
            'day'    : this_day,
            'year'   : currentYear,
            'available_posts' : JSON.parse(picker.available_posts),
            'link_to': picker.link_to,
        }

        $.ajax({
            type: "POST",
            url: picker.ajaxurl,
            data: dataq,
            success: function(data){
                $('.events_archive_block').html(data);
            }
        });
        $('.date').attr('href',`${picker.link_to}?year=${currentYear}&month=${mounth}&day=${this_day}`)


    });
    
    $('.click-left').on('click', function(e) {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    loadCalendar(new Date(currentYear, currentMonth));
    });

    $('.click-right').on('click', function(e){
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    loadCalendar(new Date(currentYear, currentMonth));

    });


});