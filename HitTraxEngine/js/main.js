jQuery(document).ready(function(){
	
	$('input[name=submit_search]').click(function(){		

		var form_variables = $("#searchEngineParameters :input[value!='']").serialize();
		
		jQuery.ajax({
			dataType: "json",
			url: "http://engine.hittraxstatscenter.com/api/userQueries.php",
			data: { "formData": form_variables },
			success: function(result){
				console.log(result);
				//alert(JSON.stringify(result, null, 4));
				jQuery('#searchData table tbody').empty();
				jQuery('#searchData table tbody').stop().delay(500).html(formatResults(result));
				jQuery('#loading').delay(1).fadeOut();
				
	            //jQuery("#leaderAge").text(skill);
			},
			error : function(result){
				//console.log(result);
				//alert(JSON.stringify(result, null, 4));
				jQuery('#loading').delay(1).fadeOut();
				//alert(result);
			}
		});
		
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
                $(".searchItems").append("<li data-parent="+ changedSelect.attr("id") +" class='"+ changedSelect.attr("id") +" tk-proxima-nova'><span class='glyphicon glyphicon-remove-circle'></span>"+ label.text() +": "+ changedSelect.val() +"</li>");
                }
                
            });

            $(".search input[type='text']").focusout(function(){
                var changedSelect = $(this);
                var label = $(this).siblings("label");
                $(".searchItems").find("." + changedSelect.attr("id")).remove().delay("1000");
                if($(this).val() != ""){
                $(".searchItems").delay(100).queue(function(next){
                    $(this).append("<li data-parent="+ changedSelect.attr("id") +" class='"+ changedSelect.attr("id") +" tk-proxima-nova'><span class='glyphicon glyphicon-remove-circle'></span>"+ label.text() +": "+ changedSelect.val() +"</li>");
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

		var birth_date = data[rowName]['BirthDate'];
		var getSport = getSport(data[rowName]['GameType']);
		var batSide = getBattingSide(data[rowName]['Bats']);
		var throwSide = getBattingSide(data[rowName]['Throws']);
		var playerPosition = getPosition(data[rowName]['Position']);
		
		html += '<tr>';
			html += '<td>' + data[rowName]['FirstName'] + ' ' + data[rowName]['LastName'] + ' <span class="glyphicon glyphicon-menu-right pull-right"></span></td>';
			html += '<td class="">' + getSport + '</td>';
			html += '<td class="">' + birth_date + '</td>';
			html += '<td class="">' + playerPosition + '</td>';
			html += '<td class="">' + batSide + '</td>';
			html += '<td class="">' + throwSide + '</td>';
			html += '<td class="">' + data[rowName]['Height'] + '</td>';
			html += '<td class="">' + data[rowName]['Weight'] + '</td>';
			html += '</tr>';
			
		count += 1;
	});
	
	
	
	jQuery('#results_spot').val(count);

	return html;
}

function getSport(passed_value){
	
	var sportPlayed = '';
	
	if(passed_value == '0'){ sportPlayed = "Baseball"; }
	if(passed_value == '1'){ sportPlayed = "Softball"; }
	if(sportPlayed == ''){ sportPlayed = "Not Set"; }
	
	return sportPlayed;	
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
	if(passed_value == '7'){ playerPosition = "OF"; }
	if(playerPosition == ''){ playerPosition = "Not Set"; }
	
	return playerPosition;	
}