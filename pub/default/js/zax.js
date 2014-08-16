/**
 * Some functions for Zax framework and CMS. I'm JS noob, so I recommend not digging into this much :-)
 *
 * @copyright Copyright (c) 2014 Zax
 */

var mouseMove = function(x, y) {
    $('#ajax-loading-cursor').css({top:y+20+'px', left:x+20+'px'});
};

var initTooltips = function() {
    $('[title]').data('placement', 'auto top');
    $('[title]').data('trigger', 'hover');
    $('[title]').data('container', 'body');
    $('[title]').tooltip();
};

var destroyTooltips = function() {
    /** Prevent bugs with tooltips not disappearing after parent element gets destroyed */
    $('.tooltip.fade').remove();
};

var initFilestyle = function() {
	$(':file').each(function() {
        /** Filestyle is bugged, this "solution" seems to work */
		$(this).filestyle({buttonBefore: true, buttonText: $(this).data('buttontext')});
	});
};

var refresh = function() {
    initTooltips();
    initFilestyle();
    $('.if-js-hide').hide();
    $('.if-not-js-hide').show();
};


var payloads = [];
var initNetteAjax = function() {

    /** Tooltips need to be destroyed with every AJAX call to prevent bugs */
    $.nette.ext('tooltipDestroyer',{
        success: function(payload) {
            destroyTooltips();
        }
    });

    /** Anchor support */
    $.nette.ext('gotoAnchor', {
        success: function(payload) {
            if(payload.anchor) {
                var destTag = $("a[name='"+ payload.anchor +"']");
                if(destTag && destTag.offset()) {
                    $('html,body').animate({scrollTop: destTag.offset().top-100},'slow');
                }
            }
        }
    });

    /** pushState */
    $.nette.ext('pushState', {
        success: function(payload) {
            if(payload.setUrl) {
                var currentState = {href: payload.setUrl};
                window.history.pushState(currentState, null, payload.setUrl);
            }
        }
    });


    /** Autofocus support */
    $.nette.ext('formsAutofocus', {
        success: function(payload) {
            if(payload.focus) {
                $('#' + payload.focus).focus();
            }
        }
    });

    /** Loading cursor */
    $.nette.ext('loading', {
        start: function(jqXHR, settings) {
            $('#ajax-loading-cursor').show();
        },
        complete: function() {
            $('#ajax-loading-cursor').hide();
        }
    });

    /** Log payload in console */
    $.nette.ext('consoleLog', {
        success: function(payload) {
            console.log('Incoming payload!');
            console.log(payload);
        },
        error: function(jqXHR, status, error) {
            console.log('Ajax failed!');
            console.log(error);
            console.log(jqXHR);
        }
    });

    /** Fade effect */
    var originalApplySnippet = $.nette.ext('snippets').applySnippet;
    $.nette.ext('snippets').applySnippet = function($el, html) {
        if($el.is('[data-zax-fade]')) {
            $el.fadeTo(400, 0.01, function() {
                $el.html(html).fadeTo(400, 1);
            });
        } else if($el.is('[data-zax-slide]')) {
            $el.slideUp(400, function() {
                $el.html(html).slideDown(400);
            });
        } else {
            originalApplySnippet($el, html);
        }
        refresh();
    };

    /** Store payloads for popState */
    $.nette.ext('snippets').updateSnippets = function(snippets, back) {
        var elements = [];
        var tmpPayload = {snippets: {}};
        for (var i in snippets) {
            var el = this.getElement(i);
            if(el.get(0)) {
                tmpPayload.snippets[i] = el.html();
                elements.push(el.get(0));
            }
            $.nette.ext('snippets').updateSnippet(el, snippets[i], back);
        }
        if(!back) {
            payloads.push(tmpPayload);
        }
        $(elements).promise().done(function() {
            $.nette.ext('snippets').completeQueue.fire();
        });
    };

    /** Exactly what it says - it's intended to "refresh stuff" ;-) */
    $.nette.ext('refreshStuff', {
        success: function(payload) {
            refresh();
        }
    });

    $.nette.init();
}

$(document).ready(function() {

    initNetteAjax();

    /** Destroy tooltips when clicking "close" button on alert (flash message) to prevent bugs */
    $(document).on('close.bs.alert', '.alert', function() {
        destroyTooltips();
    });

    $(document).on('mousemove', function(e) {
        mouseMove(e.pageX, e.pageY);
    });

    /** Restore snippets on back button */
    $(window).on('popstate', function(e) {
        console.log(payloads);
        var payload = payloads.pop();
        console.log(payload);
        if(payload) {
            $.nette.ext('snippets').updateSnippets(payload.snippets, true);
        }
    });

    refresh();
});

Nette.addError = function(elem, message) {
    if (elem.focus) {
        elem.focus();
    }
    if (message) {
        $(elem).closest('.form-group').addClass('has-error');
        $(elem).parent().append('<span class="help-block">' + message + '</span>');
        $(elem).on('change, keydown', function() {
            $(elem).closest('.form-group').removeClass('has-error');
            $(elem).parent().find('.help-block').remove();
        });
        //alert('Zax: ' + message);
    }
};