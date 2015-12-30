<?php 

namespace Lanser\OmniView\Models;

/**
 *                ____                  _ _    ___             
 *               / __ \____ ___  ____  (_) |  / (_)__ _      __
 *              / / / / __ `__ \/ __ \/ /| | / / / _ \ | /| / /
 *             / /_/ / / / / / / / / / / | |/ / /  __/ |/ |/ / 
 *             \____/_/ /_/ /_/_/ /_/_/  |___/_/\___/|__/|__/  
 *  
 * 
 * Core model for LICENSE TYPE object. 
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Lanser\OmniView\Traits\ModelHelperTrait;

use Illuminate\Database\Eloquent\Model;

class LicenseType extends Model
{
    use ModelHelperTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $connection = 'core';
	protected $table = 'license_types';

    /**
     * Define primary key for this model.
     * 
     * @note Laravel models noramlly use 'id' as name of the primary key.
     *
     * @var string
     */
    protected $primaryKey = 'type';

    /**
     * Disable auto-incrementing when we use TYPE as primary key
     *
     * @var bool
     */
    public $incrementing = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'type',
        'status', 
        'name',
        'description',
        'default_lic_mode',
        'default_term_unit',
        'default_term_len',
        'default_max_users',
        'default_max_concurrent',
        'default_max_orgs',
        'notes'
    ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

    /**
     * Validation rules
     */
    protected $rules = [
        'type'         => ['required', 'alpha_dash'],
        'status'       => ['required', 'in:active,locked,pending,expired'],
        'name'         => ['required', 'max:255'],
        'description'  => ['required', 'max:1024'],
        'default_lic_mode' => ['required', 'in:account,service,function'],
        'default_term_unit' => ['in:day,month,year'],
        'default_term_len' => ['integer', 'min:1'], 
        'default_max_users' => ['integer', 'min:0'],
        'default_max_concurrent' => ['integer', 'min:0'],
        'default_max_orgs' => ['integer', 'min:0'],
        'notes'        => ['max:1024'],
    ];

    /**
     * Core 'sanitize' filters used with PHP 'filter_var_array()''
     */
    protected $sanitizers = [
        'type'         => FILTER_SANITIZE_STRING,
        'status'       => FILTER_SANITIZE_STRING,
        'name'         => FILTER_SANITIZE_STRING,
        'description'  => FILTER_SANITIZE_STRING,
        'default_lic_mode' => FILTER_SANITIZE_STRING,
        'default_term_unit' => FILTER_SANITIZE_STRING,
        'default_term_len' => FILTER_SANITIZE_NUMBER_INT,
        'default_max_users' => FILTER_SANITIZE_NUMBER_INT,
        'default_max_concurrent' => FILTER_SANITIZE_NUMBER_INT,
        'default_max_orgs' => FILTER_SANITIZE_NUMBER_INT,
        'notes'        => FILTER_SANITIZE_STRING,
    ];

    /**
     * Ensure that we always store 'type' in lower case
     * 
     * @param string $type
     */
    public function setTypeAttribute($type)
    {
        $this->attributes['type'] = strtolower($type);
    }

    /**
     * Establish relationship to LICENSE object
     * 
     * @return License
     */
    public function licenses()
    {
        return $this->hasMany('Lanser\OmniView\Models\License', 'lic_type_id', 'type');
    }

    /**
     * Generate properly formatted term length and unit string. 
     * 
     * We support both full-length unit names (singular and plurar), and 
     * abbreviations (e.g. '11wk' vs. '11 weeks').
     * 
     * @param  bool $short
     * @return string
     */
    public function getTermString($short = false)
    {
        if ( $short )
        {
            $map = [
                MODE_DAY   => 'd',
                MODE_WEEK  => 'wk',
                MODE_MONTH => 'mon',
                MODE_YEAR  => 'yr'
            ];

            $unit = (array_key_exists($this->default_term_unit, $map)) 
                  ? $map[$this->default_term_unit]
                  : $this->default_term_unit;
        }
        else
        {
            $unit = ($this->default_term_len > 1) 
                  ? str_plural($this->default_term_unit)
                  : str_singular($this->default_term_unit);
            
            $unit = ' '. $unit;
        }
        
        return $this->default_term_len . $unit;
    }
    
}
