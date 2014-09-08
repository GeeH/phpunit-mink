<?php

use aik099\PHPUnit\BrowserTestCase;

class CommonBrowserConfigTest extends BrowserTestCase
{

    public static $browsers = array(
        array(
            'host' => 'localhost',
            'port' => 4444,
            'browserName' => 'firefox',
            'baseUrl' => 'http://www.google.com',
            'sessionStrategy' => 'shared',
        ),
    );

}
