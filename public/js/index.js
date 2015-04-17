/**
 * Created by kevin on 15-03-26.
 */


(function (window) {

    _.templateSettings = {
        interpolate: /\{\{(.+?)\}\}/g
    };

    /**
     * Filters Module
     */
    var Filters = {};
    (function (filters) {

        // All data-* tags used for tables
        var tags = ['repository', 'diagram-type', 'last-state', 'input-type', 'name'];

        var obj = {};
        var lastParams = {};
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

        // Init code:

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

        $(window).bind('popstate', _loadFromParams());
        $(window).bind('load', function () {
            lastParams = _parseUrlParams();
            _loadFromParams();
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

        table.titleTemplate = _.template('<a href="{{ remote }}" target="_blank">{{ name }}</a>' +
                                         '<a class="pull-right" title="{{ license.alt }}" href="{{ license.url }}">{{ license.link }}</a>');

        table.LICENSES = {
            CC_ATTRIBUTION_4: {
                alt: 'Creative Commons License',
                link: '<img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png">',
                url: "http://creativecommons.org/licenses/by/4.0/"
            },

            EPL: {
                alt: 'Eclipse Public License',
                link: 'EPL',
                url: "https://www.eclipse.org/legal/epl-v10.html"
            },

            W3C: {
                alt: 'World Wide Web Consortium',
                link: 'W3C',
                url: "http://www.w3.org/Consortium/Legal/2015/doc-license"
            },

            MIT: {
                alt: 'Michigan Institute of Technology Open Source License',
                link: 'MIT',
                url: "http://opensource.org/licenses/MIT"
            },

            /**
             * Signifies that the License is unknown.
             */
            UNKNOWN: {
                alt: 'Unknown',
                link: 'Unknown',
                url: ''
            }
        };

        var URL_REGEX = /https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+~#?&//=]*)\b(?:\.[-a-zA-Z0-9@:%_\+~#?&//=]+)*/;

        $(document).ready(function () {
            // requires model data..

            $('.umpr-summary .info-import').each(function () {
                var $this = $(this);
                var $a = $('.col-repo a', $this);

                var meta =  Meta.data[$this.data('repository')];

                if (_.isUndefined(meta)) {
                    throw new Error("Unknown repository: " + $this.data('repository'));
                }

                $a.popover({
                    placement: 'right',
                    title: '&nbsp',
                    content: meta.description.replace(URL_REGEX, '<a href="$&" target="_blank">$&</a>'),
                    html: true
                });

                $a.on('shown.bs.popover', function (ev) {
                    var $this = $(this);
                    if ($('.license-link', $this).length > 0) {
                        $this.off(ev);
                        return ;
                    }

                    var $title = $('.popover .popover-title', $this.parent());
                    $title.empty();
                    $title.append(table.titleTemplate({
                        name: meta.name,
                        remote: meta.remote,
                        license: table.LICENSES[meta.license.toUpperCase()]
                    }));
                });
            });
        });


    })(Table);

    var Application = {};
    (function (app) {
        $('.changer a').click(function () {
            $('')
        });
    })(Application);

    window.Filters = Filters;

})(window);
