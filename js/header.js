$(function(){
    var current = location.pathname;
    if(current != "/"){
        $('.tabs a').each(function(){
            var $this = $(this);
            // if the current path is like this link, make it active
            if($this.attr('href').indexOf(current) !== -1){
                $this.addClass('active');
            }
        })
    }
    else{
        $('.tabs a').each(function(){
            var $this = $(this);
            // if the current path is like this link, make it active
            if($this.attr('href').indexOf("index.php") !== -1){
                $this.addClass('active');
            }
        })
    }
})