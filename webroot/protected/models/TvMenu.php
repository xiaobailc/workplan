<?php

/**
 * This is the model class for table "{{tv_menu}}".
 *
 * The followings are the available columns in table '{{tv_menu}}':
 * @property string $id
 * @property string $title
 * @property integer $pid
 * @property string $key
 * @property integer $status
 * @property integer $sort
 * @property integer $wpid
 */
class TvMenu extends XBaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tv_menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, pid , key , tvid', 'required'),
			array('pid, sort, tvid', 'numerical', 'integerOnly'=>true),
			array('key', 'length', 'max'=>100),
			array('title', 'length', 'max'=>20),
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
			'title' => '栏目名称',
			'pid' => '父ID',
			'key' => '关键字',
			'status_is' => '状态',
			'sort' => 'Sort',
			'tvid' => '版本ID',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TvMenu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
