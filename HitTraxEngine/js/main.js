jQuery(document).ready(function(){
	


	$('input[name=submit_search]').click(function(){		

		var form_variables = $("#searchEngineParameters :input[value!='']").serialize();
		
		//alert(form_variables);
		
		jQuery.ajax({
			dataType: "json",
			url: "http://engine.hittraxstatscenter.com/api/userQueries.php",
			data: { "formData": form_variables },
			success: function(result){
				console.log(result);
				jQuery('#searchData table tbody').empty();
				
				var formCode = formatResults(result);
				//alert(formCode);
				// If this returns 0, means there were no results returned. Need to display that row. 
				if(formCode == 0){
					jQuery('#searchData table tbody').stop().delay(500).html('<tr class="noresults"><td colspan="8">No Results.</td></tr>');
				}
				else{
					// Otherwise display the returned results. 
					jQuery('#searchData table tbody').stop().delay(500).html(formatResults(result));
				}
		
				jQuery('#loading').delay(1).fadeOut();
			},
			error : function(result){
				jQuery('#searchData table tbody').stop().delay(500).html('<tr class="noresults"><td colspan="8">No Results.</td></tr>');
			}
		});
		
    });

	$('select[name=savedSearch]').change(function(e){

		jQuery("#results_spot").text('0');
		jQuery('#searchData table tbody').empty();
			
		// Pull the static values. 
		var savedValues = $('select[name=savedSearch]').val();
		
		jQuery.ajax({
			dataType: "html",
			url: "http://engine.hittraxstatscenter.com/api/getSavedSearch.php",
			data: { "search_id": savedValues },
			success: function(result){
				var form_values = result;
				//alert(form_values);
				setSearchFilters(form_values);
			},
			error : function(result){
				alert('Error trying to retrieve saved search.');
			}
		});
		
	});	
	
	$('#start_over_link').click(function(){		
		
		// Clear all the dropdowns
		clearSearchFilters();
		
		// Clear the only text field search filter
		jQuery('input[id=textboxName]').val('');
		jQuery(".searchItems").find(".textboxName").remove();
		
    });

	$("form.searchSave").on('submit', function(event) {
		var $form = $(this);
		var $target = $($form.attr('data-target'));

		var form_variables = $("#searchEngineParameters :input[value!='']").serialize();
		var search_name = $("#searchSaveName").val();
		var master_id = $("#MasterID").val();

		jQuery.ajax({
			dataType: "html",
			url: "http://engine.hittraxstatscenter.com/api/createSavedSearch.php",
			data: { "formData": form_variables, "searchName": search_name, "masterID": master_id },
			success: function(result){
				var new_insert_id = result;
				jQuery("#savedSearch").append($('<option></option>').val(new_insert_id).html(search_name));
			},
			error : function(result){
				alert('Error trying to save search.');
			}
		});
				
        $('#searchSave-modal').modal('toggle')
        event.preventDefault();
	});

	
	$( ".tab" ).each(function( index ) {
                    $("." + $(this).data("tab")).hide();
                });
            $(".tab").click(function(e){
                $selectedTab = $(this);
                $( ".tab" ).each(function( index ) {
                    $("." + $(this).data("tab")).hide();
                    $(this).find("img").attr("src", siteURL+"/images/tab-arrow.png");
                });

                $selectedTab.find("img").attr("src", siteURL+"/images/tab-x.png");
                var tabPanel = "." + $selectedTab.data("tab");
                $(tabPanel).show();

            });

            $(".search select").change(function(){
                var changedSelect = $(this);
                var label = $(this).siblings("label");
                $(".searchItems").find("." + changedSelect.attr("id")).remove();
                if($(this).val() != ""){
					$(".searchItems").delay(100).queue(function(next){
                		$(this).append("<li data-parent="+ changedSelect.attr("id") +" class='"+ changedSelect.attr("id") +"'><span class='glyphicon glyphicon-remove-circle'></span>"+ label.text() +": "+ changedSelect.val() +"</li>");
						next();
                	});
				}
                
            });

            $(".search input[type='text']").focusout(function(){
                var changedSelect = $(this);
                var label = $(this).siblings("label");
                $(".searchItems").find("." + changedSelect.attr("id")).remove().delay("1000");
                if($(this).val() != ""){
                $(".searchItems").delay(100).queue(function(next){
                    $(this).append("<li data-parent="+ changedSelect.attr("id") +" class='"+ changedSelect.attr("id") +"'><span class='glyphicon glyphicon-remove-circle'></span>"+ label.text() +": "+ changedSelect.val() +"</li>");
                    next();
                    });

                }
                
            });

            $(".results").on("click", "table tr td:first-child", function(){
                var selectedRow = $(this);
                $(".results table tr").removeClass("selected");
                selectedRow.parent().addClass("selected");
            });

            $("body").on("click", ".glyphicon-remove-circle", function(){
                var parentFormElement = $(this).parent().data("parent");
                $(this).parent().remove();
                $("#"+ parentFormElement).val('');
            });

});

