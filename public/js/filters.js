/**
 * Filters Module
 */
window.Filters = {};

(function (filters) {

    // All data-* tags used for tables
    var tags = ['repository', 'diagram-type', 'last-state', 'input-type', 'name'];

    // load the current params
    var lastParams = util.parseUrlParams();

    var obj = {};
    (function (obj) {
        var saveHistoryState = true;

        obj._runFilters = function () {
            if (!saveHistoryState) {
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

            var queryParams = util.parseUrlParams();
            tags.forEach(function (tag) {
                var val = $('#filter-' + tag, $filterGroup).val();
                if (tag !== 'name') {
                    appendAttrSelector(tag, val);
                } else if (val !== '') {
                    selector += '[data-name*=' + val + ']';
                }

                if (_.isNull(val) || _.isUndefined(val) || 
                    (_.isString(val) && (val === "" || val === "null"))) {
                    delete queryParams.f[tag]; 
                } else {
                    queryParams.f[tag] = val;
                }
            });

            var $umprSummary = $('.umpr-summary');
            var $allInfo = $('.info-import', $umprSummary);
            var $toShow = $(selector, $umprSummary);

            $toShow.removeClass('hidden');
            $allInfo.not($toShow).addClass('hidden');

            $('.info-error *[aria-expanded=true]', $umprSummary).collapse('hide');

            if (!_.isEqual(queryParams, lastParams)) {
                var sparams = $.param(queryParams);

                lastParams = _.clone(queryParams, true);
                history.pushState(queryParams, null, "?" + sparams);

                $(App).trigger('changed.umpr.filters');
            }
        };

        obj._safeChangeFilters = function (run) {
            saveHistoryState = false;
            run.apply(null, _.rest(arguments));
            saveHistoryState = true;
        }

    })(obj);

    var _safeChangeFilters = obj._safeChangeFilters;
    var _runFilters = obj._runFilters;

    /**
     * Load from the parameters in the URL => run on page load and history changes
     * @private
     */
    function _loadFromParams(ev, urlParams) {
        _safeChangeFilters(function () {
            var $filterGroup = $('.filter-group');
            _.each(urlParams.f, function (val, key) {

                var $e = $('#filter-' + key, $filterGroup);
                if ($e.length === 0) {
                    return ;
                }

                if (val !== "") {
                    $e.val(val);
                } else {
                    $e.val(key === "name" ? "" : "null");
                }
            });
        });
        _runFilters();
    }

    $(App).on('load.umpr', _loadFromParams);

    $(document).ready(function () {
        lastParams = util.parseUrlParams();
        $(App).trigger('load.umpr', [ lastParams ]);
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
