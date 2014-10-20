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
		    <input type="radio" name="sex-'+newid+'" id="male-'+newid+'" value="Male"><label for="male-'+newid+'">Rakki</label> \
		    <input type="radio" name="sex-'+newid+'" id="female-'+newid+'" value="Female"><label for="female-'+newid+'">Tík</label> \
		</div> \
		<a class="btn btn-danger" id="dogminus-' + newid +'">-</a> \
	</div>');
	addDogHandlers(newid);
	
}
function addDogHandlers(newid) {
	$("#dogminus-"+newid).click(function(e) {
		e.preventDefault();
		if ($(".dog-name-container").length > 1) {
			$("#"+event.target.id).parent().remove();
		}
		else {
			$("#"+event.target.id).remove();
		}
	});
}