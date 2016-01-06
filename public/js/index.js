/**
 * Created by kevin on 15-03-26.
 */

window.App = {};

(function (root) {

    // Init code:

    $(window).bind('popstate', function () {
        $(root).trigger('load.umpr', [ util.parseUrlParams() ]);
    });

})(window.App);
