<?php

namespace models;

class ListStudent extends \stdClass {
    public $offset = 0;
    public $limit  = 10;
    public $pages  = 0;
    public $page   = 1;
    public $list = [];
}