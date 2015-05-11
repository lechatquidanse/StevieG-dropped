<?php

/**
 * This file is part of the App package.
 *
 * (c) lechatquidanse
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LCQD\App\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use LCQD\App\Controller\HomeControllerProvider;

/**
 * App Provider {@inheritdoc}
 *
 * {@inheritdoc}
 *
 * @package App
 * @author lechatquidanse
 */
class AppProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        $app->mount('/', new HomeControllerProvider());
    }
}
