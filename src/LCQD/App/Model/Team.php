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

use LCQD\App\Model\Game;

/**
 * Team
 *
 * Team is used to model a team
 *
 * @package App
 * @author lechatquidanse
 */
class Team
{
    /**
     * Name
     * 
     * @var string $name
     */
    public $name;

    /**
     * Goals For
     *
     * Number of goal taken by Team
     *  
     * @var integer $goalFor
     */
    public $goalFor = 0;

    /**
     * Goals Against
     *
     * Number of goal scored by Team
     *  
     * @var integer $goalAgainst
     */
    public $goalAgainst = 0;

    /**
     * Points
     *
     * Number of game points
     *  
     * @var integer $points
     */
    public $points = 0;

    /**
     * Won Games Key
     *
     * Array of Game key won by Team
     *  
     * @var array $wonGamesKey
     */
    public $wonGamesKey;

    /**
     * Drawn Games Key
     *
     * Array of Game key drawn by Team
     *  
     * @var array $drawnGamesKey
     */
    public $drawnGamesKey;

    /**
     * Lost Games Key
     *
     * Array of Game key lost by Team
     *  
     * @var array $lostGamesKey
     */
    public $lostGamesKey;

    /**
     * Excluded Games Key
     *
     * Array of Excluded game with this Team
     *  
     * @var array $excludedGamesKey
     */
    public $excludedGamesKey;

    /**
     * Constructor
     * 
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->wonGamesKey = array();
        $this->drawnGamesKey = array();
        $this->lostGamesKey = array();
        $this->excludedGamesKey = array();
    }

    /**
     * Detect Results From Game
     *
     * Detect all the informations from a Game for Team.
     * Improve Number of points, goals scored...
     * 
     * @param  Game   $game
     */
    public function deductResultsFromGame(Game $game)
    {
        $teamScore = ($game->getHomeTeamKey() === $this->getName()) ? $game->getHomeTeamScore() : $game->getAwayTeamScore();
        $opponentScore = ($game->getHomeTeamKey() === $this->getName()) ? $game->getAwayTeamScore() : $game->getHomeTeamScore();
        
        $balance = (int) $teamScore - $opponentScore;
        $this->goalFor += (int) $teamScore;
        $this->goalAgainst += (int) $opponentScore;

        if ($balance < 0) {
            $this->lostGamesKey[] = $game->getKey();
        } elseif ($balance > 0) {
            $this->wonGamesKey[] = $game->getKey();
            $this->points += Game::WON_POINTS;
        } else {
            $this->drawnGamesKey[] = $game->getKey();
            $this->points += Game::DRAWN_POINTS;
        }
    }

    /**
     * Detect Results From Excluded Game
     *
     * Detect all the informations from a Game to exclude for Team.
     * Reduce Number of points, goals scored...
     * 
     * @param  Game   $game
     */
    public function deductResultsFromExcludedGame(Game $game)
    {
        $teamScore = ($game->getHomeTeamKey() === $this->getName()) ? $game->getHomeTeamScore() : $game->getAwayTeamScore();
        $opponentScore = ($game->getHomeTeamKey() === $this->getName()) ? $game->getAwayTeamScore() : $game->getHomeTeamScore();
        
        $balance = (int) $teamScore - $opponentScore;
        $this->goalFor -= (int) $teamScore;
        $this->goalAgainst -= (int) $opponentScore;

        if ($balance < 0) {
            $key = array_search($game->getKey(), $this->lostGamesKey);
            if (is_int($key)) {
                unset($this->lostGamesKey[$key]);
                $this->excludedGamesKey[] = $key;
            }
        } elseif ($balance > 0) {
            $key = array_search($game->getKey(), $this->wonGamesKey);
            if (is_int($key)) {
                unset($this->wonGamesKey[$key]);
                $this->excludedGamesKey[] = $key;
            }
            $this->points -= Game::WON_POINTS;
        } else {
            $key = array_search($game->getKey(), $this->drawnGamesKey);
            if (is_int($key)) {
                unset($this->drawnGamesKey[$key]);
                $this->excludedGamesKey[] = $key;
            }
            $this->points -= Game::DRAWN_POINTS;
        }
    }

    /**
     * Get Games Played
     * 
     * @return integer
     */
    public function getGamesPlayed()
    {
        return count($this->getWonGamesKey()) + count($this->getDrawnGamesKey()) + count($this->getLostGamesKey());
    }

    /**
     * Get Won Game Key
     * 
     * @return string key of the first game won
     */
    public function getWonGameKey()
    {
        return array_shift($this->wonGamesKey);
    }

    /**
     * Get Key
     *
     * Return the properties describes as a key for a Team
     * 
     * @return string
     */
    public function getKey()
    {
        return $this->getName();
    }

    /**
     * Get Name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     * @param string $name
     * @return  Team
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Points
     * 
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Get Goals For
     * 
     * @return integer
     */
    public function getGoalsFor()
    {
        return $this->goalFor;
    }

    /**
     * Get Goal Against
     * 
     * @return integer
     */
    public function getGoalAgainst()
    {
        return $this->goalAgainst;
    }

    /**
     * Get Points
     * 
     * @return integer
     */
    public function getGoalDiff()
    {
        return $this->getGoalsFor() - $this->getGoalAgainst();
    }

    /**
     * Get Won Games Key
     *
     * @return array
     */
    public function getWonGamesKey()
    {
        return $this->wonGamesKey;
    }

    /**
     * Get Drawn Games Key
     *
     * @return array
     */
    public function getDrawnGamesKey()
    {
        return $this->drawnGamesKey;
    }

    /**
     * Get Lost Games Key
     *
     * @return array
     */
    public function getLostGamesKey()
    {
        return $this->lostGamesKey;
    }

    /**
     * Get Excluded Games Key
     *
     * @return array
     */
    public function getExcludedGamesKey()
    {
        return $this->excludedGamesKey;
    }
}
