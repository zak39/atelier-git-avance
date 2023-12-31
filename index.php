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

    $perso01 = new Geralt('geralt');
    print($perso01->getName() . PHP_EOL);
}
