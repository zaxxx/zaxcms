/**
 * Portions of this code are derived from Texyla (credits to Jan Marek - github.com/janmarek, MIT licence)
 */

function Texy(textarea) {
    this.textarea = textarea;
}

Texy.prototype = jQuery.extend({}, Selection.prototype, {

    bold: function() {
        this.trimSelect();
        var text = this.text();

        if(text.match(/^\*\*.*\*\*$/)) {
            this.replace(text.substring(2, text.length - 2));
        } else {
            this.tag('**', '**');
        }
    },

    italic: function() {
        this.trimSelect();
        var text = this.text();

        if(text.match(/^\*\*\*.*\*\*\*$/) || text.match(/^\*[^*]+\*$/)) {
            this.replace(text.substring(1, text.length - 1));
        } else {
            this.tag('*', '*');
        }
    },

    align: function(type) {
        this.update();
        var lf = this.lf();
        var start = '.' + type + lf;
        var newPar = lf + lf;
        var found = this.textarea.value.substring(0, this.start).lastIndexOf(newPar);
        var beforePar = found + newPar.length;
        if(found === -1) {
            this.textarea.value = start + this.textarea.value;
        } else {
            this.textarea.value = this.textarea.value.substring(0, beforePar) + start + this.textarea.value.substring(beforePar);
        }
        this.select(this.start + start.length, this.length());
    },

    alignLeft: function() {
        this.align('<');
    },
    alignRight: function() {
        this.align('>');
    },
    alignCenter: function() {
        this.align('<>');
    },
    alignBlock: function() {
        this.align('=');
    },

    h1: function() {
        this.tag('#### ', '\n');
    },
    h2: function() {
        this.tag('### ', '\n');
    },
    h3: function() {
        this.tag('## ', '\n');
    },

    list: function(type) {
        this.selectBlock();
        var lf = this.lf();
        var lines = this.text().split(lf);
        var lineCt = this.isCursor() ? 3 : lines.length;
        var replacement = '';

        for (var i = 1; i <= lineCt; i++) {
            var bullet = {
                ul: '-',
                ol: i + ')',
                bq: '>',
                indent: ''
            };

            replacement += bullet[type] + ' ' + (!this.isCursor() ? lines[i - 1] : '') + (i != lineCt ? lf : '');

            // seznam okolo kurzoru - pozice kurzoru
            if (this.isCursor() && i === 1)  var curPos = replacement.length - 1;
        }

        if (this.isCursor()) {
            this.tag(replacement.substring(0, curPos), replacement.substring(curPos));
        } else {
            this.replace(replacement);
        }
    },

    ul: function() {
        this.list('ul');
    },
    ol: function() {
        this.list('ol');
    },
    quote: function() {
        this.list('bq');
    },

    columns: function(cols) {
        var start = '/--div .[row]';
        var end = '\\--';
        var size = 12/cols;
        var colStart = '/--div .[col-sm-' + size + ']';
        var colEnd = '\\--';
        if(!String.prototype.repeat) {
            String.prototype.repeat = function(num) {
                return new Array(num + 1).join(this);
            }
        }
        this.tag('\n' + start + '\n' + colStart + '\n\n', '\n\n' + colEnd + '\n' + (colStart + '\n\n\n\n' + colEnd + '\n').repeat(cols-1) + end);
    },

    link: function(text, destination, newTab) {
        this.trimSelect();
        this.tag('"', text + (newTab ? ' .{target:blank}' : '') + '":' + destination);
    },

    img: function(url, align, htmlClass) {
        this.replace('[* ' + url + ' ' + (htmlClass.length > 0 ? '.[' + htmlClass + '] ' : '') + align + ']');
    },

    youtube: function(url, size) {
        this.trimSelect();
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
        var match = url.match(regExp);
        var videoId = url;
        if (match && match[7].length === 11){
            videoId = match[7];
        }
        var tag = '[* youtube:' + videoId + ' *]';
        if(size != 0) {
            tag = '\n/--div .[embed-responsive embed-responsive-' + (size == 1 ? '4by3' : '16by9') + ']' + '\n' +  tag + '\n' + '\\--\n';
        }
        this.replace(tag);
    },

    div: function(divClass) {
        this.tag('/--div .[' + divClass + ']\n\n', '\n\n\\--');
    },

    closedDiv: function(divClass) {
        this.tag('<div class="' + divClass + '"></div>\n', '');
    }

});