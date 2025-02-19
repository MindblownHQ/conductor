<?php

namespace ShopMaestro\Conductor;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class PrefixDependenciesPlugin implements PluginInterface, EventSubscriberInterface {

	public function activate( Composer $composer, IOInterface $io ) {
		$io->write( '<info>PrefixDependenciesPlugin activated!</info>' );
	}

	public function deactivate( Composer $composer, IOInterface $io ) {
		$io->write( '<info>PrefixDependenciesPlugin deactivated!</info>' );
	}

	public function uninstall( Composer $composer, IOInterface $io ) {
		$io->write( '<info>PrefixDependenciesPlugin uninstalled!</info>' );
	}

	public static function getSubscribedEvents() {
		return array(
			ScriptEvents::POST_INSTALL_CMD => 'prefixDependencies',
			ScriptEvents::POST_UPDATE_CMD  => 'prefixDependencies',
		);
	}

    public function prefixDependencies(Event $event)
    {
        $io = $event->getIO();
        $composer = $event->getComposer();
        $eventDispatcher = $composer->getEventDispatcher();
    
        $io->write("<info>🔥 Current Working Directory: " . getcwd() . "</info>");
    
        try {
            // Set working directory to Conductor's directory
            chdir(__DIR__ . '/../..'); // Navigate to vendor/shop-maestro/conductor
    
            $io->write("<info>🚀 Changing directory to: " . getcwd() . "</info>");
            $io->write("<info>🚀 Dispatching 'prefix-dependencies' script from Conductor's directory...</info>");
    
            // Dispatch the script inside Conductor
            $eventDispatcher->dispatchScript('prefix-dependencies', $event->isDevMode());
    
            $io->write("<info>✅ 'prefix-dependencies' script executed successfully!</info>");
        } catch (\Exception $e) {
            $io->write("<error>❌ Error dispatching script: " . $e->getMessage() . "</error>");
        }
    }
    
    
}
