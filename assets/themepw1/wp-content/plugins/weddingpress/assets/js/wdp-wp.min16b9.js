jQuery(document).ready(function(e){e(".wdp-wa-form").on("submit",function(a){if(a.preventDefault(),a.stopPropagation(),!e("body").hasClass("elementor-editor-active")){var t=e(this).find(".wdp-form-option").val(),i=e(this).find(".wdp-form-nama").val(),n=e(this).find(".wdp-form-pesan").val(),o=e(this).find(".wdp-form-jumlah").val(),d=e(this).data("waapi");t&&i&&n&&o&&("hide"==n&&(n=""),"hide"==o&&(o=""),d=(d=(d=(d=d.replace("%25option%25",encodeURI(t))).replace("%25nama%25",encodeURI(i))).replace("%25pesan%25",encodeURI(n))).replace("%25jumlah%25",encodeURI(o)),e(this).data("fbevent")||e(this).data("fbcustomevent")||e(this).data("grc")?"undefined"!=typeof fbq&&setTimeout(function(){window.location=d},1e3):window.location=d)}})});