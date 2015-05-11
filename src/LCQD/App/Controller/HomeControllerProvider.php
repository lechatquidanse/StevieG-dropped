<?php

/**
 * This file is part of the App package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\App\Controller;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException as ClientRequestException;
use LCQD\App\Handler\ChampionshipHandler;
use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * Home Controller Proviver {@inheritdoc}
 *
 * {@inheritdoc}
 *
 * @package App
 * @author lechatquidanse
 */
class HomeControllerProvider implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        /**
         * Home page
         *
         * @param string '/' route to match
         * @return text/html
         */
        $controllers->get('/', function () use ($app) {
            return $app['twig']->render('lcqd/app/home.html.twig');
        })->bind('lcqd_app_home');


        /**
         * Favorite Team page
         *
         * Request to urlQuizz and read stream response as a string.
         * Find Operation {*} to eval for future $urlAnswer.
         * Add query path to $urlAnswer.
         * Request to urlAnswer and read stream response as a string.
         *
         * @param string '/favorite' route to match
         * @return text/html
         * @todo log exceptions
         */
        $controllers->get('/favorite', function () use ($app) {
            $urlQuiz = 'http://www.footballradar.com/quiz/';
            $urlAnswer = 'http://www.footballradar.com/quiz/answer/';
            // $urlQuiz = 'http://192.168.33.10/championship';
            // $urlAnswer = 'http://192.168.33.10/';
            
            try {
                $client = new Client();
                $streamQuiz = $client->get($urlQuiz, ['cookies' => true])->getBody()->read(2048);

                preg_match('/\{(.*?)\}/', $streamQuiz, $matches);
                if (!isset($matches[1])) {
                    throw new \Exception('No url operation { *** }  has been found');
                }
                $queryPath = eval('return ' . trim(strip_tags($matches[1])) . ';');
                
                $urlAnswer .= $queryPath;
                $content = $client->get($urlAnswer, ['cookies' => true])->getBody()->read(1024);
            } catch (ClientRequestException $e) {
                $content = 'Error request: ' . $e->getMessage();
            } catch (\Exception $e) {
                $content = 'Error: ' . $e->getMessage();
            }

            return $app['twig']->render('lcqd/app/favorite.html.twig', array('content' => $content));
        })->bind('lcqd_app_favorite');

        /**
         * Championship Key page
         *
         * Display for championship identify by championshipKey the number of game for each team to execlude in order for that team to be champion
         * A championship handler is used to load resource and create championship linked to this resource.
         * Then this championship is processing to find number of game for each team
         *
         * @param string '/championship/{championshipKey}' route to match, {championshipKey} is the name of the resource
         * @return text/html
         * @todo log exceptions
         */
        $controllers->get('/championship/{championshipKey}', function ($championshipKey) use ($app) {
            
            $championshipHandler = new ChampionshipHandler();
            $championshipHandler->load();
            
            $championship = $championshipHandler->getChampionhsipByKey($championshipKey);
            
            $gamesToExclude = $championship->findGamesToExcludeToBeChampion();
            $teams = $championship->getTeams();

            return $app['twig']->render('lcqd/app/championship.html.twig', array(
                'teams' => $teams,
                'gamesToExclude' => $gamesToExclude,
                'championshipKey' => $championshipKey));
        })->value('championshipKey', 'bpl1314')
            ->bind('lcqd_app_championship');


        /**
         * Championship Key for a specific team page
         *
         * Display for championship identify by championshipKey the number of game for a  team identify by teamKey to execlude in order for that team to be champion
         * It display the virtual ranking and the list of games to exclude
         *
         * @param string '/championship/{championshipKey}/{teamKey}' route to match, {championshipKey} is the name of the championship resource, {teamKey} of team
         * @return text/html
         * @todo log exceptions
         */
        $controllers->get('/championship/{championshipKey}/{teamKey}', function ($championshipKey, $teamKey) use ($app) {
            
            $championshipHandler = new ChampionshipHandler();
            $championshipHandler->load();
            
            $championship = $championshipHandler->getChampionhsipByKey($championshipKey);
            $team = $championship->findTeamByKey($teamKey);
            $gamesToExclude = $championship->findGamesToExcludeToBeFirst($team);

            $teams = $championship->getTeams();

            return $app['twig']->render('lcqd/app/team.championship.html.twig', array(
                'teams' => $teams,
                'gamesToExclude' => $gamesToExclude,
                'championshipKey' => $championshipKey,
                'teamKey' => $teamKey));
        })->value('championshipKey', 'bpl1314')
            ->bind('lcqd_app_championship_team');
        

        // $controllers->get('/{number}', function ($number) use ($app) {
        //     return 'bravo efhozh' . $number;
        // });

        return $controllers;
    }
}
