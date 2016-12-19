<?php
include(getcwd() . "/api/centralDB.php");
?> 
<nav>
            <div class="container">
                <ul class="row clearfix">
                    <li data-tab="playerDetail" class="tab col-sm-4 col-xs-12"><a href="#">Player Details<span class="pull-right"><img src="<?php echo get_bloginfo("template_url") ;?>/images/tab-arrow.png" alt=""></span></a></li>
                    <li data-tab="hittraxMetrics" class="tab col-sm-4 col-xs-12"><a href="#">HitTrax Metrics<span class="pull-right"><img src="<?php echo get_bloginfo("template_url") ;?>/images/tab-arrow.png" alt=""></span></a></li>
                    <li data-tab="scoutingReports" class="tab col-sm-4 col-xs-12"><a href="#">Scouting Report<span class="pull-right"><img src="<?php echo get_bloginfo("template_url") ;?>/images/tab-arrow.png" alt=""></span></a></li>
                </ul>
            </div>
        </nav>
		<?php
		$num_results = 0;
		?>
		<form id="searchEngineParameters">
        <div class="search">
            <div class="container">
                <!-- PLAYER DETAIL BEGIN -->
                <div class="row playerDetail">
                    <div class="col-sm-3">
                        <div class="searchElement">
                            <label for="textboxName" class="tk-proxima-nova">Player Name</label>
                            <input name="textboxName" id="textboxName" type="text" class="searchTextbox">
                        </div>
                        <div class="searchElement">
                            <label for="selectSport" class="tk-proxima-nova">Sport</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectSport" id="selectSport" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="Baseball">Baseball</option>
                            <option value="Softball">Softball</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectState" class="tk-proxima-nova">State</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectState" id="selectState" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="AL">Alabama</option>
								<option value="AK">Alaska</option>
								<option value="AZ">Arizona</option>
								<option value="AR">Arkansas</option>
								<option value="CA">California</option>
								<option value="CO">Colorado</option>
								<option value="CT">Connecticut</option>
								<option value="DE">Delaware</option>
								<option value="DC">District Of Columbia</option>
								<option value="FL">Florida</option>
								<option value="GA">Georgia</option>
								<option value="HI">Hawaii</option>
								<option value="ID">Idaho</option>
								<option value="IL">Illinois</option>
								<option value="IN">Indiana</option>
								<option value="IA">Iowa</option>
								<option value="KS">Kansas</option>
								<option value="KY">Kentucky</option>
								<option value="LA">Louisiana</option>
								<option value="ME">Maine</option>
								<option value="MD">Maryland</option>
								<option value="MA">Massachusetts</option>
								<option value="MI">Michigan</option>
								<option value="MN">Minnesota</option>
								<option value="MS">Mississippi</option>
								<option value="MO">Missouri</option>
								<option value="MT">Montana</option>
								<option value="NE">Nebraska</option>
								<option value="NV">Nevada</option>
								<option value="NH">New Hampshire</option>
								<option value="NJ">New Jersey</option>
								<option value="NM">New Mexico</option>
								<option value="NY">New York</option>
								<option value="NC">North Carolina</option>
								<option value="ND">North Dakota</option>
								<option value="OH">Ohio</option>
								<option value="OK">Oklahoma</option>
								<option value="OR">Oregon</option>
								<option value="PA">Pennsylvania</option>
								<option value="RI">Rhode Island</option>
								<option value="SC">South Carolina</option>
								<option value="SD">South Dakota</option>
								<option value="TN">Tennessee</option>
								<option value="TX">Texas</option>
								<option value="UT">Utah</option>
								<option value="VT">Vermont</option>
								<option value="VA">Virginia</option>
								<option value="WA">Washington</option>
								<option value="WV">West Virginia</option>
								<option value="WI">Wisconsin</option>
								<option value="WY">Wyoming</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="searchElement">
                            <label for="selectRegion" class="tk-proxima-nova">Region</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectRegion" id="selectRegion" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="All">All</option>
                            <option value="Central">Central</option>
                            <option value="Latin America">Latin America</option>
                            <option value="Mid West">Mid West</option>
                            <option value="North East">North East</option>
                            <option value="South East">South East</option>
                            <option value="West">West</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectAge" class="tk-proxima-nova">Age</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectAge" id="selectAge" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="8U">8U</option>
							<option value="10U">10U</option>
							<option value="12U">12U</option>
							<option value="13U">13U</option>
							<option value="15U">15U</option>
							<option value="16U">16U</option>
							<option value="18U">18U</option>
							<option value="20U">20U</option>
							<option value="23U">23U</option>
							<option value="24+">24+</option>
							<option value="30+">30+</option>
							<option value="40+">40+</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectGraduation" class="tk-proxima-nova">Graduation Year</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectGraduation" id="selectGraduation" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="searchElement">
                            <label for="selectGpa" class="tk-proxima-nova">GPA</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectGpa" id="selectGpa" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="4.0">4.0</option>
                            <option value=">3.0">>3.0</option>
                            <option value=">2.0">>2.0</option>
                            
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectHeight" class="tk-proxima-nova">Height</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectHeight" id="selectHeight" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="5 ft 4 in">5'4"</option>
                            <option value="5 ft 5 in">5'5"</option>
                            <option value="5 ft 6 in">5'6"</option>
                            <option value="5 ft 7 in">5'7"</option>
                            <option value="5 ft 8 in">5'8"</option>
                            <option value="5 ft 9 in">5'9"</option>
                            <option value="5 ft 10 in">5'10"</option>
                            <option value="5 ft 11 in">5'11"</option>
                            <option value="6 ft">6'0"</option>
                            <option value="6 ft 1 in">6'1"</option>
                            <option value="6 ft 2 in">6'2"</option>
                            <option value="6 ft 3 in">6'3"</option>
                            <option value="6 ft 4 in">6'4"</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectWeight" class="tk-proxima-nova">Weight</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectWeight" id="selectWeight" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="120 - 130 lbs">120 - 130 lbs</option>
                            <option value="130 - 140 lbs">130 - 140 lbs</option>
                            <option value="140 - 150 lbs">140 - 150 lbs</option>
                            <option value="150 - 160 lbs">150 - 160 lbs</option>
                            <option value="160 - 170 lbs">160 - 170 lbs</option>
                            <option value="170 - 180 lbs">170 - 180 lbs</option>
                            <option value="180 - 190 lbs">180 - 190 lbs</option>
                            <option value="190 - 200 lbs">190 - 200 lbs</option>
                            <option value="200 - 210 lbs">200 - 210 lbs</option>
                            <option value="210 - 220 lbs">210 - 220 lbs</option>
                            <option value="220 - 230 lbs">220 - 230 lbs</option>
                            <option value="230 - 240 lbs">230 - 240 lbs</option>
                            <option value="240 - 250 lbs">240 - 250 lbs</option>
                            <option value="250 - 260 lbs">250 - 260 lbs</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="searchElement">
                            <label for="selectPosition" class="tk-proxima-nova">Position</label>                        
                            <div class="overlay-arrow">▼</div>
                            <select name="selectPosition" id="selectPosition" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="C">Catcher</option>
                            <option value="1B">First Base</option>
                            <option value="2B">Second Base</option>
                            <option value="SS">Shortstop</option>
                            <option value="3B">Third Base</option>
                            <option value="OF">Outfield</option>
                            <option value="P">Pitcher</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectBats" class="tk-proxima-nova">Bats (L/R/S)</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectBats" id="selectBats" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="L">Bats L</option>
                            <option value="R">Bats R</option>
                            <option value="S">Bats S</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectThrows" class="tk-proxima-nova">Throws (L/R)</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectThrows" id="selectThrows" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="L">Throws L</option>
                            <option value="R">Throws R</option>
                            </select>
                        </div>
                    </div>
                </div> 
                <!-- PLAYER DETAIL END -->  
                <!-- HITRAX METRICS BEGIN -->
                <div class="row hittraxMetrics">
                    <div class="col-sm-2">
                        <strong>HITTING</strong>
                        <div class="searchElement">
                            <label for="selectMaxExit" class="tk-proxima-nova">Max Exit Velocity ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectMaxExit" id="selectMaxExit" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
							<option value="120 mph">120 mph</option>
							<option value="110 mph">110 mph</option>
							<option value="100 mph">100 mph</option>
                            <option value="90 mph">90 mph</option>
                            <option value="80 mph">80 mph</option>
                            <option value="70 mph">70 mph</option>
                            <option value="60 mph">60 mph</option>
                            <option value="50 mph">50 mph</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectMaxDistance" class="tk-proxima-nova">Max Distance ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectMaxDistance" id="selectMaxDistance" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="600ft">600ft</option>
                            <option value="500ft">500ft</option>
                            <option value="400ft">400ft</option>
                            <option value="350ft">350ft</option>
                            <option value="300ft">300ft</option>
                            <option value="250ft">250ft</option>
                            <option value="200ft">200ft</option>
                            <option value="150ft">150ft</option>
                            <option value="100ft">100ft</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectLaunchAngleGreater" class="tk-proxima-nova">Launch Angle ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectLaunchAngleGreater" id="selectLaunchAngleGreater" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="60%">60%</option>
                            <option value="50%">50%</option>
                            <option value="40%">40%</option>
                            <option value="30%">30%</option>
                            <option value="20%">20%</option>
                            <option value="10%">10%</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectPitchPlateSpeedGreater" class="tk-proxima-nova">Pitch Plate Speed ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectPitchPlateSpeedGreater" id="selectPitchPlateSpeedGreater" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <strong>&nbsp;</strong>
                        <div class="searchElement">
                            <label for="selectAvgExitVelGreater" class="tk-proxima-nova">Avg. Exit Velocity ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectAvgExitVelGreater" id="selectAvgExitVelGreater" class="tk-proxima-nova searchSelect">
                            	<option value=""></option>
								<option value="100 mph">100 mph</option>
	                            <option value="90 mph">90 mph</option>
	                            <option value="80 mph">80 mph</option>
	                            <option value="70 mph">70 mph</option>
	                            <option value="60 mph">60 mph</option>
	                            <option value="50 mph">50 mph</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectAvgDistanceGreater" class="tk-proxima-nova">Avg. Distance ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectAvgDistanceGreater" id="selectAvgDistanceGreater" class="tk-proxima-nova searchSelect">
 	                            <option value=""></option>
	                            <option value="400ft">400ft</option>
	                            <option value="350ft">350ft</option>
	                            <option value="300ft">300ft</option>
	                            <option value="250ft">250ft</option>
	                            <option value="200ft">200ft</option>
	                            <option value="150ft">150ft</option>
	                            <option value="100ft">100ft</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectLaunchAngleLess" class="tk-proxima-nova">Launch Angle ≤&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectLaunchAngleLess" id="selectLaunchAngleLess" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="60%">60%</option>
                            <option value="50%">50%</option>
                            <option value="40%">40%</option>
                            <option value="30%">30%</option>
                            <option value="20%">20%</option>
                            <option value="10%">10%</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectPitchPlateSpeedLess" class="tk-proxima-nova">Pitch Plate Speed ≤&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectPitchPlateSpeedLess" id="selectPitchPlateSpeedLess" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <strong>&nbsp;</strong>
                        <div class="searchElement">
                            <label for="selectLineDrive" class="tk-proxima-nova">Line Drive % ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectLineDrive" id="selectLineDrive" class="tk-proxima-nova searchSelect">
  	                            <option value=""></option>
	                            <option value="90%">90%</option>
	                            <option value="80%">80%</option>
	                            <option value="70%">70%</option>
	                            <option value="60%">60%</option>
	                            <option value="50%">50%</option>
	                            <option value="40%">40%</option>
	                            <option value="30%">30%</option>
	                            <option value="20%">20%</option>
	                            <option value="10%">10%</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectGroundBall" class="tk-proxima-nova">Ground Ball % ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectGroundBall" id="selectGroundBall" class="tk-proxima-nova searchSelect">
	                            <option value=""></option>
	                            <option value="90%">90%</option>
	                            <option value="80%">80%</option>
	                            <option value="70%">70%</option>
	                            <option value="60%">60%</option>
	                            <option value="50%">50%</option>
	                            <option value="40%">40%</option>
	                            <option value="30%">30%</option>
	                            <option value="20%">20%</option>
	                            <option value="10%">10%</option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectFlyBall" class="tk-proxima-nova">Fly Ball % ≥&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectFlyBall" id="selectFlyBall" class="tk-proxima-nova searchSelect">
  	                            <option value=""></option>
	                            <option value="90%">90%</option>
	                            <option value="80%">80%</option>
	                            <option value="70%">70%</option>
	                            <option value="60%">60%</option>
	                            <option value="50%">50%</option>
	                            <option value="40%">40%</option>
	                            <option value="30%">30%</option>
	                            <option value="20%">20%</option>
	                            <option value="10%">10%</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 vertical-divider "><img src="<?php echo get_bloginfo("template_url") ;?>/images/search-vertical-divider.png" class="hidden-xs" /></div>
                    <div class="col-sm-2">
                        <strong>PITCHING</strong>
                        <div class="searchElement">
                            <label for="selectStrike" class="tk-proxima-nova">Strike %&nbsp;</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectStrike" id="selectStrike" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="40%">40%</option>
                            <option value="50%">50%</option>
                            <option value="60%">60%</option>
                            <option value="70%">70%</option>
                            <option value="80%">80%</option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectPlateSpeed" class="tk-proxima-nova">Plate Speed</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectPlateSpeed" id="selectPlateSpeed" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <!--<div class="searchElement">
                            <label for="" class="tk-proxima-nova">Search Field Five</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="" id="selectFieldFive" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>-->
                    </div>
                    <div class="col-sm-2">
                        <strong>&nbsp;</strong>
                        <div class="searchElement">
                            <label for="selectPitchType" class="tk-proxima-nova">Pitch Type</label>                        
                            <div class="overlay-arrow">▼</div>
                            <select name="selectPitchType" id="selectPitchType" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="cvcvc">cvcvc</option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectOppAvgExitVeloticy" class="tk-proxima-nova">Opp. Avg. Exit Velocity</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectOppAvgExitVeloticy" id="selectOppAvgExitVeloticy" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <!--<div class="searchElement">
                            <label for="" class="tk-proxima-nova">Search Field Six</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="" id="selectSearchFieldSix" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>-->
                    </div>
                </div> 
                <!-- HITTRAX METRICS END -->  
                <!-- SCOUTING REPORTS BEGIN -->
                <div class="row scoutingReports">
                    <div class="col-sm-3">
                        <strong>SWING</strong>
                        <div class="searchElement">
                            <label for="selectEffIndex" class="tk-proxima-nova">Efficiency Index</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectEffIndex" id="selectEffIndex" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectPowerIndex" class="tk-proxima-nova">Power Index</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectPowerIndex" id="selectPowerIndex" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectSwingSpeed" class="tk-proxima-nova">Swing Speed</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectSwingSpeed" id="selectSwingSpeed" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectTimeToContact" class="tk-proxima-nova">Time to Contact</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectTimeToContact" id="selectTimeToContact" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <strong>FIELDING</strong>
                        <div class="searchElement">
                            <label for="selectMaxThrowVelocity" class="tk-proxima-nova">Max Throw Velocity</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectMaxThrowVelocity" id="selectMaxThrowVelocity" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="cvcvc">cvcvc</option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectAverageThrowVelocity" class="tk-proxima-nova">Average Throw Velocity</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectAverageThrowVelocity" id="selectAverageThrowVelocity" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectThrowAccuracy" class="tk-proxima-nova">Throw Accuracy</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectThrowAccuracy" id="selectThrowAccuracy" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <!--<div class="searchElement">
                            <label for="" class="tk-proxima-nova">Search Field</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="" id="selectScoutingSearchField1" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>-->
                    </div>
                    <div class="col-sm-3">
                        <strong>AGILITY</strong>
                        <div class="searchElement">
                            <label for="select60YardDash" class="tk-proxima-nova">60 Yard Dash</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="select60YardDash" id="select60YardDash" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value="cvcvc">cvcvc</option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectHomeToFirst" class="tk-proxima-nova">Home to First</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectHomeToFirst" id="selectHomeToFirst" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="select10YardDash" class="tk-proxima-nova">10 Yard Dash</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="select10YardDash" id="select10YardDash" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="selectShuttle" class="tk-proxima-nova">5-10-5 Shuttle</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="selectShuttle" id="selectShuttle" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <!--<div class="col-sm-3">
                        <strong>COLUMN 4</strong>
                        <div class="searchElement">
                            <label for="" class="tk-proxima-nova">Search Field</label>                        
                            <div class="overlay-arrow">▼</div>
                            <select name="" id="selectScoutingSearchField2" class="tk-proxima-nova searchSelect">
                            <option value="dfdfd">dfdfd</option>
                            <option value="cvcvc">cvcvc</option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="" class="tk-proxima-nova">Search Field</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="" id="selectScoutingSearchField3" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="" class="tk-proxima-nova">Search Field</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="" id="selectScoutingSearchField4" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                        <div class="searchElement">
                            <label for="" class="tk-proxima-nova">Search Field</label>
                            <div class="overlay-arrow">▼</div>
                            <select name="" id="selectScoutingSearchField5" class="tk-proxima-nova searchSelect">
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                            </select>
                        </div>
                    </div>-->
                </div> 
                <!-- SCOUTING REPORTS END -->  
            </div>
			</form>
        </div>
    </header>
    <section class="searchCriteria">
        <div class="container">
            <div class="row">
                <div class="col-md-8  col-xs-12 pull-left searchAction">
                    <ul class="searchNav clearfix">
                        <li>
                            <h3>YOUR SEARCH</h3>
                        </li>
                        <li><a class="active tk-proxima-nova" href="#"><span id='results_spot'><?php echo($num_results); ?></span> Results</a></li>
                        <li><a data-toggle="modal" href="#searchSave-modal">Save This Search</a></li>
                        <li><a href="javascript:void(0);" id="start_over_link">Start Over</a></li>
                    </ul>
                    <ul class="searchItems clearfix">
                        
                        
                    </ul>
                </div>
				<?php
				$mid = get_user_meta(get_current_user_id(), 'MasterID', true);
				//$mid = '21535:133';
				?>
				<input type='hidden' name='MasterID' id='MasterID' value='<?php echo($mid); ?>' />
                <div class="col-md-3  col-xs-12 pull-right">
                    <input type="button" class="button" value="search now" name="submit_search">
                    <span class="glyphicon glyphicon-search"></span>
                    <div class="savedSearchSelect">
                        <div class="overlay-arrow">▼</div>
                        <select name="savedSearch" id="savedSearch" class="searchSelect">
                            <option value="">SAVED SEARCH</option>
							<?php
							// Pull back all saved searches for this user. 
							$sql = "SELECT * FROM SavedRecruitingSearches WHERE MasterID = '$mid'";
							
							$result = mysqli_query($dbConnection,$sql);

							while ($row = mysqli_fetch_assoc($result)) {
								echo("<option value='".$row['id']."'>".$row['SearchName']."</option>");
							}
							?>
                        </select>
                    </div>
                    <br />
                </div>
            </div>
        </div>
    </section>
    <br />