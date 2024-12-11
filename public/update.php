    <?php
    session_start();
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id || $id <= 0) {
        header("Location:index.php");
        exit;
    }

    require __DIR__ . "/../utils/validaciones.php";
    require __DIR__ . "/../utils/conexion.php";

    //--Recuperamos el articulo a actualizar
    $q = "select id, nombre, descripcion, tipo, gratuito from cursos where id=?";
    $stmt = mysqli_stmt_init($llave);
    mysqli_stmt_prepare($stmt, $q);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $i, $no, $de, $ti, $gr);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    if (!$i) {
        header("Location:index.php");
        exit;
    }
    $esGratis = ($gr == "SI") ? "checked" : "esG";
    $noEsGratis = ($gr == "NO") ? "checked" : "nEsG";

    if (isset($_POST['nombre'])) {
        $nombre = sanearCadena($_POST['nombre']);
        $descripcion = sanearCadena($_POST['descripcion']);
        $tipo = sanearCadena($_POST['tipo']);
        $gratuito = $_POST['gratuito'] ?? -1;

        $errores = false;
        if (!esLongitudCampoValida('nombre', $nombre, 5, 50)) {
            $errores = true;
        } else {
            if (!esNombreUnico($llave, $nombre, $id)) {
                $errores = true;
            }
        }

        if (!esLongitudCampoValida('descripcion', $descripcion, 10, 150)) {
            $errores = true;
        }

        if (!esTipoValido($tipo)) {
            $errores = true;
        }

        if (!esGratuitoValido($gratuito)) {
            $errores = true;
        }

        if ($errores) {
            header("Location:update.php?id=$id");
            exit;
        }
        //si estamos aqui todo correcto vamos a guardar el curso
        $q = "update cursos set nombre=?, descripcion=?, tipo=?, gratuito=? where id=?";
        $stmt = mysqli_stmt_init($llave);
        mysqli_stmt_prepare($stmt, $q);
        mysqli_stmt_bind_param($stmt, 'ssssi', $nombre, $descripcion, $tipo, $gratuito, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($llave);
        $_SESSION['mensaje'] = "Se editó el curso.";
        header("Location:index.php");
        exit;
    }


    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Examen</title>
        <!-- CDN Tailwind css -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- CDN FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- CDN Sweetalert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <!-- Encabezado -->
        <header class="bg-gradient-to-r from-teal-500 to-blue-600 text-white py-5 shadow-md mb-8 flex items-center justify-between px-6">
            <h3 class="text-3xl font-extrabold">Editar Curso</h3>
            <a href="index.php" class="inline-flex items-center px-4 py-2 bg-white text-teal-600 font-semibold rounded-lg shadow hover:bg-gray-100 transition duration-300">
                <i class="fas fa-home mr-2"></i>Inicio
            </a>
        </header>
        <div class="w-1/2 mx-auto mt-2 p-3 rounded-xl shadow-xl border-2 border-black">
            <form action="<?= $_SERVER['PHP_SELF'] . "?id=$id"; ?>" method="POST">
                <!-- Campo de texto para nombre -->
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 font-semibold mb-2">Nombre:</label>
                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2">
                        <i class="fas fa-book text-gray-500 mr-2"></i>
                        <input type="text" id="nombre" value="<?= $no; ?>"
                            name="nombre" placeholder="Ingresa el nombre del curso" class="w-full outline-none">
                    </div>
                    <?php
                    pintarError('err_nombre');
                    ?>
                </div>

                <!-- Textarea para descripción -->
                <div class="mb-4">
                    <label for="descripcion" class="block text-gray-700 font-semibold mb-2">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Escribe una descripción" class="w-full h-24 border border-gray-300 rounded-lg px-3 py-2 outline-none"><?= $de; ?></textarea>
                </div>
                <?php
                pintarError('err_descripcion');
                ?>

                <!-- Select para tipo -->
                <div class="mb-4">
                    <label for="tipo" class="block text-gray-700 font-semibold mb-2">Tipo:</label>
                    <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2">
                        <i class="fas fa-list text-gray-500 mr-2"></i>
                        <select id="tipo" name="tipo" class="w-full outline-none">
                            <option value="ejemplo">-- Elige un tipo para el curso ---</option>
                            <?php foreach ($tipos as $tipo): $cad = ($tipo == $ti) ? "selected" : ""; ?>
                                <option <?= $cad; ?>><?= $tipo; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php
                    pintarError('err_tipo');
                    ?>
                </div>

                <!-- Radio buttons para gratuito -->
                <div class="mb-4">
                    <p class="block text-gray-700 font-semibold mb-2">Gratuito:</p>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="radio" id="gratuito_si" name="gratuito" value="SI" class="mr-2" <?= $esGratis ?>>
                            <label for="gratuito_si" class="text-gray-700">Sí</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="gratuito_no" name="gratuito" value="NO" class="mr-2" <?= $noEsGratis ?>>
                            <label for="gratuito_no" class="text-gray-700">No</label>
                        </div>
                    </div>
                    <?php
                    pintarError('err_gratuito');
                    ?>
                </div>

                <!-- Botones -->
                <div class="flex justify-between mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </button>

                    <a href="index.php" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </body>

    </html>