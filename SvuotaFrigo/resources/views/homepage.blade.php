<!DOCTYPE html>
<html lang="it">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="navbar">
        <img src="{{ asset('images/waisteless.png') }}" alt="Logo" height="70">
        <div>
            <a href="#about"    class="btnfridge">Chi Siamo</a>
            <a href="#contact"  class="btnfridge">Contatti</a>
        </div>
    </div>

    <div class="container-login">
        <h1>BENVENUTO IN W<span style="color: #459DBA;">AI</span>STELSS</h1>

        <p>Il tuo frigo <span style="color: #459DBA;">intelligente </span> a portata di click!</p>
        <button class="btn btn-primary" id="login-button">Accedi</button>


<!-- Form di Login -->
<div class="auth-form" id="login-form">
    <h3>Login</h3>
    <form id="loginForm">
        @csrf
        <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3 position-relative">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <button type="button" class="btn btn-dark position-absolute top-50 end-0 translate-middle-y" onclick="togglePassword('password', this)">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Login</button>
            <button type="button" class="btn btn-secondary" id="close-login">Chiudi</button>
        </div>
        <p id="login-error" class="text-danger mt-2" style="display: none;"></p>
        <p class="mt-2">
            <a href="#" id="show-reset-form">Password dimenticata?</a>
        </p>
        <p class="mt-2">
            Non sei registrato? <a href="#" id="show-register-form">Registrati qui</a>
        </p>
    </form>
</div>

<!-- Form di Registrazione -->
<div class="auth-form" id="register-form" style="display: none;">
    <h3>Registrazione</h3>
    <form id="registerForm">
        @csrf
        <div class="mb-3">
            <input type="text" class="form-control" id="register-name" name="name" placeholder="Nome" required>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" id="register-email" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3 position-relative">
            <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required>
            <button type="button" class="btn btn-dark position-absolute top-50 end-0 translate-middle-y" onclick="togglePassword('register-password', this)">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="mb-3 position-relative">
            <input type="password" class="form-control" id="register-password-confirm" name="password_confirmation" placeholder="Conferma Password" required>
            <button type="button" class="btn btn-dark position-absolute top-50 end-0 translate-middle-y" onclick="togglePassword('register-password-confirm', this)">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-success">Registrati</button>
            <button type="button" class="btn btn-secondary" id="close-register">Chiudi</button>
        </div>
        <p id="register-error" class="text-danger mt-2" style="display: none;"></p>
        <p class="mt-2">
            Hai già un account? <a href="#" id="show-login-form">Accedi qui</a>
        </p>
    </form>
</div>



<!-- Form di Recupero Password -->
<div class="auth-form" id="reset-form" style="display: none;">
    <h3>Recupero Password</h3>
    <form id="resetForm" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <input type="email" class="form-control" id="reset-email" name="email" placeholder="Inserisci la tua email" required>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-warning">Invia Email</button>
            <button type="button" class="btn btn-secondary" id="close-reset">Chiudi</button>
        </div>
        <p id="reset-error" class="text-danger mt-2" style="display: none;"></p>
        <p class="mt-2">
            Torna al <a href="#" id="show-login-form-from-reset">Login</a>
        </p>
    </form>
</div>



    </div>

    <div class="features" id="features">
        <div class="feature">
            <img src="{{ asset('images/img1.png') }}" alt="Registrazione">
            <p>Registra gli alimenti acquistati con scadenza e quantità.</p>
        </div>
        <div class="feature">
            <img src="{{ asset('images/img2.png') }}" alt="Notifiche">
            <p>Ricevi notifiche sulle scadenze imminenti.</p>
        </div>
        <div class="feature">
            <img src="{{ asset('images/img3.png') }}" alt="Ricette AI">
            <p>Scopri ricette personalizzate con la nostra AI.</p>
        </div>
        <div class="feature">
            <img src="{{ asset('images/img4.png') }}" alt="Ottimizzazione">
            <p>Ottimizza la tua spesa, evita sprechi e risparmia.</p>
        </div>
    </div>


        <!-- Sezione Chi Siamo -->
