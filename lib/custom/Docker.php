<?php declare(strict_types=1);

namespace StudioIQ\Phake;

require_once 'Terminal.php';
require_once 'OS.php';

// TODO - Replace all this with https://github.com/docker-php/docker-php

class Docker {

    public static function assertServicesRunning(array $services) {
        if(empty($services)) {
            throw new Exception("Missing services");
        }

        // Nothing to do
        if(count($services) === 0) {
            return;
        }

        $output = '';

        foreach($services as $service) {
            $result = OS::execute('docker-compose ps -q ' . $service);

            if ($result != 0) {
                Terminal::fail('The ' . $service . ' service is not running.');
                throw new Exception;
            }
        }
    }

    public static function up(string $services = '') {
        OS::execute('docker-compose up --detach ' . $services);
    }

    public static function startServices(string $services = '') {
        OS::execute('docker-compose start ' . $services);
    }

    public static function stopServices(array $services = []) {
        if($services) {
            self::assertServicesRunning($services);
        }

        OS::execute('docker-compose stop ' . implode(' ', $services));
    }

    public static function restartServices(array $services = []) {
        if($services) {
            self::assertServicesRunning($services);
        }

        OS::execute('docker-compose restart ' . implode(' ', $services));
    }

    public static function deleteServices(array $services = []) {
        if($services) {
            self::assertServicesRunning($services);
        }

        foreach($services as $service) {
            OS::execute(sprintf('docker-compose stop %s && docker-compose rm %s', $service, $service));
        }
    }

    public static function down() {
        OS::execute('docker-compose down');
    }

    public static function list() {
        OS::execute('docker-compose ps');
    }

    // This gets the public port for any given private port and service name.
    // TODO use inspect / config related functionality so the service doesn't have to be running to get the value.
    public static function getServicePublicPort(int $privatePort, string $service) {
        self::assertServicesRunning([$service]);

        $port = '';
        if (OS::execute('docker-compose port ' . $service . ' ' . $privatePort . ' | cut -d: -f2', $port) != 0) {
          Terminal::fail('Could not find the port for the ' . $service . ' - Is it running?');
          throw new Exception;
        }

        return trim($port);
    }

    public static function executeShellCommand(string $command, string $service, bool $silent = false, string $user = null) : int {
        self::assertServicesRunning([$service]);

        if(empty($command)) {
            throw Exception("No command specified");
        }

        if(empty($service)) {
            throw Exception("No sevice specified");
        }

        $user_string = '';
        if(!empty($user)) {
            $user_string = '--user ' . $user . ' ';
        }

        if($silent) {
            // Disable pseudo-tty
            return OS::executeSilently('docker-compose exec ' . $user_string . ' -T ' . $service . ' ' . $command);
        } else {
            return OS::execute('docker-compose exec ' . $user_string . $service . ' ' . $command);
        }
    }

    public static function excuteShellCommandSilently(string $command, string $service) {
        self::executeShellCommand($command, $service, $silent = true);
    }

    // This relies on Bash being available on the service.
    public static function shell(string $service = 'appserver') {
        if(OS::execute('docker-compose exec ' . $service . ' bash') != 0) {
            Terminal::fail("No command specified");
            return;
        }
    }
}
