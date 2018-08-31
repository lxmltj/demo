# CRM Demo

## Installation

### Code source

Obtenir le code

```bash
git clone https://github.com/lxmltj/demo.git
```


### Docker

Exécuter docker-compose pour construire l'environnement de développement

```bash
docker-composer up -d
```

#### Déployer et configurer

De connecter au conteneur de docker: nom de conteneur()

```bash
docker exec --user 1000 -it demo_php7_1 bash
```
   
Composer install

```bash
composer install
```

Database

```bash
bin/console doctrine:database:create
bin/console doctrine:schema:create
```

Happy testing :-)

[CRM Demo](http://localhost:5066)
http://localhost:5066

[PhpMyAdmin](http://localhost:8181)
http://localhost:8181