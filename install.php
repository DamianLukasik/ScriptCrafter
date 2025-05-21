<?php
echo "[ScriptCrafter] Install script running\n";
// Sprawdzenie, czy podano opcję --no-vendor
$useLocalBin = in_array('--no-vendor', $argv, true);

function getTargetLink($useLocalBin = false)
{
    return __DIR__ . ($useLocalBin ? '/bin/sc' : '/vendor/bin/sc');
}

// Ustawienia ścieżek
$target = getTargetLink($useLocalBin);
echo "Target: $target\n";
$link = './sc';
echo "Link: $link\n";

// Informacja diagnostyczna
echo "[ScriptCrafter] Tryb instalacji: " . ($useLocalBin ? "LOCAL BIN (bin/sc)" : "VENDOR BIN (vendor/bin/sc)") . "\n";

if (!file_exists($target)) {
    $target = getTargetLink(!$useLocalBin);
    if (!file_exists($target)) {
        echo "[ScriptCrafter] Nie znaleziono pliku $target\n";
        exit(1);
    }
}

if (file_exists($link) || file_exists($link . '.bat')) {
    echo "[ScriptCrafter] Link $link już istnieje\n";
    exit(0);
}

function createSymlink(&$target, &$link)
{
    // Unix – używaj symlink, ale fallback na copy
    if (@symlink($target, $link)) {
        echo "[ScriptCrafter] Symlink stworzony: $link -> $target\n";
    } else {
        echo "[ScriptCrafter] Nie udało się stworzyć symlinku, próbuję kopiować...\n";
        if (copy($target, $link)) {
            echo "[ScriptCrafter] Skopiowano plik do: $link\n";
        } else {
            echo "[ScriptCrafter] Nie udało się skopiować pliku.\n";
        }
    }
}

createSymlink($target, $link);