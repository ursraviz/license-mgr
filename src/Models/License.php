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
 * Core model for LICENSE object.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Lanser\OmniView\Traits\ModelHelperTrait;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    use SoftDeletes, ModelHelperTrait;

	/**
     * Define database connection and table for this model.
	 *
	 * @var string
	 */
    protected $connection = 'core';
	protected $table = 'licenses';

    /**
     * Attributes to be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'start_at', 'end_at'];

	/**
	 * Attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'status',
        'account_id',
        'lic_type_id',
        'lic_mode',
        'start_at',
        'end_at',
        'max_users',
        'max_concurrent',
        'max_orgs',
        'code',
        'notes'
    ];

	/**
	 * Attributes excluded from model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

    /**
     * Validation rules
     */
    protected $rules = [
        'status'     => ['required', 'in:active,locked,pending,expired'],
        'account_id' => ['required', 'exists:accounts,id'],
        'lic_type_id' => ['required', 'exists:license_types,type'],
        'lic_mode'   => ['required', 'in:account,service,function'],
        'start_at'   => ['required', 'date'],
        'end_at'     => ['required', 'date'],
        'max_users'  => ['integer', 'min:0'],
        'max_concurrent' => ['integer', 'min:0'],
        'max_orgs'   => ['integer', 'min:0'],
        'code'       => ['max:255'],
        'notes'      => ['max:1024'],
    ];

    /**
     * Core 'sanitize' filters used with PHP 'filter_var_array()''
     */
    protected $sanitizers = [
        'code'       => FILTER_SANITIZE_STRING,
        'status'     => FILTER_SANITIZE_STRING,
        'account_id' => FILTER_SANITIZE_NUMBER_INT,
        'lic_type_id'   => FILTER_SANITIZE_STRING,
        'lic_mode'   => FILTER_SANITIZE_STRING,
        'start_at'   => FILTER_SANITIZE_NUMBER_INT,
        'end_at'     => FILTER_SANITIZE_NUMBER_INT,
        'max_users'  => FILTER_SANITIZE_NUMBER_INT,
        'max_concurrent' => FILTER_SANITIZE_NUMBER_INT,
        'max_orgs'   => FILTER_SANITIZE_NUMBER_INT,
        'notes'      => FILTER_SANITIZE_STRING,
    ];

    /**
     * Establish relationship to ACCOUNT object
     *
     * @return Account
     */
    public function account()
    {
        return $this->belongsTo('Lanser\OmniView\Models\Account');
    }

    /**
     * Establish relationship to LICENSE TYPE object
     *
     * @return Account
     */
    public function lic_type()
    {
        return $this->belongsTo('Lanser\OmniView\Models\LicenseType', 'lic_type_id', 'type');
    }

    /**
     * Pre-defined query scopes
     *
     * @param type $query
     */
    public function scopeValid($query)
    {
        $query->where('start_at', '<=', Carbon::now())
              ->where('end_at',   '>=', Carbon::now());
    }

    public function scopeInvalid($query)
    {
        $query->where('start_at', '>', Carbon::now())
              ->orWhere('end_at', '<', Carbon::now());
    }

    /**
     * Ensure that we always store 'type' in lower case
     *
     * @param string $type
     */
    public function setTypeAttribute($type)
    {
        $this->attributes['lic_type_id'] = strtolower($type);
    }

    /**
     * Ensure that we always store 'code' as uppercase
     *
     * @param string $code
     */
    public function setCodeAttribute($code)
    {
        $this->attributes['code'] = strtoupper($code);
    }

    /**
     * Ensure that we get proper 'start of day' timestamp
     *
     * @param string $date
     */
    public function setStartAtAttribute($date)
    {
        $this->attributes['start_at'] = Carbon::parse($date);
    }

    /**
     * Ensure that we get proper 'end of day' timestamp
     *
     * @param string $date
     */
    public function setEndAtAttribute($date)
    {
        $this->attributes['end_at'] = Carbon::parse($date)->endOfDay();
    }

}
