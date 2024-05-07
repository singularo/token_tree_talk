
## Token tree filter.
This repo contains the code for the token tree filter presentation.

To try out the code yourself, docker and composer need to be installed,
then after some simple, the site will be ready to go.

## Setup steps after cloning the repo
```
composer install --ignore-platform-reqs
./dsh
robo build
drush uli
```

## Other useful things:

#### See the available robo commands.
```
robo
```

#### Update the default content in the profile.
```
robo dev:content-export
```
