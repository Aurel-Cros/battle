# V1 API for PHP-Battle

URL root : __api/v1/__

## Fighters

### GET

> - __GET fighters__ : returns list of all fighters
> - __GET fighters/{id}__ : returns fighter with id
### POST

> - __POST fighters__ : inserts a new fighter entry

## Fights

### GET

> - __GET fights__ : returns list of all fights
> - __GET fights/{id}__ : returns fight with id
> - __GET fights/number-of-wins__ : returns list of winners by winner id

### POST

> - __POST fights__ : inserts a new fight entry

### PATCH

> - __PATCH fights/{id}/winner__ : update fight entry with winner id
> - __PATCH fights/{id}/logs__ : update fight log