<?php

/**
 * This is the model class for table "{{daily}}".
 *
 * The followings are the available columns in table '{{daily}}':
 * @property string $id
 * @property integer $user_id
 * @property string $user_name
 * @property string $report_info
 * @property integer $status
 * @property string $date_time
 * @property integer $create_time
 */
class Daily extends XBaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{daily}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, user_name, report_info, status, date_time, create_time', 'required'),
			array('user_id, status, create_time', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, user_name, report_info, status, date_time, create_time', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'user_name' => 'User Name',
			'report_info' => 'Report Info',
			'status' => 'Status',
			'date_time' => 'Date Time',
			'create_time' => 'Create Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('report_info',$this->report_info,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('data_time',$this->data_time,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Daily the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
