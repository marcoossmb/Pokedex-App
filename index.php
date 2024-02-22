<?php

function request_pokemon() {
    if (isset($_POST['number'])) {
        $pokemonsData = file_get_contents("https://pokeapi.co/api/v2/pokemon?limit=" . $_POST['number']);
        $pokemons = json_decode($pokemonsData);

        return $pokemons;
    }
}

function request_PokemonDetalle() {
    $pokemonDetalle = file_get_contents('https://pokeapi.co/api/v2/pokemon/' . $_POST['pokemon_name'] . '/');
    $pokemon = json_decode($pokemonDetalle);

    return $pokemon;
}

function request_pokemons20() {
    $pokemonsData = file_get_contents("https://pokeapi.co/api/v2/pokemon");
    $pokemons = json_decode($pokemonsData);

    return $pokemons;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="./css/style.css">        
        <title>PokeApi • Marcos</title>
    </head>
    <body class="bg-dark text-white">
        <div class="container mt-5">
            <h1>PokeApi</h1>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <label>Numero de Pokemons a cargar:</label>
                <input class="form-control w-50" type="number" name="number" required min="20" max="40"><br>
                <button type="submit" class="btn btn-success mb-5">Actualizar lista</button>
            </form>
            <?php
            
            $pokemon_data = (isset($_POST['number'])) ? request_pokemon() : request_pokemons20();

            ?>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <label>Select Pokemon:</label>
                <select class="form-control w-50" name="pokemon_name">
                    <?php
                    foreach ($pokemon_data->results as  $pokemon) {
                        $url_parts = explode("/", $pokemon->url);
                        $id_pokemon = $url_parts[6];
                        ?>
                        <option value="<?php echo $pokemon->name ?>"><?php echo $id_pokemon ?> - <?php echo ucfirst($pokemon->name) ?></option>
                        <?php
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-warning mt-3">Cargar datos del Pokemon</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['pokemon_name'])) {

                    $pokemons_detail = request_PokemonDetalle();
                    ?>
                    <h2 class="mt-5"><?php echo ucfirst($pokemons_detail->name) ?></h2>
                    <img src="<?php echo $pokemons_detail->sprites->front_default ?>">
                    <h3>Type:</h3>
                    <div class="d-flex">
                        <?php
                        foreach ($pokemons_detail->types as $type) {
                            ?>
                            <p><?php echo ucfirst($type->type->name) ?></p>&nbsp;
                            <?php
                        }
                        ?>
                    </div>                    
                    <h3>Stats:</h3>
                    <ul>
                        <?php
                        foreach ($pokemons_detail->stats as $stat) {
                            ?>
                            <li><?php echo ucfirst($stat->stat->name) ?>: <?php echo $stat->base_stat ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
            }
            ?>
        </div>        
    </body>
</html>