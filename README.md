StevieG-dropped
========================

This application determine, for each team of a championship, the minimum number of games that need to be excluded in order for that team to win the league.
Based on the latest silex edition, it includes a vagrant environnement and an online documentation.
For the moment it displays only one league described by a list of game in [this csv file][1].
You can find more technical informations [here][2].

1) Installing
----------------------------------

### Clone the repository

    git clone https://github.com/lechatquidanse/StevieG-dropped.git
    
### Vagrant Environnement

The vagrant proposed here is the vagrant using Ansible and developped by [kleiram/vagrant-symfony][3].
To use it, go to /vagrant folder, and run this command:

    vagrant up

If you have any problem, follow [this instructions][4].

### Use Composer to install vendors

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `composer` command to install required vendors:

    php composer.phar install


2) Browsing the Application
--------------------------------

Congratulations! You're now ready to use the application.
You can go to [http://192.168.33.10/][5] or the vhost that you use.

3) Browsing Documentation
--------------------------------

You can generate phpdoc, by running this command:

    vendor/bin/phpdoc

You can go to [http://192.168.33.10/docs/phpdoc/index.html][6] or the vhost that you use.

Enjoy!

[1]:  https://github.com/lechatquidanse/StevieG-dropped/blob/master/src/LCQD/App/Resources/doc/index.rst
[2]:  https://github.com/kleiram/vagrant-symfony
[3]:  https://github.com/lechatquidanse/StevieG-dropped/blob/master/vagrant/README.md
[4]:  http://192.168.33.10/
[5]:  http://192.168.33.10/docs/phpdoc/index.html

Authors
-------

* Stéphane EL MANOUNI
