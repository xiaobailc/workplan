<?php

/**
 * This is the model class for table "{{tv_tpl_history}}".
 *
 * The followings are the available columns in table '{{tv_tpl_history}}':
 * @property string $id
 * @property integer $version
 * @property string $data
 * @property string $create_time
 * @property integer $tplid
 */
class TvTplHistory extends XBaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tv_tpl_history}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('version, data, tplid', 'required'),
			array('version, tplid', 'numerical', 'integerOnly'=>true),
			array('create_time', 'length', 'max'=>10),
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
			'version' => '版本号',
			'data' => '模板数据',
			'create_time' => '创建时间',
			'status_is' => '状态',
			'tplid' => '模板ID',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TvTplHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
