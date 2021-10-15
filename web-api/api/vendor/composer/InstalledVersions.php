<?php

namespace Composer;

use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => 'dev-master',
    'version' => 'dev-master',
    'aliases' => 
    array (
    ),
    'reference' => '1ee9f57239bf833c82e03948811960ab26965d76',
    'name' => '__root__',
  ),
  'versions' => 
  array (
    '__root__' => 
    array (
      'pretty_version' => 'dev-master',
      'version' => 'dev-master',
      'aliases' => 
      array (
      ),
      'reference' => '1ee9f57239bf833c82e03948811960ab26965d76',
    ),
    'cakephp/core' => 
    array (
      'pretty_version' => '3.9.4',
      'version' => '3.9.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '4b45635d6be8a98be175fea9c9f575de29d515b3',
    ),
    'cakephp/filesystem' => 
    array (
      'pretty_version' => '3.9.4',
      'version' => '3.9.4.0',
      'aliases' => 
      array (
      ),
      'reference' => '06014a335c840f092cc9b9b9f57c0b8e7faa09a1',
    ),
    'cakephp/utility' => 
    array (
      'pretty_version' => '3.9.4',
      'version' => '3.9.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e655b399b7492e517caef52fb87af9db10543112',
    ),
    'dilab/resumable.php' => 
    array (
      'pretty_version' => '0.1.4',
      'version' => '0.1.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ad6ec9e06a0a3c89676071ede1243f6e7a77f0a2',
    ),
    'monolog/monolog' => 
    array (
      'pretty_version' => '1.26.0',
      'version' => '1.26.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '2209ddd84e7ef1256b7af205d0717fb62cfc9c33',
    ),
    'mpdf/mpdf' => 
    array (
      'pretty_version' => 'v8.0.8',
      'version' => '8.0.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '4ce221329d0918146514605db1644b2771c5e308',
    ),
    'myclabs/deep-copy' => 
    array (
      'pretty_version' => '1.10.2',
      'version' => '1.10.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '776f831124e9c62e1a2c601ecc52e776d8bb7220',
      'replaced' => 
      array (
        0 => '1.10.2',
      ),
    ),
    'paragonie/random_compat' => 
    array (
      'pretty_version' => 'v9.99.100',
      'version' => '9.99.100.0',
      'aliases' => 
      array (
      ),
      'reference' => '996434e5492cb4c3edcb9168db6fbb1359ef965a',
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.3',
      'version' => '1.1.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '0f73288fd15629204f9d42b7055f72dacbe811fc',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0.0',
      ),
    ),
    'setasign/fpdi' => 
    array (
      'pretty_version' => 'v2.3.5',
      'version' => '2.3.5.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f2246c8669bd25834f5c264425eb0e250d7a9312',
    ),
  ),
);







public static function getInstalledPackages()
{
return array_keys(self::$installed['versions']);
}









public static function isInstalled($packageName)
{
return isset(self::$installed['versions'][$packageName]);
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

$ranges = array();
if (isset(self::$installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = self::$installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', self::$installed['versions'][$packageName])) {
$ranges = array_merge($ranges, self::$installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}





public static function getVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['version'])) {
return null;
}

return self::$installed['versions'][$packageName]['version'];
}





public static function getPrettyVersion($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return self::$installed['versions'][$packageName]['pretty_version'];
}





public static function getReference($packageName)
{
if (!isset(self::$installed['versions'][$packageName])) {
throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}

if (!isset(self::$installed['versions'][$packageName]['reference'])) {
return null;
}

return self::$installed['versions'][$packageName]['reference'];
}





public static function getRootPackage()
{
return self::$installed['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
}
}
