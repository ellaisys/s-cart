<?php
#app/Models/ShopCategory.php
namespace App\Models;

use App\Models\ShopCategoryDescription;
use App\Models\ShopProduct;
use Cache;
use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    public $timestamps = false;
    public $table = SC_DB_PREFIX . 'shop_category';
    protected $guarded = [];
    protected $connection = SC_CONNECTION;
    public $appends = [
        'name',
        'keyword',
        'description',
    ];

    public function products()
    {
        return $this->belongsToMany(ShopProduct::class, SC_DB_PREFIX . 'shop_product_category', 'category_id', 'product_id');
    }

    public function descriptions()
    {
        return $this->hasMany(ShopCategoryDescription::class, 'category_id', 'id');
    }

    /**
     * Get category parent
     */
    public function getParent()
    {
        return $this->find($this->parent);
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


    /**
     * Get all ID category children of parent
     * @param  integer $parent     [description]
     * @param  [type]  &$arrayID      [description]
     * @param  [object]  $categories [description]
     * @return [array]              [description]
     */
    public function getIdCategories($parent = 0, &$arrayID = [], $categories = [])
    {
        $categories = $categories ?? $this->getList();
        $arrayID = $arrayID ?? [];
        $lisCategory = $categories[$parent] ?? [];
        if (count($lisCategory)) {
            foreach ($lisCategory as $category) {
                $arrayID[] = $category['id'];
                if (!empty($categories[$category['id']])) {
                    $this->getIdCategories($category['id'], $arrayID, $categories);
                }
            }
        }
        return $arrayID;
    }

    /**
     * Get tree categories
     *
     * @param   [type]  $parent      [$parent description]
     * @param   [type]  &$tree       [&$tree description]
     * @param   [type]  $categories  [$categories description]
     * @param   [type]  &$st         [&$st description]
     *
     * @return  [type]               [return description]
     */
    public function getTreeCategories($parent = 0, &$tree = [], $categories = null, &$st = '')
    {
        $categories = $categories ?? $this->getList();
        $tree = $tree ?? [];
        $lisCategory = $categories[$parent] ?? [];
        if ($lisCategory) {
            foreach ($lisCategory as $category) {
                $tree[$category['id']] = $st . $category['name'];
                if (!empty($categories[$category['id']])) {
                    $st .= '--';
                    $this->getTreeCategories($category['id'], $tree, $categories, $st);
                    $st = '';
                }
            }
        }
        return $tree;
    }

    /**
     * [getCategoriesTop description]
     * @return [type] [description]
     */
    public function getCategoriesTop()
    {
        return $this->where('status', 1)->where('top', 1)->get();
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
        return route('category', ['alias' => $this->alias]);
    }

    //Fields language
    public function getName()
    {
        return $this->processDescriptions()['name'] ?? '';
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
    public function getNameAttribute()
    {
        return $this->getName();
    }
    public function getKeywordAttribute()
    {
        return $this->getKeyword();
    }
    public function getDescriptionAttribute()
    {
        return $this->getDescription();
    }

    //Scort
    public function scopeSort($query, $sortBy = null, $sortOrder = 'desc')
    {
        $sortBy = $sortBy ?? 'sort';
        return $query->orderBy($sortBy, $sortOrder);
    }

    public function processDescriptions()
    {
        return $this->descriptions->keyBy('lang')[sc_get_locale()] ?? [];
    }

    /**
     * Get list category
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
        if(sc_config('cache_status') && sc_config('cache_category')) {
            $prefix = implode('_', $arrOpt).'__'.implode('_', $arrLimit).'__'.implode('_', $arrSort);
            if (!Cache::has('all_cate_' . $prefix)) {
                $listFullCategory = $this->processList($arrOpt = [], $arrSort = [], $arrLimit = []);
                Cache::put('all_cate_' . $prefix, $listFullCategory, $seconds = sc_config('cache_time', 0)?:600);
            }
            return Cache::get('all_cate_' . $prefix);
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
    private function processList($arrOpt = [], $arrSort = [], $arrLimit = [])
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
     * [getCategory description]
     *
     * @param   [int]  $id     [$id description]
     * @param   [string]  $alias  [$alias description]
     *
     * @return  [type]          [return description]
     */
    public function getCategory($id = null, $alias = null)
    {
        $category = null;
        if ($id) {
            $category = $this->where('id', (int) $id);
        } else {
            $category = $this->where('alias', $alias);
        }
        return $category
            ->where('status', 1)
            ->first();
    }

    /**
     * [getCategories description]
     * @param  [type] $parent    [description]
     * @param  [type] $limit     [description]
     * @param  [type] $opt       [description]
     * @param  [type] $sortBy    [description]
     * @param  string $sortOrder [description]
     * @return [type]            [description]
     */
    public function getCategories($parent = 0, $limit = null, $opt = null, $sortBy = null, $sortOrder = 'asc')
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

}
