$(function() {
      var $locationWidget = $(
        '<div id="location-selects">'+
            '<p>Select desired location:</p>'+
            '<select id="country-select" ></select>'+
            '<select id="state-select" class="loc-select" size="6" multiple="multiple"></select>'+
            '<select id="city-select" class="loc-select" size="6" multiple="multiple"></select>'+
        '</div>');
        $locationWidget.insertAfter('#kenexaq_1140');
        
       // alert($('#kenexaq_1140').length);
        var createLocationSelects = function() {
            // Current selected country.
            var currentCountry,
			
            // Fills the states widget based on country.
                fillStates = function(country) {
                var states = locData[country],
                    str="<option value='All'>All</option>";
                // Pull out states for selected country.
                for(var state in states) {
                    if(state!= "NO_STATE") {
                        str+= "<option value='" + state + "'>" + state + "</option>";
                    }
                }
                $('#state-select').html(str);
                $('#state-select').val("All");  // Select the first.
                $('#state-select').multiselect("destroy").multiselect({header:false,selectedText:'States'});
            },
            
            // Fills the cities widged based on states.
            fillCities = function(country,states) {
                // If All option, then fill array with all states.
                if(states[0]=="All") {
                    states=[];
                    for(var state in locData[country])
                        states.push(state);
                }
                // Run through states and pull out cities, and create the option values.
                var str = "<option value='All'>All</option>";
                for(var i=0;i<states.length;i++) {
                    var cities = locData[country][states[i]];
                    for(var c=0; c<cities.length;c++) {
                        str += "<option value='";
                            if(states[i] != "NO_STATE")  str += states[i] + " - ";
                            str += cities[c] + " - " + country + "'>" + 
                            cities[c] + "</option>";
                    }
                }
                $('#city-select').html(str);
                $('#city-select').val("All");   // Select the first.
                $('#city-select').multiselect("destroy").multiselect({header:false,selectedText:'Cities'});
            },
            // Setup initial widgets, defaulting to 1st country.		
            str="";
            for(var country in locData) {
                
                if(currentCountry==undefined) currentCountry = country;
                str+= "<option value='" + country + "'>" + country + "</option>";
            }
            $('#country-select').html(str);
            // Fill in starting states and cities.
            fillStates(currentCountry);
            fillCities(currentCountry,$("#state-select").val());
			
            // On country change, fill in states and cities.
            $('#country-select').bind('change',function(evt){
                currentCountry = $(evt.target).val();
                fillStates(currentCountry);
                fillCities(currentCountry,$("#state-select").val());
            });
					
            // On state change, fill in cities.
            $('#state-select').bind('change',function(evt){
                // Default to all states if nothing selected.
                if (!$(evt.target).val()) {
                    $(evt.target).val("All");
                    $('#state-select').multiselect("destroy").multiselect({header:false,selectedList:1,selectedText:"States"});
                }
                fillCities(currentCountry,$(evt.target).val());	
            });
            $('#city-select').bind('change',function(evt){
                // Default to all cities if nothing selected.
                if (!$(evt.target).val()) {
                    $(evt.target).val("All");
                    $('#city-select').multiselect("destroy").multiselect({header:false,selectedText:"Cities"});
                }
            });	
        }();
        
        $('#country-select').multiselect({multiple:false,header:false,selectedList:1});

        // Creates a search string based on the location inputs,
        // in CSV format for the Kenexa query value.
        var createLocSearchCSV = function() {
            var selectedCities = $("#city-select").val(),
                query = "";
            // If all cities selected, go through all select options and add them to query.
            if (selectedCities[0] == "All") {
                $('#city-select option').each(function(){
                    if ($(this).attr('value') !== "All") query += $(this).attr('value') + ','; 
                })
            }else
            // If specific cities selected, only add those to query.
            for(var i=0;i<selectedCities.length;i++) {
                query += selectedCities[i] + ',';
            }
            // Return query with trailing comma removed.
            return query.slice(0, -1);
        }
        
				
        var ajaxBusy = false;
        $('#ajaxSubmit').bind('click',function(){
            // Fill in the location input from the value of the special location widgets.
            $('#kenexaq_1140').val(createLocSearchCSV());
            var   params = "";
            if(ajaxBusy) {
                return;
            }
            ajaxBusy = true;
            $('.kenexa-question',$('#searchForm')).each(function(){
                params += $(this).attr('name') +'=' + $(this).val() + "&";
            });
            $('#ajax-loader').css('display','block');
            $('#results').html("<hr/>");
            $.ajax({
                type: "POST",
                url: "index.php",
                data: params,
                dataType:"HTML",
                success: function(data) {
                    $('#results').html(data);
                    $('#ajax-loader').css('display','none');
                    ajaxBusy = false;
                },
                error: function() {
                    $('#ajax-loader').css('display','none');
                    ajaxBusy = false;
                    alert('An error occurred.');
                }
            })
        });
    });