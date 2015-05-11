<?php

/**
 * This file is part of the App package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\App\Handler;

use LCQD\App\Loader\LoaderInterface;
use LCQD\App\Loader\CsvFileLoader;
use LCQD\App\Collection\ChampionshipCollection;
use LCQD\App\Model\Championship;

/**
 * Championship Handler
 *
 * @package App
 * @author lechatquidanse
 */
class ChampionshipHandler
{
    /**
     * Championships
     *
     * @var ChampionshipCollection $championships
     */
    public $championships;

    /**
     * Resources
     *
     * Array of resources to load to fill championships collection
     *
     * @var array $ressources
     */
    public $resources = array();

    /**
     * Constructor
     *
     * Set championships as a ChampionshipCollection
     * Load Default Resources
     *
     * @return ChampionshipHandler
     */
    public function __construct()
    {
        $this->championships = new ChampionshipCollection();
        $this->loadDefaultResources();
    }

    /**
     * Load
     *
     * Load for each resources championship and store it in chamionships
     *
     * @throws \Exception if an errors occured during process
     * @todo log the exception
     */
    public function load()
    {
        foreach ($this->resources as $key => $data) {
            try {
                $championship = new Championship();
                $championship->load($data['resource'], $data['format']);
                $this->championships->set($key, $championship);
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * Get Championship By Key
     *
     * @param string $key
     * @return Championship
     */
    public function getChampionhsipByKey($key)
    {
        return $this->championships->get($key);
    }

    /**
     * Get Championships
     *
     * @return ChampionshipCollection
     */
    public function getChampionhsips()
    {
        return $this->championships;
    }

    /**
     * Load Default Resources
     */
    protected function loadDefaultResources()
    {
        $this->resources = array(
            'bpl1314' => array (
                    'resource' => __DIR__ . '/../Resources/data/PremierLeague1314.csv',
                    'format' => 'csv'
                )
            );
    }
}
