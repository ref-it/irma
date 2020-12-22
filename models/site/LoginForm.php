<?php


namespace app\models\site;



use app\models\MixedUserIdentity;
use app\models\User;
use yii\web\IdentityInterface;
use Yii;


class LoginForm extends \yii\base\Model
{
    public string $username = '';
    public string $password = '';
    public string $mail = '';
    public string $recoveryToken = '';
    public bool $rememberMe = false;

    public const SCENARIO_LOGIN = 'login';
    public const SCENARIO_RECOVER = 'recover';
    public const SCENARIO_REGISTER = 'register';

    private ?IdentityInterface $_user = null;

    public function scenarios() : array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['username', 'password', 'rememberMe'];
        $scenarios[self::SCENARIO_RECOVER] = ['mail', 'recoveryToken'];
        $scenarios[self::SCENARIO_REGISTER] = ['username', 'mail', 'password'];
        return $scenarios;
    }

    public function rules() : array
    {
        return [
            [['username', 'password'], 'required', 'on' => [self::SCENARIO_LOGIN]],
            [['rememberMe'], 'boolean', 'on' => [self::SCENARIO_LOGIN]],
            ['password', 'validatePassword', 'on' => [self::SCENARIO_LOGIN]],
            [['username', 'password'], 'string', 'on' => [self::SCENARIO_LOGIN]],
            ['mail', 'email', 'on' => [self::SCENARIO_RECOVER]],
            ['recoveryToken', 'string', 'on' => [self::SCENARIO_RECOVER]],
        ];
    }

    public function attributeLabels() : array
    {
        return [
            'username' => Yii::t('user', 'username_label'),
            'password' => Yii::t('user','password_label'),
            'recoveryMail' => Yii::t('user','recoveryMail_label'),
            'recoveryToken' => Yii::t('user','recoveryToken_label'),
            'rememberMe' => Yii::t('user', 'rememberMe_label')
        ];
    }

    public function validatePassword($attribute, $params) : void
    {
        if (!$this->hasErrors()) {
            $user = MixedUserIdentity::findIdentity($this->username);

            if($user && Yii::$app->ldapAuth->authenticate($user->getDn(), $this->password)){
                $this->_user = $user;
            }else{
                $this->addError($attribute, \Yii::t('user', 'wrong_username_password'));
            }
        }
    }

    public function login() : bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() : ?IdentityInterface
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}