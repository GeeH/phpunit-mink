<?php

use aik099\PHPUnit\BrowserTestCase;

class PerTestCaseBrowserConfigTest extends BrowserTestCase
{

    public static $browsers = array(
        array(
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://www.google.com',
        ),
        array(
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'chrome',
            'baseUrl' => 'http://www.google.com',
        ),
    );

}
