<?php
/**
 * Laminas Framework (http://framework.Laminas.com/)
 *
 * @link      http://github.com/Laminasframework/AdminApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Laminas Technologies USA Inc. (http://www.Laminas.com)
 * @license   http://framework.Laminas.com/license/new-bsd New BSD License
 */

namespace CostAdmin\Controller;

use CostAdmin\Controller\BasicController;
use CostTranslation\Entity\Message;
use Zf2datatable\Datagrid as Datagrid;
use Zf2datatable\Column as Column;
use Zf2datatable\Action\Mass as Mass;
use Zf2datatable\Column\Type as Type;
use Zf2datatable\Column\Style as Style;
use Laminas\Http\Response\Stream as ResponseStream;
use Laminas\Http\Headers;
use Zf2datatable\Column\Formatter as Formatter;
use PHPExcel;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;
use Zf2datatable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Sql\Predicate\Expression;

class IndexController extends BasicController
{


    public function indexAction()
    {
        return array();
    }


    public function userAction()
    {
        $sm = $this->getServiceLocator();
        $translator = $this->getTranslator();;
        $request    = $this->getRequest();

        $grid = $sm->get('zf2datatablegrid');
        $grid->setTitle('Users');
        $grid->setDefaultItemsPerPage(5);
        $grid->setTranslator($translator);

        $doctrine2Service = $sm->get('doctrine2service');
        $em = $sm->get('doctrine.entitymanager.orm_default');

        $qb = $em->createQueryBuilder();
        $qb->select('u.id,u.lastName,u.firstName,u.email,l.name as language_name,r.name as role_name');
        $qb->from('CostAuthentication\Entity\User', 'u');
        $qb->leftJoin('u.role', 'r');
        $qb->leftJoin('u.language', 'l');
        //$qb->where('l.code = \'It\'');

        $EntitiName = 'CostAuthentication\Entity\User';
        //$grid->setDataSource($doctrine2Service->getLanguage($EntitiName,'u'));
        $grid->setDataSource($doctrine2Service->getUser($qb));
        $grid->getDataSource()->setEntity($EntitiName);

        $identity = $grid->getIdentyColumns();
        $grid->setIsCrud(true);

        $col = new Column\Select('id', 'u');
        $col->setLabel('Id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $actionview = new Column\Action\Button ();
        $actionview->setLabel ('Change');
        $actionview->setAttribute ( 'href', "userpasswd?op=u&id{$identity[0]}=" . $actionview->getRowIdPlaceholder () );
        $actionview->setAttribute ( 'id','change_password');
        $col = new Column\Action('edit_passwd');
        $col->setLabel('Change Password');
        $col->setWidth(2);
        $col->addAction($actionview);
        $grid->addColumn($col);

        $col = new Column\Select('username', 'u');
        $col->setLabel('Username');
        $grid->addColumn($col);

        $col = new Column\Select('lastName', 'u');
        $col->setLabel('LastName');
        $grid->addColumn($col);

        $col = new Column\Select('firstName', 'u');
        $col->setLabel('FirstName');
        $grid->addColumn($col);

        $col = new Column\Select('email', 'u');
        $col->setLabel('Email');
        $grid->addColumn($col);

        $col = new Column\Select('name', 'r');
        $col->setLabel('Role');
        $grid->addColumn($col);

        $col = new Column\Select('state', 'u');
        $col->setLabel('Status');
        $col->setReplaceValues ( [0=>'Disabled',1=>'Active']);
        $grid->addColumn($col);

        $col = new Column\Select('name', 'l');
        $col->setLabel('Language');
        $grid->addColumn($col);

        /*
        $grid->setOptions(
            array('form'=>
                array('doctrine_elements'=>
                    array(
                        'role'=>array(
                            'fieldName'=>'name',
                            'column-size'=>'lg-9 md-9 sm-9',
                            'label_attributes' => array('class' => 'col-md-3'),
                            'multiple'=>false
                        ),
                    ))));


        $grid->setOptions(
            array('form'=>
                array('doctrine_elements'=>
                    array(
                        'language'=>array(
                            'fieldName'=>'name',
                            'column-size'=>'lg-9 md-9 sm-9',
                            'label_attributes' => array('class' => 'col-md-3'),
                            'multiple'=>false
                        ),
                    ))));
        */

        $grid->setFrmMainCrud(new $EntitiName(), true);
        $grid->setPathFileUpload(realpath(dirname(__FILE__).'/../../../../../../data/upload'));
        $grid->removeFormElement('id');
        if($request->getQuery('op')=='u'){
            $grid->removeFormElement('password');
            $grid->removeFormElement('passwordSalt');
            $grid->removeFormElement('lastlogin');
            $grid->removeFormElement('registrationDate');
            $grid->removeFormElement('answer');
            //$grid->removeFormElement('picture');
            $grid->removeFormElement('registrationToken');
            $grid->removeFormElement('question');
        }
        elseif($request->getQuery('op')=='i'){
            $grid->removeFormElement('password');
            $grid->removeFormElement('passwordSalt');
            $grid->removeFormElement('lastlogin');
            $grid->removeFormElement('registrationDate');
            $grid->removeFormElement('answer');
            //$grid->removeFormElement('picture');
            $grid->removeFormElement('registrationToken');
            $grid->removeFormElement('question');


            $grid->addFormElement('password', array(
                'type' => 'password',
                'name' => 'password',
                'attributes' => array(
                    'type' => 'hidden'
                ),
            ), 1000);


            $grid->addFormElement('__PASSWORD_NEW__', array(
                'type' => 'password',
                'name' => '__PASSWORD_NEW__',
                'attributes' => array(
                    'type' => 'password',
                    'class'=>'form-control'
                ),
                'options' => array(
                    'label' => 'PASSWORD NEW'
                )
            ), 200);


            $grid->addFormElement('__PASSWORD_NEW_RETYPE__', array(
                'type' => 'password',
                'name' => '__PASSWORD_NEW_RETYPE__',
                'attributes' => array(
                    'type' => 'password',
                    'class'=>'form-control'
                ),
                'options' => array(
                    'label' => 'PASSWORD NEW RETYPE'
                )
            ), 100);

            $grid->addFormFilterElements(array(
                array(
                    'name' => 'password',
                    'required'=>false,
                ),
                array(
                    'name' => '__PASSWORD_NEW__',
                    'required'=>true,
                    'filters' => array (
                        array (
                            'name' => 'StripTags'
                        ),
                        array (
                            'name' => 'StringTrim'
                        )
                    ),
                    'validators' => array (
                        array (
                            'name' => 'NotEmpty',
                            'options' => array (
                                'messages' => array (
                                    'isEmpty' => 'Password is required'
                                )
                            )
                        ),array(
                         'name' => 'StringLength',
                                'options' => array(
                                    'min' => 7,
                                    'max' => 50,
                                    'encoding' => 'utf-8',
                                    'messages' => array(
                                        \Laminas\Validator\StringLength::TOO_LONG => 'Password can not be more than 50 characters long.',
                                        \Laminas\Validator\StringLength::TOO_SHORT => 'Password name can  be more than 7 characters.',
                                    )
                                )
                           )
                    )
                ),
                array (
                    'name' => '__PASSWORD_NEW_RETYPE__',
                    'required'=>true,
                    'filters' => array (
                        array (
                            'name' => 'StripTags'
                        ),
                        array (
                            'name' => 'StringTrim'
                        )
                    ),
                    'validators' => array (
                        array (
                            'name' => 'identical',
                            'options' => array (
                                'token' => '__PASSWORD_NEW__'
                            )
                        ),
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'min' => 7,
                                'max' => 50,
                                'encoding' => 'utf-8',
                                'messages' => array(
                                    \Laminas\Validator\StringLength::TOO_LONG => 'Password can not be more than 50 characters long.',
                                    \Laminas\Validator\StringLength::TOO_SHORT => 'Password name can  be more than 7 characters.',
                                )
                            )
                        )
                    )

                )
            )
         );

            $grid->getFrmMainCrud()->getInputFilter()->get('username')->getValidatorChain()->attach(
                new \DoctrineModule\Validator\NoObjectExists(array(
                    'use_context' => true,
                    'fields' => ['username'],
                    'object_repository' => $em->getRepository('\CostAuthentication\Entity\User'),
                    'object_manager' => $em,
                ))
                );

            $grid->getFrmMainCrud()->setInputFilter($grid->getFrmMainCrud()->getInputFilter());

       }

