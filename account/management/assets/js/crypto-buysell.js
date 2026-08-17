$(function(){
    'use strict'

  /*LIne-Chart */
	var ctx = document.getElementById("earningbit").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: ["SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT"],
			datasets: [{
				label: 'Buy',
				data: [420, 380, 610, 454, 530, 420, 680],
				borderWidth: 2,
				backgroundColor: 'transparent',
				borderColor: '#4d65d9',
				borderWidth: 3,
				pointBackgroundColor: '#ffffff',
				pointRadius: 2,
				lineTension: 0.3,
			},
      {
				label: 'Sell',
				data: [520, 450, 550, 424, 580, 470, 640],
				borderWidth: 2,
				backgroundColor: 'transparent',
				borderColor: '#afbaef',
				borderWidth: 3,
				pointBackgroundColor: '#ffffff',
        		borderDash: [5,5],
				pointRadius: 2,
				lineTension: 0.3,
			}
    ]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,

			scales: {
				x: {
					ticks: {
						fontColor: "#8492a6",
						fontFamily: 'Roboto',
					 },
					display: true,
					gridLines: {
						color: 'rgba(132, 146, 166, 0.2)'
					}
				},
				y: {
					ticks: {
						fontColor: "#8492a6",
						fontFamily: 'Roboto',
					 },
					display: true,
					gridLines: {
						color: 'rgba(132, 146, 166, 0.2)'
					},
					scaleLabel: {
						display: false,
						labelString: 'Thousands',
						fontColor: 'rgba(132, 146, 166, 0.2)'
					}
				}
			},
			legend: {
				labels: {
					fontColor: "#8492a6"
				},
			},
		}
	});

   /*LIne-Chart */


	function iformat(icon) {
		var originalOption = icon.element;
		return $('<span><i class="fa ' + $(originalOption).data('icon') + '"></i> ' + icon.text + '</span>');
	}
	$('.icons_select2').select2({
		width: "100%",
		templateSelection: iformat,
		templateResult: iformat,
		allowHtml: true
	});

	$('.select2').on('click', () => {
		let selectField = document.querySelectorAll('.select2-search__field')
		selectField.forEach((element,index)=>{
			element.focus();
		})
	});

});


