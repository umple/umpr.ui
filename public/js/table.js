/**
 * Table module
 */
window.Table = {};

(function (table) {

    _.templateSettings = {
        interpolate: /\{\{(.+?)\}\}/g
    };

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

    /**
     * Get the currently showing rows.
     * @type {Function}
     * @return All showing rows after filtering.
     */
    table.getShowingRows = function () {
        var $umprSummary = $('.umpr-summary');
        return $('.info-import:not(.hidden)', $umprSummary);
    };

    /**
     * Pagination
     */
    table.Pagination = {
        chunk: 25,

        /**
         * Update the pagination controls to show the numbers currently.
         * @param total     Total rows showing. 
         * @param current   The current chunk to show.
         */
        updateControls: function (current, total) {
            var count = Table.Pagination.getChunkCount(total),
                off   = Math.min(2, Math.floor(count / 2)),
                $pgn  = $('.umpr-pgn');

            // get all of the items, partition them then zip them into pairs
            var $items = _.zip.apply(_, _.partition($('.pgn.item', $pgn), function (_, idx) {
                if (idx < 5) {
                    return 0;
                } else {
                    return 1;
                }
            }));

            $('li', $pgn).removeClass('disabled');

            var start = Math.max(1, Math.min(current - off, count - 4)),
                end   = Math.min(Math.max(current + off, 5), count) + 1;
            _.zip($items, _.range(start, end)).forEach(function (pairs) {
                var $item = $(pairs[0]),
                    $a    = $('a', $item),
                    idx   = pairs[1];

                if (_.isUndefined(idx)) {
                    $item.addClass('hidden');
                    return;
                }

                $item.removeClass('hidden');
                $item.data('pageIdx', idx);
                $a.text(idx);

                if (idx === current) {
                    $item.addClass('active');
                    $a.append('<span class="sr-only">(current)</span>');
                } else {
                    $item.removeClass('active');
                }
            });

            if (current === 1) {
                $('.pgn.first, .pgn.prev', $pgn).addClass('disabled');
            }

            if (current === count) {
                $('.pgn.last, .pgn.next', $pgn).addClass('disabled');
            }
        },

        /**
         * Returns the active index number. 
         * @return active index or -1 if none are selected
         */
        getActiveIdx: function () {
          var $actives = $('.umpr-pgn .pgn.item.active');
          if ($actives.length > 0) {
            return $actives.data('pageIdx'); 
          } else {
              return -1;
          }
        },

        /**
         * @param {Number} [total] Computes the current total showing elements if unspecified.
         * @return {Number} Computes the chunk count.
         */
        getChunkCount: function (total) {
            if (_.isUndefined(total)) {
                total = Table.getShowingRows().length;
            }

            return Math.ceil( total / Table.Pagination.chunk);
        },

        /**
         * Set the pagination page to an index.
         * @param {Number}  pageIdx       Page index to set to
         * @param {Boolean} [force=false] If `true`, the function will do all control updates.
         */ 
        setToPage: function (pageIdx, force) {
            if (_.isUndefined(force)) {
                force = false;
            }

            pageIdx = Math.min(pageIdx, Table.Pagination.getChunkCount());

            var current = Table.Pagination.getActiveIdx();
            $('.umpr-pgn .pgn.direct-enter input').collapse('hide');
            
            if (!force && pageIdx === current) {
                // nothing to change
                return ;
            }

            var $showing = Table.getShowingRows();
            Table.Pagination.updateControls(pageIdx, $showing.length);
            
            var $newToShow = $showing.slice((pageIdx-1)*Table.Pagination.chunk, pageIdx*Table.Pagination.chunk);
            $showing.not($newToShow).hide();
            $newToShow.show();
        },

        paginate: function () {
            Table.Pagination.updateControls(1);
            Table.Pagination.setToPage(1, true);
        },

        onSelectNumber: function () {
            var $this = $(this);
            var idx = $this.data('page-idx');

            Table.Pagination.setToPage(idx);  
        },

        /**
         * Initializes navigation arrows
         */
        initNavArrows: function () {
            $('.umpr-pgn .pgn.item').click(Table.Pagination.onSelectNumber);

            $('.umpr-pgn .pgn.first a').click(function () {
                if (!$(this).parent().hasClass('disabled')) {
                    Table.Pagination.setToPage(1);
                }
            });

            $('.umpr-pgn .pgn.prev a').click(function () {
                if (!$(this).parent().hasClass('disabled')) {
                    Table.Pagination.setToPage(Table.Pagination.getActiveIdx() - 1);
                }
            });

            $('.umpr-pgn .pgn.next a').click(function () {
                if (!$(this).parent().hasClass('disabled')) {
                    Table.Pagination.setToPage(Table.Pagination.getActiveIdx() + 1);
                }
            });

            $('.umpr-pgn .pgn.last a').click(function () {
                if (!$(this).parent().hasClass('disabled')) {
                    Table.Pagination.setToPage(Math.ceil(Table.getShowingRows().length / Table.Pagination.chunk));
                }
            });

            // collapse direct enter
            var $direct_enter_input = $('.umpr-pgn .pgn.direct-enter input');
            $direct_enter_input.on('show.bs.collapse', function () {
                var $parent = $(this).parents('.umpr-pgn .pgn.set');
                $('.pgn.direct-btn a', $parent).removeClass('first');

                var idx = Table.Pagination.getActiveIdx();
                $(this).val(idx);
            });

            $direct_enter_input.on('shown.bs.collapse', function () {
                var $parent = $(this).parents('.umpr-pgn .pgn.set');
                var $arrow = $('.pgn.direct-btn .expand-arrow', $parent);
                $arrow.removeClass('fa-angle-left');
                $arrow.addClass('fa-angle-right');
            });

            $direct_enter_input.on('hidden.bs.collapse', function () {
                var $parent = $(this).parents('.umpr-pgn .pgn.set');
                var $btn = $('.pgn.direct-btn', $parent);
                $('a', $btn).addClass('first');

                var $arrow = $('.pgn.direct-btn .expand-arrow', $parent);
                $arrow.addClass('fa-angle-left');
                $arrow.removeClass('fa-angle-right');
            });

            var R_NUMS = /[^\d]/g;
            $direct_enter_input.keyup(_.debounce(function () {
                var $this = $(this);
                var oval = $this.val().replace(R_NUMS, '');

                if (oval.length > 0) {
                    var val = Math.min(Math.max(1, Number(oval)), Table.Pagination.getChunkCount());
                    $this.val(val); 
                }
            }, 120));

            $direct_enter_input.change(function () {
                Table.Pagination.setToPage($(this).val());
            });

            $('.umpr-pgn .pgn.direct-btn a').each(function () {
                var $this = $(this);
                var $parent = $this.parents('.umpr-pgn .pgn.set');
                var $input = $('.pgn.direct-enter input', $parent);

                $input.attr('id', _.uniqueId('pgn-direct-input-'));
                $this.attr('id', _.uniqueId('pgn-direct-btn-'));

                $this.attr('data-target', '#' + $input.attr('id'));
                $this.attr('aria-controls', $input.attr('id'));
                $input.attr('data-parent', '#' + $this.attr('id'));  


            });
        },

        init: function () {
            $(App).on('changed.umpr.filters', Table.Pagination.paginate);

            Table.Pagination.initNavArrows();

            $chunk_select = $('.umpr-pgn .pgn-chunk .chunk-select');
            
            $chunk_select.click(function () {
                var $this = $(this);
                var newChunk = $this.data('chunk');

                if (newChunk !== Table.Pagination.chunk) {
                    // only set it if required
                    Table.Pagination.chunk = newChunk;
                    Table.Pagination.setToPage(1, true);

                    $('.umpr-pgn .pgn-chunk .chunk-select').removeClass('active');
                    $this.addClass('active');

                    var $input = $('.umpr-pgn .pgn .direct-enter input');
                    $input.attr('max', Table.Pagination.getChunkCount());
                }
            });

            // make sure the right chunk is active
            $chunk_select.removeClass('active');
            $chunk_select.each(function () {
               var $this = $(this);
               if ($this.data('chunk') === Table.Pagination.chunk) {
                   $this.addClass('active');
               } 
            });

            // trigger pagination
            $('.umpr-pgn .pgn .direct-enter input').attr('max', Table.Pagination.getChunkCount());
            Table.Pagination.setToPage(1, true); 
        }
    };

    $(document).ready(function () {
        table.Pagination.init();
    });


})(Table);
