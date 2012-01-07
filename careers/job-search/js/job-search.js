$(function() {
	var createLocationSelects = function() {
		// Current selected country.
		var currentCountry;

		// Fills the states widget based on country.
		var fillStates = function(country) {
			var states = locData[country], str = "<option value='All'>All</option>";
			// Pull out states for selected country.
			for(var state in states) {
				if(state != "NO_STATE") {
					str += "<option value='" + state + "'>" + state + "</option>";
				}
			}
			$('#state-select').html(str);
			$('#state-select').val("All");
			// Select the first.
		}
		// Fills the cities widged based on states.
		var fillCities = function(country, states) {
			// If All option, then fill array with all states.
			if(states[0] == "All") {
				states = [];
				for(var state in locData[country])
				states.push(state);
			}
			// Run through states and pull out cities.
			var str = "<option value='All'>All</option>";
			for(var i = 0; i < states.length; i++) {
				var cities = locData[country][states[i]];
				for(var c = 0; c < cities.length; c++) {
					str += "<option value='" + cities[c] + "'>" + cities[c] + "</option>";
				}
			}
			$('#city-select').html(str);
			$('#city-select').val("All");
			// Select the first.
		};
		// Setup initial widgets, defaulting to 1st country.
		var str = "";
		for(var country in locData) {

			if(currentCountry == undefined)
				currentCountry = country;
			str += "<option value='" + country + "'>" + country + "</option>";
		}
		$('#country-select').html(str);
		// Fill in starting states and cities.
		fillStates(currentCountry);
		fillCities(currentCountry, $("#state-select").val());

		// On country change, fill in states and cities.
		$('#country-select').bind('change', function(evt) {
			currentCountry = $(evt.target).val();
			fillStates(currentCountry);
			fillCities(currentCountry, $("#state-select").val());
		});
		// On state change, fill in cities.
		$('#state-select').bind('change', function(evt) {
			fillCities(currentCountry, $(evt.target).val());
		});
	}();

	var ajaxBusy = false;
	$('#ajaxSubmit').bind('click', function() {
		var params = "";
		if(ajaxBusy) {
			return;
		}
		ajaxBusy = true;
		$('.kenexa-question', $('#searchForm')).each(function() {
			params += $(this).attr('name') + '=' + $(this).val() + "&";
		});
		$('#ajax-loader').css('display', 'block');
		$('#results').html("<hr/>");
		$.ajax({
			type : "POST",
			url : "index.php",
			data : params,
			dataType : "HTML",
			success : function(data) {
				$('#results').html(data);
				$('#ajax-loader').css('display', 'none');
				ajaxBusy = false;
			},
			error : function() {
				$('#ajax-loader').css('display', 'none');
				ajaxBusy = false;
				alert('An error occurred.');
			}
		})
	});
});
