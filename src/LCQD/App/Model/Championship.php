<?php

/**
 * This file is part of the App package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\App\Model;

use LCQD\App\Collection\GameCollection;
use LCQD\App\Collection\TeamCollection;
use LCQD\App\Loader\LoaderInterface;
use LCQD\App\Loader\CsvFileLoader;
use LCQD\App\Model\Team;

/**
 * Championship
 *
 * Championship is used to handle games and teams of a same resource
 *
 * @package App
 * @author lechatquidanse
 */
class Championship
{
    /**
     * Games
     *
     * @var GameCollection $games
     */
    protected $games;

    /**
     * Teams
     *
     * @var TeamCollection $games
     */
    protected $teams;

    /**
     * Resource
     * 
     * @var string $resource
     */
    protected $resource;

    /**
     * Format
     * 
     * @var string $format
     */
    protected $format;

    /**
     * Loaders
     *
     * @var LoaderInterface[]
     */
    private $loaders = array();

    /**
     * Constructor
     *
     * @return Championship
     */
    public function __construct()
    {
        $this->initLoaders();
    }

    /**
     * Sort Teams By Points
     * 
     * @param  boolean $sortByGoalAverage if true sort tied teams by goal difference
     */
    public function sortTeamsByPoints($sortByGoalDiff = true)
    {
        $iterator = $this->teams->getIterator();
        $iterator->uasort(function ($teamA, $teamB) use ($sortByGoalDiff) {
            $pointDiff = $teamA->getPoints() - $teamB->getPoints();
            $goalDiff = $teamA->getGoalDiff() - $teamB->getGoalDiff();

            if ($pointDiff > 0) {
                return -1;
            } elseif ($pointDiff == 0 && $sortByGoalDiff) {
                if ($goalDiff > 0 || $goalDiff == 0) {
                    return -1;
                } else {
                    return 1;
                }
            } else {
                return 1;
            }
        });

        $this->teams = new TeamCollection(iterator_to_array($iterator));
    }

    /**
     * Load
     *
     * Load resource according to the format to store informations in games and teams
     * Sort teams loaded by points DESC
     *
     * @param string $resource
     * @param string $format 'csv' and for future loader 'xml', 'json'...
     */
    public function load($resource, $format)
    {
        $this->loaders[$format]->load($resource);
        $this->resource = $resource;
        $this->format = $format;
        $this->games = $this->loaders[$format]->getGames();
        $this->teams = $this->loaders[$format]->getTeams();
        $this->sortTeamsByPoints();
    }

    /**
     * Reload
     *
     * reload resource and format if set
     */
    public function reload()
    {
        if ($this->format && $this->resource) {
            return $this->load($this->resource, $this->format);
        }
    }

    /**
     * Find Won Game Between Tow Teams
     *
     * Find a Game won by teamA against teamB
     * 
     * @param  Team   $teamA
     * @param  Team   $teamB
     * @return Game|null if no game has been found
     */
    public function findWonGameBetweenTwoTeams(Team $teamA, Team $teamB)
    {
        foreach ($teamA->getWonGamesKey() as $gameKey) {
            $wonGame = $this->games->get($gameKey);

            if ($wonGame && in_array($teamB->getName(), array($wonGame->getHomeTeamKey(), $wonGame->getAwayTeamKey()))) {
                return $wonGame;
            }
        }
    }

    /**
     * Find Won Game By Team
     *
     * @param  Team   $team
     * @return Game|null if no game has been found
     * @todo find game with most difference goal
     */
    public function findWonGameByTeam(Team $team)
    {
        $gameKey = $team->getWonGameKey();

        if ($gameKey) {
            return $this->games->get($gameKey);
        }
    }

    /**
     * Find Drawn Game Between Tow Teams
     *
     * Find a Game drawn by teamA without teamB
     * 
     * @param  Team   $teamA
     * @param  Team   $teamB
     * @return Game|null if no game has been found
     */
    public function findDrawnGameNotBetweenTwoTeams(Team $teamA, Team $teamB)
    {
        foreach ($teamA->getDrawnGamesKey() as $gameKey) {
            $drawnGame = $this->games->get($gameKey);

            if ($drawnGame && !in_array($teamB->getName(), array($drawnGame->getHomeTeamKey(), $drawnGame->getAwayTeamKey()))) {
                return $drawnGame;
            }
        }
    }

    /**
     * Find Game To Exclude Between Tow Teams
     *
     * Find a game to exclude in teamToPass games, for team to pass teamToPass.
     * First we look for a won by teamToPass against team (to improve goal diff of team, and reduce for teamToPass).
     * If no game is found, then looking for a victory of teamToPass.
     * Otherwise a draw of teamToPass witouht team
     * 
     * @param  Team   $teamToPass
     * @param  Team   $team
     * @return Game|null if no game has been found
     */
    public function findGameToExcludeBetweenTwoTeams(Team $teamToPass, Team $team)
    {
        $game = $this->findWonGameBetweenTwoTeams($teamToPass, $team);

        if (!$game) {
            $game = $this->findWonGameByTeam($teamToPass);
        }

        if (!$game) {
            $game = $this->findDrawnGameNotBetweenTwoTeams($teamToPass, $team);
        }

        return $game;
    }

    /**
     * Find Games To Exclude To Be First
     *
     * For a team, find games to exclude for this team to be first
     * 
     * @param  Team   $team
     * @return GameCollection
     */
    public function findGamesToExcludeToBeFirst(Team $team)
    {
        $this->sortTeamsByPoints();

        $gamesExcluded = new GameCollection();
        $teamFirst = $this->teams->first();
        $countGame = $this->games->count();

        while ($team->getKey() != $teamFirst->getKey() && $countGame >= 0) {

            $gameToExclude = $this->findGameToExcludeBetweenTwoTeams($teamFirst, $team);

            if ($gameToExclude) {
                $teamFirst->deductResultsFromExcludedGame($gameToExclude);
                $gamesExcluded->set($gameToExclude->getKey(), $gameToExclude);
            }

            $this->sortTeamsByPoints();
            $teamFirst = $this->teams->first();
            $countGame--;
        }

        return $gamesExcluded;
    }

    /**
     * Find Team By Key
     * 
     * @param  string $teamKey
     * @return Team 
     */
    public function findTeamByKey($teamKey)
    {
        if (!$team = $this->teams->get($teamKey)) {
            throw new \Exception(sprintf("Error Processing Request, no team named %s", $teamKey), 1);
        }

        return $team;
    }

    /**
     * Find Games To Exclude To Be Champion
     *
     * Find for each teams stored in $teams, games that has to be excluded for that team to be champion
     * 
     * @return array of GameCollection, each array of GameCollection is identity by a key which is team key
     */
    public function findGamesToExcludeToBeChampion()
    {
        $res = array();
        $this->sortTeamsByPoints();
        $teams = $this->teams;

        foreach ($teams as $key => $team) {
            $gamesExcluded = $this->findGamesToExcludeToBeFirst($team);
            $this->reload();

            $res[$key] = $gamesExcluded;
        }

        return $res;
    }

    /**
     * Adds a Loader.
     *
     * @param string          $format The name of the loader
     * @param LoaderInterface $loader A LoaderInterface instance
     *
     */
    public function addLoader($format, LoaderInterface $loader)
    {
        $this->loaders[$format] = $loader;
    }

    /**
     * TODO: when registering services
     *
     * @return [type] [description]
     */
    public function initLoaders()
    {
        $this->loaders['csv'] = new CsvFileLoader();
    }

    /**
     * Get Games
     *
     * @return GameCollection
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Get Teams
     *
     * @return TeamCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
