<?php

namespace Soda\Cms\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Soda\Cms\Models\Traits\DraftableTrait;
use Soda\Cms\Models\Traits\DynamicCreatorTrait;
use Soda\Cms\Models\Traits\OptionallyInApplicationTrait;

class BlockType extends Model {
    use OptionallyInApplicationTrait, DraftableTrait, DynamicCreatorTrait;

    protected $table = 'block_types';
    protected $fillable = [
        'name',
        'description',
        'status',
        'identifier',
        'application_id',
        'package',
        'action',
        'action_type',
        'edit_action',
        'edit_action_type',
    ];

    public function fields() {
        return $this->morphToMany(Field::class, 'fieldable');
    }

    public function block() {
        return $this->hasMany(Block::class, 'block_type_id');
    }
}
