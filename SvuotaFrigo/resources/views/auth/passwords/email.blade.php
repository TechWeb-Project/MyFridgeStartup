<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Richiesta di Reset Password</h2>
    <p>Hai ricevuto questa email perché hai richiesto il reset della tua password.</p>
    <p>Clicca sul link sottostante per reimpostare la tua password:</p>
    <a href="{{ route('password.reset', ['token' => $token]) }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
        Reimposta la Password
    </a>
    <p>Se non hai richiesto un reset della password, ignora questa email.</p>
    <p>Grazie,<br>Il Team di wAIstless</p>
</body>
</html>
