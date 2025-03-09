
$(document).ready(function() {
  var languageSelect = document.getElementById('language-select');
  languageSelect.addEventListener('change', function() {

    var selectedLanguage = languageSelect.value;
    var welcomeMessage = document.getElementById('welcome-message');
    var requiredmail = document.getElementById('required-mail');
    var requiredpassword = document.getElementById('required-password');
    var login = document.getElementById('login');
    var login = document.getElementById('login');
    var languageselect = document.getElementById('choose_lang');
    var passwordlabel = document.getElementById('password_label');

    if (selectedLanguage === 'fr') {
      welcomeMessage.textContent = 'Bienvenue 🖐️ !';
      requiredmail.textContent = 'Veillez renseigner une adresse mail valide !';
      requiredpassword.textContent = 'Veillez confirmer votre mot de passe !';
      login.textContent = 'Ouvrir ma session';
      languageselect.textContent = 'Choisir une langue';
      passwordlabel.textContent = 'Mot de passe:';
    } else if (selectedLanguage === 'eng') {
      welcomeMessage.textContent = 'Welcome 🖐️ !';
      requiredmail.textContent = 'please fill in your email';
      requiredpassword.textContent = 'please fill in your password';
      login.textContent = 'Login';
      passwordlabel.textContent = 'Mot de passe:';
      languageselect.textContent = 'Password';
    }
  });
});