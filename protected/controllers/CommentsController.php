<?php

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Всички права запазени.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */
class CommentsController extends Controller
{
	public $layout='//layouts/column1';
	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
		);
	}

	public function actionUpdate($id, $bid = NULL)
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, "Нямате достатъчно права");
		$model=$this->loadModel($id);

		// $this->performAjaxValidation($model);

		if(isset($_POST['Comments']))
		{
			$model->attributes=$_POST['Comments'];
			if($model->save())
			{
				if($bid)
					$this->redirect(array('/bans/view','id'=>intval($bid)));
				$this->redirect(array('/site/index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, "Нямате достатъчно права");
		if($this->loadModel($id)->delete())
			Yii::app()->end("$('#{$id}').remove();");

		return FALSE;
		//if(!isset($_GET['ajax']))
		//	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actions(){
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
			),
		);
	}

	public function loadModel($id)
	{
		$model=Comments::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'Заявената страница не съществува.');
		return $model;
	}

	protected function performAjaxValidation($model, $id='comments-form')
	{
		if(isset($_POST['ajax']) && $_POST['ajax']===$id)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
