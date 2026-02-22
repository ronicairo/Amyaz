import './bootstrap.js';
import './home.js';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/home.css';


document.addEventListener('DOMContentLoaded', function () {
const langSelect = document.getElementById('lang');
const rifainCharacters = document.getElementById('rifain-characters');
const toggleKeyboard = document.getElementById('toggle-keyboard');
const searchInput = document.getElementById('search-input');

// Visibilité du clavier Rifain
function toggleRifainKeyboard() {
    if (rifainCharacters.style.display === 'none' || rifainCharacters.style.display === '') {
        rifainCharacters.style.display = 'flex';
    } else {
        rifainCharacters.style.display = 'none';
    }
}

// Insérer un caractère dans le clavier
function insertCharacter(character) {
  const startPos = searchInput.selectionStart;
  const endPos = searchInput.selectionEnd;
  searchInput.value = searchInput.value.substring(0, startPos) + character + searchInput.value.substring(endPos);
  searchInput.setSelectionRange(startPos + 1, startPos + 1);
  searchInput.focus();

  // Déclencher l'événement input pour l'autocomplétion
  const event = new Event('input', { bubbles: true });
  searchInput.dispatchEvent(event);

  // Masquer la boîte des caractères après l'insertion
  rifainCharacters.style.display = 'none';
}

// Ecouteur d'évenements clavier
if (toggleKeyboard) {
toggleKeyboard.addEventListener('click', toggleRifainKeyboard);
}

// Ecouteur d'évenements caractères rifain
if (rifainCharacters) {
rifainCharacters.addEventListener('click', function (event) {
if (event.target.tagName === 'SPAN') {
insertCharacter(event.target.textContent);
}
})
};

// Afficher/masquer le bouton du clavier en fonction de l'option
if (langSelect) {
langSelect.addEventListener('change', function () {
const lang = langSelect.value;
if (lang === 'rif-en' || lang === 'rif-fr') {
toggleKeyboard.style.display = 'flex';
} else {
toggleKeyboard.style.display = 'none';
rifainCharacters.style.display = 'none';
}
})
};

// Vérification initiale pour afficher/masquer le bouton du clavier
if (langSelect) {
const initialLang = langSelect.value;
if (initialLang === 'rif-en' || initialLang === 'rif-fr') {
toggleKeyboard.style.display = 'flex';
} else {
toggleKeyboard.style.display = 'none';
}}
});

function reveal() {
  var reveals = document.querySelectorAll(".reveal");

  for (var i = 0; i < reveals.length; i++) {
    var windowHeight = window.innerHeight;
    var elementTop = reveals[i].getBoundingClientRect().top;
    var elementVisible = 120;

    if (elementTop < windowHeight - elementVisible) {
      reveals[i].classList.add("activ");
    }
  }
}

window.addEventListener("scroll", reveal);
window.addEventListener("load", reveal);

reveal();
