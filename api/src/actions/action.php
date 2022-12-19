<?php

namespace actions;

abstract class Action {
    abstract public function run(\mysqli $db);
}