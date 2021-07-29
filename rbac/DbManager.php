<?php


namespace app\rbac;

use app\rbac\items\Gremium;
use app\rbac\items\Item;
use app\rbac\items\Permission;
use app\rbac\items\Realm;
use app\rbac\items\Role;
use Exception;
use ReflectionClass;
use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\base\InvalidValueException;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\rbac\Rule;

class DbManager extends \yii\rbac\DbManager
{
    /**
     * @var array keys: value of Item::TYPE_XXX, value: className of XXX with Namespace from ITEM
     */
    private array $itemChildClasses;

    public function init()
    {
        parent::init();
        $itemReflection = new ReflectionClass(Item::class);
        $namespace = $itemReflection->getNamespaceName();
        $consts = array_flip($itemReflection->getConstants());
        // only accept consts starting with TYPE_
        $childClasses = array_map(static function ($value) use ($namespace){
            $explodedValue = explode('_', $value);
            if($explodedValue[0] === "TYPE"){
                return $namespace . '\\' . ucfirst(strtolower($explodedValue[1]));
            }
            return null;
        }, $consts);
        // filter out all consts not started with TYPE_
        $childClasses = array_filter($childClasses, static function ($value){
            return !is_null($value);
        });
        $this->itemChildClasses = $childClasses;
    }

    public function getAssignment($roleName, $userId, bool $onlyValid = true): ?Assignment
    {
        if ($this->isEmptyUserId($userId)) {
            return null;
        }

        $query = (new Query())->from($this->assignmentTable)
            ->where(['user_id' => (string) $userId, 'item_name' => $roleName]);

        // new part
        if($onlyValid){
            $now = time();
            $query->andWhere(['<=', 'valid_from', $now])
                ->andWhere(['>=', 'valid_until', $now]);
        }

        $row = $query->one($this->db);

        if ($row === false) {
            return null;
        }

        return new Assignment([
            'userId' => $row['user_id'],
            'roleName' => $row['item_name'],
            'createdAt' => $row['created_at'],
            'validFrom' => $row['valid_from'], // new
            'validUntil' => $row['valid_until'], // new
        ]);
    }

    public function getAssignments($userId, bool $onlyValid = true) : array
    {
        if ($this->isEmptyUserId($userId)) {
            return [];
        }

        $query = (new Query())
            ->from($this->assignmentTable)
            ->where(['user_id' => (string) $userId]);

        // new
        if($onlyValid){
            $now = time();
            $query->andWhere(['<=', 'valid_from', $now])
                ->andWhere(['>=', 'valid_until', $now]);
        }

        $assignments = [];
        foreach ($query->all($this->db) as $row) {
            $assignments[$row['item_name']] = new Assignment([
                'userId' => $row['user_id'],
                'roleName' => $row['item_name'],
                'createdAt' => $row['created_at'],
                'validFrom' => $row['valid_from'], // new
                'validUntil' => $row['valid_until'], // new
            ]);
        }
        return $assignments;
    }



    /**
     * Populates an auth item with the data fetched from database.
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected function populateItem($row): Item
    {
        if(!isset($this->itemChildClasses[$row['type']])){
            throw new InvalidValueException("There is no Item::TYPE_XXX with value {$row['type']}");
        }

        $class = $this->itemChildClasses[$row['type']];

        if (!isset($row['data']) || ($data =
                @unserialize(
                is_resource($row['data']) ? stream_get_contents($row['data']) : $row['data'],
                ['allowed_classes' => array_values($this->itemChildClasses)])) === false
        ){
            $data = null;
        }

        return new $class([
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'],
            'ruleName' => $row['rule_name'] ?: null,
            'data' => $data,
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
            'validFrom' => $row['valid_from'],
            'validUntil' => $row['valid_until'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function assign($role, $userId, int $validFrom = null, int $validUntil = null)
    {
        if(is_null($validFrom)){
            $validFrom = time();
        }

        $assignment = new Assignment([
            'userId' => $userId,
            'roleName' => $role->name,
            'createdAt' => time(),
            'validFrom' => $validFrom,
            'validUntil' => $validUntil,
        ]);

        /** @noinspection MissedFieldInspection */
        $this->db->createCommand()
            ->insert($this->assignmentTable, [
                'user_id' => $assignment->userId,
                'item_name' => $assignment->roleName,
                'created_at' => $assignment->createdAt,
                'valid_from' => $assignment->validFrom,
                'valid_until' => $assignment->validUntil,
            ])->execute();

