(function (jQuery) {
    var LanguageLinksPlugin = function (element, existing, input) {
        var elem = jQuery(element);
        var obj = this;
        var prototype = existing.first();
        var count = existing.length;
        var i = input;

        this.duplicatePrototype = function () {
            var newElement = prototype.clone();
            newElement.find('input.text').attr('value', '');

            newElement.find('label').each(function () {
                var e = jQuery(this);
                e.attr('for', e.attr('for').replace('0', count));
            });

            newElement.find('select').each(function () {
                var e = jQuery(this);
                e.attr('name', e.attr('name').replace(0, count));
                e.attr('id', e.attr('id').replace(0, count));
            });

            newElement.find('input.text').each(function () {
                var e = jQuery(this);
                e.attr('name', e.attr('name').replace('0', count));
                e.attr('id', e.attr('id').replace('0', count));
            });

            newElement.find('.remove-language').click(obj.removeLink);

            i.append(newElement);
            count++;
        };

        this.removeLink = function () {
            jQuery(this).closest('.language-link').remove();
        };

        elem.find('#language-link-add-language').click(function () {
            obj.duplicatePrototype();
        });

        elem.find('.remove-language').click(obj.removeLink);
    };

    jQuery.fn.languageLink = function (proto, i) {
        return this.each(function () {
            var element = jQuery(this);
            if (element.data('languageLink')) return;
            var prototype = element.find(proto);
            var input = element.find(i);

            var languageLinks = new LanguageLinksPlugin(this, prototype, input);
            element.data('languageLink', languageLinks);
        });
    }
})(jQuery);


jQuery(document).ready(function () {
    jQuery('#language-links-meta-box').languageLink('.language-link', '.language-link-input');
});