function formatResults(data){
	var count = 0;
	html = '';
	
	jQuery.each(data, function(rowName){

		var playerPosition = getPosition(data[rowName]['Position']);
		var graduationYear = getGraduationYear(data[rowName]['GraduationYear']);
		var playerState = getState(data[rowName]['State']);
		var playerAEV = getMPH(data[rowName]['AEV']);
		var playerMEV = getMPH(data[rowName]['MEV']);
		var playerAD = getFT(data[rowName]['AD']);
		var playerMD = getFT(data[rowName]['MD']);
		
		html += '<tr>';
			html += '<td>' + data[rowName]['FirstName'] + ' ' + data[rowName]['LastName'] + ' <span class="glyphicon glyphicon-menu-right pull-right"></span></td>';
			html += '<td class="">' + playerPosition + '</td>';
			html += '<td class="">' + graduationYear + '</td>';
			html += '<td class="">' + playerState + '</td>';
			html += '<td class="hidden-xs">' + playerAEV + ' mph</td>';
			html += '<td class="hidden-xs">' + playerMEV + ' mph</td>';
			html += '<td class="hidden-xs">' + playerAD + ' ft</td>';
			html += '<td class="hidden-xs">' + playerMD + ' ft</td>';
			html += '</tr>';
			
		count += 1;
	});
	
	jQuery("#results_spot").text(count);

	if(count == 0){
		return 0;
	}
	else{
		return html;
	}
}


function getMPH(passed_value){
	return Math.round((passed_value*1*60*60)/(1609*1*1));
}

function getFT(passed_value){
	return Math.round(passed_value * 3.2808);
}

function getGraduationYear(passed_value){
	
	var gradYear = '';
	
	if(passed_value == '1' || passed_value == '0'){ 
		gradYear = "Not Set"; 
	}
	else{
		gradYear = passed_value; 
	}
	
	return gradYear;	
}

function getState(passed_value){
	
	var passedState = '';
	
	if(passed_value == ''){ 
		passedState = "Not Set"; 
	}
	else{
		passedState = passed_value; 
	}
	
	return passedState;	
}

function getSport(passed_value){
	
	var sportPlayed = '';
	
	if(passed_value == '0'){ sportPlayed = "Baseball"; }
	if(passed_value == '1'){ sportPlayed = "Softball"; }
	if(sportPlayed == ''){ sportPlayed = "Not Set"; }
	
	return sportPlayed;	
}

function getConvertedHeight(passed_value){
	
	var convertedHeight = passed_value;
	
	if((convertedHeight == 0) || (convertedHeight === undefined) || (isNaN(convertedHeight))){
		convertedHeight="Not Set";
	}
	else{
		// Convert the meters into inches.
		var totalInches = convertedHeight * 39.3701;
		var footValue = Math.floor(totalInches/12); 
		var remainingInches = Math.floor(totalInches - (footValue * 12));
		
		convertedHeight = footValue + "' " + remainingInches + '"';
	}
	
	return convertedHeight;	
}

function getConvertedWeight(passed_value){
	
	var convertedWeight =  Math.ceil(passed_value * 2.20462);
	
	
	if((convertedWeight == 0) || (convertedWeight === undefined) || (isNaN(convertedWeight))){
		convertedWeight="Not Set";
	}
	else{
		convertedWeight = convertedWeight + ' lbs';
	}
	
	return convertedWeight;	
}

function getBattingSide(passed_value){
	
	var bats = '';
	
	if(passed_value == '2'){ bats = "Left"; }
	if(passed_value == '1'){ bats = "Right"; }
	if(passed_value == '3'){ bats = "Switch"; }
	if(bats == ''){ bats = "Not Set"; }
	
	return bats;	
}

