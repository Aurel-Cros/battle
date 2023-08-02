# V1 API for PHP-Battle

URL root : __api/v1/__

## Fighters

### GET

> - __GET fighters__ : returns list of all fighters
> - __GET fighters/{id}__ : returns fighter with id
### POST

> - __POST fighters__ : inserts a new fighter entr> y

## Fights

### GET

> - __GET fights__ : returns list of all fights
>      > * __?winner-id={fighter-id}__ : returns all fights won by fighter-id
>      > * __?fighter={fighter-id}__ : returns all fights with that fighter
>      > * __?fighters={fighter-id},{fighter-id}__ : returns all fight with those two fighters
> - __GET fights/{id}__ : returns fight with id
> - __GET fights/{id}/logs__ : returns all events from that fight
> - __GET fights/{id}/winner__ : returns the winner of that fight

### POST

> - __POST fights__ : inserts a new fight entry

### PATCH

> - __PATCH fights/{id}/winner__ : update fight entry with winner id
> - __PATCH fights/{id}/logs__ : update fight log