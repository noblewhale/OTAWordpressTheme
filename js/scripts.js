(function ($, root, undefined) 
{   
    var scrollThread;
    
    $(function () 
    {
        $("cube").hover(
            function () {
                $(this).removeClass("unhover");
            },
            function () {
                $(this).addClass("unhover");
            }
        );

        $('.track-info').hover(
            scrollText, 
            unScrollText
        );
    });
    
    function unScrollText()
    {
        clearInterval(scrollThread);
        $(this).children('h2').css('left', 0);
    }
    
    function scrollText()
    {
        var $el = $(this).children('h2');
        var $parent = $(this);
        
        if ($parent.width() >= $el.width()) return;
        
        var $dir = -1;
        var isDelaying = false;
        var delayCount = 0;
        var delayDuration = 80;
        
        scrollThread = setInterval(
            function() { 
                if(isDelaying) {
                    delayCount++;
                    if(delayCount > delayDuration) {
                        delayCount = 0;
                        isDelaying = false;
                    }
                }
                else {
                    var left = $el.position().left;
                    if (left < $parent.width() - $el.width() || left > 0) {
                        isDelaying = true;
                        $dir *= -1;
                    }
                    left += $dir;
                    $el.css('left', left); 
                }
            }, 
            15
        );
    }
	
})(jQuery, this);

Number.prototype.clamp = function(min, max) {
    return Math.min(Math.max(this, min), max);
};

