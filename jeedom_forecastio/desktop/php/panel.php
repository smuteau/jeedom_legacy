<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>

<script src="plugins/forecastio/desktop/js/skycons.js"></script>

<link rel="stylesheet" type="text/css" href="plugins/forecastio/desktop/weather-icons/css/weather-icons.min.css" />

<div class="container-fluid forecastio-panel">
	<div class="row">
		<div class="col-md-12">
			<div class="btn-group">

				<?php
				$first = 0;
				$eqLogics = forecastio::byType('forecastio', true);
				foreach ($eqLogics as $forecastio) {
					if ($first == 0 ) {
						$selected = $forecastio->getId();
						$first = 1;
					}
					echo '<button class="btn btn-default forecastioEqlogic" id="' . $forecastio->getId() . '" type="button" onClick="loadingData(' . $forecastio->getId() . ')">' . $forecastio->getName() . '</button>';
				}
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<center><strong> Actuellement </strong></center></br>
			<div style="position : relative; left : 15px;">
				<span class="pull-left">
					<canvas id="icone-status" width="56" height="56"></canvas>
				</span>

				<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
					<div id="wind-status" style="width: 80px; height: 80px;"></div>
					<center><i class="wi wi-strong-wind"></i><div class="weather-status" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
				</div>
				<i class="jeedom-thermo-moyen"></i><span class="weather-status" data-l1key="temperature" style="margin-left: 5px;">   </span><span class="weather-status" data-l1key="apparentTemperature" style="margin-left: 5px;font-size: 0.8em;"> </span><br/>
				<span class="weather-status" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
			</br>
			<i class="wi wi-humidity"></i><span class="weather-status" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-status" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-status" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
			<i class="wi wi-barometer"></i><span class="weather-status" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-status" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

		</div>

	</div>
	<div class="col-md-4">
		<center><strong> Dans 1H </strong></center></br>
		<div style="position : relative; left : 15px;">
			<span class="pull-left">
				<canvas id="icone-hour" width="56" height="56"></canvas>
			</span>

			<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
				<div id="wind-hour" style="width: 80px; height: 80px;"></div>
				<center><i class="wi wi-strong-wind"></i><div class="weather-hour" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
			</div>
			<i class="jeedom-thermo-moyen"></i><span class="weather-hour" data-l1key="temperature" style="margin-left: 5px;">   </span><span class="weather-hour" data-l1key="apparentTemperature" style="margin-left: 5px;font-size: 0.8em;"> </span><br/>
			<span class="weather-hour" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
		</br>
		<i class="wi wi-humidity"></i><span class="weather-hour" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-hour" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-hour" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
		<i class="wi wi-barometer"></i><span class="weather-hour" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-hour" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	</div>
</div>
<div class="col-md-4">
	<center><strong> Aujourd'hui </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<canvas id="icone-day0" width="56" height="56"></canvas>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day0" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day0" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day0" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day0" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day0" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day0" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day0" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day0" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day0" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-day0" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day0" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day0" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
</div>
</br>
<div class="row">
	<div class="col-md-12">
		<div id="previsions">

		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<center><strong> Jour +1 </strong></center></br>
		<div style="position : relative; left : 15px;">
			<span class="pull-left">
				<canvas id="icone-day1" width="56" height="56"></canvas>
			</span>

			<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
				<div id="wind-day1" style="width: 80px; height: 80px;"></div>
				<center><i class="wi wi-strong-wind"></i><div class="weather-day1" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
			</div>
			<i class="jeedom-thermo-moyen"></i><span class="weather-day1" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day1" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
			<span class="weather-day1" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
		</br>
		<i class="wi wi-humidity"></i><span class="weather-day1" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day1" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day1" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
		<i class="wi wi-barometer"></i><span class="weather-day1" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-day1" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

		<div>
			<i class="wi wi-sunrise"></i><span class="weather-day1" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day1" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
		</div>
	</div>
</div>
<div class="col-md-4">
	<center><strong> Jour +2 </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<canvas id="icone-day2" width="56" height="56"></canvas>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day2" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day2" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day2" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day2" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day2" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day2" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day2" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day2" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day2" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-day2" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day2" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day2" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
<div class="col-md-4">
	<center><strong> Jour +3 </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<canvas id="icone-day3" width="56" height="56"></canvas>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day3" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day3" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day3" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day3" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day3" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day3" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day3" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day3" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day3" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-day3" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day3" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day3" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
</div>
</br>
<div class="row">
	<div class="col-md-4">
		<center><strong> Jour +4 </strong></center></br>
		<div style="position : relative; left : 15px;">
			<span class="pull-left">
				<canvas id="icone-day4" width="56" height="56"></canvas>
			</span>

			<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
				<div id="wind-day4" style="width: 80px; height: 80px;"></div>
				<center><i class="wi wi-strong-wind"></i><div class="weather-day4" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
			</div>
			<i class="jeedom-thermo-moyen"></i><span class="weather-day4" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day4" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
			<span class="weather-day4" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
		</br>
		<i class="wi wi-humidity"></i><span class="weather-day4" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day4" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day4" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
		<i class="wi wi-barometer"></i><span class="weather-day4" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-day4" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

		<div>
			<i class="wi wi-sunrise"></i><span class="weather-day4" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day4" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
		</div>
	</div>
