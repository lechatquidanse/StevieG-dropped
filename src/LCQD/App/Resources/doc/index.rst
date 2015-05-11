Application
====================

Technical Process
--------------------

Thanks to resource_ and its adapted loader_, a Championship_ model il created.
This Championship stores all teams_ and games_ described in resource.
It can determine, for each team, the minimum number of games that need to be excluded in order for that team to win the league.
In order to do that, it follows two rules, point difference and if equality, then checks goal difference.

These operations can be done on multi Championship thanks to a ChampionshipHandler_.

.. _resource: https://github.com/lechatquidanse/StevieG-dropped/blob/master/src/LCQD/App/Resources/data/PremierLeague1314.csv
.. _loader: https://github.com/lechatquidanse/StevieG-dropped/blob/master/src/LCQD/App/Loader/CsvFileLoader.php
.. _Championship: https://github.com/lechatquidanse/StevieG-dropped/blob/master/src/LCQD/App/Model/Championship.php
.. _teams: https://github.com/lechatquidanse/StevieG-dropped/blob/master/src/LCQD/App/Collection/TeamCollection.php
.. _games: https://github.com/lechatquidanse/StevieG-dropped/blob/master/src/LCQD/App/Collection/GameCollection.php
.. _ChampionshipHandler: https://github.com/lechatquidanse/StevieG-dropped/blob/master/src/LCQD/App/Handler/ChampionshipHandler.php
