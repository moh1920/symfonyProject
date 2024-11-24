document.getElementById('repas-form').addEventListener('submit', function(event) {
    var repasSelectionnes = document.querySelectorAll('input[name="repas[]"]:checked');
    var indisponibles = [];
    
    repasSelectionnes.forEach(function(repas) {
        // Récupérer la valeur de l'attribut data-disponible
        var estDisponible = repas.getAttribute('data-disponible') === 'true';
        
        if (!estDisponible) {
            var card = repas.closest('.card');
            var nomRepas = card.querySelector('.card-title').textContent.trim();
            indisponibles.push(nomRepas);
        }
    });

    if (indisponibles.length > 0) {
        event.preventDefault(); // Empêcher la soumission du formulaire
        alert('Les repas suivants sont indisponibles: ' + indisponibles.join(', ') + '. Veuillez sélectionner d\'autres repas.');
    }
});
