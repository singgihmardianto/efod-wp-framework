(()=>{"use strict";const t=(t,e)=>{const i=()=>{var i;const a=t(".ef-cpt-meta-img-galleries__add_btn"),l=t(".ef-cpt-meta-img-galleries__list");let n=t(".ef-cpt-meta-img-galleries__input_hidden"),s=null!==(i=n.val()?.split(","))&&void 0!==i?i:"";l.sortable({cursor:"-webkit-grabbing",stop(e,i){i.item.removeAttr("style");const a=new Array,l=t(this);l.find("li").each((()=>{a.push(t(this).attr("data-id"))})),l.parent().next().val(a.join())}}),a.click((i=>{i.preventDefault();const a=e.media({title:"Insert images",library:{type:"image"},button:{text:"Use these images"},multiple:!0});a.on("select",(function(){const e=a.state().get("selection").map((t=>(t.toJSON(),t)));s=[],t(".ef-cpt-meta-img-galleries__list").html(""),e.forEach((e=>{t(".ef-cpt-meta-img-galleries__list").append('<li data-id="'+e.id+'"><span style="background-image:url('+e.attributes.url+')"></span></li>'),s.push(e.id)})),t(".ef-cpt-meta-img-galleries__list").sortable("refresh"),t(".ef-cpt-meta-img-galleries__input_hidden").val(s.join())})),a.on("open",(function(){const i=a.state().get("selection");n=t(".ef-cpt-meta-img-galleries__input_hidden"),s=n.val().split(","),s.forEach((function(t){const a=e.media.attachment(t);a.fetch(),a&&i.add([a])}))})),a.open()}))};return{run:()=>{i()}}};!function(e,i){t(e,i).run()}(jQuery,wp)})();