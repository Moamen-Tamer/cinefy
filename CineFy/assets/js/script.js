document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('q');
    var movieCards = document.querySelectorAll('.movie-grid .movie-card[data-title]');

    if (searchInput && movieCards.length > 0) {
        searchInput.addEventListener('input', function () {
            var value = searchInput.value.toLowerCase();

            movieCards.forEach(function (card) {
                var title = card.getAttribute('data-title') || '';

                if (title.indexOf(value) !== -1) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    var registerForm = document.querySelector('form[action="../app/handlers/register-handler.php"]');

    if (registerForm) {
        registerForm.addEventListener('submit', function (event) {
            var password = document.getElementById('password');
            var confirmPassword = document.getElementById('confirm_password');

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                event.preventDefault();
                alert('Passwords do not match.');
            }
        });
    }
});
