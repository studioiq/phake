<?php declare(strict_types=1);

namespace StudioIQ\Phake;

Class Path {

    /*
     * Our Phake tasks all assume that they're running from the directory the Phakefile is in.
     * This command always resets the CWD to the path of the Phakefile (which will always be at the Git root)
     */
    public static function resetWorkingDirectory() {
        if(defined(GIT_ROOT)) {
            chdir(GIT_ROOT);
        } else {
            chdir(dirname(dirname(__FILE__)));
        }
    }

    public static function pushd(string $dir) {
        array_push($GLOBALS['dirstack'], getcwd());
        chdir($dir);
    }

    public static function popd() : string {
        $dir = array_pop($GLOBALS['dirstack']);
        assert($dir !== null);
        chdir($dir);
        return $dir;
    }
}
