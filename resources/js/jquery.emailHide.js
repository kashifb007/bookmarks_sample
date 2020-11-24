$(document).ready(function()
{
var at = / at /;
var dot = / dot /g;
$('span.mailme').each(function () {
var addr = $(this).text().replace(at,"@").replace(dot,".");
$(this).after('<a href="mailto:'+ addr +'" title="Send an email">'+ addr +'</a>');
$(this).remove();
});
}
);