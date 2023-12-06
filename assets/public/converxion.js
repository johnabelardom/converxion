jQuery(document).ready(function($) {
    var pageId = window.converxionParams.postID;

    $.post('/wp-admin/admin-ajax.php', {
        action: 'converxion_record_visit',
        page_id: pageId,
    }, function(response) {
        console.log('PageVisit Recorded')
    });

    $('body main a').on('click', function(event) {
        event.preventDefault();

        var url = $(this).attr('href');
        var conversionCookie = getCookie('converxion_unique_' + pageId);
        var isUnique = conversionCookie == null;
        // console.log(conversionCookie, isUnique, ! isUnique);

        $.post('/wp-admin/admin-ajax.php', {
            action: 'converxion_record_conversion',
            page_id: pageId,
            is_unique: isUnique
        }, function(response) {
            if (isUnique) {
                setCookie('converxion_unique_' + pageId, '1'.toString(), 30); // Set cookie for 30 days
            }
            window.location.href = url;
        });

    });

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

});