function getThrowingSide(passed_value){
	
	var throwSide = '';
	
	if(passed_value == '2'){ throwSide = "Left"; }
	if(passed_value == '1'){ throwSide = "Right"; }
	if(throwSide == ''){ throwSide = "Not Set"; }
	
	return throwSide;	
}

function getPosition(passed_value){
	
	var playerPosition = '';
	
	if(passed_value == '1'){ playerPosition = "P"; }
	if(passed_value == '2'){ playerPosition = "C"; }
	if(passed_value == '3'){ playerPosition = "1B"; }
	if(passed_value == '4'){ playerPosition = "2B"; }
	if(passed_value == '5'){ playerPosition = "3B"; }
	if(passed_value == '6'){ playerPosition = "SS"; }
	if(passed_value == '7' || passed_value == '8' || passed_value == '9'){ playerPosition = "OF"; }
	if(playerPosition == ''){ playerPosition = "Not Set"; }
	
	return playerPosition;	
}

function clearSearchFilters(){

	var item;
	var dropdowns = ["selectSport","selectState","selectRegion","selectAge","selectGraduation","selectGpa","selectHeight","selectWeight","selectPosition","selectBats","selectThrows","selectMaxExit","selectMaxDistance","selectLaunchAngleGreater","selectPitchPlateSpeedGreater","selectAvgExitVelGreater","selectAvgDistanceGreater","selectLaunchAngleLess","selectPitchPlateSpeedLess","selectLineDrive","selectGroundBall","selectFlyBall","selectStrike","selectPlateSpeed","selectPitchType","selectOppAvgExitVeloticy","selectEffIndex","selectPowerIndex","selectSwingSpeed","selectTimeToContact","selectMaxThrowVelocity","selectAverageThrowVelocity","selectThrowAccuracy","select60YardDash","selectHomeToFirst","select10YardDash","selectShuttle"];
	
	// Set all the dropdowns back to blank. 
	for (i = 0; i < dropdowns.length; i++) { 
	    item = dropdowns[i];
		jQuery("select#"+item)[0].selectedIndex = 0;
		$(".searchItems").find("."+item).remove();
	}
	
	// Clear the content from the name selection field. 
	jQuery("input[name=textboxName]").val('');
	$(".searchItems").find(".textboxName").remove();
	
	// Remove the content from the table. 
	jQuery('#searchData table tbody').empty();
	jQuery('#searchData table tbody').stop().delay(500).html('<tr class="noresults"><td colspan="8">No Results.</td></tr>');
	
	jQuery("#results_spot").text('0');
}

function setSearchFilters(formData){

	clearSearchFilters();
	
	var filter_parts = formData.split("&");
	var label;
	
	// For context, here is a sample set of the form data passed:
	/* textboxName=&selectSport=&selectState=&selectRegion=&selectAge=&selectGraduation=2018&selectGpa=&selectHeight=&selectWeight=
	&selectPosition=3B&selectBats=&selectThrows=&selectMaxExit=&selectMaxDistance=&selectLaunchAngleGreater=&selectPitchPlateSpeedGreater=
	&selectAvgExitVelGreater=&selectAvgDistanceGreater=&selectLaunchAngleLess=&selectPitchPlateSpeedLess=&selectLineDrive=&selectGroundBall=
	&selectFlyBall=&selectStrike=&selectPlateSpeed=&selectPitchType=&selectOppAvgExitVeloticy=&selectEffIndex=&selectPowerIndex=
	&selectSwingSpeed=&selectTimeToContact=&selectMaxThrowVelocity=&selectAverageThrowVelocity=&selectThrowAccuracy=&select60YardDash=
	&selectHomeToFirst=&select10YardDash=&selectShuttle= */
	
	// Loop through formdata, putting each field and revieiwng it. 
	for (i = 0; i < filter_parts.length; i++) { 
	    item = filter_parts[i];
		item_parts = item.split("=");
		
		// Only do this section if a value was provided for the field. 
		if(item_parts[1] != ''){
			
			// Name is the only text field form object so custom population code for that. 
			if(item_parts[0] == 'textboxName'){
				$('input[id=textboxName]').val(item_parts[1]);
 			}
			else{
				// The rest are dropdown objects, so select value. 
				$("#"+item_parts[0]).val(item_parts[1]);
			}
			
			// Get the label and display that search 'card' below all the search criteria. 
			label = getLabel(item_parts[0]);
			targetParent = item_parts[0];
			targetValue = item_parts[1];
			
				addSearchItems(targetParent, targetValue, label);
			
			// $(".searchItems").delay(100).queue(function(){
			//  	$(".searchItems").append("<li data-parent="+ targetParent +" class='"+ targetParent +" tk-proxima-nova'><span class='glyphicon glyphicon-remove-circle'></span>"+ label +": "+ targetValue +"</li>");
			//  	$(this).dequeue();
			// });
			// prev();
			//$(".searchItems").append("<li data-parent="+ item_parts[0] +" class='"+ item_parts[0] +"'><span class='glyphicon glyphicon-remove-circle'></span>"+ label +": "+ item_parts[1] +"</li>");
		
			// Default to the Player Detail search tab when a filter has been selected. 
			var tabPanel = ".playerDetail";
            $(tabPanel).show();
			
			// Needed a slight delay for the form elements to be populated before searching.
			setTimeout(clickSearch, 500);
		}
	}		
}

