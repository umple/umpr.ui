/**
 * Filters Module
 */
window.Filters = {};

(function (filters) {

    // All data-* tags used for tables
    var tags = ['repository', 'diagram-type', 'last-state', 'input-type', 'name'];

    function _parseUrlParams() {
        var match,
            pl     = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
            query  = window.location.search.substring(1);

        var urlParams = {};
        while (match = search.exec(query))
            urlParams[decode(match[1])] = decode(match[2]);

        return urlParams;
    }

    // load the current params
    var lastParams = _parseUrlParams();


    var obj = {};
    (function (obj) {
        var shouldRunFilters = true;

        obj._runFilters = function () {
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

            if (shouldRunFilters) {
                if (!_.isEqual(queryParams, lastParams)) {
                    console.log("FUUUUUUU", queryParams);
                    var sparams = $.param(queryParams);
                    history.pushState({}, "", "?" + sparams);
                    lastParams = queryParams;
                }
            }
        };

        obj._safeChangeFilters = function (run) {
            shouldRunFilters = false;
            run.apply(null, _.rest(arguments));
            shouldRunFilters = true;
        }

    })(obj);

    var _safeChangeFilters = obj._safeChangeFilters;
    var _runFilters = obj._runFilters;

    /**
     * Load from the parameters in the URL => run on page load and history changes
     * @private
     */
    function _loadFromParams() {
        var urlParams = _parseUrlParams();

        _safeChangeFilters(function () {
            var $filterGroup = $('.filter-group');
            _.each(urlParams, function (val, key) {
                if (val !== "") {
                    $('#filter-' + key, $filterGroup).val(val);
                } else {
                    $('#filter-' + key, $filterGroup).val(key === "name" ? "" : "null");
                }
            });
        });
        _runFilters();
    }

    // Init code:

    $(window).bind('popstate', _loadFromParams());
    $(window).bind('load', function () {
        lastParams = _parseUrlParams();
        _loadFromParams();
    });

    var $filterGroup = $('.filter-group');

    tags.forEach(function (tag) {
        $('#filter-' + tag, $filterGroup).change(function () {
            _runFilters();
        });
    });

    $('#filter-reset-btn', $filterGroup).click(function () {
        var $filterGroup = $('.filter-group');

        _safeChangeFilters(function () {
            tags.forEach(function (tag) {
                $('#filter-' + tag, $filterGroup).val("null");
            });
            $('#filter-name').val("");
        });

        _runFilters();
    });

    /*
     * Expose modules
     */

    /**
     * Run the filters against the front end UI code
     * @type {Function}
     */
    filters.runFilters = _runFilters;

    $('.filter-group[data-spy="affix"]').affix({
        offset: {
            top: 60,
            bottom: function () {
                return (this.bottom = $('.footer').outerHeight(true) + 60)
            }
        }
    })

})(Filters);