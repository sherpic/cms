<?php

namespace Soda\Cms\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait DynamicCreatorTrait {
    public function getDynamicType() {
        $table = $this->getTable();

        return preg_replace('/_types$/', '', $table);
    }

    public function getDynamicTableName() {
        return 'soda_' . str_slug($this->identifier, '_');
    }

    public static function bootDynamicCreatorTrait() {
        static::creating(function ($type) {
            $table = $type->getDynamicTableName();
            $fields = $type->fields;

            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) use ($type) {
                    $table->increments('id');
                    $table->integer($type->getDynamicType() . '_id')->unsigned();
                    $table->foreign($type->getDynamicType() . '_id')->references('id')->on('blocks')->onUpdate('cascade')->onDelete('cascade');
                    $table->timestamps();
                });
            } else {
                Throw new Exception('Table ' . $table . ' already exists');
            }

            $type->addFields($fields);
        });

        static::deleting(function ($type) {
            $table = $type->getDynamicTableName();

            if (Schema::hasTable($table)) {
                Schema::rename($table, $table . '_deleted_' . Carbon::now()->timestamp);
            }
        });
    }

    public function addFields($fields) {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }

    public function addField($field) {
        $table = $this->getDynamicTableName();
        $field_name = $field->field_name;

        if (!Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field_name) {
                $table->string($field_name);
            });
        }

        return $this;
    }

    public function removeField($field) {
        $table = $this->getDynamicTableName();
        $field_name = $field->field_name;

        if (Schema::hasColumn($table, $field_name)) {
            Schema::table($table, function ($table) use ($field_name) {
                $table->dropColumn($field_name);
            });
        }

        return $this;
    }

    public function syncFields() {
        // todo
    }
}
