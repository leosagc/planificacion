$html = $('html');

$(document).on('click','.preventDefault', function(event){
    event.preventDefault();
})

$(function () {
    $('form').on('keyup keypress', "input", function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) {
        e.preventDefault();
        return false;
      }
    });
});

function scrollTop(){
    $("html").scrollTop(0);
}

// Live Search

function ajaxSearch() {
    var route = $('.tabs .selected').attr('href');
    var keywords = $('#keywords').val();
    var type = $('#type').val();
    var district = $('#district').val();
    var perPage = $('#perPage').val();

    if(keywords){
        keywords.toLowerCase().replace(/ /g, '-');
    }

    $.ajax({
        type : 'GET',
        url : route,
        data:{ 'type':type, 'keywords':keywords, 'district':district, 'perPage': perPage },
        success:function(data){
            $('.data').html(data);
            perPageSelected();
        }
    });
}

$(document).on('keyup','#keywords',function(){
    ajaxSearch();
})

$(document).on('click','.pagination a',function(e){
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    var route = $(this).attr('href').split('page=')[0];
    $.ajax({
        url:route,
        data: { page: page },
        type:'GET',
        dataType: 'json',
        success: function(data){
            $(".data").html(data);
            perPageSelected();
        }
    })
})

// Dropdown

var filters = [];

$(document).on('click','.dropdown-wrapper',function(){
    $this = $(this);
    $this.hasClass('open') ? $this.removeClass('open') : $this.addClass('open');

    $('html').mouseup(function (e) {
        if (!$($this).is(e.target) && $($this).has(e.target).length === 0){
            $($this).removeClass('open');
        }
    });
})

function selectItem($item){
    $wrapper = $item.parents('.dropdown-wrapper');

    var input = $wrapper.attr('data-input');
    var value = $item.attr('data-value');
    var text = $item.text();

    $item.siblings().removeClass('selected');
    $item.addClass('selected');
    $wrapper.find('.dropdown-toggle').text(text);

    filters['#'+input] = value;
}

function perPageSelected(){
    $('.dropdown-wrapper').each(function() {
        $this = $(this);
        var input = $this.attr('data-input');
        var value = $('#'+input).val()
        $item = $this.find('a[data-value=' + value + ']').first();
        selectItem($item);
    });
}

function confirmItems(){
    for (let key in filters) {
        $(key).val(filters[key])
    }
}

$(document).on('click','.dropdown-list .dropdown-list-item',function(e){
    e.preventDefault();
    $this = $(this);
    selectItem($this);

    var input = $this.parents('.dropdown-wrapper').attr('data-input');

    if(input=='district' || input=='type'){

    } else {
        confirmItems();
    }

    if($this.parents('.dropdown-wrapper').attr('data-input')=='perPage'){
        ajaxSearch();
    }
})

// Custom file input

$('.custom-file-input').on('change',function(){
    var fileName = document.getElementById("customFile").files[0].name;
    $(this).next('.custom-file-label').html(fileName);
})

// Panels

function disablePointerEvents($exceptThis){
    $html.addClass('overflow-hidden');
    $html.addClass('pointer-events-none');
    $exceptThis.addClass('pointer-events-auto');
}

function enablePointerEvents(){
    $html.removeClass('overflow-hidden');
    $html.removeClass('pointer-events-none');
}

$(document).on('click','.link',function(e){
    $this = $(this);
    if($this.hasClass('preventDefault')){
        var url = $this.attr('href');
        $panel = $('.overlay');
        $.ajax({
            url: url,
            data:{},
            type:'GET',
            dataType: 'json',
            success: function(data){
                $panel.html(data);
                $panel.addClass('show');
                disablePointerEvents($panel);
            }
        });
    }    
})

$(document).on('click','.filter-button',function(e){
    $('.filters').addClass('show');
    disablePointerEvents($('.filters'));
})

$(document).on('click','.add-button',function(e){
    $('.add').addClass('show');
    disablePointerEvents($('.add'));
})

$(document).on('click','.submit',function(e){
    $(this).parents('.overlay').find('form').submit();
})

$(document).on('click','.submit-school',function(e){
    $(this).parents('.add').find('form').submit();
})

$(document).on('click','.submit-filters',function(e){
    confirmItems();
    $('.filters').removeClass('show');
    $('.add').removeClass('show');
    enablePointerEvents();
    ajaxSearch();
})

$(document).on('click','.close-button',function(e){
    $panel = $('.overlay');
    $panel.removeClass('show');
    $('.filters').removeClass('show');
    $('.add').removeClass('show');
    setTimeout(
        function(){
            $panel.empty();
        }, 350
    );
    enablePointerEvents();
})

// Confirm year

function confirmYear($row, $form){
    $.ajax({
        url: $form.attr('action'),
        type: 'post',
        data: $form.serializeArray(),
        success: function(data){
            $($row).html(data);
        }
    });
}

$(document).on('click','.confirm-check', function(){
    $this = $(this);
    $row = $('#' + $this.attr('data-row'));
    $form = $row.find('form');
    $period = $form.find('input[name=period]');

    var year = $this.attr('data-year');
    var newPeriod;
    var clause;

    if(!$this.hasClass('editable')){
        $period = $this.parents('.periods').find('input[name=period]');
    }

    if($this.hasClass('checked')){
        newPeriod = $period.val().replace(year, '');
        clause = 'removeClass';
    } else {
        newPeriod = $period.val() + '-' + year;
        clause = 'addClass';
    }

    var years = newPeriod.split('-');

    years = years.filter(v=>v!='');
    years.sort(function(a, b){
        return parseInt(a) - parseInt(b);
    });

    newPeriod = years.join('-');

    $period.val(newPeriod);

    if($this.hasClass('editable')){
        confirmYear($row, $form);
    } else {
        $this[clause]('checked');
    }
})

