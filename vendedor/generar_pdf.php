<?php
require('libs/fpdf.php'); // Ajusta la ruta de acuerdo a la ubicación de la carpeta

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
    $sql = "SELECT f.id, c.nombre AS nombre_cliente, f.total, f.fecha_factura, f.detalles
            FROM facturas f
            JOIN clientes c ON f.cliente_id = c.id
            WHERE f.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$factura_id]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        die("Factura no encontrada.");
    }

    // Crear un nuevo PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Encabezado de la factura
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Factura', 0, 1, 'C');
    $pdf->Ln(10);

    // Información de la factura
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'ID Factura: ' . $factura['id']);
    $pdf->Ln(8);
    $pdf->Cell(40, 10, 'Cliente: ' . $factura['nombre_cliente']);
    $pdf->Ln(8);
    $pdf->Cell(40, 10, 'Total: $' . $factura['total']);
    $pdf->Ln(8);
    $pdf->Cell(40, 10, 'Fecha: ' . $factura['fecha_factura']);
    $pdf->Ln(8);
    $pdf->Cell(40, 10, 'Detalles: ' . $factura['detalles']);
    $pdf->Ln(10);

    // Salida del PDF
    $pdf->Output('I', 'Factura_' . $factura['id'] . '.pdf');
} else {
    die("ID de factura no proporcionado.");
}
?>