        unset($this->checkAccessAssignments[(string) $userId]);
        return $assignment;
    }

    public function addChild($parent, $child): bool
    {
        if ($child instanceof Realm) {
            throw new InvalidArgumentException('Cannot add a realm as a child.');
        }
        if ($child instanceof Gremium && !($parent instanceof Realm || $parent instanceof Gremium)) {
            throw new InvalidArgumentException('Cann only add Gremium as a child of realm or gremium not ' . get_class($parent));
        }
        // TODO: check more?

        if ($parent->name === $child->name) {
            throw new InvalidArgumentException("Cannot add '{$parent->name}' as a child of itself.");
        }

        if ($parent instanceof Permission) {
            throw new InvalidArgumentException('Cannot add to a permission as parent.');
        }

        if ($this->detectLoop($parent, $child)) {
            throw new InvalidCallException("Cannot add '{$child->name}' as a child of '{$parent->name}'. A loop has been detected.");
        }

        /** @noinspection MissedFieldInspection */
        $this->db->createCommand()
            ->insert($this->itemChildTable, ['parent' => $parent->name, 'child' => $child->name])
            ->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * Adds a role, permission, realm, gremium or rule to the RBAC system.
     * @param Role|Permission|Rule|Gremium|Realm|Item $object
     * @return bool whether the item is successfully added to the system
     * @throws Exception if data validation or saving fails (such as the name of item is not unique)
     */
    public function add($object) : bool
    {
        if (is_subclass_of($object, Item::class)) {
            if ($object->ruleName && $this->getRule($object->ruleName) === null) {
                $rule = \Yii::createObject($object->ruleName);
                $rule->name = $object->ruleName;
                $this->addRule($rule);
            }

            return $this->addItem($object);
        }

        if ($object instanceof Rule) {
            return $this->addRule($object);
        }

        throw new InvalidArgumentException('Adding unsupported object type.');
    }

    /**
     * Adds an auth item to the RBAC system.
     * @param Item $item the item to add
     * @return bool whether the auth item is successfully added to the system
     * @throws Exception if data validation or saving fails (such as the name of the role or permission is not unique)
     */
    protected function addItem($item)
    {
        $time = time();
        if ($item->createdAt === null) {
            $item->createdAt = $time;
        }
        if ($item->updatedAt === null) {
            $item->updatedAt = $time;
        }
        if (is_null($item->validFrom)) {
            $item->validFrom = $time;
        }

        /** @noinspection MissedFieldInspection */
        $this->db->createCommand()
            ->insert($this->itemTable, [
                'name' => $item->name,
                'type' => $item->type,
                'description' => $item->description,
                'rule_name' => $item->ruleName,
                'data' => $item->data === null ? null : serialize($item->data),
                'created_at' => $item->createdAt,
                'updated_at' => $item->updatedAt,
                'valid_from' => $item->validFrom,
                'valid_until' => $item->validUntil
            ])->execute();

        $this->invalidateCache();

        return true;
    }

    /**
     * {@inheritdoc}
     * The roles returned by this method include the roles assigned via [[$defaultRoles]].
     */
    public function getRolesByUser($userId, bool $onlyValid = true): array
    {
        if ($this->isEmptyUserId($userId)) {
            return [];
        }

        $query = (new Query())->select('b.*')
            ->from(['a' => $this->assignmentTable, 'b' => $this->itemTable])
            ->where('{{a}}.[[item_name]]={{b}}.[[name]]')
            ->andWhere(['a.user_id' => (string) $userId])
            ->andWhere(['b.type' => Item::TYPE_ROLE]);

        $roles = $this->getDefaultRoleInstances();
        foreach ($query->all($this->db) as $row) {
            $item = $this->populateItem($row);
            if(!$onlyValid || $item->isValid()){
                $roles[$row['name']] = $item;
            }
        }

        return $roles;
    }

}