$(function(){
    $.each($('pre'), function(index, item){
        var className = $(item).attr('class');
        if (typeof(className) != 'undefined' && className.indexOf('brush:') != -1) {
            var thisLanguage = className.match(/brush:(\S+?);/);
            if (thisLanguage) {
                $(item).html('<code class="'+thisLanguage[0]+' hljs">'+$(item).html()+'</code>')
            }
        }
    })
})