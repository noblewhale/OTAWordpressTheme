(function ($, root, undefined) 
{
	
	$(function () 
        {
                adjust();
                $(window).resize(adjust);
	});
        
        function adjust() 
        {
            $(".artist-photo").width($(".artist").width() - $(".external-links-outer").outerWidth(true));
            $(".about article").width($(".about section").width() - $(".about .external-links-outer").outerWidth(true) - 5);
        }
	
})(jQuery, this);
