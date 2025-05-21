<?php
namespace ThunderbirdDeveloper\Bifrost;

use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Composer\Plugin\Capable;

class ScriptCrafterPlugin implements PluginInterface, Capable
{
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
    public function activate(Composer $composer, IOInterface $io)
    {
        // Plugin aktywowany – tu możesz zarejestrować logikę
        $io->write("[ScriptCrafter] Plugin aktywowany. Spróbuję utworzyć symlink...");
        $target = __DIR__ . '/../bin/sc';
        $link = getcwd() . '/sc';
        $this->createSymlink($target, $link);
        $io->write("[ScriptCrafter] Utworzono symlink: sc -> bin/sc");
    }

    public function deactivate(Composer $composer, IOInterface $io) {}
    public function uninstall(Composer $composer, IOInterface $io) {}

    public function getCapabilities()
    {
        return [];
    }
}
