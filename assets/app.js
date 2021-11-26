/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');
global.$ = global.jQuery = $;

require('bootstrap');
require('./chart.min.js');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

function getResults(myChart) {
    
    $.ajax({        
        type: 'GET',
        url: '/results',
        dataType: 'json',
        success: function(data) {                          

            let results = [];                        
            results = data['results'];              
            chartUpdate(myChart, results);            
       
        }
    });

}

function chartUpdate(myChart, results) {

    let framNames = [];
    let framValues = []

    $.each(results, function(){        
        framNames.push(this.label);
        framValues.push(this.value);
    });    

    myChart.data.labels = framNames;
    myChart.data.datasets.forEach((dataset) => {
        dataset.data = framValues;
    });
    myChart.update();

}

$(document).ready(function() {    

    $( "#vote-form" ).on( "submit", function(e) { 
        e.preventDefault();        

        var data = $(this).serialize();        
                
        $.ajax({
            data: data,
            type: 'POST',
            url: '/submit',
            dataType: 'json',
            success: function(data) {                          

                if(data['error']==1) {
                    $('#messages').addClass('messageError').html(data['message']);
                } else {
                    $('#messages').addClass('messageOK').html(data['message']);
                    $('#vote-form')[0].reset();
                }

                setTimeout(function(){                    
                    $('#messages').removeClass('messageError').removeClass('messageOK').html('');                                        
                }, 3000); 
            }
        });
        
    });

    const ctx = document.getElementById('myChart');
    const myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                label: '# of Votes',
                data: [],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'                
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var intervalId = window.setInterval(function(){
        getResults(myChart);   
    }, 1000);

});