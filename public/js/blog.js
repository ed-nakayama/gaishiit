/* ------------------------------------------------------ */
/* 目次を開く */
/* ------------------------------------------------------ */
$(function () {
	$(".index_ttl").on("click", function () {
		$(this).next(".index_content").slideToggle(300);
		$(this).toggleClass("open", 300);
	});
});