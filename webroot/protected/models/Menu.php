<?php

/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property string $id
 * @property string $title
 * @property string $pid
 * @property string $sort
 * @property string $url
 * @property integer $hide
 * @property string $tip
 * @property string $group
 * @property integer $is_dev
 */
class Menu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hide, is_dev', 'numerical', 'integerOnly'=>true),
			array('title, group', 'length', 'max'=>50),
			array('pid, sort', 'length', 'max'=>10),
			array('url, tip', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, pid, sort, url, hide, tip, group, is_dev', 'safe', 'on'=>'search'),
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
			'id' => '文档ID',
			'title' => '标题',
			'pid' => '上级分类ID',
			'sort' => '排序（同级有效）',
			'url' => '链接地址',
			'hide' => '是否隐藏',
			'tip' => '提示',
			'group' => '分组',
			'is_dev' => '是否仅开发者模式可见',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('sort',$this->sort,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('hide',$this->hide);
		$criteria->compare('tip',$this->tip,true);
		$criteria->compare('group',$this->group,true);
		$criteria->compare('is_dev',$this->is_dev);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getDevValue(){
		return $this->is_dev?'是':'否';
	}
	
	public function getHideValue(){
		return $this->hide?'是':'否';
	}
	
	public function getPath($id){
		$path = array();
		$nav = $this->findByPk($id);
		$path[] = $nav->attributes;
		if($nav['pid']>0){
			$path = array_merge($this->getPath($nav['pid']),$path);
		}
		return $path;
	}
}
