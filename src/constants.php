<?php

/**
 *                ____                  _ _    ___
 *               / __ \____ ___  ____  (_) |  / (_)__ _      __
 *              / / / / __ `__ \/ __ \/ /| | / / / _ \ | /| / /
 *             / /_/ / / / / / / / / / / | |/ / /  __/ |/ |/ /
 *             \____/_/ /_/ /_/_/ /_/_/  |___/_/\___/|__/|__/
 *
 *
 * Global Constants
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */
const __OMNIVIEW__      = 'OmniView';

const STATUS_DELETED    = 'deleted';
const STATUS_BANNED     = 'banned';
const STATUS_SUSPENDED  = 'suspended';
const STATUS_PENDING    = 'pending';
const STATUS_EXPIRED    = 'expired';
const STATUS_ACTIVE     = 'active';
const STATUS_LOCKED     = 'locked';

const STATUS_AUTO       = 'automatic';
const STATUS_OPEN       = 'open';
const STATUS_CLOSED     = 'closed';
const STATUS_CREATED    = 'created';
const STATUS_UPDATED    = 'updated';
const STATUS_ONHOLD     = 'on hold';

const STATUS_SUCCESS    = 'success';
const STATUS_FAILURE    = 'failure';

const STATUS_INFO       = 'info';
const STATUS_ERROR      = 'error';
const STATUS_WARNING    = 'warning';

const LOGTYPE_EMERGENCY = 'emergency';
const LOGTYPE_ALERT     = 'alert';
const LOGTYPE_CRITICAL  = 'critical';
const LOGTYPE_ERROR     = 'error';
const LOGTYPE_WARNING   = 'warning';
const LOGTYPE_NOTICE    = 'notice';
const LOGTYPE_INFO      = 'info';
const LOGTYPE_DEBUG     = 'debug';

const BOOL_YES          = 'yes';
const BOOL_NO           = 'no';
const BOOL_TRUE         = 'true';
const BOOL_FALSE        = 'false';

const ACCTTYPE_OWNER    = 'owner';
const ACCTTYPE_CLIENT   = 'client';
const ACCTTYPE_VENDOR   = 'vendor';
const ACCTTYPE_PARTNER  = 'partner';
const ACCTTYPE_RESELLER = 'reseller';

const LICTYPE_DEMO      = 'demo';
const LICTYPE_TRIAL     = 'trial';
const LICTYPE_FULL      = 'full';
const LICTYPE_DEV       = 'developer';

const EVNTTYPE_CREATED  = 'created';
const EVNTTYPE_UPDATED  = 'updated';
const EVNTTYPE_DELETED  = 'deleted';
const EVNTTYPE_RENEWED  = 'renewed';
const EVNTTYPE_EXPIRED  = 'expired';
const EVNTTYPE_CANCELED = 'canceled';
const EVNTTYPE_UNCANCELED = 'uncanceled';
const EVNTTYPE_NEW_LIC_TYPE = 'new lic type';
const EVNTTYPE_OTHER    = 'other';

const EVNTTYPE_SCHEDULED = 'scheduled';
const EVNTTYPE_MAINTENANCE = 'maintenance';
const EVNTTYPE_INTERMIT = 'intermit';
const EVNTTYPE_OUTAGE   = 'outage';

const ACTIVITY_LOGIN    = 'login';
const ACTIVITY_LOGOUT   = 'logout';
const ACTIVITY_VIEW     = 'view';
const ACTIVITY_EDIT     = 'edit';
const ACTIVITY_CREATE   = 'create';
const ACTIVITY_DELETE   = 'delete';
const ACTIVITY_TRUNCATE = 'truncate';
const ACTIVITY_UNDELETE = 'undelete';
const ACTIVITY_EXPORT   = 'export';
const ACTIVITY_IMPORT   = 'import';
const ACTIVITY_CANCEL   = 'cancel';
const ACTIVITY_UNCANCEL = 'uncancel';
const ACTIVITY_OTHER    = 'other';

const RECTYPE_DBOARD    = 'dashboard';
const RECTYPE_APP       = 'application';
const RECTYPE_ORG       = 'organization';
const RECTYPE_ACCT      = 'account';
const RECTYPE_USER      = 'user';
const RECTYPE_LIC       = 'license';
const RECTYPE_OTHER     = 'other';

const TICKET_MISC       = 'misc';
const TICKET_SUPPORT    = 'support';

const DELIM_CLI         = '|';

const TIMEFMT_STD       = 'H:i';
const DATEFMT_STD       = 'Y-m-d';
const DATEFMT_MYSQL     = 'Y-m-d H:i:s';
const DATEFMT_EXCEL     = 'Y-m-d';
const DATEFMT_UTC       = '_UTC_';  // @note this is a not a valid date format
                                    //       and must be checked for

const EXPTFMT_CSV       = 'csv';    // standard CSV format
const EXPTFMT_XLS       = 'xls';    // (old) Excel
const EXPTFMT_XLSX      = 'xlsx';   // Excel 2007

const MODE_L1D          = 'l1d';    // Last 1 day -- yesterday/today
const MODE_L7D          = 'l7d';    // Last 7 days
const MODE_LWK          = 'lwk';    // Last week
const MODE_L30D         = 'l30d';   // Last 30 days
const MODE_L90D         = 'l30d';   // Last 90 days
const MODE_LMON         = 'lmon';   // Last month
const MODE_L12W         = 'l12w';   // Last 12 weeks
const MODE_L12M         = 'l12m';   // Last 12 months

const MODE_ALL          = 'all';
const MODE_YEAR         = 'year';
const MODE_HALF         = 'half';
const MODE_QTR          = 'qtr';
const MODE_MONTH        = 'month';
const MODE_WEEK         = 'week';
const MODE_DAY          = 'day';
const MODE_DATE         = 'date';

const MODE_ACCT         = 'account';
const MODE_SERV         = 'service';
const MODE_FUNC         = 'function';

const MODE_DEMO         = 'demo';
const MODE_OFFSET       = 'offset';

const MODE_EMAIL        = 'email';
const MODE_AUTO         = 'auto';

const METHOD_PUT        = 'PUT';

const PRIO_HI           = 10;
const PRIO_MED          = 5;
const PRIO_LO           = 1;
const PRIO_NONE         = 0;

const MAX_LOGIN         = 10;
const MAX_INTERVAL      = 30;

const STR_EMPTY         = '';
const STR_BLANK         = '- blank -';
const STR_NA            = '- n/a -';

const ROLE_SYSADMIN     = 'sysadmin';
const ROLE_SUPPORT      = 'support';


// Get primes at http://www.rsok.com/~jrm/printprimes.html
//
// @notes Create new numbers with "php vendor/bin/optimus spark MY_LARGE_PRIME"
//
// @usage "new Optimus(1678917679, 265488079, 1360120761);"
//        "new Optimus(OPTIMUS_PRIME, OPTIMUS_INVERSE, OPTIMUS_RANDOM);"
//
const OPTIMUS_PRIME     = 1678917679;
const OPTIMUS_INVERSE   = 265488079;
const OPTIMUS_RANDOM    = 1360120761;
