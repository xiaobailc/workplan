<?php

/**
 * This is the model class for table "{{stucture}}".
 *
 * The followings are the available columns in table '{{stucture}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $user_name
 * @property integer $pid
 * @property integer $department_id
 * @property string $acl_ids
 */
class Structure extends XBaseModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{structure}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, user_id, user_name, pid, department_id, deep', 'required'),
			array('id, user_id, pid, department_id, deep', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, user_name, pid, department_id, deep', 'safe', 'on'=>'search'),
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
			'pid' => 'Pid',
			'department_id' => 'Department',
			'deep' => 'Deep',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('department_id',$this->department_id);
		$criteria->compare('deep',$this->deep,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stucture the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 根据ID递归获取所有下属树结构
	 * @param unknown $pid
	 */
	public function getlower($id,&$res=[]){
	    $items = $this->findAll("pid=".$id);
	    if(empty($items)){
	        return;
	    }
	    foreach ($items as $item){
	        $res[] = $item->attributes;
	        if($item->id){
	            $this->getlower($item->id,$res);
	        }
	    }
	    return;
	}
}
