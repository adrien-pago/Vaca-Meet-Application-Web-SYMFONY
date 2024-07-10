$(document).ready(function() {
    // Gestion du chargement dynamique du contenu
    $('#load-planning').click(function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien

        var url = $(this).data('url'); // Récupère l'URL depuis l'attribut data-url

        // Charge le contenu depuis l'URL récupérée
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response); // Remplace le contenu dynamique avec le HTML chargé
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors du chargement des données :', error);
            }
        });
    });

    // Récupération des éléments du DOM
    const addActivityBtn = document.getElementById("addActivityBtn");
    const libelleInput = document.getElementById("libelle");
    const dateDebutInput = document.getElementById("dateDebut");
    const dateFinInput = document.getElementById("dateFin");
    const planningTable = document.getElementById("planningTable");
    const weekLabel = document.getElementById("week-label");
    const prevWeekBtn = document.getElementById("prevWeek");
    const nextWeekBtn = document.getElementById("nextWeek");

    // Définir le début de la semaine actuelle
    let currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1);

    // Fonction pour récupérer les données de planning
    function fetchPlanningData() {
        const startDate = currentWeekStart.toISOString().split('T')[0];
        const endDate = new Date(currentWeekStart.getTime() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
        console.log(`Fetching planning data from ${startDate} to ${endDate}`);

        fetch(`/api/planning?start=${startDate}&end=${endDate}`)
            .then(response => response.json())
            .then(data => {
                console.log("Planning data received:", data);
                renderPlanning(data);
            })
            .catch(error => console.error('Error fetching planning data:', error));
    }

     // Fonction pour afficher les données de planning
     function renderPlanning(planning) {
        console.log("Rendering planning data");

        // Efface les cellules existantes
        for (let hour = 6; hour <= 23; hour++) {
            for (let day = 0; day <= 6; day++) {
                const cell = document.getElementById(`cell-${hour}-${day}`);
                if (cell) cell.innerHTML = "";
            }
        }

        // Tableau des jours de la semaine en français
        const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        // Parcours des activités et remplissage des cellules
        planning.forEach(activity => {
            const start = new Date(activity.dateDebut);
            const end = new Date(activity.dateFin);

            const dayIndex = start.getDay() - 1; // Jour de la semaine (0 = Lundi)
            const startHour = start.getHours();
            const startMinutes = start.getMinutes();
            const endHour = end.getHours();
            const endMinutes = end.getMinutes();

            const dayLabel = daysOfWeek[dayIndex];

            // Boucler à travers chaque heure entre dateDebut et dateFin
            let current = new Date(start);
            while (current <= end) {
                const currentHour = current.getHours();
                const currentMinutes = current.getMinutes();

                const cellId = `cell-${currentHour}-${dayIndex}`;
                const cell = document.getElementById(cellId);

                if (cell) {
                    // Si c'est la demi-heure, ajouter un trait
                    if (currentMinutes === 30 && currentHour === startHour) {
                        cell.innerHTML += "<hr>";
                    } else {
                        // Sinon, afficher le libellé de l'activité
                        cell.innerHTML = `<strong>${activity.libelleActivity}</strong>`;
                    }
                }

                // Passer à l'heure suivante
                current.setHours(current.getHours() + 1); // Ajoute 1 heure
                current.setMinutes(0); // Réinitialise les minutes à 0
            }
        });
    }

    // Événement pour ajouter une activité
    addActivityBtn.addEventListener("click", () => {
        console.log("Add activity button clicked");

        const libelle = libelleInput.value;
        const dateDebut = dateDebutInput.value;
        const dateFin = dateFinInput.value;

        if (libelle && dateDebut && dateFin) {
            console.log("Sending new activity to the server");

            fetch('/api/planning', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ libelle, dateDebut, dateFin }),
            })
                .then(response => response.json())
                .then(data => {
                    console.log("Activity added:", data);
                    fetchPlanningData(); // Rafraîchit les données de planning
                })
                .catch(error => console.error('Error adding activity:', error));
        } else {
            alert("Veuillez remplir tous les champs.");
        }
    });

    // Événement pour afficher la semaine précédente
    prevWeekBtn.addEventListener("click", () => {
        console.log("Previous week button clicked");

        currentWeekStart.setDate(currentWeekStart.getDate() - 7);
        updateWeekLabel();
        fetchPlanningData();
    });

    // Événement pour afficher la semaine suivante
    nextWeekBtn.addEventListener("click", () => {
        console.log("Next week button clicked");

        currentWeekStart.setDate(currentWeekStart.getDate() + 7);
        updateWeekLabel();
        fetchPlanningData();
    });

    function updateWeekLabel() {
        const startDate = new Date(currentWeekStart);
        const endDate = new Date(currentWeekStart.getTime() + 6 * 24 * 60 * 60 * 1000);

        const startDay = startDate.getDate();
        const endDay = endDate.getDate();
        const monthName = startDate.toLocaleString('fr-FR', { month: 'long' });
        const year = startDate.getFullYear();

        weekLabel.textContent = `Semaine du ${startDay} au ${endDay} ${monthName} ${year}`;
    }

    // Initialisation de l'étiquette de la semaine et chargement des données de planning
    updateWeekLabel();
    fetchPlanningData();
});
