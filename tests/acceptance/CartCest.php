<?php

class CartCest
{
    public function scenarioOne(AcceptanceTester $I)
    {
        $I->amOnPage('/1');
        $I->see('Blue Widget');
        $I->see('Green Widget');
        $I->dontSee('Red Widget');
        $I->see('Shipping');
        $I->dontSee('Discount');
        $I->see('Total');
    }

    public function scenarioTwo(AcceptanceTester $I)
    {
        $I->amOnPage('/2');
        $I->dontSee('Blue Widget');
        $I->dontSee('Green Widget');
        $I->see('Red Widget');
        $I->see('Shipping');
        $I->see('Discount');
        $I->see('Total');
    }

    public function scenarioThree(AcceptanceTester $I)
    {
        $I->amOnPage('/3');
        $I->dontSee('Blue Widget');
        $I->see('Green Widget');
        $I->see('Red Widget');
        $I->see('Shipping');
        $I->dontSee('Discount');
        $I->see('Total');
    }

    public function scenarioFour(AcceptanceTester $I)
    {
        $I->amOnPage('/4');
        $I->see('Blue Widget');
        $I->dontSee('Green Widget');
        $I->see('Red Widget');
        $I->dontSee('Shipping');
        $I->see('Discount');
        $I->see('Total');
    }
}
