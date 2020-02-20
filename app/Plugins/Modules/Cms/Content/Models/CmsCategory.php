<?php
#app/Modules/Cms/Content/Models/CmsCategory.php
namespace App\Plugins\Modules\Cms\Content\Models;

use App\Plugins\Modules\Cms\Content\Models\CmsCategoryDescription;
use App\Plugins\Modules\Cms\Content\Models\CmsContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CmsCategory extends Model
{
    public $timestamps = false;
    public $table = SC_DB_PREFIX.'cms_category';
    protected $guarded = [];
    protected $connection = SC_CONNECTION;
    protected $appends = [
        'title',
        'keyword',
        'description',
    ];

    public function descriptions()
    {
        return $this->hasMany(CmsCategoryDescription::class, 'category_id', 'id');
    }
    public function contents()
    {
        return $this->hasMany(CmsContent::class, 'category_id', 'id');
    }


/**
 * Get category parent
 * @return [type]     [description]
 */
    public function getParent()
    {
        return $this->find($this->parent);

    }

/**
 * Get all products in category, include child category
 * @param  [type] $id    [description]
 * @param  [type] $limit [description]
 * @return [type]        [description]
 */
    public function getContentsToCategory($id, $limit = null, $opt = null)
    {
        $arrChild = $this->arrChild($id);
        $arrChild[] = $id;
        $query = (new CmsContent)->where('status', 1)->whereIn('category_id', $arrChild)->sort();
        if (!(int) $limit) {
            return $query->get();
        } else
        if ($opt == 'paginate') {
            return $query->paginate((int) $limit);
        } else {
            return $query->limit($limit)->get();
        }

    }

    /**
     * Get list categories cms
     *
     * @param   [type]  $parent     [$parent description]
     * @param   [type]  $limit      [$limit description]
     * @param   [type]  $opt        [$opt description]
     * @param   [type]  $sortBy     [$sortBy description]
     * @param   [type]  $sortOrder  [$sortOrder description]
     *
     * @return  [type]              [return description]
     */
    public function getCategories($parent, $limit = null, $opt = null, $sortBy = null, $sortOrder = 'asc')
    {
        $query = $this->where('status', 1)->where('parent', $parent);
        $query = $query->sort($sortBy, $sortOrder);
        if (!(int) $limit) {
            return $query->get();
        } else
        if ($opt == 'paginate') {
            return $query->paginate((int) $limit);
        } else
        if ($opt == 'random') {
            return $query->inRandomOrder()->limit($limit)->get();
        } else {
            return $query->limit($limit)->get();
        }

    }

     /**
     * Get list category cms
     *
     * @param   array  $arrOpt
     * Example: ['status' => 1, 'top' => 1]
     * @param   array  $arrSort
     * Example: ['sortBy' => 'id', 'sortOrder' => 'asc']
     * @param   array  $arrLimit  [$arrLimit description]
     * Example: ['step' => 0, 'limit' => 20]
     * @return  [type]             [return description]
     */
    public function getList($arrOpt = [], $arrSort = [], $arrLimit = [])
    {
        if(empty($arrOpt) && empty($arrSort) && empty($arrLimit)) {
            return $this->processListFull();
        } else {
            return $this->processList($arrOpt = [], $arrSort = [], $arrLimit = []);
        }
    }

    /**
     * Process get list category
     *
     * @param   array  $arrSort   [$arrSort description]
     * @param   array  $arrLimit  [$arrLimit description]
     * @param   array  $arrOpt    [$arrOpt description]
     *
     * @return  collect
     */
    public function processList($arrOpt = [], $arrSort = [], $arrLimit = [])
    {
        $sortBy = $arrSort['sortBy'] ?? null;
        $sortOrder = $arrSort['sortOrder'] ?? 'asc';
        $step = $arrLimit['step'] ?? 0;
        $limit = $arrLimit['limit'] ?? 0;

        $data = $this->sort($sortBy, $sortOrder);
        if(count($arrOpt = [])) {
            foreach ($arrOpt as $key => $value) {
                $data = $data->where($key, $value);
            }
        }
        if((int)$limit) {
            $start = $step * $limit;
            $data = $data->offset((int)$start)->limit((int)$limit);
        }
        $data = $data->get()->groupBy('parent');

        return $data;
    }

    /**
     * Process list full cactegory cms
     *
     * @return  [type]  [return description]
     */
    private function processListFull()
    {
        if(sc_config('cache_status') && sc_config('cache_category_cms')) {
            if (!Cache::has('cache_category_cms')) {
                $listFullCategory = $this->processList();
                Cache::put('cache_category_cms', $listFullCategory, $seconds = sc_config('cache_time', 0)?:600);
            }
            return Cache::get('cache_category_cms');
        } else {
            return $this->processList();
        }
    }

    /*
    Get thumb
    */
    public function getThumb()
    {
        return sc_image_get_path_thumb($this->image);
    }

    /*
    Get image
    */
    public function getImage()
    {
        return sc_image_get_path($this->image);

    }

    public function getUrl()
    {
        return route('cms.category', ['alias' => $this->alias]);
    }

    //Fields language
    public function getTitle()
    {
        return $this->processDescriptions()['title'] ?? '';
    }
    public function getKeyword()
    {
        return $this->processDescriptions()['keyword'] ?? '';
    }
    public function getDescription()
    {
        return $this->processDescriptions()['description'] ?? '';
    }

//Attributes
    public function getTitleAttribute()
    {
        return $this->getTitle();
    }
    public function getKeywordAttribute()
    {
        return $this->getKeyword();

    }
    public function getDescriptionAttribute()
    {
        return $this->getDescription();

    }

    public function processDescriptions()
    {
        return $this->descriptions->keyBy('lang')[sc_get_locale()] ?? [];
    }

    protected static function boot()
    {
        parent::boot();
        // before delete() method call this
        static::deleting(function ($category) {
            //Delete category descrition
            $category->descriptions()->delete();
        });
    }

//Scort
    public function scopeSort($query, $column = null)
    {
        $column = $column ?? 'sort';
        return $query->orderBy($column, 'asc')->orderBy('id', 'desc');
    }

//=========================

    public function uninstall()
    {
        if (Schema::hasTable($this->table)) {
            Schema::drop($this->table);
        }
    }

    public function install()
    {
        $return = ['error' => 0, 'msg' => 'Install modules success'];
        if (!Schema::hasTable($this->table)) {
            try {
                Schema::create($this->table, function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('image', 100)->nullable();
                    $table->tinyInteger('parent')->default(0);
                    $table->string('alias', 120)->unique();
                    $table->tinyInteger('sort')->default(0);
                    $table->tinyInteger('status')->default(0);
                });

            } catch (\Exception $e) {
                $return = ['error' => 1, 'msg' => $e->getMessage()];
            }
        } else {
            $return = ['error' => 1, 'msg' => 'Table ' . $this->table . ' exist!'];
        }
        return $return;
    }
}
