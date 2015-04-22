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


})(Table);