        $grid->render();

        return $grid->getResponse();
    }


    public function userpasswdAction()
    {
       $sm = $this->getServiceLocator();
        $translator = $this->getTranslator();;

        $grid = $sm->get('zf2datatablegrid');
        $grid->setTitle('Users');
        $grid->setDefaultItemsPerPage(5);
        $grid->setTranslator($translator);

        $doctrine2Service = $sm->get('doctrine2service');
        $em = $sm->get('doctrine.entitymanager.orm_default');

        $qb = $em->createQueryBuilder();
        $qb->select('u.id,u.lastName,u.firstName,u.email,l.name as language_name,r.name as role_name');
        $qb->from('CostAuthentication\Entity\User', 'u');
        $qb->leftJoin('u.role', 'r');
        $qb->leftJoin('u.language', 'l');
        //$qb->where('l.code = \'It\'');

        $EntitiName = 'CostAuthentication\Entity\User';
        //$grid->setDataSource($doctrine2Service->getLanguage($EntitiName,'u'));
        $grid->setDataSource($doctrine2Service->getUser($qb));
        $grid->getDataSource()->setEntity($EntitiName);

        $identity = $grid->getIdentyColumns();
        $grid->setIsCrud(true);


        $col = new Column\Select('id', 'u');
        $col->setLabel('Id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $grid->setFrmMainCrud(new $EntitiName(), true);

        //$grid->setFrmMainCrud('frmusr');
        $grid->removeFormElement('id');
        $grid->removeFormElement('username');
        $grid->removeFormElement('email');
        $grid->removeFormElement('lastName');
        $grid->removeFormElement('firstName');
        $grid->removeFormElement('displayName');
        $grid->removeFormElement('displayName');
        $grid->removeFormElement('emailConfirmed');
        $grid->removeFormElement('passwordSalt');
        $grid->removeFormElement('lastlogin');
        $grid->removeFormElement('registrationDate');
        $grid->removeFormElement('answer');
        $grid->removeFormElement('picture');
        $grid->removeFormElement('registrationToken');
        $grid->removeFormElement('question');
        $grid->removeFormElement('state');
        $grid->removeFormElement('role');
        $grid->removeFormElement('language');


        $grid->addFormElement('password', array(
            'type' => 'password',
            'name' => 'password',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ), 1000);


        $grid->addFormElement('__PASSWORD_NEW__', array(
            'type' => 'password',
            'name' => '__PASSWORD_NEW__',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'PASSWORD NEW'
            )
        ), 990);


        $grid->addFormElement('__PASSWORD_NEW_RETYPE__', array(
            'type' => 'password',
            'name' => '__PASSWORD_NEW_RETYPE__',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'PASSWORD NEW RETYPE'
            )
        ), 980);

        $grid->addFormFilterElements(array(
            array(
                'name' => 'password',
                'required'=>false,
            ),
            array(
            'name' => '__PASSWORD_NEW__',
            'required'=>true,
                    'filters' => array (
                            array (
                                    'name' => 'StripTags'
                            ),
                            array (
                                    'name' => 'StringTrim'
                            )
                    ),
                    'validators' => array (
                            array (
                                    'name' => 'NotEmpty',
                                    'options' => array (
                                            'messages' => array (
                                                    'isEmpty' => 'Password is required'
                                            )
                                    )
                            ),
                         array(
                         'name' => 'StringLength',
                                'options' => array(
                                    'min' => 7,
                                    'max' => 50,
                                    'encoding' => 'utf-8',
                                    'messages' => array(
                                        \Laminas\Validator\StringLength::TOO_LONG => 'Password can not be more than 50 characters long.',
                                        \Laminas\Validator\StringLength::TOO_SHORT => 'Password name can  be more than 7 characters.',
                                    )
                                )
                           )
                    )
                ),
                array (
                    'name' => '__PASSWORD_NEW_RETYPE__',
                    'required'=>true,
                    'filters' => array (
                        array (
                            'name' => 'StripTags'
                        ),
                        array (
                            'name' => 'StringTrim'
                        ),
                    ),
                    'validators' => array (
                        array (
                            'name' => 'identical',
                            'options' => array (
                                'token' => '__PASSWORD_NEW__'
                            )
                        ),
                       array(
                         'name' => 'StringLength',
                                'options' => array(
                                    'min' => 7,
                                    'max' => 50,
                                    'encoding' => 'utf-8',
                                    'messages' => array(
                                        \Laminas\Validator\StringLength::TOO_LONG => 'Password Retype can not be more than 50 characters long.',
                                        \Laminas\Validator\StringLength::TOO_SHORT => 'Password Retype name can  be more than 7 characters.',
                                    )
                                )
                          )
                    )

                )
            )
        );

        $grid->getFrmMainCrud()->setValidationGroup('__PASSWORD_NEW_RETYPE__','__PASSWORD_NEW_RETYPE__','password');


        $grid->setUrlRouteRedirectCrud("admin-user",array("controller"=>"Index","action"=>"user"),array());
        $grid->render();

        return $grid->getResponse();
    }


    public function roleAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/role
        $sm = $this->getServiceLocator();
        $translator = $this->getTranslator();;
        $dbAdapter = $this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');

        $oRoles     = $this->getTableService()->get('roles');

        $grid = $this->getServiceLocator()->get('zf2datatablegrid');
        $grid->setTitle('Roles');
        $grid->setTranslator($translator);

        $grid->setisAllowAdd(true);
        $grid->setisAllowEdit(true);
        $grid->setisAllowDelete(true);

        $grid->setDefaultItemsPerPage(parent::NUMBER_PAGINATOR_PER_PAGE);
        $grid->setDataSource($oRoles->getRoles(), $dbAdapter);
        $grid->getDataSource()->setTable('roles', 'r');
        $grid->setIsCrud(true);

        $col = new Column\Select('id', 'r');
        $col->setLabel('Id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $col = new Column\Select('name', 'r');
        $col->setLabel('Role');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $col = new Column\Select('name', 'r1');
        $col->setLabel('Parent');
        $col->setIdentity(false, false);
        $col->setFilterSelectOptions ( $oRoles->fetchPairs(false) );
        $grid->addColumn($col);

        $col = new Column\Select('name_id', 'r');
        $col->setSelectType(\Zf2datatable\Column\AbstractColumn::$selectFieldType['Expression']);
        $col->setSelectExpression(new Expression('CONCAT(r.name,\'_\',r1.name)'));
        $col->setLabel('Role -Parent Role');
        $col->setIdentity(false, false);
        $grid->addColumn($col);


        $grid->setFrmMainCrud('frmusr');
        $grid->removeFormElement('id');
        $grid->replaceFormElement('parent_id', array(
            'type' => 'select',
            'name' => 'parent_id',
            'attributes' => array(
                'type' => 'select'
            ),
            'options' => array(
                'label' => 'Role',
                'value_options' => $oRoles->fetchPairs(true)
            )
        ), 970);


        $grid->render();

        return $grid->getResponse();

    }


    /**
     * action per gestire le risorse:
     * possono essere mappate come controller o risorse generiche (es griglie e altro)
     */
    public function resourceAction()
    {
        $sm = $this->getServiceLocator();
        $translator = $this->getTranslator();

        $dbAdapter = $this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');
        $oResources     = $this->getTableService()->get('resources');


        $grid = $this->getServiceLocator()->get('zf2datatablegrid');
        $grid->setTitle('Resources (Controller\'s Key)');
        $grid->setTranslator($translator);

        $grid->setisAllowAdd(true);
        $grid->setisAllowEdit(true);
        $grid->setisAllowDelete(true);

        $grid->setDefaultItemsPerPage(parent::NUMBER_PAGINATOR_PER_PAGE);
        $grid->setDataSource($oResources, $dbAdapter);
        $grid->getDataSource()->setTable('resources', 'rs');
        $grid->setIsCrud(true);

        $col = new Column\Select('id', 'rs');
        $col->setLabel('Id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $col = new Column\Select('name', 'rs');
        $col->setLabel('Resource');
        $grid->addColumn($col);

        $col = new Column\Select('type', 'rs');
        $col->setLabel('Tipo');
        $grid->addColumn($col);

        $grid->setFrmMainCrud('frmusr');
        $grid->removeFormElement('id');

        $grid->replaceFormElement('type', array(
            'type' => 'select',
            'name' => 'type',
            'attributes' => array(
                'type' => 'select'
            ),
            'options' => array(
                'label' => 'Type',
                'value_options' => \CostAuthorization\Model\Entity\Resources::$resuorceType
            )
        ), 970);

        $grid->render();

        return $grid->getResponse();
    }


    /***
     * gestione permessi a database
     */
    public function permissionAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/permissiom
        $request = $this->getRequest();
        $sm = $this->getServiceLocator();
        $translator = $this->getTranslator();;
        $dbAdapter = $this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');

        $oPermissions   = $this->getTableService()->get('permissions');
        $oResources     = $this->getTableService()->get('resources');
        $oRoles         = $this->getTableService()->get('roles');

        $grid = $this->getServiceLocator()->get('zf2datatablegrid');
        $grid->setTitle('Permission (Acl Management)');
        $grid->setTranslator($translator);

        $grid->setisAllowAdd(true);
        $grid->setisAllowEdit(true);
        $grid->setisAllowDelete(true);

        $grid->setDefaultItemsPerPage(parent::NUMBER_PAGINATOR_PER_PAGE);
        $grid->setDataSource($oPermissions, $dbAdapter);
        $grid->getDataSource()->setTable('permissions', 'pr');
        $grid->setIsCrud(true);

        $col = new Column\Select('id', 'pr');
        $col->setLabel('id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $col = new Column\Select('name', 'rs');
        $col->setLabel('Resource');
        $col->setFilterDefaultOperation(Zf2datatable\Filter::EQUAL);
        $col->setFilterSelectOptions($oResources->fetchGridPairs());
        $grid->addColumn($col);

        $col = new Column\Select('name', 'r');
        $col->setLabel('Role');
        $col->setReplaceValues ($oRoles->fetchPairsByName(true));
        $col->setTranslationEnabled ( true );
        $grid->addColumn($col);

        $col = new Column\Select('name', 'pr');
        $col->setLabel('Name');
        $grid->addColumn($col);

        $col = new Column\Select('privilege', 'pr');
        $col->setLabel('Privilege');
        $grid->addColumn($col);

        $col = new Column\Select('permission_allow', 'pr');
        $col->setLabel('Allow');
        $col->setReplaceValues ( array (
            '0' =>'NoValue',
            '2' => 'Denie',
            '1' => 'Allow'
        ) );
        $col->setTranslationEnabled ( true );
        $grid->addColumn($col);


        $col = new Column\Select('assert_class', 'pr');
        $col->setLabel('Assert Class');
        $grid->addColumn($col);



        $grid->setFrmMainCrud('frmusr');


        if($request->getQuery('op')=='u' || $request->getQuery('op')=='i') {
            $grid->removeFormElement('id');

            $grid->replaceFormElement('role_id', array(
                'type' => 'select',
                'name' => 'role_id',
                'attributes' => array(
                    'type' => 'select'
                ),
                'options' => array(
                    'label' => 'Role',
                    'value_options' => $oRoles->fetchPairs(true)
                )
            ), 970);


            $grid->replaceFormElement('resource_id', array(
                'type' => 'select',
                'name' => 'resource_id',
                'attributes' => array(
                    'type' => 'select'
                ),
                'options' => array(
                    'label' => 'Resource',
                    'value_options' => $oResources->fetchPairs(true)
                )
            ), 960);


            $grid->replaceFormElement('permission_allow', array(
                'type' => 'select',
                'name' => 'permission_allow',
                'attributes' => array(
                    'type' => 'select'
                ),
                'options' => array(
                    'label' => 'Allow',
                    'value_options' => array(1=>'Yes',2=>'No')
                )
            ), 950);

            //$grid->getFormElement('privilege')->setLabel($translator->translate('Privilege (Action)'));
        }


        $grid->render();
        return $grid->getResponse();


        return array();
    }

    /**
     * gestione lingue a database
     */

    public function languageAction()
    {
        $sm = $this->getServiceLocator();
        $translator = $this->getTranslator();;

        $grid = $sm->get('zf2datatablegrid');
        $grid->setTitle('Language');
        $grid->setDefaultItemsPerPage(5);
        $grid->setTranslator($translator);

        $doctrine2Service = $sm->get('doctrine2service');

        $em = $sm->get('doctrine.entitymanager.orm_default');

        $qb = $em->createQueryBuilder();
        $qb->select('l.id,l.name,l.code');
        $qb->from('CostAuthentication\Entity\Language', 'l');
        //$qb->where('l.abbrevation = \'En\'');

        $EntitiName = 'CostAuthentication\Entity\Language';
        //$grid->setDataSource($doctrine2Service->getLanguage($EntitiName,'l'));
        $grid->setDataSource($doctrine2Service->getLanguage($qb));
        $grid->getDataSource()->setEntity($EntitiName);
        $identity = $grid->getIdentyColumns();
        $grid->setIsCrud(true);

        $col = new Column\Select('id', 'l');
        $col->setLabel('Id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $col = new Column\Select('name', 'l');
        $col->setLabel('Language');
        $grid->addColumn($col);

        $col = new Column\Select('code', 'l');
        $col->setLabel('Code');

        $col = new Column\Select('default', 'l');
        $col->setLabel('Default');

        $grid->addColumn($col);

        //$grid->setFrmElementDefaultPriority(array('name'=>700,'abbrevation'=>800));
        $grid->setFrmMainCrud(new $EntitiName(), true);
        $grid->removeFormElement('id');
        $grid->removeFormElement('slug');
        $grid->removeFormElement('createdFromIp');
        $grid->removeFormElement('updated');

        $grid->render();
        return $grid->getResponse();
    }



    /**
     *
     */
    public function menuAction()
    {
        $sm = $this->getServiceLocator();
        $request = $this->getRequest();
        $translator = $this->getTranslator();;
        $dbAdapter = $this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');

        $moduleManager          = $sm->get('ModuleManager');
        $loadedModules          = array_combine(array_keys($moduleManager->getLoadedModules()), array_keys($moduleManager->getLoadedModules()));


        $oMenus = $this->getTableService()->get('menus');
        $oPermissions   = $this->getTableService()->get('permissions');
        $oResources     = $this->getTableService()->get('resources');

        $grid = $this->getServiceLocator()->get('zf2datatablegrid');
        $grid->setTitle('Menus');
        $grid->setTranslator($translator);
        $grid->setisAllowAdd(true);
        $grid->setisAllowEdit(true);
        $grid->setisAllowDelete(true);

        $grid->setDefaultItemsPerPage(parent::NUMBER_PAGINATOR_PER_PAGE);
        $grid->setDataSource($oMenus, $dbAdapter);
        $grid->getDataSource()->setTable('menus', 'm');
        $grid->setIsCrud(true);

        $col = new Column\Select('id', 'm');
        $col->setLabel('Id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $col = new Column\Select('parent_id', 'm');
        $col->setLabel('Parent');
        $grid->addColumn($col);

        $col = new Column\Select('name', 'm');
        $col->setLabel('Name');
        $grid->addColumn($col);

        $col = new Column\Select('label', 'm');
        $col->setLabel('Label');
        $grid->addColumn($col);

        $col = new Column\Select('route', 'm');
        $col->setLabel('Route');
        $grid->addColumn($col);

        $col = new Column\Select('controller', 'm');
        $col->setLabel('Controller');
        $grid->addColumn($col);

        $col = new Column\Select('action', 'm');
        $col->setLabel('Action');
        $grid->addColumn($col);

        $col = new Column\Select('resource', 'm');
        $col->setLabel('Resource');
        $grid->addColumn($col);

        $col = new Column\Select('privilege', 'pr');
        $col->setLabel('Permission');
        $grid->addColumn($col);

        $col = new Column\Select('module', 'm');
        $col->setLabel('Module');
        $grid->addColumn($col);

        /*$col = new Column\Select('Icon', 'm');
        $col->setLabel('Icon');
        $grid->addColumn($col);*/

        $col = new Column\Select('sort_order', 'm');
        $col->setLabel('Order');
        $grid->addColumn($col);

        $grid->setFrmMainCrud('frmusr');


        if($request->getQuery('op')=='u' || $request->getQuery('op')=='i') {
            $grid->removeFormElement('id');


            $grid->replaceFormElement('parent_id', array(
                'type' => 'select',
                'name' => 'parent_id',
                'attributes' => array(
                    'type' => 'select'
                ),
                'options' => array(
                    'label' => 'Parent',
                    'value_options' => $oMenus->fetchPairs(true)
                )
            ), 1000);


            $grid->replaceFormElement('resource', array(
                'type' => 'select',
                'name' => 'resource',
                'attributes' => array(
                    'type' => 'select'
                ),
                'options' => array(
                    'label' => 'Resource',
                    'value_options' => $oResources->fetchPairsMenu(true)
                )
            ), 940);

            $grid->replaceFormElement('privilege', array(
                'type' => 'select',
                'name' => 'privilege',
                'attributes' => array(
                    'type' => 'select'
                ),
                'options' => array(
                    'label' => 'Permission',
                    'value_options' => $oPermissions->fetchPairs(true)
                )
            ), 930);


            $grid->replaceFormElement('module', array(
                'type' => 'select',
                'name' => 'module',
                'attributes' => array(
                    'type' => 'select'
                ),
                'options' => array(
                    'label' => 'Module',
                    'value_options' => $loadedModules
                )
            ), 920);

            if ($request->getQuery('op') == 'i') {
                $grid->getFrmMainCrud()->get('sort_order')->setValue(1);
            }

        }


        $grid->render();

        return $grid->getResponse();
    }


    public function translationAction()
    {
        $sm = $this->getServiceLocator();
        $translator = $sm->get('translator');
        $request = $this->getRequest();
        $grid = $sm->get('zf2datatablegrid');
        $grid->setTitle('Users');
        $grid->setDefaultItemsPerPage(5);
        $grid->setTranslator($translator);

        $doctrine2Service = $sm->get('doctrine2service');
        $em = $sm->get('doctrine.entitymanager.orm_default');
        $EntitiName = Message::class;
        $qb = $em->createQueryBuilder();
        $qb->select('msg');
        $qb->from($EntitiName, 'msg');
        $grid->setDataSource($doctrine2Service->getSource($qb));
        $grid->getDataSource()->setEntity($EntitiName);

        $grid->setisAllowAdd(true);
        $grid->setisAllowEdit(true);
        $grid->setisAllowDelete(true);
        $grid->setisAllowView(false);

        $identity = $grid->getIdentyColumns();
        $grid->setIsCrud(true);
        $grid->setFrmMainCrud(new $EntitiName(), true);

        $col = new Column\Select('messageId', 'msg');
        $col->setLabel('Id');
        $col->setIdentity(true, false);
        $grid->addColumn($col);

        $col = new Column\Select('key', 'msg');
        $col->setLabel('Translatin Key');
        $grid->addColumn($col);

        $col = new Column\Select('textEn', 'msg');
        $col->setLabel('English');
        $grid->addColumn($col);

        $col = new Column\Select('textIt', 'msg');
        $col->setLabel('Italian');
        $grid->addColumn($col);

        $grid->render();
        return $grid->getResponse();
    }



}
