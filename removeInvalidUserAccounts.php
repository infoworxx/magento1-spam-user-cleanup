<?php
/**
 * Cleanup Script for Magento 1 Shops
 * for invalid users - see README.md
 *
 * inspired by:
 * https://magento.stackexchange.com/questions/71910/find-and-clean-up-unconfirmed-customer-accounts
 * https://inchoo.net/magento/delete-spam-customer-accounts-magento/
 * https://inchoo.net/magento/programming-magento/how-to-delete-magento-product-from-frontend-template-code-from-view-files/
 *
 * infoworxx GmbH 2019, Sebastian Lemke
 * WITHOUT ANY WARRANTY!
 * USE ON YOUR OWN RISK!
 * MAKE A BACKUP BEFORE USING!
 */

ini_set("display_errors", true);
define('MAGENTO_ROOT', getcwd());

require MAGENTO_ROOT . '/app/Mage.php';

Mage::app();
umask(0);   

$customers = Mage::getModel('customer/customer')
    ->getCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter(
        array(
            array('attribute' => 'confirmation', array('notnull' => true)),
        )
    )
    ->addAttributeToFilter(
        array(
            array('attribute' => 'email', 'like' => '%.ru'),
            array('attribute' => 'lastname', 'regexp' => '[a-z][A-Z]{2}'),
            array('attribute' => 'lastname', 'regexp' => '[0-9]'),
            array('attribute' => 'firstname', 'regexp' => '[0-9]'),
            array('attribute' => 'firstname', 'regexp' => '[0-9]'),
        )
      );

// addAttributeToFilter ist eine OR-Condition!

foreach ($customers as $customer) {
    $customerAddresses = $customer->getAddresses();
    if ($customerAddresses) continue;

    $customerOrders = Mage::getModel('sales/order')
        ->getCollection()
        ->addAttributeToFilter('customer_id', $customer->getId())
        ->load();
    if ($customerOrders->count()) continue;

    try {
        // Delete
        Mage::log($customer->getEmail(). " will be DELETED", null, 'removeInvalidUserAccounts.log');
        Mage::register('isSecureArea', true);
        Mage::getModel('customer/customer')->load($customer->getId())->delete();
        Mage::unregister('isSecureArea');
    } catch(Exception $e) {
        Mage::log($e->getMessage() ,null, 'removeInvalidUserAccounts.log');
        echo "exception occured, aborting!\n";
        exit(255);
    }
} // foreach

exit;

