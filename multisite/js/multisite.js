
function ElggMultisite() {}


$(document).ready(function () {
    $('a.confirmlink').click(function (e) {

	

	var confirmtext = $(this).attr('data-confirmtext');

	if (typeof confirmtext == 'undefined') {
	    confirmtext = 'Are you sure?';
	}

	if (confirm(confirmtext)) {
	    return true;
	}
	e.preventDefault();

	return false;
    });
});