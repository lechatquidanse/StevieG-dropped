Application
====================

Technical Process
--------------------

Thanks to resource_ and its adapted loader_, w a Championship_ model il created.
This Championship stores all teams_ and games_ described in resource.
It can determine, for each team, the minimum number of games that need to be excluded in order for that team to win the league.
In order to do that, it follows two rules, point difference and if equality, then checks goal difference.

These operations can be done on multi Championship thanks to a ChampionshipHandler_.

.. _resource: https://github.com/lechatquidanse/data-dealer-sandbox/blob/master/src/Sportlobster/Bundle/DataBundle/Manager/DataManager.php
.. _loader: https://github.com/lechatquidanse/data-dealer-sandbox/blob/master/src/Sportlobster/Bundle/DataBundle/Loader/DataLoader.php
.. _Championship: https://github.com/lechatquidanse/data-dealer-sandbox/blob/master/src/Sportlobster/Bundle/DataBundle/Manager/NewsManager.php
.. _teams: https://github.com/lechatquidanse/data-dealer-sandbox/blob/master/src/Sportlobster/Bundle/DataBundle/Collection/DataCollection.php
.. _games: https://github.com/lechatquidanse/data-dealer-sandbox/blob/master/src/Sportlobster/Bundle/DataBundle/Model/News.php
.. _ChampionshipHandler: https://github.com/lechatquidanse/data-dealer-sandbox/blob/master/src/Sportlobster/Bundle/DataBundle/Resources/data/flux/newsFlux.xml