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

/**
 * Game
 *
 * Game is used to model a game between two Team identified by their key
 *
 * @package App
 * @author lechatquidanse
 */
class Game
{
    /**
     * Won Points
     *
     * Points given to the winning team
     */
    const WON_POINTS = 3;

    /**
     * Drawn Points
     *
     * Points given to drawn team
     */
    const DRAWN_POINTS = 1;

    /**
     * Won Points
     *
     * Points given to the winning team
     */
    const LOST_POINTS = 0;
    
    /**
     * Key
     *
     * key is used to identify a Game
     *
     * @var string $key
     */
    public $key;

    /**
     * Date
     *
     * date is used to store the date of the Game
     *
     * @var string $date
     */
    public $date;

    /**
     * Home Team Key
     *
     * homeTeamKey is the key to identify home team
     *
     * @var string $homeTeamKey
     */
    public $homeTeamKey;

    /**
     * Home Team Score
     *
     * homeTeamScore is used to store goals scored by home team
     *
     * @var integer $homeTeamScore
     */
    public $homeTeamScore;

    /**
     * Away Team Key
     *
     * awayTeamKey is the key to identify away team
     *
     * @var string $awayTeamKey
     */
    public $awayTeamKey;

    /**
     * Away Team Score
     *
     * awayTeamScore is used to store goals scored by away team
     *
     * @var integer $awayTeamScore
     */
    public $awayTeamScore;

    /**
     * Construct
     *
     * Game constructor
     *
     * @param string $key
     * @param string $date
     * @param string $homeTeamKey
     * @param integer $homeTeamScore
     * @param string $awayTeamKey
     * @param integer $awayTeamScore
     *
     * @return Game
     */
    public function __construct($key, $date, $homeTeamKey, $homeTeamScore, $awayTeamKey, $awayTeamScore)
    {
        $this->key = $key;
        $this->date = $date;
        $this->homeTeamKey = $homeTeamKey;
        $this->homeTeamScore = $homeTeamScore;
        $this->awayTeamKey = $awayTeamKey;
        $this->awayTeamScore = $awayTeamScore;
    }

    /**
     * Get Key
     *
     * Return game key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get Date
     *
     * Return game date
     * 
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get Home Team Key
     *
     * Return home team key
     *
     * @return string
     */
    public function getHomeTeamKey()
    {
        return $this->homeTeamKey;
    }

    /**
     * Get Home Team Score
     *
     * Return home team score
     *
     * @return integer
     */
    public function getHomeTeamScore()
    {
        return $this->homeTeamScore;
    }

    /**
     * Get Away Team Key
     *
     * Return away team key
     *
     * @return string
     */
    public function getAwayTeamKey()
    {
        return $this->awayTeamKey;
    }

    /**
     * Get Away Team Score
     *
     * Return away team score
     *
     * @return integer
     */
    public function getAwayTeamScore()
    {
        return $this->awayTeamScore;
    }
}
