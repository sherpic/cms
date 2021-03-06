<?php namespace Soda\Cms\Components\Forms;

use Exception;
use Config;
use Request;
use Soda\Cms\Models\BlockType;
use Soda\Cms\Models\Field;
use Soda\Cms\Models\ModelBuilder;

class FormBuilder {
    protected $registrar;

    public function __construct(FormFieldRegistrar $registrar) {
        $this->registrar = $registrar;
    }

    public function getRegistrar() {
        return $this->registrar;
    }

    /**
     * Returns an array of registered fields, keyed by field slug,
     * values in readable format
     *
     * @return array
     */
    public function getFieldTypes() {
        return array_map(function($value) {
            $class_name = class_basename($value);
            return ucfirst(strtolower(preg_replace('/\B([A-Z])/', ' $1', $class_name)));
        }, $this->getRegistrar()->getRegisteredFields());
    }

    /**
     * Instantiates a new FormField from our Field model or array
     *
     * @param $field
     *
     * @return mixed
     * @throws \Exception
     */
    public function field($field) {
        if (is_array($field)) {
            $field = new Field($field);
        }

        if (!$field instanceOf Field) {
            Throw new Exception("Field must be instance of " . Field::class . " or array.");
        }

        return $this->getRegistrar()->resolve($field);
    }

    /**
     * Work In Progress
     * Returns a view for an inline editable field
     *
     * @param $model
     * @param $element
     * @param $type
     *
     * @return \Illuminate\View\View
     */
    public function editable($model, $element, $type) {
        $field_value = $model->{$element};
        if (Request::get('soda_edit')) {
            $unique = uniqid();
            if ($model instanceof ModelBuilder) {
                //we need to get the db name and attach to the field..
                $type = BlockType::where('identifier', $type)->first();
                $link = route('soda.dyn.inline.edit', [
                    'type'  => $type->identifier,
                    'model' => $model->id,
                    'field' => $element,
                ]);
            }

            //TODO: figure out which type of field we need to use here..
            return view('soda::inputs.inline.text', [
                'link'        => $link,
                'element'     => $element,
                'model'       => $model,
                'unique'      => $unique,
                'field_value' => $field_value,
            ]);
        } else {
            return $field_value;
        }
    }

    /**
     * Formats a JSON string to be pasted into our Javascript
     *
     * @param $parameters
     *
     * @return string
     */
    public function buildJsParams($parameters) {
        if(!$parameters) return '';

        $json_parameters = json_encode($parameters, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return trim($json_parameters, '{}');
    }

    public function __call($name, $arguments) {
        if($this->getRegistrar()->isRegistered($name)) {
            $field = new Field($arguments[0]);
            $field->field_type = $name;

            return $this->getRegistrar()->resolve($field);
        } else {
            throw new Exception("Field " . $name ." is not registered.");
        }
    }

    /**
     * Set the default path to input layouts
     *
     * @param $layout
     *
     * @return $this
     */
    public function setDefaultLayout($layout) {
        Config::set('soda.cms.form.default-layout', $layout);

        return $this;
    }

    /**
     * Set the default path to input views
     *
     * @param $view_path
     *
     * @return $this
     */
    public function setDefaultViewPath($view_path) {
        Config::set('soda.cms.form.default-view-path', $view_path);

        return $this;
    }
}
