<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class school extends Model
{
    /**
	 * The database table used by the model
	 *
	 * @var string
	 */

    protected $table = 'school';

    protected $fillable = ['number', 'code', 'name', 'phone', 'district_id', 'school_type_id'];

    /**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */

    public function schoolType()
    {
        return $this->belongsTo(SchoolType::class);
    }

    /**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */

    /**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\hasMany
	 */

    public function apafas()
    {
        return $this->hasMany(Apafa::class);
    }

    /**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\hasMany
	 */

    public function coneis()
    {
        return $this->hasMany(Conei::class);
    }

    /**
     * Scope a query to only include the keywords given
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $keywords
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeSearch($query, $keywords, $columns)
    {
        if(!empty($keywords)){
            return $query->searchIn($keywords, $columns);
        }
    }

    /**
     * Scope a query to only include the filters given
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $relationships
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeFilters($query, $relationships)
    {
        if(!empty($relationships)){
            foreach($relationships as $relativeTable => $relativeValue){
                $query->whereHas($relativeTable, function($relationQuery) use ($relativeValue) {
                    $relationQuery->searchIn([$relativeValue], ["id"]);
                });
            }

            return $query;
        }
    }

    /**
     * Scope a query to set relationships alias
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $relativeTables
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeAlias($query, $relativeTables)
    {
        foreach($relativeTables as $relativeTable => $relativeColumn){
            $query->withCount(["$relativeTable as $relativeTable" => function ($query) use ($relativeColumn) {
                $query->select($relativeColumn);
            }]);
        }
        
        return $query;
    }

    /**
     * Scope a query to sort the schools
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $criteria
     * @param string $order
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeSortBy($query, $criteria, $order)
    {
        return $query->orderBy($criteria, $order);
    }

    /**
     * Scope a query to paginate the schools
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopePaging($query, $parameters)
    {
        return $query->simplePaginate($parameters[3])
            ->appends([
                'type' => $parameters[0],
                'keywords' => $parameters[1],
                'district' => $parameters[2],
                'perPage' => $parameters[3],
            ]);
    }

    /**
     * Scope a query to get Schools
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $parameters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeSchools($query, $getType, $getKeywords, $getDistrict, $getPerPage){
        $type = ['schoolType' => $getType];
        $district = ['district' => $getDistrict];
        $keywords = explode('-', $getKeywords);
        
        $relationships = [];

        if($getType && $getType !== 'all'){
            $relationships = array_merge($relationships, $type);
        }

        if($getDistrict && $getDistrict !== 'all'){
            $relationships = array_merge($relationships, $district);
        }

        if(!$getPerPage){
            $getPerPage = 10;
        }

        $columns = ['code', 'name'];
        
        $relativeTables = ['district' => 'name'];
        $parameters=[$getType, $getKeywords, $getDistrict, $getPerPage];

        $schools = $query->search($keywords, $columns)
            ->filters($relationships)
            ->with('apafas')
            ->with('coneis')
            ->alias($relativeTables)
            ->sortBy('name', 'asc');

        $countResults = $schools->count();

        $schools = $schools->paging($parameters);

        $firstRowIndex = $schools->firstItem();
        $lastRowIndex = $schools->lastItem();

        if(!$firstRowIndex && !$lastRowIndex){
            $firstRowIndex = 0;
            $lastRowIndex = 0;
        }

        return array($schools, $countResults, $firstRowIndex, $lastRowIndex);
    }
}
