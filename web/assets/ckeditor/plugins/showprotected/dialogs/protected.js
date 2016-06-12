﻿CKEDITOR.dialog.add("showProtectedDialog", function(b) {
    return {
        title: "Edit Protected Source",
        minWidth: 300,
        minHeight: 60,
        onOk: function() {
            var a = this.getContentElement("info", "txtProtectedSource").getValue();
            this._.selectedElement.setAttribute("data-cke-realelement", CKEDITOR.plugins.showprotected.encodeProtectedSource(a));
            this._.selectedElement.setAttribute("title", a);
            this._.selectedElement.setAttribute("alt", a)
        },
        onHide: function() {
            delete this._.selectedElement
        },
        onShow: function() {
            this._.selectedElement =
                b.getSelection().getSelectedElement();
            this.setValueOf("info", "txtProtectedSource", CKEDITOR.plugins.showprotected.decodeProtectedSource(this._.selectedElement.getAttribute("data-cke-realelement")))
        },
        contents: [{
            id: "info",
            label: "Edit Protected Source",
            accessKey: "I",
            elements: [{
                type: "text",
                id: "txtProtectedSource",
                label: "Value",
                required: !0,
                validate: function() {
                    return !this.getValue() ? (alert("The value cannot be empty"), !1) : !0
                }
            }]
        }]
    }
});
