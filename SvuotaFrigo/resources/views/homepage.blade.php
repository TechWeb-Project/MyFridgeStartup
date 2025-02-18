<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #f5f5dc;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .logo img {
            height: 150px;
        }
        
        .contact{
            position: absolute;
            top: 19px;
            right: 70px;
            font-size: 1.7rem;
            font-weight: bold;
            cursor: pointer;
            color: #333;
            transition: color 0.3s ease-in-out;
        }
        .contact:hover {
            color: #555;
            text-decoration: underline;
}
        .header {
            position: absolute;
            top: 20px;
            right: 270px;
            font-size: 1.7rem;
            font-weight: bold;
            cursor: pointer;
            color: #333;
            transition: color 0.3s ease-in-out;
        }

        .header:hover {
            color: #555;
            text-decoration: underline;
        }

        .container {
            flex: 1;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 160px;
            text-align: right;
            margin-top: 300px;
        }

        .text-container {
            text-align: left;
            max-width: 400px;
        }

        h1 {
            font-size: 4rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        p {
            font-size: 1.4rem;
            font-weight: 400;
            color: #555;
            margin-top: 10px;
        }

        .login-button {
            padding: 12px 24px;
            font-size: 1.5rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 30px;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #0056b3;
        }

        /* Form di Login */
        .auth-form {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f5f5dc;
            border: 2px solid #d2b48c;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 350px;
            text-align: center;
        }

        .auth-form input {
            width: 80%;
            padding: 10px;
            margin: 10px auto;
            display: block;
            border: 1px solid #d2b48c;
            border-radius: 5px;
            background-color: white;
            text-align: center;
        }

        .auth-form button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .auth-form button:hover {
            background-color: #218838;
        }

        .register-link {
            font-size: 1rem;
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 10px;
            display: block;
            text-align: center;
        }

        /* BARRA MARRONE - CHI SIAMO */
        .brown-bar {
            width: 95.87%;
            height: 500px;
            background-color: #d2b48c;
            position: relative;
            margin-top: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
            font-weight: 400;
            line-height: 1.6;
        }

        .brown-bar h2 {
            font-size: 3.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .brown-bar strong {
            font-size: 1.5rem;
            color: #54aedb;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* SEZIONE BEIGE SOTTO LA BARRA MARRONE */
        .beige-section {
            width: 100%;
            height: 500px;
            background-color: #f5f5dc;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: #333;
        } 

        /* IMMAGINI NELLA PAGINA INIZIALE */
        .image {
            position: absolute;
        }

        .image1 {
            left: 900px;
            width: 300px;
            top: 150px;
        }

        .image3 {
            top: 300px;
            left: 120px;
            width: 400px;
        }
            /*SEZIONE IMMAGINI E DESCRIZIONi*/
        .features {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 30px;
        }

        .feature {
            text-align: center;
            max-width: 200px;
        }

        .feature img {
            width: 90px;
            height: 90px;
        }

        .feature p {
            font-size: 1.3rem;
            color: white;
            font-weight: 400;
            margin-top: 10px;
        }
        .footer {
    width: 100%;
    background-color: #402500;
    padding: 50px 50px;
    text-align: center;
    color: white;
    font-size: 1.2rem;
}

.footer-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.footer-links a {
    color: white;
    margin: 0 10px;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #f5f5dc;
}

.footer-social img {
    width: 50px;
    height: 50px;
    margin: 0 5px;
}

.footer-copy {
    margin-top: 10px;
    font-size: 0.8rem;
    opacity: 0.8;
}

    </style>
</head>
<body>

    <div class="logo">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo">
    </div>

    <!-- Scritta "Chi Siamo" in alto a destra -->
    <div class="header" id="scrollToBar">Chi Siamo</div>

    <!-- Scritta "Contatti" in alto a destra -->
    <div class="contact" id="scrollContact">Contatti</div>
    <!-- Immagini nella pagina iniziale -->
    <img src="{{ asset('images/immagine1.png') }}" alt="Immagine 1" class="image image1">
    <img src="{{ asset('images/immagine3.png') }}" alt="Immagine 3" class="image image3">

    <div class="container">
        <div class="text-container">
            <h1>Benvenuto in wAIstless</h1>
            <p>La tua intelligenza per ridurre lo spreco</p>
            <button class="login-button" id="login-button">Accedi</button>

            <!-- Form Login -->
            <div class="auth-form" id="login-form">
                <h3>Login</h3>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Accedi</button>
                </form>
                <p class="register-link" id="show-register">Non sei registrato? Registrati</p>
            </div>

            <!-- Form Registrazione -->
            <div class="auth-form" id="register-form">
                <h3>Registrati</h3>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Nome" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="password_confirmation" placeholder="Conferma Password" required>
                    <button type="submit">Registrati</button>
                </form>
            </div>
        </div>
    </div>

    <!-- BARRA MARRONE CHI SIAMO -->
   <!-- BARRA MARRONE CHI SIAMO -->
   <div class="brown-bar" id="brown-bar">
        <div>
            <h2>Chi Siamo</h2>
            Ogni anno vengono sprecati milioni di tonnellate di cibo a causa di scadenze dimenticate e cattiva gestione degli alimenti.  
            <strong>wAIstless</strong> nasce per risolvere questo problema, aiutandoti a <strong>organizzare il tuo frigo in modo intelligente e sostenibile</strong>.  
            Con la nostra piattaforma, puoi tenere traccia dei prodotti, ricevere notifiche sulle scadenze e ottenere ricette smart <strong>tramite AI dedicata</strong> per ridurre gli sprechi alimentari.
        </div>

        <!-- SEZIONE DELLE FUNZIONALITÀ -->
        <div class="features">
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
    </div>

<div class="beige-section" id="beige-section">

</div>

<!-- FOOTER -->
<div class="footer">
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
        <div class="footer-copy">
            <p>© 2025 wAIstless. Tutti i diritti riservati.</p>
        </div>
    </div>
</div>


    </div>

    <script>
        const loginButton = document.getElementById('login-button');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const showRegister = document.getElementById('show-register');
        const scrollToBar = document.getElementById('scrollToBar');
        const brownBar = document.getElementById('brown-bar');
        const beigeSection= document.getElementById('beige-section');

        loginButton.addEventListener('click', () => {
            loginButton.style.display = 'none';
            loginForm.style.display = 'block';
        });

        showRegister.addEventListener('click', () => {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        });

        scrollToBar.addEventListener('click', () => {
            brownBar.scrollIntoView({ behavior: 'smooth' });
        }); 
        document.getElementById('scrollToBar').addEventListener('click', function() {
            document.getElementById('brown-bar').scrollIntoView({ behavior: 'smooth' });
        });

        document.getElementById('scrollContact').addEventListener('click', function() {
            document.getElementById('beige-section').scrollIntoView({ behavior: 'smooth' });
        });
    </script>

</body>
</html>
