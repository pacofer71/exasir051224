<?php
session_start();
require __DIR__ . "/../utils/conexion.php";
$q = "select * from cursos order by tipo, nombre";

$cursos = mysqli_query($llave, $q);
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

<body class="bg-gray-100 font-sans">

    <!-- Encabezado -->
    <header class="bg-gradient-to-r from-teal-500 to-blue-600 text-white py-5 shadow-md">
        <h3 class="text-center text-3xl font-extrabold">Listado de Cursos</h3>
    </header>

    <main class="w-3/4 mx-auto mt-10 p-6 bg-white shadow-xl rounded-2xl border border-gray-300">
        <!-- Botón Crear Curso -->
        <div class="mb-6 text-right">
            <a href="nuevo.php" class="inline-flex items-center px-4 py-2 rounded-xl bg-green-600 text-white text-lg font-semibold shadow hover:bg-green-700 transition duration-300">
                <i class="fas fa-plus-circle mr-2"></i>Crear Curso
            </a>
        </div>

        <!-- Tabla de Cursos -->
        <table class="w-full text-sm text-left text-gray-700 border border-gray-300 rounded-lg">
            <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Descripción</th>
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3">Gratuito</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $item):
                    $color = ($item['gratuito'] == 'SI') ? "text-green-600" : "text-red-600";
                ?>
                    <tr class="bg-white border-b hover:bg-gray-100">
                        <th class="px-6 py-4 font-medium text-gray-900"><?= $item['nombre']; ?></th>
                        <td class="px-6 py-4"><?= $item['descripcion']; ?></td>
                        <td class="px-6 py-4"><?= $item['tipo'] ?></td>
                        <td class="px-6 py-4">
                            <span class="font-bold <?= $color ?>"><?= $item['gratuito'] ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <form action="borrar.php" method="POST" class="inline-block">
                                <a href="update.php?id=<?= $item['id'] ?>" class="text-green-600 hover:text-green-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                                <button type="submit" class="ml-2 text-red-600 hover:text-red-800"
                                    onclick="return confirm('¿Borrar definitivamente el curso?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- SweetAlert -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <script>
            Swal.fire({
                icon: "success",
                title: "<?= $_SESSION['mensaje']; ?>",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    <?php unset($_SESSION['mensaje']);
    endif; ?>

</body>

</html>