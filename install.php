<?php
// Sprawdzenie, czy podano opcję --no-vendor
$useLocalBin = in_array('--no-vendor', $argv, true);

// Ustawienia ścieżek
$target = __DIR__ . ($useLocalBin ? '/bin/sc' : '/vendor/bin/sc');
$link = __DIR__ . '/sc';

// Informacja diagnostyczna
echo "[ScriptCrafter] Tryb instalacji: " . ($useLocalBin ? "LOCAL BIN (bin/sc)" : "VENDOR BIN (vendor/bin/sc)") . "\n";

if (!file_exists($target)) {
    echo "[ScriptCrafter] Nie znaleziono pliku $target\n";
    exit(1);
}

if (file_exists($link) || file_exists($link . '.bat')) {
    echo "[ScriptCrafter] Link $link już istnieje\n";
    exit(0);
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // Windows – twórz .bat jako wrapper
    $binPathForBat = str_replace('/', '\\', $target);
    $batContent = <<<BAT
@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0{$binPathForBat}
SET COMPOSER_RUNTIME_BIN_DIR=%~dp0
php "%BIN_TARGET%" %*
BAT;
    file_put_contents(__DIR__ . '/sc.bat', $batContent);
    echo "[ScriptCrafter] Utworzono sc.bat dla Windows\n";
} else {
    // Unix/Linux/Mac – próbuj symlink
    if (symlink($target, $link)) {
        echo "[ScriptCrafter] Symlink stworzony: $link -> $target\n";
    } else {
        echo "[ScriptCrafter] Nie udało się stworzyć symlinku\n";
    }
}
