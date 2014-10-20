$(function() {
	$("#dogplus-0").click(function(e) {
		e.preventDefault();
		writeDogdetails(event.target.id);
	});
});

function writeDogdetails(container) {

	var newid = $(".dog-name-container").length;

	$("#"+container).parent().after().append(' \
		<div class="dog-name-container"> \
		<div class="form-group dog-name"> \
		    <label for="name">Nafn</label> \
		    <input type="text" name="name[]" class="form-control" placeholder="Nafn"> \
		</div> \
	    <div class="form-group dog-name sex"> \
		    <input type="radio" name="sex[]" value="male"><label for="male">Rakki</label> \
		    <input type="radio" name="sex[]" value="female"><label for="female">Tík</label> \
		</div> \
		<a class="btn btn-danger" id="dogminus-' + newid +'">-</a> \
	</div>');
	addDogHandlers(newid);
	
}
function addDogHandlers(newid) {
	$("#dogminus-"+newid).click(function(e) {
		e.preventDefault();
		console.log(newid);
		if ($(".dog-name-container").length > 1) {
			$("#"+event.target.id).parent().remove();
		}
		else {
			$("#"+event.target.id).remove();
		}
	});
}