$(function(){
    $.each($("pre[class~='brush:']"), function(index, item){
        var thisLanguage = $(item).attr('class').match(/brush:(\S+?);/);
        if (thisLanguage) {
            $(item).html('<code class="'+thisLanguage[0]+' hljs">'+$(item).html()+'</code>')
        }
    })
})