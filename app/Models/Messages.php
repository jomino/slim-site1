<?php

namespace App\Models;

class Messages extends \Framework\Model
{

    /**
    * @read
    */
    protected $_withLocal = true;

    /**
    * @column
    * @primary
    * @readwrite
    * @type integer
    * @label id
    * @validate required
    */
    protected $_id_msg;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id clients
    */
    protected $_id_cli;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id user
    * @belongto users.id_ref::-
    */
    protected $_id_user;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id msgcls
    * @belongto msgcls.id_cls::msgcls.ref_cls
    */
    protected $_id_cls;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label id msgtypes
    * @belongto msgtypes.id_msgtype::msgtypes.ref_msgtype
    */
    protected $_id_msgtype;

    /**
    * @column
    * @readwrite
    * @label title
    * @type text
    */
    protected $_title;

    /**
    * @column
    * @readwrite
    * @label msg text
    * @type text
    */
    protected $_text;

    /**
    * @column
    * @readwrite
    * @type integer
    * @label message read
    * @comment values[0/1]
    */
    protected $_proceed;

    /**
    * @column
    * @readwrite
    * @label msg uniq id
    * @type text
    */
    protected $_uid;

    /**
    * @column
    * @readwrite
    * @type date
    * @label recieved
    */
    protected $_received;

    /**
    * @column
    * @readwrite
    * @type date
    * @label respond
    */
    protected $_sent;

}