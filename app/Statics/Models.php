<?php

namespace App\Statics;

class Models
{
    const CONTACT_TYPE_NONE = 1;
    const CONTACT_TYPE_PHONE = 2;
    const CONTACT_TYPE_EMAIL = 3;
    const CONTACT_TYPE_FAX = 4;
                
    const CATEGORY_TYPE_NONE = 1;
    const CATEGORY_TYPE_USERS = 2;
    const CATEGORY_TYPE_PROPERTY = 3;
    const CATEGORY_TYPE_CONTRACT = 4;

    const LEVEL_TYPE_NONE = 1;
    const LEVEL_TYPE_ADMIN = 2;
    const LEVEL_TYPE_REDAC = 3;
    const LEVEL_TYPE_EDIT = 4;

    const USER_TYPE_OTHER = 1;
    const USER_TYPE_TENANT = 2;
    const USER_TYPE_OWNER = 3;
    const USER_TYPE_SYNDIC = 4;

    const LANG_TYPE_NONE = 1;
    const LANG_TYPE_FR = 2;
    const LANG_TYPE_NL = 3;

    const CIVIL_TYPE_NONE = 1;
    const CIVIL_TYPE_MR = 2;
    const CIVIL_TYPE_MM = 3;

    const ECIV_TYPE_NC = 1;
    const ECIV_TYPE_ALONE = 2;
    const ECIV_TYPE_MARIED = 3;
    const ECIV_TYPE_SPLIT = 4;
    const ECIV_TYPE_DIV = 5;
    const ECIV_TYPE_WID = 6;

    const STATUS_TYPE_ACTIVE = 1;
    const STATUS_TYPE_WAITNG = 2;
    const STATUS_TYPE_SUSPEND = 3;

    const COUNTRY_TYPE_DEFAULT = 56;
    
    const PROPERTY_TYPE_OTHER = 1;
    const PROPERTY_TYPE_APPART = 2;
    const PROPERTY_TYPE_HOUSE = 3;

    const BS_LAYOUT_6COL = "col-md-2 col-sm-4 col-xs-6";
    const BS_LAYOUT_4COL = "col-md-3 col-sm-6 col-xs-12";
    const BS_LAYOUT_3COL = "col-md-4 col-sm-6 col-xs-12";
    const BS_LAYOUT_2COL = "col-md-6 col-sm-12";
    const BS_LAYOUT_1COL = "col-md-12 col-xs-12";

    const BS_LAYOUT_CELL = "col-md-1 col-sm-2 col-xs-3";

}