<?php
namespace CostAdmin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Zf2datatable\Datagrid as Datagrid;
use Zf2datatable\Column as Column;
use Zf2datatable\Column\Type as Type;
use Zf2datatable\Column\Style as Style;
use Laminas\Http\Response\Stream as ResponseStream;
use Laminas\Http\Headers;
use PHPExcel;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;

class BasicController extends AbstractActionController
{
    protected $tableService;
    protected $helperManager;
    protected $sessionAdminManager;
    protected $loggerAdminManager;
    const NUMBER_PAGINATOR_PER_PAGE = 25;
    
    
    protected $ServiceLocator;
    
    protected $translator;
    
    /**
     * @return the $translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param field_type $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return the $ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->ServiceLocator;
    }
    
    /**
     * @param \Laminas\ServiceManager\ServiceLocatorInterface $ServiceLocator
     */
    public function setServiceLocator($ServiceLocator)
    {
        $this->ServiceLocator = $ServiceLocator;
    }
    
    /**
     *
     * @return the $tableService
     */
    public function getTableService()
    {
        return $this->tableService = $this->getServiceLocator()->get('table-gateway');
    }
    
    
    /**
     *
     * @param field_type $tableService
     */
    public function setTableService($tableService)
    {
        $this->tableService = $this->getServiceLocator()->get('table-gateway');
    }
    
    
    /**
     * 
     * @return Ambigous <unknown, object, multitype:, restfbs_session_manager>
     */
    public function getSessionAdminContainer() {
    
        if($this->sessionAdminManager == null){
            $this->sessionAdminManager = $this->getServiceLocator()->get('session_admin_manager');
        }
        return $this->sessionAdminManager;
    }
    
    /**
     * 
     * @param unknown $sessionAdminManager
     * @return \AdminApplication\Controller\BasicController
     */
    public function setSessionAdminContainer($sessionAdminManager) {
        $this->sessionAdminManager = $sessionAdminManager;
        return $this;
    }
    
    
    /**
     * @return the $loggerRestfbs
     */
    public function getLoggerAdminManager() {
    
        if(null === $this->loggerAdminManager )
            $this->loggerAdminManager=$this->getServiceLocator()->get('logger_admin_manager');
        
        return $this->loggerAdminManager;
    }
    
    /**
     * @param field_type $loggerRestfbs
     */
    public function setLoggerAdminManager($LoggerAdminManager) {
        $this->loggerAdminManager = $LoggerAdminManager;
    }
    
    
    protected function getViewHelper(){
        return $this->helperManager = $this->getServiceLocator()->get('viewhelpermanager');
    }
    
    
    /*** clear cache system ***/
    protected function clearCacheByTag($tag){
        if($this->getServiceLocator()->has('var-cache')){
            $cacheSystem = $this->getServiceLocator()->get('var-cache');
            $cacheSystem->clearByTags($tag);
        }
    }
    
}

?>