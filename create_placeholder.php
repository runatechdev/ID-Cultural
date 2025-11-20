<?php
// Script temporal para crear imagen placeholder
$width = 400;
$height = 400;

$image = imagecreatetruecolor($width, $height);

// Colores
$bg_color = imagecolorallocate($image, 240, 240, 240);
$text_color = imagecolorallocate($image, 102, 102, 102);

// Rellenar fondo
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Texto
$text = "Perfil\nPendiente\nde\nValidacion";
$font_file = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';

// Dividir en líneas
$lines = explode("\n", $text);
$y = 150;

foreach ($lines as $line) {
    $bbox = imagettfbbox(24, 0, $font_file, $line);
    $text_width = $bbox[2] - $bbox[0];
    $x = ($width - $text_width) / 2;
    
    imagettftext($image, 24, 0, $x, $y, $text_color, $font_file, $line);
    $y += 50;
}

// Guardar
imagejpeg($image, '/home/maxii/Documentos/ID-Cultural/public/static/img/perfil_pendiente.jpg', 90);
imagedestroy($image);

echo "Imagen creada exitosamente\n";
?>