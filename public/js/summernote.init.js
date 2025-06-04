function setEditor(e) {
    if (1 === parseInt(e.attr("data-editor"))) return !1;

    e.attr("data-editor", 1);

    e.summernote({
        styleTags: [
            "p",
            {
                title: "Blockquote",
                tag: "blockquote",
                className: "blockquote",
                value: "blockquote",
            },
            "pre",
            "h1",
            "h2",
            "h3",
            "h4",
            "h5",
            "h6",
        ],
        toolbar: [
            ["style", ["bold", "italic", "underline", "strikethrough"]],
            ["font", ["style", "fontsize", "fontsizeunit"]],
            ["color", ["color", "backcolor"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
            ["code", ["clear", "undo", "redo", "codeview", "fullscreen"]],
            /*['height', ['height']]*/
        ],
        onpaste: function (e) {
            var a = $(this);

            setTimeout(function () {
                var e, t;
                t = CleanPastedHTML((e = a).code());
                e.code("").html(t);
            }, 10);
        },
    });
}

function CleanPastedHTML(e) {
    var t = e.replace(/( class=(")?Mso[a-zA-Z]+(")?)/g, " ");
    (a = new RegExp("\x3c!--(.*?)--\x3e", "g")),
        (t = t.replace(a, "")),
        (r = new RegExp(
            "<(/)*(meta|link|span|\\?xml:|st1:|o:|font)(.*?)>",
            "gi"
        ));

    t = t.replace(r, "");

    for (
        var n = ["style", "script", "applet", "embed", "noframes", "noscript"],
            o = 0;
        o < n.length;
        o++
    ) {
        (r = new RegExp("<" + n[o] + ".*?" + n[o] + "(.*?)>", "gi")),
            (t = t.replace(r, ""));
    }

    for (var s = ["style", "start"], o = 0; o < s.length; o++) {
        var i = new RegExp(" " + s[o] + '="(.*?)"', "gi");

        t = t.replace(i, "");
    }

    return t;
}
