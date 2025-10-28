<?php
header('Content-Type: application/json; charset=utf-8');

$baseDir = __DIR__ . '/periodicos';
$result = [];

if (is_dir($baseDir)) {
    $anos = scandir($baseDir);
    foreach ($anos as $ano) {
        if ($ano === '.' || $ano === '..') continue;
        $anoPath = "$baseDir/$ano";
        if (is_dir($anoPath)) {
            $result[$ano] = [];
            $meses = scandir($anoPath);
            foreach ($meses as $mes) {
                if ($mes === '.' || $mes === '..') continue;
                $mesPath = "$anoPath/$mes";
                if (is_dir($mesPath)) {
                    $arquivos = array_values(array_filter(scandir($mesPath), function ($f) use ($mesPath) {
                        return is_file("$mesPath/$f") && pathinfo($f, PATHINFO_EXTENSION) === 'pdf';
                    }));
                    if (!empty($arquivos)) {
                        $result[$ano][$mes] = $arquivos;
                    }
                }
            }
        }
    }
}

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
