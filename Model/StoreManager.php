<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Model;

use Symfony\Component\DependencyInjection\Container;

use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreManagerInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class StoreManager implements StoreManagerInterface {

    protected $currentStore;
    protected $storeClass;
    protected $stores;
    protected $storesConfigurations;

    public function __construct($storeClass, $storesConfigurations) {

        $this->storeClass = $storeClass;
        $this->storesConfigurations = $storesConfigurations;

    }

    public function createStore($id, $displayName)
    {

        $baseClass = $this->storeClass;
        $store = new $baseClass;
        $store->setId($id);
        $store->setDisplayName($displayName);

        return $store;
    }

    abstract function findStoreById($id);

    public function loadStore($host)
    {

        if (!$this->stores) {

            $this->loadStoresConfigurations();
        }

        if (!$this->currentStore) {
            //err
        }

        return $this->currentStore;
    }

    public function loadStoresConfigurations()
    {
        foreach ($this->storesConfigurations as $storeID => $storeConfiguration) {

            $store = $this->createStore($storeID, $storeConfiguration['display_name']);

            $store->setOperationalMode($storeConfiguration['operational_mode']);
            $store->setSalesChannel($storeConfiguration['sales_channel']);

            $this->stores[$storeID] = $store;

            if ($storeConfiguration['default']) {

                $this->currentStore = $store;
            }
        }
    }

    public function getCurrentStore()
    {
        return $this->currentStore;
    }


    public function setCurrentStore(StoreInterface $currentStore)
    {
        $this->currentStore = $currentStore;
    }
}
