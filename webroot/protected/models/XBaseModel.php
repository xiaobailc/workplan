<?php
/**
 * 模型基础类，所有模型均需继承此类
 * @author Shiliang <guan.shiliang@gmail.com>
 */
class XBaseModel extends CActiveRecord
{
	/**
	 * 检测用户密码
	 *
	 * @return boolean
	 */
	public function validatePassword($password)
	{
		return $this->hashPassword($this->password) === $password;
	}

	/**
	 * 密码进行加密
	 * @return string password
	 */
	public function hashPassword($password)
	{
		return md5($password);
	}

	/**
	 * 数据保存前处理
	 * @return boolean.
	 */
	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			$this->hasAttribute('create_time') && $this->create_time = time();
			$this->hasAttribute('update_time') && $this->update_time = time();
		} else {
			$this->hasAttribute('update_time') && $this->update_time = time();
		}
		return true;
	}
}