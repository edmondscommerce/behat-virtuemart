<?php

namespace EdmondsCommerce\BehatPrestashop;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class VirtuemartAdminContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    public $adminUserName;
    public $adminPassword;
    public $adminPath;

    public function __construct($adminUser, $adminPassword, $adminPath)
    {
        $this->adminUserName = $adminUser;
        $this->adminPassword = $adminPassword;
        $this->adminPath = $adminPath;
    }

    /**
     * @Then /^I go to the VirtueMart admin/
     */
    public function iGoToTheVirtueMartAdmin()
    {
        $this->visitPath('/'.$this->adminPath);
        $this->assertSession()->pageTextContains("Administration");
        $this->getSession()->getPage()->fillField('username', $this->adminUserName);
        $this->getSession()->getPage()->fillField('passwd', $this->adminPassword);

        $this->getSession()->getPage()->find('css', '.button-holder .button1 .next a')->click();
        $this->getSession()->wait(9000);
    }

    /**
     * @Then /^(?:|I )go to virtuemart order with reference of "(?P<text>(?:[^"]|\\")*)"$/
     */
    public function iGoToOrderInAdmin($orderReference)
    {
        //.icon-16-menu-icon16-orders
        $orderSectionLink = $this->getSession()->getPage()->find('css', 'a.icon-16-menu-icon16-orders');
        $orderSectionPath = $orderSectionLink->getAttribute('href');
        $this->visitPath('/'.$this->adminPath.'/'.$orderSectionPath);

        $orderPageLink = $this->getSession()->getPage()->find('css', 'a[title="Edit Order Number '.$orderReference.'"]');
        $orderPagePath = $orderPageLink->getAttribute('href');
        $this->visitPath($orderPagePath);

        $this->assertSession()->pageTextContains($orderReference);

    }

    /**
     * @Then /^(?:|I )log out of the VirtueMart admin/
     */
    public function iLogOutOfTheVirtueMartAdmin()
    {
        //logout
        $logoutElement = $this->getSession()->getPage()->find('css', 'a.icon-16-logout');
        $logoutPath = $logoutElement->getAttribute('href');
        $this->visitPath($logoutPath);
    }
}