</div>
<div class="col-md-4">
	<center><strong> Jour +5 </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<canvas id="icone-day5" width="56" height="56"></canvas>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day5" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day5" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day5" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day5" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day5" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day5" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day5" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day5" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day5" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-day5" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day5" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day5" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
<div class="col-md-4">
	<center><strong> Jour +6 </strong></center></br>
	<div style="position : relative; left : 15px;">
		<span class="pull-left">
			<canvas id="icone-day6" width="56" height="56"></canvas>
		</span>

		<div class="pull-right" style="margin-right: 20px;margin-top: 0px;">
			<div id="wind-day6" style="width: 80px; height: 80px;"></div>
			<center><i class="wi wi-strong-wind"></i><div class="weather-day6" data-l1key="windSpeed" style="margin-left: 5px;font-size: 0.8em;"></div></center>
		</div>
		<i class="jeedom-thermo-moyen"></i><span class="weather-day6" data-l1key="temperatureMin" style="margin-left: 5px;">   </span> / <span class="weather-day6" data-l1key="temperatureMax" style="margin-left: 5px;"> </span><br/>
		<span class="weather-day6" data-l1key="summary" style="margin-left: 5px;">   </span><br/>
	</br>
	<i class="wi wi-humidity"></i><span class="weather-day6" data-l1key="humidity" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-cloud"></i><span class="weather-day6" data-l1key="cloudCover" style="margin-left: 5px;font-size: 0.8em;">  </span><i class="wi wi-umbrella"></i><span class="weather-day6" data-l1key="precipProbability" style="margin-left: 5px;font-size: 0.8em;">  </span><br/>
	<i class="wi wi-barometer"></i><span class="weather-day6" data-l1key="pressure" style="margin-left: 5px;font-size: 0.8em;">  </span> <i class="fa fa-flask"></i> <span class="weather-day6" data-l1key="ozone" style="margin-left: 5px;font-size: 0.8em;">   </span>

	<div>
		<i class="wi wi-sunrise"></i><span class="weather-day6" data-l1key="sunriseTime" style="font-size: 0.8em;"></span><i class="wi wi-sunset"></i><span class="weather-day6" data-l1key="sunsetTime" style="font-size: 0.8em;"></span>
	</div>
</div>
</div>
</div>
<div class="row">
	<div class="col-md-12">
	</div>
</div>
</div>

