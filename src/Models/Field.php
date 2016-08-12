<?php

/**
 * Created by PhpStorm.
 * User: sidavies
 * Date: 6/02/2016
 * Time: 5:37 PM
 */

namespace Soda\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    protected $fillable = [
        'name',
        'field_name',
        'field_type',
        'field_params',
        'value',
        'name',
        'field_name',
        'description',
    ];
    protected $table = 'fields';
    protected $casts = [
        'field_params' => 'array',
    ];

    public function block_types() {
        return $this->morphedByMany(BlockType::class, 'fieldable');
    }

    public function page_types() {
        return $this->morphedByMany(PageType::class, 'fieldable');
    }

    /**/

    public static function getFieldTypes() {
        return Soda::getFormBuilder()->getFieldTypes();
    }
}
