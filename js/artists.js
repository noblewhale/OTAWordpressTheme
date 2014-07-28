(function ($, root, undefined) 
{
    var $sections, $nextSection;
    var nextSectionIndex, nextSectionTop;
    var originalTransformsWebkit = [];
    var originalTransformsMS = [];
    var originalTransformsMOZ = [];
    var perspectiveOrigin = [0, 0];
    
    $(function () 
    {
        $sections = $('.letter-section');
        nextSectionIndex = 0;
        $nextSection = $($sections[nextSectionIndex]);
        nextSectionTop = $nextSection.offset().top;

        // Auto scroll to position
        $('a[href*=#]:not([href=#])').click(function() 
        {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) 
            {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) 
                {
                    $('html,body').animate({
                        scrollTop: target.offset().top + 25
                    }, 50*Math.pow(Math.abs(target.offset().top - Math.max($('body').scrollTop(), $('html').scrollTop())), .5), 'easeInOutQuad');
                    return false;
                }
            }
        });
        
        $.each($(".artists .letters"), function(key, value) {
            $(value).data("top", $(value).offset().top); // set original position on load
        });
        
        if(!has3d())
        {
            applyParentsPerspectiveToChildren('.letters');
            applyParentsPerspectiveToChildren('.letter-cube');
        }
        
        $.each($(".letter-cube div"), function(key, value) {
            originalTransformsWebkit[key] = $(value).css('-webkit-transform');
            if(originalTransformsWebkit[key] === "none") originalTransformsWebkit[key] = "";
            originalTransformsMS[key] = $(value).css('-ms-transform');
            if(originalTransformsMS[key] === "none") originalTransformsMS[key] = "";
            originalTransformsMOZ[key] = $(value).css('-moz-transform');
            if(originalTransformsMOZ[key] === "none") originalTransformsMOZ[key] = "";
        });
        setInterval(fixDiv, 10);
    });
    
    function applyParentsPerspectiveToChildren(parentSelector)
    {
        $parent = $(parentSelector);
        var perspectiveOrigin = $parent.css('perspective-origin').split(' ');
        perspectiveOrigin[0] = parseInt(perspectiveOrigin[0]);
        perspectiveOrigin[1] = parseInt(perspectiveOrigin[1]);
        $.each($parent.children(), function(key, value) {
            var relativeOrigin = perspectiveOrigin;
            relativeOrigin[0] -= $(value).position().left;
            relativeOrigin[1] -= $(value).position().top;
            console.log(relativeOrigin);
            relativeOrigin = relativeOrigin[0] + 'px ' + relativeOrigin[1] + 'px';
            
            $(value).css('perspective-origin', relativeOrigin);
            $(value).css('perspective', $parent.css('perspective'));
        });
    }
    
    function has3d() 
    {
        var detect = document.createElement("div");
        detect.style.transformStyle = "preserve-3d";
        return detect.style.transformStyle.length > 0;
    }
    
    var prevDegree = 0;
    function fixDiv() 
    {
        var scrollPos = Math.max($('body').scrollTop(), $('html').scrollTop());
        // Magical rotation incantation
        if(scrollPos >= nextSectionTop + 25)
        {
            nextSectionIndex++;
            $nextSection = $($sections[nextSectionIndex]);
            nextSectionTop = $nextSection.offset().top;
        }
        else if (nextSectionIndex !== 0 && scrollPos <= $($sections[nextSectionIndex-1]).offset().top + 25)
        {
            nextSectionIndex--;
            $nextSection = $($sections[nextSectionIndex]);
            nextSectionTop = $nextSection.offset().top;
        }
        
        if(nextSectionIndex !== 0)
        {
            var s = (nextSectionIndex % 4) + 1;
            $('.letter-cube .side'+s).text($($sections[nextSectionIndex-1]).children('a').attr('id'));
        }
        else
        {
            var s = (nextSectionIndex % 4) + 1;
            $('.letter-cube .side'+s).text(' ');
        }
        var s = ((nextSectionIndex+1) % 4) + 1;
        $('.letter-cube .side'+s).text($($sections[nextSectionIndex]).children('a').attr('id'));
        
        var degree = Math.floor(-90*nextSectionIndex - (90 - (nextSectionTop + 25 - scrollPos)).clamp(0, 90));
        var dif = degree - prevDegree;
        var maxDif = 6;
        if (Math.abs(dif) > maxDif)
        {
            var sign = dif ? dif < 0 ? -1 : 1 : 0;
            degree = prevDegree+sign*maxDif;
        }
        
        if (Math.abs(degree)%90 <= 1 || Math.abs(degree)%90 >= 89)
        {
            $('.letter-cube div').addClass('boxShadowed');
        }
        else
        {
            $('.letter-cube div').removeClass('boxShadowed');
        }
        prevDegree = degree;
        $.each($('.letter-cube div'), function(key, value) {
            $(value).css('-webkit-transform', 'rotateX(' + degree + 'deg)' + ' ' + originalTransformsWebkit[key]);
            $(value).css('-ms-transform', 'rotateX(' + degree + 'deg)' + ' ' + originalTransformsMS[key]);
            $(value).css('-moz-transform', 'rotateX(' + degree + 'deg)' + ' ' + originalTransformsMOZ[key]);
        });
//        $('.letter-cube div').css({ MozTransform: 'rotateX(' + degree + 'deg)'});
//        $('.letter-cube div').css({ Transform: 'rotateX(' + degree + 'deg)'});
        
        if (scrollPos > $(".artists .letters").data("top")) 
        { 
            $(".artists .letters").css({'position': 'fixed', 'top': '0px'}); 
        }
        else 
        {
            $(".artists .letters").css({'position': 'relative', 'top': 'auto'});
        }
    }
	
})(jQuery, this);