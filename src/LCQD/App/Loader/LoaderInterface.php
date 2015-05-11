<?php

/**
 * This file is part of the App package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\App\Loader;

use LCQD\App\Collection\GameCollection;
use LCQD\App\Collection\TeamCollection;

/**
 * Loader Interface
 *
 * Load a resource of games between two teams to store it in GameCollection and TeamCollection
 *
 * @package App
 * @author lechatquidanse
 */
interface LoaderInterface
{
    /**
     * Load
     *
     * Load a resource to fill TeamCollection and GameCollection
     *
     * @param string $resource
     */
    public function load($resource);

    /**
     * Get Teams
     *
     * @return TeamCollection
     */
    public function getTeams();
   
    /**
      * Get Games
      *
      * @return GameCollection
      */
    public function getGames();
}
