<?php
// Conexión a la base de datos usando PDO
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thunderbike";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si la columna 'imagen' existe
    $stmt = $pdo->query("SHOW COLUMNS FROM productos LIKE 'imagen'");
    if ($stmt->rowCount() == 0) {
        // Si la columna no existe, la añadimos
        $pdo->exec("ALTER TABLE productos ADD COLUMN imagen VARCHAR(255)");
        echo "Columna 'imagen' añadida con éxito.";
    }
    // Verificar si la columna 'cantidad' existe
    $stmt = $pdo->query("SHOW COLUMNS FROM productos LIKE 'cantidad'");
    if ($stmt->rowCount() == 0) {
    // Si la columna no existe, la añadimos
    $pdo->exec("ALTER TABLE productos ADD COLUMN cantidad INT DEFAULT 0");
    echo "Columna 'cantidad' añadida con éxito.";
}
} catch (PDOException $e) {
    die("Error de conexión o modificación de la tabla: " . $e->getMessage());
}

// Controlador de productos
class ConexionProductos {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Crear un nuevo producto
// Crear un nuevo producto y reflejarlo en la tabla de insumos
public function crearProducto($nombre, $descripcion, $precio, $cantidad, $imagenArchivo) {
    $directorio = "uploads/";

    // Verificar si la carpeta 'uploads' existe, y si no, crearla
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombreArchivo = basename($imagenArchivo['name']);
    $rutaArchivo = $directorio . $nombreArchivo;
    $tipoArchivo = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));

    // Validar que el archivo es una imagen
    $check = getimagesize($imagenArchivo['tmp_name']);
    if ($check !== false) {
        // Mover el archivo subido a la carpeta 'uploads'
        if (move_uploaded_file($imagenArchivo['tmp_name'], $rutaArchivo)) {
            // Iniciar una transacción para asegurar integridad
            $this->pdo->beginTransaction();

            try {
                // Insertar producto en la base de datos con la ruta de la imagen
                $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen) VALUES (:nombre, :descripcion, :precio, :cantidad, :imagen)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':descripcion' => $descripcion,
                    ':precio' => $precio,
                    ':cantidad' => $cantidad,
                    ':imagen' => $rutaArchivo
                ]);

                // Obtener el ID del producto recién insertado
                $productoId = $this->pdo->lastInsertId();

                // Insertar insumo relacionado al producto en la tabla 'insumos'
                $sqlInsumo = "INSERT INTO insumos (producto_id, nombre, cantidad, descripcion, imagen) VALUES (:producto_id, :nombre, :cantidad, :descripcion, :imagen)";
                $stmtInsumo = $this->pdo->prepare($sqlInsumo);
                $stmtInsumo->execute([
                    ':producto_id' => $productoId,
                    ':nombre' => $nombre, // Suponiendo que el insumo tiene el mismo nombre que el producto
                    ':cantidad' => $cantidad, // Suponiendo que la cantidad es igual
                    ':descripcion' => $descripcion,
                    ':imagen' => $rutaArchivo
                ]);

                // Confirmar la transacción
                $this->pdo->commit();
            } catch (Exception $e) {
                // En caso de error, deshacer la transacción
                $this->pdo->rollBack();
                throw new Exception("Error al insertar el producto y su insumo: " . $e->getMessage());
            }
        } else {
            throw new Exception("Error al mover la imagen a la carpeta 'uploads'. Verifica los permisos y la ruta.");
        }
    } else {
        throw new Exception("El archivo no es una imagen válida.");
    }
}


    // Obtener todos los productos
    public function obtenerProductos($offset = 0, $limit = 10) {
        $sql = "SELECT * FROM productos LIMIT :offset, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener el número total de productos
    public function obtenerTotalProductos() {
        $sql = "SELECT COUNT(*) FROM productos";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }

    // Obtener un producto por ID
    public function obtenerProductoPorId($id) {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar un producto
    public function actualizarProducto($id, $nombre, $descripcion, $precio, $cantidad, $imagenArchivo) {
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, cantidad = :cantidad, imagen = :imagen WHERE id = :id";
    
        $rutaArchivo = '';
        if ($imagenArchivo['tmp_name'] !== '') {
            // Subir la nueva imagen si se ha proporcionado
            $directorio = "uploads/";
    
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
    
            $nombreArchivo = basename($imagenArchivo['name']);
            $rutaArchivo = $directorio . $nombreArchivo;
    
            if (!move_uploaded_file($imagenArchivo['tmp_name'], $rutaArchivo)) {
                throw new Exception("Error al mover la imagen a la carpeta 'uploads'.");
            }
        }
    
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':cantidad' => $cantidad,
            ':imagen' => $rutaArchivo
        ]);
    }
    

    // Eliminar un producto
    public function eliminarProducto($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

// Instanciar el controlador de productos
$conexionProductos = new ConexionProductos($pdo);

// Manejar acciones del CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'crear':
                $conexionProductos->crearProducto($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['cantidad'],$_FILES['imagen']);
                break;
            case 'actualizar':
                $conexionProductos->actualizarProducto($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['cantidad'], $_FILES['imagen']);
                break;
            case 'eliminar':
                $conexionProductos->eliminarProducto($_POST['id']);
                break;
        }
    }
}

// Número de productos por página
$productosPorPagina = 6;

// Calcular la página actual
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$paginaActual = max(1, $paginaActual);

// Calcular el desplazamiento
$offset = ($paginaActual - 1) * $productosPorPagina;

