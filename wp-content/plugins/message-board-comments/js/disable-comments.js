jQuery(function () {
    function disableComments($elem) {
        var $comments = jQuery('#comment_status');
        if ($elem.val().length > 0) {
            $comments.prop('checked', false);
            $comments.prop('disabled', true);
        } else {
            $comments.prop('disabled', false);
        }
    }

    var $el = jQuery('#comment-url');

    disableComments($el);

    $el.change(function () {
        disableComments(jQuery(this));
    });
});