<script>
$(function () {

	loadingData(
		<?php
		echo $selected;
		?>);

	});

	function loadingData(eqLogic){

		$.ajax({// fonction permettant de faire de l'ajax
		type: "POST", // methode de transmission des données au fichier php
		url: "plugins/forecastio/core/ajax/forecastio.ajax.php", // url du fichier php
		data: {
			action: "loadingData",
			value: eqLogic,
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) { // si l'appel a bien fonctionné
		if (data.state != 'ok') {
			$('#div_alert').showAlert({message: data.result, level: 'danger'});
			return;
		}

		$('.forecastioEqlogic').removeClass('btn-success');
		$('.forecastioEqlogic').addClass('btn-default');
		$('#' + eqLogic).removeClass('btn-default');
		$('#' + eqLogic).addClass('btn-success');

		$('.weather-status').value('');
		for (var i in data.result.status) {
			$('.weather-status[data-l1key=' + i + ']').value(data.result.status[i]);
		}
		$('.weather-hour').value('');
		for (var i in data.result.hour) {
			$('.weather-hour[data-l1key=' + i + ']').value(data.result.hour[i]);
		}
		$('.weather-day0').value('');
		for (var i in data.result.day0) {
			$('.weather-day0[data-l1key=' + i + ']').value(data.result.day0[i]);
		}
		$('.weather-day1').value('');
		for (var i in data.result.day1) {
			$('.weather-day1[data-l1key=' + i + ']').value(data.result.day1[i]);
		}
		$('.weather-day2').value('');
		for (var i in data.result.day2) {
			$('.weather-day2[data-l1key=' + i + ']').value(data.result.day2[i]);
		}
		$('.weather-day3').value('');
		for (var i in data.result.day3) {
			$('.weather-day3[data-l1key=' + i + ']').value(data.result.day3[i]);
		}
		$('.weather-day4').value('');
		for (var i in data.result.day4) {
			$('.weather-day4[data-l1key=' + i + ']').value(data.result.day4[i]);
		}
		$('.weather-day5').value('');
		for (var i in data.result.day5) {
			$('.weather-day5[data-l1key=' + i + ']').value(data.result.day5[i]);
		}
		$('.weather-day6').value('');
		for (var i in data.result.day6) {
			$('.weather-day6[data-l1key=' + i + ']').value(data.result.day6[i]);
		}

		var skycons = new Skycons({'color':'black'});
		skycons.set('icone-status', data.result.status.icon);
		skycons.set('icone-hour', data.result.hour.icon);
		skycons.set('icone-day0', data.result.day0.icon);
		skycons.set('icone-day1', data.result.day1.icon);
		skycons.set('icone-day2', data.result.day2.icon);
		skycons.set('icone-day3', data.result.day3.icon);
		skycons.set('icone-day4', data.result.day4.icon);
		skycons.set('icone-day5', data.result.day5.icon);
		skycons.set('icone-day6', data.result.day6.icon);
		skycons.play();

		roseTrace('wind-status',data.result.status.windBearing);
		roseTrace('wind-hour',data.result.hour.windBearing);
		roseTrace('wind-day0',data.result.day0.windBearing);
		roseTrace('wind-day1',data.result.day1.windBearing);
		roseTrace('wind-day2',data.result.day2.windBearing);
		roseTrace('wind-day3',data.result.day3.windBearing);
		roseTrace('wind-day4',data.result.day4.windBearing);
		roseTrace('wind-day5',data.result.day5.windBearing);
		roseTrace('wind-day6',data.result.day6.windBearing);

		//console.log(data.result.temp.value);

		var options = {
			title : {	text : 'Prévisions'	},
			subtitle: {
				text: 'Température, pression et précipitation des 48h',
				x: -20
			},
			chart: { renderTo: 'previsions' },
			xAxis: {
				type: 'datetime',
			},

			yAxis: [{ // temperature axis
				title: {
					text: 'Température (°C)'
				},
			}, { // precipitation axis
				title: {
					text: null
				},
				labels: {
					enabled: false
				},
				gridLineWidth: 0,
				tickLength: 0
			}, { // Air pressure
				allowDecimals: false,
				title: { // Title on top of axis
					text: 'Pression (hPa)',
				},
				gridLineWidth: 0,
				opposite: true,
				showLastLabel: false
			}],
			credits: {
				enabled: false
			},
			xAxis: {
				categories: [],
				labels: {
					rotation: -45,
					y: 20
				}
			},
			series: [{
				name: 'Température',
				tooltip: {
					valueSuffix: ' °C'
				},
				color: '#FF3333',
				negativeColor: '#48AFE8',
				data: []
			},
			{
				name: 'Précipitations',
				type: 'column',
				color: '#68CFE8',
				yAxis: 1,
				tooltip: {
					valueSuffix: ' mn/h'
				},
				data: []
			},
			{
				name: 'Pression',
				yAxis: 2,
				tooltip: {
					valueSuffix: ' hPa'
				},
				data: []
			}
			],
		};

		for (var i in data.result.previsions.time) {
			//console.log(data.result.previsions.temperature[i]);
			var date = new Date(parseInt(data.result.previsions.time[i]));
			var displayDate = date.getDate() + '/' + (date.getMonth()+1) + ' ' + date.getHours() + ':' + date.getMinutes();
			options.series[0].data.push(parseFloat(data.result.previsions.temperature[i],2));
			options.series[1].data.push(parseFloat(data.result.previsions.precipIntensity[i],2));
			options.series[2].data.push(parseInt(data.result.previsions.pressure[i]));
			options.xAxis.categories.push(displayDate);

		};

		var chart = new Highcharts.Chart(options);

	}
});
}

function roseTrace(id,value){
	new Highcharts.Chart({
		chart: {
			renderTo: id,
			type: 'gauge',
			backgroundColor: 'transparent',
			plotBackgroundColor: null,
			plotBackgroundImage: null,
			plotBorderWidth: 0,
			plotShadow: false,
			spacingTop: 0,
			spacingLeft: 0,
			spacingRight: 0,
			spacingBottom: 0
		},
		title: {
			text: null
		},
		credits: {
			enabled: false
		},
		pane: {
			startAngle: 0,
			endAngle: 360,
		},
		exporting : {
			enabled: false
		},
		plotOptions: {
			series: {
				dataLabels: {
					enabled: false
				},
				color: '#000000',
			},
			gauge: {
				dial: {
					radius: '90%',
					backgroundColor: 'silver',
					borderColor: 'silver',
					borderWidth: 1,
					baseWidth: 6,
					topWidth: 1,
					baseLength: '75%', // of radius
					rearLength: '15%'
				},
				pivot: {
					backgroundColor: 'white',
					radius: 0,
				}
			}
		},
		pane: {background: [{backgroundColor: 'transparent'}]},
		yAxis: {
			min: 0,
			max: 360,
			tickWidth: 2,
			tickLength: 10,
			tickColor: '#000000',
			tickInterval: 90,
			lineColor: '#000000',
			lineWidth: 4,
			labels: {
				formatter: function () {
					if (this.value == 360) {
						return '<span style="color : #000000;font-weight:bold;">N</span>';
					} else if (this.value == 90) {
						return '<span style="color : #000000;font-weight:bold;">E</span>';
					} else if (this.value == 180) {
						return '<span style="color : #000000;font-weight:bold;">S</span>';
					} else if (this.value == 270) {
						return '<span style="color : #000000;font-weight:bold;">W</span>';
					}
				}
			},
			title: {
				text: null
			}},
			series: [{
				name: 'Vent',
				data: [value]
			}]
		});

	}



	</script>
