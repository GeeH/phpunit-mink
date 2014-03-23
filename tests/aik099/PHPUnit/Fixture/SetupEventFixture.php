<?php
/**
 * This file is part of the phpunit-mink library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/phpunit-mink
 */

namespace tests\aik099\PHPUnit\Fixture;


use aik099\PHPUnit\Application;
use aik099\PHPUnit\BrowserConfiguration\IBrowserConfigurationFactory;
use aik099\PHPUnit\BrowserConfiguration\SauceLabsBrowserConfiguration;
use aik099\PHPUnit\BrowserTestCase;
use Behat\Mink\Session;
use Mockery as m;

class SetupEventFixture extends BrowserTestCase
{

	/**
	 * Creating browser configuration that would listen for events.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$api_client = m::mock('WebDriver\\SauceLabs\\SauceRest');
		$api_client->shouldReceive('updateJob')->withAnyArgs()->once();

		/** @var IBrowserConfigurationFactory $factory */
		$factory = m::mock('aik099\\PHPUnit\\BrowserConfiguration\\IBrowserConfigurationFactory');
		$factory->shouldReceive('createAPIClient')->once()->andReturn($api_client);

		$browser_config = array('api_username' => 'a', 'api_key' => 'b');
		$browser = new SauceLabsBrowserConfiguration($this->readAttribute($this, '_eventDispatcher'), $factory);
		$factory->shouldReceive('createBrowserConfiguration')->with($browser_config, $this)->once()->andReturn($browser);
		$this->setBrowserConfigurationFactory($factory);

		$this->setBrowserFromConfiguration($browser_config);

		parent::setUp();
	}

	/**
	 * Test description.
	 *
	 * @return void
	 */
	public function testEvents()
	{
		$driver = m::mock('\\Behat\\Mink\\Driver\\Selenium2Driver');
		$driver->shouldReceive('getWebDriverSessionId')->once()->andReturn('SID');

		$session = m::mock('Behat\\Mink\\Session');

		// For SauceLabsBrowserConfiguration::onTestEnded.
		$session->shouldReceive('getDriver')->once()->andReturn($driver);

		// For IsolatedSessionStrategy::onTestEnd.
		$session->shouldReceive('stop')->once();

		$this->_setSession($session);

		// For SauceLabsBrowserConfiguration::onTestSetup.
		$desired_capabilities = $this->getBrowser()->getDesiredCapabilities();
		$this->assertArrayHasKey(SauceLabsBrowserConfiguration::NAME_CAPABILITY, $desired_capabilities);
	}

	/**
	 * Replaces session with a given one.
	 *
	 * @param Session $session Session.
	 *
	 * @return void
	 */
	private function _setSession(Session $session)
	{
		$property = new \ReflectionProperty('aik099\\PHPUnit\\BrowserTestCase', '_session');
		$property->setAccessible(true);
		$property->setValue($this, $session);
	}

}
