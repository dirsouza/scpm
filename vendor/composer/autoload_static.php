<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc6f27db263439c41af24b623b5d5066b
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SCMM\\' => 5,
        ),
        'F' => 
        array (
            'FontLib\\' => 8,
        ),
        'D' => 
        array (
            'Dompdf\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SCMM\\' => 
        array (
            0 => __DIR__ . '/..' . '/scmm',
        ),
        'FontLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phenx/php-font-lib/src/FontLib',
        ),
        'Dompdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/dompdf/dompdf/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Svg\\' => 
            array (
                0 => __DIR__ . '/..' . '/phenx/php-svg-lib/src',
            ),
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
            'Sabberworm\\CSS' => 
            array (
                0 => __DIR__ . '/..' . '/sabberworm/php-css-parser/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Cpdf' => __DIR__ . '/..' . '/dompdf/dompdf/lib/Cpdf.php',
        'HTML5_Data' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Data.php',
        'HTML5_InputStream' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/InputStream.php',
        'HTML5_Parser' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Parser.php',
        'HTML5_Tokenizer' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Tokenizer.php',
        'HTML5_TreeBuilder' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/TreeBuilder.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc6f27db263439c41af24b623b5d5066b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc6f27db263439c41af24b623b5d5066b::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitc6f27db263439c41af24b623b5d5066b::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitc6f27db263439c41af24b623b5d5066b::$classMap;

        }, null, ClassLoader::class);
    }
}
