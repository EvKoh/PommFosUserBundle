<?php

namespace Vibby\PommProjectFosUserBundle\Manager;

use FOS\UserBundle\Model\UserManager as BaseUserManager;
use FOS\UserBundle\Model\UserInterface;
use Vibby\PommProjectFosUserBundle\Exception\Exception;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Vibby\PommProjectFosUserBundle\Entity\UserEntity;
use PommProject\Foundation\Inflector;
use PommProject\Foundation\Where;
use Vibby\PommProjectFosUserBundle\Model\User;

/**
 * Description of UserManager
 *
 * @author florian
 */
class UserManager extends BaseUserManager
{

    /**
     * @var WriteModel
     */
    protected $model;

    public function __construct(
            EncoderFactoryInterface $encoderFactory,
            CanonicalizerInterface $usernameCanonicalizer,
            CanonicalizerInterface $emailCanonicalizer,
            $pommModelManager
    ) {
        parent::__construct(
            $encoderFactory,
            $usernameCanonicalizer,
            $emailCanonicalizer
        );
        $this->pommModel = $pommModelManager->getModel();
    }

    public function createUser()
    {
        return $this->model->createEntity();
    }

    /**
     * Check if $user is a good instance and return the model
     * @param UserInterface $user instance to check
     * @return WriteModel the model
     * @throws Exception throwed if $user is not a good instance
     */
    protected function checkUser(User $user)
    {
        return $this->pommModel;
    }

    public function deleteUser(UserInterface $user)
    {
        return $this->checkUser($user)
                    ->deleteOne($user);
    }

    public function findUserBy(array $criteria)
    {
        $where = $this->createWhereByCriteria($criteria);
        $result = $this->pommModel->findWhere($where);
        if ($result->count() > 0) {
            return $result->current();
        }
        return null;
    }

    public function findUsers()
    {
        $this->pommModel->findAll();
    }

    public function getClass()
    {
        return trim($this->pommModel->getFlexibleEntityClass(), '\\');
    }

    public function reloadUser(UserInterface $user)
    {
        return $this->checkUser($user)
                    ->findByPK(
                        $this->getPrimaryKeyValues($user)
                    );
    }

    public function updateUser(UserInterface $user)
    {
        $this->checkUser($user);
        // $user->updateRoles();
        $this->updateCanonicalFields($user);
        $this->updatePassword($user);
        return $this->pommModel->updateOne($user, ['password', 'username_canonical', 'email_canonical']);
    }

    protected function getPrimaryKeyValues(UserEntity $user)
    {
        $colnames = $this->pommModel->getStructure()->getPrimaryKey();
        $values = array();
        for($i = 0, $size = count($colnames); $i < $size; $i++) {
            $colname = $colnames[$i];
            $value = $user->get($colname);
            $values[$colname] = $value;
        }
        return $values;
    }

    protected function createWhereByCriteria($criteria = array()) {
        $where = new Where();
        foreach($criteria as $colname => $value) {
            $colname = Inflector::underscore($colname);
            $element = sprintf('%s = $*', $colname);
            $subWhere = new Where($element, array($value));
            $where->andWhere($subWhere);
        }
        return $where;
    }
}
