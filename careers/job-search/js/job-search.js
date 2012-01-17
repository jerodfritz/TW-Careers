$(function() {
       var createLocationSelects = function() {
            // Current selected country.
            var currentCountry,
			
            // Fills the states widget based on country.
            fillStates = function(country) {
                var states = locData[country],
                    //str="<option value='All'>All</option>";
                    str="";
                 
                    //noneSelectedText = "Select State";
                // Pull out states for selected country.
                for(var state in states) {
                    if(state!= "NO_STATE") {
                        str+= "<option value='" + state + "'>" + state + "</option>";
                    }else {
                        str+= "<option value='" + state + "'>" + "All States" + "</option>";
                        //noneSelectedText = "States"; // A single option causes none selected text to display;
                    }
                }
                $('#state-select').html(str)
                    .multiselect("destroy");
                
                if(state == "NO_STATE") {
                   $('#state-select').val("NO_STATE")
                        .multiselect({multiple:false,header:false,selectedText:"N/A"})
                        .multiselect("disable");
                   return;
                }
                    
                $('#state-select').multiselect({header:true,selectedText:'States',noneSelectedText:'States'})
                    .multiselect("enable").multiselect("checkAll")
                    
            },
            
            // Fills the cities widget based on states.
            fillCities = function(country,states) {
                // If no states selected, then all cities should dissapear and select becomes disabled.
                if(!states) {
                    $('#city-select').html("")
                        .multiselect("destroy")
                        .multiselect({header:false,selectedText:'N/A',noneSelectedText:'N/A'})
                        .multiselect("disable")
                    return;
                    
                }
                var str="",
                numCities = 0;
                for(var i=0;i<states.length;i++) {
                    var cities = locData[country][states[i]];
                    for(var c=0; c<cities.length;c++) {
                        str += "<option value='";
                            if(states[i] != "NO_STATE")  str += states[i] + " - ";
                            str += cities[c] + " - " + country + "'>" + 
                            cities[c] + "</option>";
                            numCities++;
                    }
                }
                $('#city-select').html(str).multiselect("destroy");
                if(numCities==1) {
                   
                   $('#city-select option:first').attr('selected','selected');
                  
                   $('#city-select').multiselect({multiple:false,header:false,
                       selectedText:$('#city-select option:first').text()
                   }).multiselect("disable");
                   return;
                }else {
                    $('#city-select').multiselect({header:true,selectedText:'Cities',noneSelectedText:"Cities"})
                        .multiselect("enable").multiselect("checkAll")
                }
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
                fillCities(currentCountry,$(evt.target).val());
            });
            $('#city-select').bind('change',function(evt){
            });	
        }();
        
        $('#country-select').multiselect({multiple:false,header:false,selectedText:function(a,b,c){
                return $(c[0]).val(); // return value for selected text display.
        }});

        // Creates a search string based on the location inputs,
        // in CSV format for the Kenexa query value.
        var createLocSearchCSV = function() {
            var selectedCities = $("#city-select").val(),
                query = "";
            if(!selectedCities) {
                return null;
            }
            // Add seleted cities to query.
            for(var i=0;i<selectedCities.length;i++) {
                query += selectedCities[i] + ',';
            }
            // Return query with trailing comma removed.
            return query.slice(0, -1);
        }
        
				
        var ajaxBusy = false;
	var numSearches = 0;
        var csv = null;
        $('#ajaxSubmit').bind('click',function(){
            // Fill in the location input from the value of the special location widgets.
           
            if(ajaxBusy) {
                return;
            }
            csv = createLocSearchCSV();
            if(!csv) {
                alert("Please select a city");
                return false;
            }
            var   params = "";
			$('#location').val(createLocSearchCSV());
            ajaxBusy = true;
			
            $('.kenexa-question',$('#searchForm')).each(function(){
				// Only bother with inputs that have a value.
				if ($(this).val() != "" )
					params += $(this).attr('name') +'=' + $(this).val() + "&";
            });
			
            numSearches++;
            $('#search-history').append("<a target='_blank' href='" + window.location +"?" +params +"'>Search #" + numSearches + "</a><br/>");
			
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