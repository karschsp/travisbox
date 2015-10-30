<?php
/**
 * @file search.php
 *
 * Tests the search functionality for menshealth, runnersworld, womenshealthmag
 */

require_once('./vendor/facebook/webdriver/vendor/autoload.php');

class zeusSearch extends \PHPUnit_Framework_TestCase
{
  protected $driver;
  
  protected function setUp() {
    parent::setUp();
    $capabilities = DesiredCapabilities::firefox();
    $host = 'http://localhost:4444/wd/hub';
    $this->driver = RemoteWebDriver::create($host, $capabilities, 45000);
  }

  protected function tearDown() {
    $this->driver->close();
    parent::tearDown();
  }


  public function testSearch() {
    $sites = array(
      'menshealth' => array(
        'site_url' => 'http://www.menshealth.com',
        'burger_link' => 'a#menu-btn-toggle',
      ),
	  'runnersworld' => array(
	    'site_url' => 'http://www.runnersworld.com',
	    'burger_link' => 'a.menu-icon-link',
	  ),
	  'womenshealth' => array(
	    'site_url' => 'http://www.womenshealthmag.com',
	    'burger_link' => 'a#menu-btn-toggle',
	  ),
    ); 
      
    foreach ($sites as $site) {
      $this->driver->get($site['site_url']);

	  $search_term = 'fitness';
      $search_link = $this->driver->findElement(
        WebDriverBy::className('search-submit')
      );
      $search_link->click();

      $search_field = $this->driver->findElement(
        WebDriverBy::className('search-text')
      );
      $search_field->sendKeys($search_term)->submit();
    
      $results_stats = $this->driver->findElement(
        WebDriverBy::cssSelector('.google-appliance-search-stats .placeholder')
      );
      $this->assertContains($search_term, $results_stats->getText());
    }
  }
}  
