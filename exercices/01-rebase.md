# 01 rebase

## Mettre à jour sa branche

Depuis la branche `main`, créez une branche (peut importe le nom).

```bash
git switch -c feature/ajout-de-signe
```

Retournez sur la branche `main`, copiez / collez le code suivant dans `index.php` :

```php
<?php

namespace App {
    abstract class Personnage {
        public function __construct(
            private string $name,
            private int $lifepoints = 100)
        {
        }

        public function getName(): string {
            return $this->name;
        }

        public function getLifePoints(): int {
            return $lifepoints;
        }
    }

    class Geralt extends Personnage {
        public function __construct(private string $name)
        {
            parent::__construct($name);
        }
    }
}

namespace {

    use App\Geralt;

    $geralt = new Geralt('geralt');
    print($geralt->getName() . PHP_EOL);
}
```

Créer votre commit :


```bash
git add index.php
git commit -m "changer le nom d'une variable"
```

Retourner sur la branche `feature/ajout-de-signe` et réaliser la commande :

```bash
git rebase main
```
