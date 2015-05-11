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
use LCQD\App\Model\Team;

/**
 * Team Collection
 *
 * @package App
 * @author lechatquidanse
 */
class TeamCollection extends ArrayCollection
{
    /**
     * Set
     * 
     * @param string $key
     * @param object $team
     */
    public function set($key, $team)
    {
        if (!($team instanceof Team)) {
            throw new \Exception(sprintf("Error TeamCollection team %s is not LCQD\App\Model\Team", $key), 1);
        }

        parent::set($key, $team);
    }
}
