# drupal-dog-breeds-challenge
Drupal 9 project for Dog Breeds challenge

INSTALL
-------

Run
    `lando start`
    `lando composer install`
    `lando db-import drupal9.2023-01-17-1673998281.sql.gz`

DOG BREEDS CONTENT TYPE
-----------------------

    To add a dog breed, use "Dog Breeds" content type. Inform a common name and a slug.

DOG BREEDS BLOCK
----------------

    Add a slug in the menu admin -> content authoring -> Dog Breed Block Configuration.
    Place the "Dog Breed Custom Block" in the sidebar.

HOW IT WORKS
------------

    Both block and content type will get an image from https://dog.ceo/ API.

AFTER IMPORT DATABASE
---------------------

    You can see the block in the sidebar and a list of dog breeds in the /dog-breeds route.
