$(document).ready(function() {
    function loadPlanning() {
        $.ajax({
            url: '/api/planning',
            method: 'GET',
            success: function(data) {
                let planningTable = '<table class="table table-bordered">';
                planningTable += '<thead><tr>';
                planningTable += '<th>Horaire</th>';
                for (let i = 0; i < 7; i++) {
                    planningTable += '<th>' + ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'][i] + '</th>';
                }
                planningTable += '</tr></thead>';
                planningTable += '<tbody>';

                for (let hour = 0; hour < 24; hour++) {
                    planningTable += '<tr>';
                    planningTable += '<td>' + hour + ':00</td>';
                    for (let day = 0; day < 7; day++) {
                        planningTable += '<td id="cell-' + day + '-' + hour + '"></td>';
                    }
                    planningTable += '</tr>';
                }

                planningTable += '</tbody></table>';
                $('#planning-table').html(planningTable);

                data.forEach(function(event) {
                    let startDate = new Date(event.dateDebut);
                    let endDate = new Date(event.dateFin);
                    let startDay = startDate.getDay() - 1; // Adjusting for Monday as the first day of the week
                    let startHour = startDate.getHours();
                    let endHour = endDate.getHours();

                    for (let hour = startHour; hour <= endHour; hour++) {
                        $('#cell-' + startDay + '-' + hour).html(event.libelleActivity);
                    }
                });
            }
        });
    }

    loadPlanning();
});
