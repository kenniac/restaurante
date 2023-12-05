<?php
include "../conexion.php";

// Insertar una nueva categoría o actualizar una existente
function agregarCategoria($conexion, $nombre, $id = null) {
    if ($id) {
        $sql = "UPDATE categorias SET nombre = '$nombre' WHERE id = $id";
    } else {
        $sql = "INSERT INTO categorias (nombre) VALUES ('$nombre')";
    }

    $result = $conexion->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conexion->error);
    }
}

// Función para obtener categorías
function obtenerCategorias() {
    global $conexion;  
    $result = $conexion->query("SELECT * FROM categorias");

    if (!$result) {
        die("Error en la consulta: " . $conexion->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Función para obtener una categoría por su ID
function obtenerCategoriaPorID($conexion, $id) {
    $sql = "SELECT * FROM categorias WHERE id = $id";
    $result = $conexion->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conexion->error);
    }

    return $result->fetch_assoc();
}

// Eliminar una categoría por ID
function eliminarCategoria($conexion, $id) {
    $sql = "DELETE FROM categorias WHERE id = $id";
    $result = $conexion->query($sql);
    return $result;
}

// Página para agregar, editar y eliminar categorías
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["agregar_categoria"])) {
        $idCategoria = isset($_POST["id_categoria"]) ? $_POST["id_categoria"] : null;
        $nombreCategoria = $_POST["nombre_categoria"];
        agregarCategoria($conexion, $nombreCategoria, $idCategoria);
    } elseif (isset($_POST["eliminar_categoria"])) {
        $categoria_id = $_POST["id_categoria"];
        eliminarCategoria($conexion, $categoria_id);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Categorías</title>
</head>
<body>

<?php
// Determina el título del formulario según si estás editando o agregando
$titulo = isset($_POST["editar_categoria"]) ? "Editar Categoría" : "Agregar Categoría";
?>

    <h2><?php echo $titulo; ?></h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        if (isset($_POST["editar_categoria"])) {
            // Si estás editando, muestra un campo oculto con el ID actual
            echo "<input type='hidden' name='id_categoria' value='{$_POST["editar_categoria"]}'>";
        }
        ?>
        <label for="nombre_categoria">Nombre de la categoría:</label>
        <input type="text" name="nombre_categoria" required>
        <?php
        if (isset($_POST["editar_categoria"])) {
            echo "<button type='submit' name='actualizar_categoria'>Actualizar</button>";
        } else {
            echo "<button type='submit' name='agregar_categoria'>Agregar</button>";
        }
        ?>
    </form>

</body>
</html>


    <h2>Lista de Categorías</h2>
    <ul>
        <?php
        $categorias = obtenerCategorias();
        foreach ($categorias as $categoria) {
            echo "<li>{$categoria['nombre']} ";
            echo "<form method='post' action='{$_SERVER['PHP_SELF']}'>";
            echo "<input type='hidden' name='id_categoria' value='{$categoria['id']}'>";
            echo "<button type='submit' name='editar_categoria'>Editar</button>";
            echo "<button type='submit' name='eliminar_categoria'>Eliminar</button>";
            echo "</form></li>";
        }
        ?>
    </ul>
</body>
</html>
