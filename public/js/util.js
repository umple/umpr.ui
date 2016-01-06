
window.util = {};
(function (root) {

    var defaultUrlParams = {
        f: { },
        pgn: { }
    };

    root.parseUrlParams = function () {
        var query  = window.location.search.substring(1);

        return _.clone(_.extend(defaultUrlParams, $.deparam(query)), true);
    };

})(window.util);

