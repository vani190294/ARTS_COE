<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coe_login_details}}".
 *
 * @property integer $login_detail_id
 * @property integer $login_user_id
 * @property string $login_at
 * @property string $login_out
 * @property string $login_ip_address
 * @property integer $login_status
 *
 * @property User $loginUser
 */
class LoginDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coe_login_details}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login_user_id', 'login_at', 'login_out', 'login_ip_address'], 'required'],
            [['login_user_id', 'login_status'], 'integer'],
            [['login_at', 'login_out'], 'safe'],
            [['login_ip_address'], 'string', 'max' => 100],
            [['login_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['login_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login_detail_id' => 'Login Detail ID',
            'login_user_id' => 'Login User ID',
            'login_at' => 'Login At',
            'login_out' => 'Login Out',
            'login_ip_address' => 'Login Ip Address',
            'login_status' => 'Login Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLoginUser()
    {
        return $this->hasOne(User::className(), ['id' => 'login_user_id']);
    }

    public function get_ip_address()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe 
                    return $ip=="::1"?"127.0.0.1":$ip;
                    // if returns ::1 means its a IPV4 loopback address                    
                }
            }
        }
    }
}
