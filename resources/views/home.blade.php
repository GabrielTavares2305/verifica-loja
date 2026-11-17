<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veja se uma loja tem sinais de risco antes de comprar</title>
</head>

<body>
    <h1>Veja se uma loja tem sinais de risco antes de comprar</h1>
    <p>Cole abaixo o link do anúncio que você recebeu ou encontrou.</p>
    <div>
        
        <form method="POST" action="">
            @csrf
            <label for="url">Link do anúncio</label><br>
            <input type="url" id="url" name="url" placeholder="https://..." required><br><br>
            <button type="submit">Verificar</button>
        </form>
    </div>
</body>

</html>