<div id="about" class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Testo -->
            <div class="col-lg-6">
                <h2 class="section-title">Chi Siamo</h2>
                <p class="section-text">
                    Siamo quattro ragazzi con una missione: <strong>ridurre lo spreco alimentare</strong> e aiutare le persone nella gestione del loro frigorifero.
                    Con <strong>WAISTELESS</strong>, puoi tenere traccia degli alimenti che acquisti, ricevere notifiche sulle scadenze e, grazie alla nostra <strong>AI dedicata</strong>,
                    ottenere ricette personalizzate con gli ingredienti che hai già in casa.
                </p>
                <p class="section-text">
                    Accetta, salva o rifiuta le ricette proposte e scopri un modo più intelligente per <strong>mangiare senza sprechi</strong>.
                </p>
            </div>
            <!-- Immagine -->
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/waisteless.jpg') }}" alt="Chi Siamo" class="about-img">
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
<div id="contact" class="footer">
    <div class="footer-content">
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Termini e Condizioni</a>
            <a href="#">FAQ</a>
            <a href="#">Contattaci</a>
        </div>

        <div class="footer-social">
            <img src="{{ asset('images/facebook.png') }}" alt="Facebook">
            <img src="{{ asset('images/instagram.png') }}" alt="Instagram">
            <img src="{{ asset('images/twitter.png') }}" alt="Twitter">
        </div>

        <!-- Spazio per l'immagine aggiuntiva -->
        <div class="footer-image">
            <img src="{{ asset('images/waisteless.png') }}" alt="Immagine Extra">
        </div>

        <div class="footer-copy">
            <p>© 2025 WAISTELESS. Tutti i diritti riservati.</p>
        </div>
    </div>
</div>


</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const loginButton = document.getElementById("login-button");
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const resetForm = document.getElementById("reset-form");

    const closeLogin = document.getElementById("close-login");
    const closeRegister = document.getElementById("close-register");
    const closeReset = document.getElementById("close-reset");

    const loginError = document.getElementById("login-error");
    const registerError = document.getElementById("register-error");
    const resetError = document.getElementById("reset-error");

    const showRegisterForm = document.getElementById("show-register-form");
    const showLoginForm = document.getElementById("show-login-form");
    const showResetForm = document.getElementById("show-reset-form");
    const showLoginFromReset = document.getElementById("show-login-form-from-reset");

    // Mostra il form di login
    loginButton.addEventListener("click", function() {
        loginForm.style.display = "block";
        registerForm.style.display = "none";
        resetForm.style.display = "none";
    });

    // Chiude il login
    closeLogin.addEventListener("click", function() {
        loginForm.style.display = "none";
    });

    // Mostra il form di registrazione
    showRegisterForm.addEventListener("click", function(event) {
        event.preventDefault();
        loginForm.style.display = "none";
        registerForm.style.display = "block";
        resetForm.style.display = "none";
    });

    // Chiude la registrazione
    closeRegister.addEventListener("click", function() {
        registerForm.style.display = "none";
    });

    // Mostra il form di reset password dal login
    showResetForm.addEventListener("click", function(event) {
        event.preventDefault();
        loginForm.style.display = "none";
        resetForm.style.display = "block";
    });

    // Torna al login dal reset
    showLoginFromReset.addEventListener("click", function(event) {
        event.preventDefault();
        resetForm.style.display = "none";
        loginForm.style.display = "block";
    });

    // Chiude il reset form e torna al login
    closeReset.addEventListener("click", function() {
        resetForm.style.display = "none";
        loginForm.style.display = "block";
    });

    // AJAX per il login
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch("{{ route('login.post') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                loginError.textContent = data.message;

                loginError.style.display = "block";
            }
        })
        .catch(error => console.error("Errore:", error));
    });

    // AJAX per la registrazione
    document.getElementById("registerForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch("{{ route('register.post') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                registerError.innerHTML = "";
                for (const [key, messages] of Object.entries(data.errors)) {
                    registerError.innerHTML += messages.join("<p>beccato</p");
                }
                registerError.style.display = "block";
            }
        })
        .catch(error => console.error("Errore:", error));
    });

    // AJAX per il recupero password
    document.getElementById("resetForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch("{{ route('password.email') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                alert("Email di recupero inviata con successo! Controlla la tua casella di posta.");
                resetForm.style.display = "none";
                loginForm.style.display = "block"; // Torna al login dopo il reset
            } else {
                resetError.textContent = "Errore: verifica l'email inserita.";
                resetError.style.display = "block";
            }
        })
        .catch(error => console.error("Errore:", error));
    });

    // Scorrimento fluido per "Chi Siamo"
    document.querySelector(".navbar a[href='#about']").addEventListener("click", function(event) {
        event.preventDefault();
        document.querySelector("#about").scrollIntoView({ behavior: "smooth" });
    });

    // Scorrimento fluido per "Contatti" (footer)
    document.querySelector(".navbar a[href='#contact']").addEventListener("click", function(event) {
        event.preventDefault();
        document.querySelector("#contact").scrollIntoView({ behavior: "smooth" });
    });

});


//mostra password
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}


</script>



</body>
</html>
