(function (e, t, n, r) {
    function o(t, n) {
        this.element = t;
        this.$element = e(t);
        this.options = e.extend({}, s, n);
        this._defaults = s;
        this._name = i, this.$topLevelBranches, this.$allBranches, this.init()
    }

    var i = "abixTreeList",
        s = {collapsedIconClass: "glyphicon glyphicon-plus", expandedIconClass: "glyphicon glyphicon-minus"};
    o.prototype.init = function () {
        var t = this;
        t.$topLevelBranches = t.$element.children("li");
        t.$allBranches = t.$element.find("li");
        t.$element.addClass("abix-tree-list");
        t.$allBranches.not(t.$topLevelBranches).hide();
        t.$allBranches.each(function () {

            var n = e(this).children("ul,ol");
            if (n.size() > 0) {
                e(this).addClass("collapsed");
                e('</span><svg class="dropdown_arrow"><use xlink:href="#arrow-down"></use></svg>').prependTo(e(this))
            }
        });
        t.$allBranches.children("svg.dropdown_arrow").on("click", function (n) {
            if (e(this).parent().hasClass("collapsed")) {
                e(this).parent().children("svg.dropdown_arrow").css({"-webkit-transform": "rotate(180deg)","-ms-transform":"rotate(180deg)","transform":"rotate(180deg)"})
                t.expand(e(this).parent());
                n.stopPropagation()
            }
            if (e(this).parent().hasClass("expanded")) {
                e(this).parent().children("svg.dropdown_arrow").css({"-webkit-transform": "","-ms-transform":"","transform":""})
                t.collapse(e(this).parent());
                n.stopPropagation()
            }
        });
    };
    o.prototype.expand = function (e) {
        var t = this;
        e.children("ul,ol").children("li").show(500, function () {
            e.removeClass("collapsed").addClass("expanded");
        })
    };
    o.prototype.collapse = function (e) {
        var t = this;
        e.children("ul,ol").children("li").hide(500, function () {
            e.removeClass("expanded").addClass("collapsed");
        })
    };
    o.prototype.collapseAll = function () {
        var e = this;
        e.$allBranches.not(e.$topLevelBranches).hide(1e3, function () {
            e.$allBranches.removeClass("expanded").addClass("collapsed");
            e.$allBranches.children("span.icon").removeClass(e.options.expandedIconClass).addClass(e.options.collapsedIconClass)
        })
    };
    o.prototype.expandAll = function () {
        var e = this;
        e.$allBranches.show(1e3, function () {
            e.$allBranches.removeClass("collapsed").addClass("expanded");
            e.$allBranches.children("span.icon").removeClass(e.options.collapsedIconClass).addClass(e.options.expandedIconClass)
        })
    };
    e.fn[i] = function (t) {
        return this.each(function () {
            if (!e.data(this, "plugin_" + i)) {
                e.data(this, "plugin_" + i, new o(this, t))
            }
        })
    }
})(jQuery, window, document)
$(document).ready(function () {
    $('#tree').abixTreeList();
    $('#tree2').abixTreeList();
});