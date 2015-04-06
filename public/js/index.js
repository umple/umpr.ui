/**
 * Created by kevin on 15-03-26.
 */


(function (window) {

    /**
     * Filters Module
     */
    var Filters = {};
    (function (filters) {

        // All data-* tags used for tables
        var tags = ['repository', 'diagram-type', 'last-state', 'input-type', 'name'];

        var shouldRunFilters = true;
        function _runFilters() {
            if (!shouldRunFilters) {
                return;
            }

            var $filterGroup = $('.filter-group');

            // child selector used below in $toShow variable
            var selector = '.info-import';
            function appendAttrSelector(dataTag, currValue) {
                if (_.isString(currValue) && currValue !== "null") {
                    selector += '[data-' + dataTag + '="' + currValue + '"]';
                }
            }

            var queryParams = {};
            tags.forEach(function (tag) {
                var val = $('#filter-' + tag, $filterGroup).val();
                if (tag !== 'name') {
                    appendAttrSelector(tag, val);
                } else {
                    if (val !== '') {
                        selector += '[data-name*=' + val + ']';
                    }
                }

                queryParams[tag] = (_.isNull(val) || _.isUndefined(val) || val === "null") ? "" : val;
            });

            var $umprSummary = $('.umpr-summary');
            var $allInfo = $('.info-import', $umprSummary);
            var $toShow = $(selector, $umprSummary);
            var $toHide = $allInfo.not($toShow);

            $('.info-error *[aria-expanded=true]', $umprSummary).collapse('hide');
            $toHide.hide();
            $toShow.show();

            console.log("queryParams =", queryParams);

            if (shouldRunFilters) {
                history.pushState({}, "", "?" + $.param(queryParams));
            }
        }

        // Init code:

        var $filterGroup = $('.filter-group');

        tags.forEach(function (tag) {
            $('#filter-' + tag, $filterGroup).change(function () {
                _runFilters();
            });
        });

        $('#filter-reset-btn', $filterGroup).click(function () {
            var $filterGroup = $('.filter-group');

            shouldRunFilters = false;
            tags.forEach(function (tag) {
                $('#filter-' + tag, $filterGroup).val("null");
            });
            $('#filter-name').val("");

            shouldRunFilters = true;
            _runFilters();
        });

        /**
         * Load from the parameters in the URL => run on page load and history changes
         * @private
         */
        function _loadFromParams() {
            var match,
                pl     = /\+/g,  // Regex for replacing addition symbol with a space
                search = /([^&=]+)=?([^&]*)/g,
                decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
                query  = window.location.search.substring(1);

            var urlParams = {};
            while (match = search.exec(query))
                urlParams[decode(match[1])] = decode(match[2])

            shouldRunFilters = false;
            var $filterGroup = $('.filter-group');
            _.each(urlParams, function (val, key) {
                if (val !== "") {
                    $('#filter-' + key, $filterGroup).val(val);
                } else {
                    $('#filter-' + key, $filterGroup).val(key === "name" ? "" : "null");
                }
            });
            shouldRunFilters = true;
            _runFilters();
        }

        $(window).bind('popstate', _loadFromParams());
        $(window).bind('load',     _loadFromParams());

        /*
         * Expose modules
         */

        /**
         * Run the filters against the front end UI code
         * @type {Function}
         */
        filters.runFilters = _runFilters;

    })(Filters);

    /**
     * Table module
     */
    var Table = {};
    (function (table) {

        // Init code:
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