function addSearchItems(targetParent, targetValue, label){
	
			$(".searchItems").delay(100).queue(function(){
			 	$(".searchItems").append("<li data-parent="+ targetParent +" class='"+ targetParent +" tk-proxima-nova'><span class='glyphicon glyphicon-remove-circle'></span>"+ label +": "+ targetValue +"</li>");
			 	$(this).dequeue();
			});
}

function clickSearch(){
	// Click the search button to execute the search.
	$('input[name=submit_search]').click();
}

function getLabel(form_id){
	switch(form_id){
		case 'textboxName':
			return 'Player Name';
			break;
		case 'selectSport':
			return 'Sport';
			break;
		case 'selectState':
			return 'State';
			break;
		case 'selectRegion':
			return 'Region';
			break;
		case 'selectAge':
			return 'Age';
			break;
		case 'selectGraduation':
			return 'Graduation Year';
			break;
		case 'selectGraduation':
			return 'Graduation Year';
			break;
		case 'selectGpa':
			return 'GPA';
			break;
		case 'selectHeight':
			return 'Height';
			break;
		case 'selectWeight':
			return 'Weight';
			break;
		case 'selectPosition':
			return 'Position';
			break;
		case 'selectBats':
			return 'Bats (L/R/S)';
			break;
		case 'selectThrows':
			return 'Throws (L/R)';
			break;
		case 'selectMaxExit':
			return 'Max Exit Velocity ≥';
			break;	
		case 'selectMaxDistance':
			return 'Max Distance ≥';
			break;
		case 'selectLaunchAngleGreater':
			return 'Launch Angle ≥';
			break;
		case 'selectPitchPlateSpeedGreater':
			return 'Pitch Plate Speed ≥';
			break;
		case 'selectAvgExitVelGreater':
			return 'Avg. Exit Velocity ≥';
			break;
		case 'selectAvgDistanceGreater':
			return 'Avg. Distance ≥';
			break;
		case 'selectLaunchAngleLess':
			return 'Launch Angle ≤';
			break;
		case 'selectPitchPlateSpeedLess':
			return 'Pitch Plate Speed ≤';
			break;
		case 'selectLineDrive':
			return 'Line Drive % ≥';
			break;
		case 'selectGroundBall':
			return 'Ground Ball % ≥';
			break;
		case 'selectFlyBall':
			return 'Fly Ball % ≥';
			break;
		case 'selectStrike':
			return 'Strike %';
			break;
		case 'selectPlateSpeed':
			return 'Plate Speed';
			break;
		case 'selectPitchType':
			return 'Pitch Type';
			break;	
		case 'selectOppAvgExitVeloticy':
			return 'Opp. Avg. Exit Velocity';
			break;
		case 'selectEffIndex':
			return 'Efficiency Index';
			break;
		case 'selectPowerIndex':
			return 'Power Index';
			break;
		case 'selectSwingSpeed':
			return 'Swing Speed';
			break;	
		case 'selectTimeToContact':
			return 'Time to Contact';
			break;
		case 'selectMaxThrowVelocity':
			return 'Max Throw Velocity';
			break;
		case 'selectAverageThrowVelocity':
			return 'Average Throw Velocity';
			break;
		case 'selectThrowAccuracy':
			return 'Throw Accuracy';
			break;	
		case 'select60YardDash':
			return '60 Yard Dash';
			break;
		case 'selectHomeToFirst':
			return 'Home to First';
			break;
		case 'select10YardDash':
			return '10 Yard Dash';
			break;	
		case 'selectShuttle':
			return '5-10-5 Shuttle';
			break;								
	}	
}