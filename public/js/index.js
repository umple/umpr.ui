/**
 * Created by kevin on 15-03-26.
 */


(function (window) {

    var Filters = {};
    (function (filters) {

        var $filterGroup = $('.filter-group');


        var tags = ['repository', 'diagram-type', 'last-state', 'file-type', 'name'];

        function _runFilters() {
            var $filterGroup = $('.filter-group');

            // child selector used below in $toShow variable
            var selector = '.info-import';
            function appendAttrSelector(dataTag, currValue) {
                if (_.isString(currValue) && currValue !== "null") {
                    selector += '[data-' + dataTag + '="' + currValue + '"]';
                }
            }

            tags.forEach(function (tag) {
                if (tag !== 'name') {
                    appendAttrSelector(tag, $('#filter-' + tag, $filterGroup).val());
                } else {
                    var name = $('#filter-name', $filterGroup).val();
                    if (name !== '') {
                        selector += '[data-name*=' + name + ']';
                    }
                }
            });

            var $umprSummary = $('.umpr-summary');
            var $allInfo = $('.info-import', $umprSummary);
            var $toShow = $(selector, $umprSummary);
            var $toHide = $allInfo.not($toShow);

            $('.info-error *[aria-expanded=true]', $umprSummary).collapse('hide');
            $toHide.hide();
            $toShow.show();

            console.log('selector = ' + selector);
            console.log('results =', $(selector));
        }

        tags.forEach(function (tag) {
            $('#filter-' + tag, $filterGroup).change(function () {
                _runFilters();
            });
        });

    })(Filters);

    var Table = {};
    (function (table) {

        var $infoErrors = $('.umpr-summary .info-error');

        $infoErrors.on('hidden.bs.collapse', function () {
            $(this).hide();
        });

        $infoErrors.on('show.bs.collapse', function () {
            $(this).show();
        });

        $infoErrors.hide();

    })(Table);

    window.Filters = Filters;

})(window);
