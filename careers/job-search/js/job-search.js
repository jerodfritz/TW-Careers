    $(function(){
        var sortQuestion = "date";  // Default sort field.    
        var pageNumber = 1;			// Default search page number
		var lastSearchParams = "";	// The url params string of the last search performed (exluding pagenum parameter).
		// Setup non-location multi selects.
		$("select.kenexa-question",$('#careers-advanced-search')).each(function(){
                    $(this).multiselect(
                        {
                            multiple:true,
                            noneSelectedText:$(this).attr('title'),
                            selectedText: $(this).attr('title') + ' (# selected)'
                        });           
                })     
        
		// Setup location multi selects.
		var $locationWidget = $(
        '<div id="location-selects">'+
            '<select id="country-select" ></select>'+
            '<select id="state-select" class="loc-select" size="6" multiple="multiple"></select>'+
            '<select id="city-select" class="loc-select" size="6" multiple="multiple"></select>'+
        '</div>');
        $locationWidget.insertAfter('#location');
		
        // Bind click events to table header for sorting.
        $('#results-table th.hover-effect').live('click',function(){
                $('#results-table th').removeClass('sort-on-this');
                $(this).addClass('sort-on-this');
                // sort question gets added to the &sortby param on submission.
                sortQuestion = $(this).attr('id');
                // Search again with new sort parameter.
                $('#ajaxSubmit').trigger('click');
        });
		
		// Find the page links (if any) for pagination.
		$('#results div.page-link').live('click', function(){
			// Get the desired page number from the dom element.
			pageNumber = $(this).text().replace(/[^0-9]/g, '');
			// Trigger the search.
			$('#ajaxSubmit').trigger('click');
		});
        
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
			
			// Run through inputs and pull out values.
            $('.kenexa-question',$('#searchForm')).each(function(){
				
				if ($(this).hasClass('multi-select') ) {
					var arr = $(this).val();
					var str="";
					if (arr && arr.length)	{
						params += $(this).attr('name') +'=';
						for(var i=0;i<arr.length;i++) {str+=  arr[i] + ',';}
						str = str.slice(0, -1);
						params +=str+"&";
					}else {
						// If nothing selected in multi-select,
						// then assume search all - this will ensure the question is still included for sorting.
					params += $(this).attr('name') + "=TG_SEARCH_ALL&";
					}
				}else {
					params += $(this).attr('name') +'=' + $(this).val() + "&";
				}
				

            });
			params += "sortby=" + sortQuestion;
			
			// If the new search is different to the previous search,
			// Then we must reset the page number to 1, as the new results
			// will probably NOT have the same number of search pages.
			// Scenario: User searches USA jobs, 700 results returned over 14 pages.
			// User selects UK, and clicks on page 14 of page links generated by previous USA jobs search.
			// Page 14 is invalid as UK jobs only have one page of results.
			// Similarly performing a new sort on the same results should also reset to P1
			// as the order of pages will change.
			// 
			// There is probably a better way of handling this.
			
			if (lastSearchParams != params) pageNumber =1;
			lastSearchParams = params;
			
			// Add the desired page number.
			params += "&pagenum=" + pageNumber;
			
            numSearches++;
            $('#search-history').append("<a target='_blank' href='" + window.location +"?" +params +"'>Search #" + numSearches + "</a><br/>");
			
            $('#ajax-loader').css('display','block');
            //$('#results').html("<hr/>");
            $.ajax({
                type: "POST",
                url: "./ajax-search/",
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