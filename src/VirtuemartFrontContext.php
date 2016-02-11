<?php

namespace EdmondsCommerce\BehatPrestashop;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    private $faker;

    /**
     * @var string Full name of the test customer
     */
    private $name;

    /**
     * @var string Email used by the test customer
     */
    private $email;

    /**
     * @var string Username
     */
    private $userName;
    private $password;

    /**
     * @Then I add a product to the cart
     */
    public function addAProductToTheCart()
    {
        $selector = '.latest-view .addtocart-button input.addtocart-button';
        $elements = $this->getSession()->getPage()->findAll("css", $selector);
        if(count($elements) == 0){
            throw new \Exception("Product could not be found");
        }

        /**
         * @var Behat\Mink\Element\Element $element
         */
        $element = current($elements);

        $element->click();
    }

    /** @BeforeScenario */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        date_default_timezone_set('Europe/London');
        $this->faker = \Faker\Factory::create();

        $this->faker->addProvider(new Faker\Provider\en_GB\PhoneNumber($this->faker));
    }

    /**
     * @Then /^(?:|I )fill in the billing address/
     */
    public function fillInTheBillingAddress()
    {
        $generator = new Faker\Provider\Internet($this->faker);

        $this->email = $this->faker->email;
        $this->userName = $this->faker->userName;

        $this->password = $generator->password(10,12);

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        $this->name = $firstName.' '.$lastName;
        $address1 = $this->faker->streetAddress;
        $city = $this->faker->city;
        $postcode = $this->faker->postcode;

        $this->getSession()->getPage()->fillField('email', $this->email);
        $this->getSession()->getPage()->fillField('username_field', $this->userName);
        $this->getSession()->getPage()->fillField('name_field', $this->userName);
        $this->getSession()->getPage()->fillField('password_field', $this->password);
        $this->getSession()->getPage()->fillField('password2_field', $this->password);

        $this->getSession()->getPage()->fillField('first_name', $firstName);
        $this->getSession()->getPage()->fillField('last_name', $lastName);

        $this->getSession()->getPage()->fillField('address_1', $address1);
        $this->getSession()->getPage()->fillField('zip', $postcode);
        $this->getSession()->getPage()->fillField('city', $city);

        $this->getSession()->getPage()->find('css',
            '#virtuemart_country_id_chzn')->click();

        $this->getSession()->getPage()->find('css',
            '#virtuemart_country_id_chzn .chzn-search input')->setValue('United Kingdom');
    }

    /**
     * @Then /^(?:|I )go to basket/
     */
    public function goToBasket()
    {
        $cartLink = $this->getSession()->getPage()->find('css', '#fancybox-content div a.showcart');
        $cartLinkValue = $cartLink->getAttribute('href');

        $this->visitPath($cartLinkValue);
    }
}