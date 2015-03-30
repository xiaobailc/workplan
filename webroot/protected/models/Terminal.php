<?php

/**
 * This is the model class for table "{{terminal}}".
 *
 * The followings are the available columns in table '{{terminal}}':
 * @property string $id
 * @property string $name
 * @property string $headface_url
 * @property integer $type
 * @property integer $create_time
 * @property integer $update_time
 * @property string $status_is
 */
class Terminal extends XBaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{terminal}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, partial , type , group', 'required'),
			array('type, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('partial', 'length', 'max'=>20),
			array('partial', 'unique'),
			array('name', 'length', 'max'=>50),
			array('headface_url', 'length', 'max'=>255),
			array('status_is', 'length', 'max'=>1),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '版本名称',
			'partial' => '版本代号',
			'headface_url' => '封面图片地址',
			'group' => '所属公司',
			'type' => '版本类型',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'status_is' => '状态',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Terminal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
