// Conexión a la base de datos usando PDO
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thunderbike";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Controlador de productos
class ConexionProductos {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Obtener el historial de productos de un proveedor
    public function obtenerHistorialProveedor($proveedorId) {
        $sql = "
            SELECT p.nombre, p.descripcion, p.precio, p.cantidad, p.imagen, hp.fecha_entrega
            FROM historial_proveedores hp
            JOIN productos p ON hp.producto_id = p.id
            WHERE hp.proveedor_id = :proveedor_id
            ORDER BY hp.fecha_entrega DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':proveedor_id' => $proveedorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Instanciar el controlador de productos
$conexionProductos = new ConexionProductos($pdo);

// Verificar si se ha pasado el ID del proveedor
if (isset($_GET['proveedor_id'])) {
    $proveedorId = $_GET['proveedor_id'];
    $historialProductos = $conexionProductos->obtenerHistorialProveedor($proveedorId);
} else {
    die("No se ha proporcionado un ID de proveedor.");
}
?>

<!-- Mostrar historial de productos del proveedor -->
<div class="historial-proveedor">
    <h2>Historial de Productos del Proveedor</h2>

    <?php if (empty($historialProductos)): ?>
        <p>No se encontraron productos para este proveedor.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th>Fecha de Entrega</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historialProductos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                        <td><?php echo number_format($producto['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($producto['cantidad']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del producto" width="50"></td>
                        <td><?php echo date('d-m-Y H:i', strtotime($producto['fecha_entrega'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
