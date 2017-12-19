<?php

namespace EvKoh\PommProjectFosUserBundle\Model;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use EvKoh\PommProjectFosUserBundle\Model\User;
use EvKoh\PommProjectFosUserBundle\Model\UserStructure;

/**
 * UserModel
 *
 * Model class for table user.
 *
 * @see Model
 */
class UserModel extends Model
{
    use WriteQueries;

    public $keyForId = 'id';

    /**
     * __construct()
     *
     * Model constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->structure = new UserStructure;
        $this->flexible_entity_class = '\EvKoh\PommProjectFosUserBundle\Model\User';
    }

    public function findUserWhere(Where $where)
    {
        return $this->findWhere($where);
    }
}

