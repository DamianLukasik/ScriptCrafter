<?php
namespace ThunderbirdDeveloper\Bifrost;

use Composer\Plugin\PluginInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Composer\Plugin\Capable;

class ScriptCrafterPlugin implements PluginInterface, Capable
{
    private $namePlugin = "\e[32m[ScriptCrafter]\e[0m";

    function createSymlink(&$target, &$link)
    {
        // Unix – try symlink, fallback to copy
        if (@symlink($target, $link)) {
            echo $this->namePlugin . " Symlink created: $link -> $target\n";
        } else {
            echo $this->namePlugin . " Failed to create symlink, attempting to copy the file...\n";
            if (copy($target, $link)) {
                echo $this->namePlugin . " File copied to: $link\n";
            } else {
                echo $this->namePlugin . " Failed to copy the file.\n";
            }
        }
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        // Plugin activated – you can register logic here
        $io->write($this->namePlugin . " Plugin activated. Attempting to create symlink...");
        $target = __DIR__ . '/../bin/sc';
        $link = getcwd() . '/sc';
        $this->createSymlink($target, $link);
        $io->write($this->namePlugin . " Symlink created: sc -> bin/sc");
    }

    public function deactivate(Composer $composer, IOInterface $io) {}
    public function uninstall(Composer $composer, IOInterface $io) {}

    public function getCapabilities()
    {
        return [];
    }
}
