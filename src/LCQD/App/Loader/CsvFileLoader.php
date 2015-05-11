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

use LCQD\App\Model\Team;
use LCQD\App\Model\Game;
use LCQD\App\Collection\TeamCollection;
use LCQD\App\Collection\GameCollection;

/**
 * Csv File Loader implements {@inheritdoc}
 *
 * {@inheritdoc}, for CSV file
 *
 * @package App
 * @author lechatquidanse
 */
class CsvFileLoader implements LoaderInterface
{
    /**
     * Teams
     *
     * @var TeamCollection $teams
     */
    protected $teams;

    /**
     * Games
     *
     * @var GameCollection $games
     */
    protected $games;

    /**
     * Get Team By key
     *
     * @param string $key
     * @return Team or NULL, if no element exists for the given key
     */
    private function getTeamByKey($key)
    {
        if (!$this->teams->get($key)) {
            $this->teams->set($key, new Team($key));
        }

        return $this->teams->get($key);
    }

    /**
     * Set Teams And Games From Data
     *
     * Get needed data from array data.
     * Add Game to games collection.
     * Update or create both team with game result
     *
     * @param array $data
     */
    private function setTeamsAndGamesFromData(array $data)
    {
        $gameKey = $this->getArrayValueFromKey('game_key', $data);
        $gameDate = $this->getArrayValueFromKey(1, $data);
        $homeTeamKey = $this->getArrayValueFromKey(2, $data);
        $homeTeamScore = $this->getArrayValueFromKey(4, $data);
        $awayTeamKey = $this->getArrayValueFromKey(3, $data);
        $awayTeamScore = $this->getArrayValueFromKey(5, $data);

        $game = new Game($gameKey, $gameDate, $homeTeamKey, $homeTeamScore, $awayTeamKey, $awayTeamScore);
        $this->games->set($gameKey, $game);

        $teamHome = $this->getTeamByKey($homeTeamKey);
        $teamAway = $this->getTeamByKey($awayTeamKey);

        $teamHome->deductResultsFromGame($game);
        $teamAway->deductResultsFromGame($game);
    }

    /**
     * Load
     *
     * {@inheritdoc}. Check if resource is secured.
     * For each line (except the first), of the resource, fill teams and games for data line
     */
    public function load($resource)
    {
        $this->isResourceSecured($resource);

        $this->teams = new TeamCollection();
        $this->games = new GameCollection();

        try {
            $file = new \SplFileObject($resource, 'rb');
            $it = new \LimitIterator($file, 1);
        } catch (\RuntimeException $e) {
            throw new \Exception(sprintf('Error opening file "%s".', $resource), 0, $e);
        }

        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);

        foreach ($it as $data) {
            if (is_array($data)) {
                $data['game_key'] = $it->getPosition();
                $this->setTeamsAndGamesFromData($data);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTeams()
    {
        return $this->teams;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Get Array Value From Key
     *
     * @param string $key
     * @param array $array
     * @throws \Exception if key is not found in array or value is NULL
     * @return string
     */
    private function getArrayValueFromKey($key, array $array)
    {
        if (!isset($array[$key])) {
            throw new \Exception(sprintf("CSV ressource loader no data not null found for %s key", $key), 1);
        }

        return $array[$key];
    }

    /**
     * Is Resource Secured
     *
     * @param string $resource
     * @throws \Exception exception is strown if resource is not secured
     */
    private function isResourceSecured($resource)
    {
        if (!stream_is_local($resource)) {
            throw new \Exception(sprintf('This is not a local file "%s".', $resource));
        }

        if (!file_exists($resource)) {
            throw new \Exception(sprintf('File "%s" not found.', $resource));
        }
    }
}
