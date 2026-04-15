<?php
echo "getenv: '" . (getenv('DB_DATABASE') ?: '') . "'\n";
echo "_ENV: '" . (isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : '') . "'\n";
echo "_SERVER: '" . (isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : '') . "'\n";
