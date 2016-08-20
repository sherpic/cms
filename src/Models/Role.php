<?php namespace Soda\Cms\Models;

use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {
    use OptionallyInApplicationTrait;
}
