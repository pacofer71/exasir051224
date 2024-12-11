<?php
$tipos = ["MATEMATICAS", "FISICA", "LENGUA", "HISTORIA"];
sort($tipos);
function sanearCadena(string $cadena): string
{
    return htmlspecialchars(trim($cadena));
}

function esLongitudCampoValida(string $nomCampo, string $valorCampo, int $lMin, int $lMax): bool
{
    if (strlen($valorCampo) < $lMin || strlen($valorCampo) > $lMax) {
        $_SESSION["err_$nomCampo"] = "*** Error, este valor debe estar comprendido entre $lMin y $lMax";
        return false;
    }
    return true;
}

function esTipoValido($tipo): bool
{
    global $tipos;
    if (!in_array($tipo, $tipos)) {
        $_SESSION['err_tipo'] = "*** Error en tipo de curso o no seleccionó ninguno";
        return false;
    }
    return true;
}
function esGratuitoValido(string $gratuito): bool
{
    if (!in_array($gratuito, ["SI", "NO"])) {
        $_SESSION['err_gratuito'] = "*** Error debes seleccionar si el curso será o no gratuito";
        return false;
    }
    return true;
}
function esNombreUnico($llave, $nombre, $id = null): bool
{
    $q = (is_null($id)) ? "select id from cursos where nombre=?"
        : "select id from cursos where nombre=? AND id != ?";
    $stmt = mysqli_stmt_init($llave);
    mysqli_stmt_prepare($stmt, $q);
    (is_null($id)) ? mysqli_stmt_bind_param($stmt, 's', $nombre)
        : mysqli_stmt_bind_param($stmt, 'si', $nombre, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $filas = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    if ($filas != 0) {
        $_SESSION['err_nombre'] = "*** Error el curso:  '$nombre'  ya está dado de alta";
        return false;
    }
    return true;
}


function pintarError($nomError)
{
    if (isset($_SESSION[$nomError])) {
        echo "<p class='mt-2 text-red-500 italic text-sm'>{$_SESSION[$nomError]}</p>";
        unset($_SESSION[$nomError]);
    }
}
