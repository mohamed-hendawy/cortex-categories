<?php

declare(strict_types=1);

namespace Cortex\Categories\Models;

use Kalnoy\Nestedset\NestedSet;
use Vinkla\Hashids\Facades\Hashids;
use Cortex\Foundation\Traits\Auditable;
use Spatie\Activitylog\Traits\LogsActivity;
use Rinvex\Categories\Models\Category as BaseCategory;

/**
 * Cortex\Categories\Models\Category.
 *
 * @property int                                                                           $id
 * @property string                                                                        $name
 * @property array                                                                         $title
 * @property array                                                                         $description
 * @property int                                                                           $_lft
 * @property int                                                                           $_rgt
 * @property int                                                                           $parent_id
 * @property string                                                                        $style
 * @property string                                                                        $icon
 * @property \Carbon\Carbon|null                                                           $created_at
 * @property \Carbon\Carbon|null                                                           $updated_at
 * @property \Carbon\Carbon|null                                                           $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Cortex\Foundation\Models\Log[] $activity
 * @property-read \Kalnoy\Nestedset\Collection|\Cortex\Categories\Models\Category[]        $children
 * @property-read \Cortex\Categories\Models\Category|null                                  $parent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Categories\Models\Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends BaseCategory
{
    use Auditable;
    use LogsActivity;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'title',
        'description',
        NestedSet::LFT,
        NestedSet::RGT,
        NestedSet::PARENT_ID,
        'style',
        'icon',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => 'string',
        NestedSet::LFT => 'integer',
        NestedSet::RGT => 'integer',
        NestedSet::PARENT_ID => 'integer',
        'style' => 'string',
        'icon' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * Indicates whether to log only dirty attributes or all.
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are logged on change.
     *
     * @var array
     */
    protected static $logFillable = true;

    /**
     * The attributes that are ignored on change.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.categories.tables.categories'));
        $this->setRules([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000',
            'name' => 'required|alpha_dash|max:150|unique:'.config('rinvex.categories.tables.categories').',name',
            NestedSet::LFT => 'sometimes|required|integer',
            NestedSet::RGT => 'sometimes|required|integer',
            NestedSet::PARENT_ID => 'nullable|integer',
            'style' => 'nullable|string|max:150',
            'icon' => 'nullable|string|max:150',
        ]);
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return Hashids::encode($this->getAttribute($this->getRouteKeyName()));
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        $value = Hashids::decode($value)[0];

        return $this->where($this->getRouteKeyName(), $value)->first();
    }
}
