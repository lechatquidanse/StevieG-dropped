<?php

/**
 * This file is part of the App package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\App\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use LCQD\App\Model\Championship;

/**
 * Championship Collection
 *
 * @package App
 * @author lechatquidanse
 */
class ChampionshipCollection extends ArrayCollection
{
    /**
     * Set
     * 
     * @param string $key
     * @param object $championship
     */
    public function set($key, $championship)
    {
        if (!($championship instanceof Championship)) {
            throw new \Exception(sprintf("Error ChampionshipCollection team %s is not LCQD\App\Model\Championship", $key), 1);
        }

        parent::set($key, $championship);
    }
}
