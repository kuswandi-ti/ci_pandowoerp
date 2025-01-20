$(document).ready(function () {
	// Initialize Bootstrap tooltips
	$('[data-toggle="tooltip"]').tooltip();

	// Add event listener to the "back" button
	$("#back").on("click", function () {
		// Navigate back to the previous page
		window.history.back();
	});
});
