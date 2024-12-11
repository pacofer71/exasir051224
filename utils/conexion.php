    <?php
    try {
        $llave = mysqli_connect('127.0.0.1', 'user', 'secret0', 'exasir1');
    } catch (Exception $ex) {
        throw new Exception("error en la conexion: " . $ex->getMessage());
    }
