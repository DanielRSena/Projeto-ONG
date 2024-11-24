<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de login</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #F2F2F2;
        }

        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 50px;
            width: 200px;
        }

        h1 {
            text-align: center;
        }

        div {
            background-color: #0D0D0D;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 40px;
            border-radius: 15px;
            color: #fff;
        }

        input {
            padding: 15px;
            padding-left: 100px;
            padding-right: 100px;
            border: none;
            outline: none;
            font-size: 15px;
            border-radius: 15px;
            text-align: center;
        }

        button {
            background-color: dodgerblue;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            color: white;
            font-size: 15px;
        }

        button:hover {
            background-color: #0378A6;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <img src="img/logo.png" alt="">
    <div>
        <h1>Login</h1>
        <input id="username" type="text" placeholder="Login">
        <br><br>
        <input id="password" type="password" placeholder="Senha">
        <br><br>
        <button onclick="enviarDados()">Entrar</button>
    </div>


    <script>
        function enviarDados() {
            // Captura os valores dos inputs
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            // Cria um objeto FormData para enviar os dados
            var formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);

            // Cria uma requisição AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../session.php', true);

            // Define o que fazer quando a resposta for recebida
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Exemplo: resposta do PHP
                    if (xhr.responseText === 'true') {
                        window.location.href = "home.php";
                    } else alert(xhr.responseText);
                } else {

                }
            };

            // Envia a requisição com os dados
            xhr.send(formData);
        }
    </script>
</body>

</html>