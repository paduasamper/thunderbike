<?php
require('LIBS/fpdf.php'); // Ajusta la ruta de acuerdo a la ubicación de la carpeta

// Conexión a la base de datos
$dsn = 'mysql:host=localhost;dbname=thunderbike;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Verificar que se reciba el ID de la factura
if (isset($_GET['id'])) {
    $factura_id = $_GET['id'];

    // Obtener datos de la factura desde la base de datos
    $sql = "SELECT f.id, c.nombre AS nombre_cliente, f.total, f.fecha_factura, f.productos, f.cantidad, f.vendedor
            FROM facturas f
            JOIN clientes c ON f.cliente_id = c.id
            WHERE f.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$factura_id]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        die("Factura no encontrada.");
    }

    // Decodificar productos
    $productos = json_decode($factura['productos'], true);
    $cantidades = explode(',', $factura['cantidad']); // Divide la lista de cantidades

    // Calcular subtotal e IVA (19%)
    $total = (float) $factura['total'];
    $iva = $total * 0.19;
    $subtotal = $total - $iva;

    // Crear un nuevo PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Agregar logo
    $logoPath = 'img/thunderbikes.png';
    $pdf->Image($logoPath, 80, 10, 50); // Centrado en la página (ajusta según tus necesidades)
    $pdf->Ln(30); // Salto de línea para separar el logo del resto del contenido

    // Título de la factura
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Factura', 0, 1, 'C');
    $pdf->Ln(10);

    // Información de la factura
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'ID Factura: ' . $factura['id'], 0, 1);
    $pdf->Cell(0, 10, 'Cliente: ' . $factura['nombre_cliente'], 0, 1);
    $pdf->Cell(0, 10, 'Fecha: ' . $factura['fecha_factura'], 0, 1);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Vendedor: ' . $factura['vendedor'], 0, 1);

    // Tabla de productos
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(190, 10, 'Productos', 1, 1, 'C');

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(140, 10, 'Descripcion', 1, 0, 'C');
    $pdf->Cell(50, 10, 'Cantidad', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);
    if ($productos && $cantidades) {
        foreach ($productos as $index => $producto) {
            $descripcion = htmlspecialchars(trim($producto));
            $cantidad = isset($cantidades[$index]) ? trim($cantidades[$index]) : '0';
            $pdf->Cell(140, 10, $descripcion, 1, 0, 'L');
            $pdf->Cell(50, 10, $cantidad, 1, 1, 'C');
        }
    } else {
        $pdf->Cell(190, 10, 'No se registraron productos.', 1, 1, 'C');
    }

    $pdf->Ln(10);

    // Tabla con detalles financieros
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 10, 'Descripcion', 1, 0, 'C');
    $pdf->Cell(95, 10, 'Cantidad', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 10, 'Subtotal', 1, 0, 'C');
    $pdf->Cell(95, 10, '$' . number_format($subtotal, 2), 1, 1, 'C');

    $pdf->Cell(95, 10, 'IVA (19%)', 1, 0, 'C');
    $pdf->Cell(95, 10, '$' . number_format($iva, 2), 1, 1, 'C');

    $pdf->Cell(95, 10, 'Total', 1, 0, 'C');
    $pdf->Cell(95, 10, '$' . number_format($total, 2), 1, 1, 'C');

    $pdf->Ln(20); // Salto de línea antes del pie de página

    // Pie de página
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Gracias por su compra. Thunderbike.', 0, 1, 'C');

    // Salida del PDF
    $pdf->Output('I', 'Factura_' . $factura['id'] . '.pdf');
} else {
    die("ID de factura no proporcionado.");
}
?>
