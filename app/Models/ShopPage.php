<?php
#app/Models/ShopPage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
class ShopPage extends Model
{
    public $timestamps = false;
    public $table = SC_DB_PREFIX.'shop_page';
    protected $connection = SC_CONNECTION;
    protected $guarded = [];
    protected $appends = [
        'title',
        'keyword',
        'description',
        'content',
    ];

    public function descriptions()
    {
        return $this->hasMany(ShopPageDescription::class, 'page_id', 'id');
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
    public function getContent()
    {
        return $this->processDescriptions()['content'] ?? '';
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
        return route('pages', ['alias' => $this->alias]);
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

    public function getContentAttribute()
    {
        return $this->getContent();

    }
    public function processDescriptions()
    {
        return $this->descriptions->keyBy('lang')[sc_get_locale()] ?? [];
    }

/**
     * Get list page
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
     * Process get list page
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
        $data = $data->get()->groupBy('id');

        return $data;
    }

    /**
     * Process list full page
     *
     * @return  [type]  [return description]
     */
    private function processListFull()
    {
        if(sc_config('cache_status') && sc_config('cache_page')) {
            if (!Cache::has('cache_page')) {
                $listFullPage = $this->processList();
                Cache::put('cache_page', $listFullPage, $seconds = sc_config('cache_time', 0)?:600);
            }
            return Cache::get('cache_page');
        } else {
            return $this->processList();
        }
    }

}
