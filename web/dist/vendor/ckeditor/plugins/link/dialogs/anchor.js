CKEDITOR.dialog.add("anchor",(function(e){var t=function(e){this._.selectedElement=e,this.setValueOf("info","txtName",e.data("cke-saved-name")||"")};return{title:e.lang.link.anchor.title,minWidth:300,minHeight:60,onOk:function(){var t,n={id:n=CKEDITOR.tools.trim(this.getValueOf("info","txtName")),name:n,"data-cke-saved-name":n};this._.selectedElement?this._.selectedElement.data("cke-realelement")?(n=e.document.createElement("a",{attributes:n}),e.createFakeElement(n,"cke_anchor","anchor").replace(this._.selectedElement)):this._.selectedElement.setAttributes(n):(t=(t=e.getSelection())&&t.getRanges()[0]).collapsed?(CKEDITOR.plugins.link.synAnchorSelector&&(n.class="cke_anchor_empty"),CKEDITOR.plugins.link.emptyAnchorFix&&(n.contenteditable="false",n["data-cke-editable"]=1),n=e.document.createElement("a",{attributes:n}),CKEDITOR.plugins.link.fakeAnchor&&(n=e.createFakeElement(n,"cke_anchor","anchor")),t.insertNode(n)):(CKEDITOR.env.ie&&9>CKEDITOR.env.version&&(n.class="cke_anchor"),(n=new CKEDITOR.style({element:"a",attributes:n})).type=CKEDITOR.STYLE_INLINE,e.applyStyle(n))},onHide:function(){delete this._.selectedElement},onShow:function(){var n=e.getSelection(),l=n.getSelectedElement();l?CKEDITOR.plugins.link.fakeAnchor?((n=CKEDITOR.plugins.link.tryRestoreFakeAnchor(e,l))&&t.call(this,n),this._.selectedElement=l):l.is("a")&&l.hasAttribute("name")&&t.call(this,l):(l=CKEDITOR.plugins.link.getSelectedLink(e))&&(t.call(this,l),n.selectElement(l)),this.getContentElement("info","txtName").focus()},contents:[{id:"info",label:e.lang.link.anchor.title,accessKey:"I",elements:[{type:"text",id:"txtName",label:e.lang.link.anchor.name,required:!0,validate:function(){return!!this.getValue()||(alert(e.lang.link.anchor.errorName),!1)}}]}]}}));