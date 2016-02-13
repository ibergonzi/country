$("input[name='exportColumns[]']").on("change", function() {
	$(this).each(function()
        {
			if ($(this).is(":checked") ) {
				noexportar=false;
			} else {
				noexportar=true;
			}
			busca="[data-col-seq=" + $(this).val() + "]";
			$(busca).each(function() {
				$(this).removeClass("skip-export");
				if (noexportar) {
					$(this).addClass("skip-export");
				}
			}
			);
        });
});
$(document).on("pjax:complete", function() {
	$("input[name='exportColumns[]']").each(function()
        {
			if ($(this).is(":checked") ) {
				noexportar=false;
			} else {
				noexportar=true;
			}
			busca="[data-col-seq=" + $(this).val() + "]";
			$(busca).each(function() {
				$(this).removeClass("skip-export");
				if (noexportar) {
					$(this).addClass("skip-export");
				}
			}
			);
        });   
});
