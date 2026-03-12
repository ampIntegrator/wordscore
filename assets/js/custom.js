jQuery(function ($) {

    // Ajuster le padding-top du body selon la hauteur du header fixed
    // Si le premier bloc est un hero fullscreen, on met le padding sur le hero au lieu du body
    function adjustBodyPadding() {
        var headerHeight = $('#masthead').outerHeight();
        var $firstBloc = $('#flexilder > .bloc-section:first-child');
        var isFirstBlocFullscreenHero = $firstBloc.hasClass('hero-fullscreen');

        if (isFirstBlocFullscreenHero) {
            // Hero fullscreen en premier : padding sur le hero, pas sur le body
            $('body').css('padding-top', '0');
            $firstBloc.css('padding-top', headerHeight + 'px');
        } else {
            // Comportement normal : padding sur le body
            $('body').css('padding-top', headerHeight + 'px');
            // Retirer le padding-top des hero fullscreen qui ne sont pas en premier
            $('.hero-fullscreen').css('padding-top', '');
        }
    }

    // Au chargement
    adjustBodyPadding();

    // Au resize
    $(window).on('resize', function() {
        adjustBodyPadding();
    });

}); // jQuery End
