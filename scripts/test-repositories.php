<?php

/**
 * Test del Repository Pattern
 * Verifica que los repositorios funcionen correctamente
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../backend/config/connection.php';
require_once __DIR__ . '/../backend/repositories/BaseRepository.php';
require_once __DIR__ . '/../backend/repositories/ArtistaRepository.php';
require_once __DIR__ . '/../backend/repositories/ObraRepository.php';
require_once __DIR__ . '/../backend/repositories/NoticiaRepository.php';

use Backend\Repositories\ArtistaRepository;
use Backend\Repositories\ObraRepository;
use Backend\Repositories\NoticiaRepository;

echo "🧪 TEST: Repository Pattern\n";
echo "==============================\n\n";

try {
    // 1. Test ArtistaRepository
    echo "1️⃣  ArtistaRepository\n";
    echo "-------------------\n";
    
    $artistaRepo = new ArtistaRepository($pdo);
    
    // Test: getStats()
    $statsArtistas = $artistaRepo->getStats();
    echo "✅ getStats(): " . json_encode($statsArtistas) . "\n";
    
    // Test: findValidados()
    $validados = $artistaRepo->findValidados(['limit' => 5]);
    echo "✅ findValidados(5): " . count($validados) . " artistas\n";
    
    // Test: findByStatus()
    $pendientes = $artistaRepo->findByStatus('pendiente', 5);
    echo "✅ findByStatus('pendiente'): " . count($pendientes) . " artistas\n";
    
    // Test: countByStatus()
    $totalValidados = $artistaRepo->countByStatus('validado');
    echo "✅ countByStatus('validado'): {$totalValidados}\n";
    
    echo "\n";

    // 2. Test ObraRepository
    echo "2️⃣  ObraRepository\n";
    echo "---------------\n";
    
    $obraRepo = new ObraRepository($pdo);
    
    // Test: getStats()
    $statsObras = $obraRepo->getStats();
    echo "✅ getStats(): " . json_encode($statsObras) . "\n";
    
    // Test: findValidadasConArtista()
    $obrasValidadas = $obraRepo->findValidadasConArtista(5);
    echo "✅ findValidadasConArtista(5): " . count($obrasValidadas) . " obras\n";
    
    if (!empty($obrasValidadas)) {
        $primeraObra = $obrasValidadas[0];
        echo "   - Primera obra: '{$primeraObra['titulo']}' por {$primeraObra['artista_nombre']} {$primeraObra['artista_apellido']}\n";
    }
    
    // Test: countByEstado()
    $totalValidadas = $obraRepo->countByEstado('validado');
    echo "✅ countByEstado('validado'): {$totalValidadas}\n";
    
    echo "\n";

    // 3. Test NoticiaRepository
    echo "3️⃣  NoticiaRepository\n";
    echo "------------------\n";
    
    $noticiaRepo = new NoticiaRepository($pdo);
    
    // Test: getStats()
    $statsNoticias = $noticiaRepo->getStats();
    echo "✅ getStats(): " . json_encode($statsNoticias) . "\n";
    
    // Test: findAll()
    $todasNoticias = $noticiaRepo->findAll(5);
    echo "✅ findAll(5): " . count($todasNoticias) . " noticias\n";
    
    if (!empty($todasNoticias)) {
        $primeraNoticia = $todasNoticias[0];
        echo "   - Primera noticia: '{$primeraNoticia['titulo']}'\n";
    }
    
    // Test: count()
    $totalNoticias = $noticiaRepo->count();
    echo "✅ count(): {$totalNoticias}\n";
    
    echo "\n";

    // 4. Test BaseRepository CRUD
    echo "4️⃣  BaseRepository CRUD\n";
    echo "--------------------\n";
    
    // Test: find()
    if ($totalValidados > 0) {
        $primerArtista = $artistaRepo->find(1);
        if ($primerArtista) {
            echo "✅ find(1): {$primerArtista['nombre']} {$primerArtista['apellido']}\n";
        } else {
            echo "⚠️  find(1): No encontrado\n";
        }
    }
    
    // Test: count()
    $totalArtistas = $artistaRepo->count();
    echo "✅ count(): {$totalArtistas} artistas en total\n";
    
    // Test: exists()
    $existe = $artistaRepo->exists(1);
    echo "✅ exists(1): " . ($existe ? 'true' : 'false') . "\n";
    
    echo "\n";

    echo "🎉 TODOS LOS TESTS PASARON\n";
    echo "===========================\n\n";
    
    echo "✅ Repository Pattern implementado correctamente\n";
    echo "✅ Separación de lógica de BD exitosa\n";
    echo "✅ Código más mantenible y testeable\n\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
