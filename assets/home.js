/**
 * HOME.JS - JavaScript pour la page d'accueil
 * Contient tous les scripts nécessaires pour le fonctionnement de la page
 */

// ========================================
// 1. BARRE DE RECHERCHE
// ========================================

/**
 * Gestion de la touche Enter dans la barre de recherche
 * Redirige vers la page de résultats avec les paramètres lang et q
 */
document.querySelectorAll(".autocomplete-input").forEach(function (inputElement) {
    inputElement.addEventListener("keydown", function (event) {
        // Vérifier si la touche "Enter" est pressée
        if (event.key === "Enter") {
            event.preventDefault(); // Empêcher l'action par défaut

            const lang = document.getElementById("lang").value;
            const q = inputElement.value;

            // Redirection vers la nouvelle URL avec les paramètres
            // Note: Adapter l'URL selon votre environnement (dev/prod)
            const baseUrl = window.location.origin;
            window.location.replace(baseUrl + "/?lang=" + lang + "&q=" + q);
        }
    });
});

/**
 * Normalisation des apostrophes dans le champ de recherche
 * Remplace toutes les variantes d'apostrophes par des apostrophes simples
 */
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            let inputValue = searchInput.value;
            // Remplacer toutes les variantes d'apostrophes par des apostrophes simples
            inputValue = inputValue.replace(/'|'|ʼ|`|´|′|ʾ|ʿ|ˈ|‛|❛|❜|ʹ/g, "'");
            searchInput.value = inputValue;
        });
    }
});

// ========================================
// 2. SYSTÈME DE FAVORIS
// ========================================

/**
 * Gestion du système de favoris (étoiles)
 * Permet d'ajouter/retirer des traductions des favoris
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.action-favorite').forEach(function (star) {
        star.addEventListener('click', function () {
            const traductionId = this.getAttribute('data-id');
            const isFavorite = this.querySelector('i').classList.contains('text-warning');
            
            // Déterminer l'URL selon l'état actuel
            const url = isFavorite ? 
                document.querySelector('[data-favorite-remove-url]')?.dataset.favoriteRemoveUrl || '/favorite/remove' :
                document.querySelector('[data-favorite-add-url]')?.dataset.favoriteAddUrl || '/favorite/add';
            
            // Récupérer le token CSRF depuis un attribut data ou meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    traduction_id: traductionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (isFavorite) {
                        this.querySelector('i').classList.remove('text-warning');
                    } else {
                        this.querySelector('i').classList.add('text-warning');
                    }
                    location.reload(); // Reload the page to show flash messages
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            });
        });
    });
});



// ========================================
// 4. UTILITAIRES
// ========================================

/**
 * Fonction utilitaire pour obtenir l'URL de base
 * Adapte automatiquement entre dev et prod
 */
function getBaseUrl() {
    return window.location.origin;
}

/**
 * Fonction utilitaire pour récupérer un token CSRF
 */
function getCsrfToken(tokenName = 'csrf-token') {
    const metaTag = document.querySelector(`meta[name="${tokenName}"]`);
    return metaTag ? metaTag.content : '';
}

// ========================================
// 5. GESTION DES ERREURS GLOBALES
// ========================================

/**
 * Gestion globale des erreurs JavaScript
 */
window.addEventListener('error', function(event) {
    console.error('Erreur JavaScript:', event.error);
    // Vous pouvez envoyer les erreurs à un service de logging ici
});

/**
 * Gestion des promesses rejetées non gérées
 */
window.addEventListener('unhandledrejection', function(event) {
    console.error('Promise rejetée:', event.reason);
    // Vous pouvez envoyer les erreurs à un service de logging ici
});

 document.querySelectorAll('.tifinagh-toggle').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);
            
            if (container) {
                container.classList.toggle('show-tifinagh');
                this.classList.toggle('active');
                
                // Change le titre du bouton
                if (this.classList.contains('active')) {
                    this.setAttribute('title', 'Basculer vers Latin');
                } else {
                    this.setAttribute('title', 'Basculer vers Tifinagh');
                }
            }
        });
    });