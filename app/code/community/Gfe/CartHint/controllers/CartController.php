<?php

require 'app/code/core/Mage/Checkout/controllers/CartController.php';

class Gfe_CartHint_CartController extends Mage_Checkout_CartController {
    public function indexAction() {
        if ($this->_getQuote()->hasItems()) {
            $sum = $this->_getQuote()->collectTotals()->getGrandTotal();
            $offset = $this->parsefloat(Mage::getStoreConfig('carthint/settings/offset'));

            $h = Mage::helper('core');

            if ($sum < $offset) {
                $this->_getSession()->addNotice(
                        $this->__("You're only %s short of getting free shipping (starts at %s)!", $h->formatPrice($offset - $sum, false), $h->formatPrice($offset, false))
                );
            } else {
                $this->_getSession()->addSuccess($this->__("Shipping is free for you!"));
            }
        }
        return parent::indexAction();
    }

    /*
     * taken from php.net
     */    
    public function parsefloat($str) {
        if (strstr($str, ",")) {
            $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs 
            $str = str_replace(",", ".", $str); // replace ',' with '.' 
        }

        if (preg_match("#([0-9\.]+)#", $str, $match)) { // search for number that may contain '.' 
            return floatval($match[0]);
        } else {
            return floatval($str); // take some last chances with floatval 
        }
    }    
}