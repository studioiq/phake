<?php declare(strict_types=1);

require_once "Environ.php";
require_once 'Docker.php';
require_once 'Terminal.php';

class Web {
    
    function reloadWebServerConfig(string $web_service_name = 'webserver') {
        Docker::assertServicesRunning($web_service_name);
    
        shout('Reloading Apache configuration ...');
        
        if(Docker::executeShellCommand('apache2ctl -k graceful', $web_service_name) != 0) {
            Terminal::fail('Could not reload Apache configuration');
            throw new Exception;
        }
    }
}