// Obtener el total de productos y el número total de páginas
$totalProductos = $conexionProductos->obtenerTotalProductos();
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// Obtener los productos para la página actual
$productos = $conexionProductos->obtenerProductos($offset, $productosPorPagina);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="icon" type="image/png" href="img/thunderbikes.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            position: relative;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Evitar el desbordamiento horizontal */
            color: black; /* Color negro para el texto */
            background-color: #808080; /* Gris */
        }
        /* Estilo para el video de fondo */
        .background-video {
        position: fixed; /* Cambia a fixed para que el video se quede fijo en la pantalla */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1; /* Colocar el video detrás del contenido */
        }
        .navtop {
            background-color: goldenrod;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1; /* Asegurarse de que la navegación esté encima del video */
        }
        .navtop a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .navtop a:hover {
            text-decoration: underline;
        }
        .btn-primary {
            background-color: goldenrod;
            border: none;
        }
        .btn-primary:hover {
            background-color: darkgoldenrod;
        }
        .formulario-producto {
            display: none;
        }
        .card {
            border: 1px solid #ddd; /* Borde gris claro alrededor de la tarjeta */
            border-radius: 5px; /* Bordes ligeramente redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra ligera para profundidad */
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco semi-transparente */
            transition: box-shadow 0.3s; /* Transición suave para el efecto de sombra */
        }
        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Efecto de sombra al pasar el mouse */
        }
        .card-img-top {
            border-radius: 5px 5px 0 0; /* Bordes redondeados en la parte superior de la imagen */
        }
        .card-title, .card-text {
            color: #000; /* Color negro para los textos dentro de la tarjeta */
        }
        .modal-content {
            border-radius: 10px;
        }
        /* Asegurar que el contenido no quede oculto detrás del video */
        .container {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
<nav class="navtop">
        <div>
            <div class="container">
                <div class="button-container">
                    <!-- Botones de navegación -->
                    <a href="inicio.php" id="indexBtn" class="button">Inicio</a>
                    <a href="perfil.php" id="perfilBtn" class="button">Perfil</a>
                    <a href="usuarios.php" id="usuariosBtn" class="button">Usuarios</a>
                    <a href="clientes.php" id="clientesBtn" class="button">Clientes</a>
                    <a href="proveedores.php" id="proveedoresBtn" class="button active">Proveedores</a>
                    <a href="reparaciones.php" id="reparacionesBtn" class="button">Reparaciones</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Video de fondo -->
    <video class="background-video" autoplay muted loop>
        <source src="img/montaña.mp4" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>
    
    <div class="container">
        <h1 class="text-center text-light mt-5">Productos</h1>

        <!-- Botón para agregar producto -->
        <button class="btn btn-primary btn-agregar mb-4" id="btn-agregar">Agregar Producto</button>

<!-- Formulario para crear producto -->
<form action="productos.php" method="POST" enctype="multipart/form-data" class="mb-4 formulario-producto">
    <input type="hidden" name="action" value="crear">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
    </div>
    <div class="form-group">
        <label for="precio">Precio:</label>
        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="cantidad">Cantidad:</label>
        <input type="number" class="form-control" id="cantidad" name="cantidad" min="0" required>
    </div>
    <div class="form-group">
        <label for="imagen">Subir Imagen:</label>
        <input type="file" class="form-control" id="imagen" name="imagen" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Producto</button>
</form>


        <!-- Lista de productos -->
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p class="card-text"><strong>Precio: $<?php echo number_format($producto['precio'], 2); ?></strong></p>
                            <p class="card-text"><strong>Cantidad disponible: <?php echo htmlspecialchars($producto['cantidad']); ?></strong></p>
                            <button class="btn btn-primary btn-editar" data-id="<?php echo $producto['id']; ?>"
                                data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                data-descripcion="<?php echo htmlspecialchars($producto['descripcion']); ?>"
                                data-precio="<?php echo $producto['precio']; ?>"
                                data-imagen="<?php echo $producto['imagen']; ?>"
                                data-toggle="modal" data-target="#modal-editar-producto">Editar</button>
                            <form action="productos.php" method="POST" class="d-inline">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal para Editar Producto -->
    <div class="modal fade" id="modal-editar-producto" tabindex="-1" role="dialog" aria-labelledby="modal-editar-producto-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-editar-producto-label">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-editar-producto" action="productos.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="actualizar">
                        <input type="hidden" name="id" id="producto-id">
                        <div class="form-group">
                            <label for="editar-nombre">Nombre:</label>
                            <input type="text" class="form-control" id="editar-nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="editar-descripcion">Descripción:</label>
                            <textarea class="form-control" id="editar-descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editar-precio">Precio:</label>
                            <input type="number" class="form-control" id="editar-precio" name="precio" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="editar-cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="editar-cantidad" name="cantidad" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="editar-imagen">Subir Imagen:</label>
                            <input type="file" class="form-control" id="editar-imagen" name="imagen">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Controles de paginación centrados -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- Botón "Anterior" -->
            <li class="page-item <?php echo $paginaActual == 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?pagina=<?php echo max(1, $paginaActual - 1); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Páginas -->
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?php echo $i == $paginaActual ? 'active' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>

            <!-- Botón "Siguiente" -->
            <li class="page-item <?php echo $paginaActual == $totalPaginas ? 'disabled' : ''; ?>">
                <a class="page-link" href="?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mostrar el formulario de agregar producto
            $('#btn-agregar').click(function() {
                $('.formulario-producto').toggle();
            });

            // Rellenar el modal con los datos del producto a editar
            $('.btn-editar').click(function() {
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var descripcion = $(this).data('descripcion');
                var precio = $(this).data('precio');
                var cantidad = $(this).data('cantidad');
                
                $('#producto-id').val(id);
                $('#editar-nombre').val(nombre);
                $('#editar-descripcion').val(descripcion);
                $('#editar-precio').val(precio);
                $('#editar-cantidad').val(cantidad);
            });
        });
    </script>
</body>
</html>