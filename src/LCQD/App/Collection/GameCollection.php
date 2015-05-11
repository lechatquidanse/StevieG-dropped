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
use LCQD\App\Model\Game;

/**
 * Game Collection
 *
 * @package App
 * @author lechatquidanse
 */
class GameCollection extends ArrayCollection
{
    /**
     * Set
     * 
     * @param string $key
     * @param object $game
     */
    public function set($key, $game)
    {
        if (!($game instanceof Game)) {
            throw new \Exception(sprintf("Error GameCollection game %s is not LCQD\App\Model\Game", $key), 1);
        }

        parent::set($key, $game);
    }
}
