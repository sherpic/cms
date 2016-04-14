<?php
namespace Soda\Models;

use Franzose\ClosureTable\Models\Entity;
use Soda\Models\Scopes\FromApplicationScope;
use Soda\Models\Scopes\LiveScope;
use Soda\Models\Scopes\PositionScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Soda\Models\Traits\SluggableTrait;

class Template extends Entity implements TemplateInterface
{
    use SoftDeletes;
    use SluggableTrait;

    protected $table = 'templates';
    public $fillable = ['name', 'slug', 'action_type', 'action', 'package', 'application_id', 'status_id', 'position'];

    /**
     * The table associated with the model.
     *
     * @var string
     */



    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new FromApplicationScope);
        static::addGlobalScope(new LiveScope);
        static::addGlobalScope(new PositionScope);
    }


    /**
     * ClosureTable model instance.
     *
     * @var pagesClosure
     */
    protected $closure = 'Soda\Models\TemplateClosure';

    public function type()
    {
        return $this->belongsTo(PageType::class);
    }

    public function blocks()
    {
        return $this->belongsToMany(Block::class, 'template_blocks');
    }
}
