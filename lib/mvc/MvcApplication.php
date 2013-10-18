<?php

require_once ('lib/classes/Application.php');

/**
 * MVC Application / FrontController
 *
 * @author TLamy
 */
class MvcApplication extends Application {

	protected $masterController;

	/**
	 * @var Router_Abstract
	 */
	protected $router;
	protected $request;
	protected $response;

	protected $controller;

	protected $view;

	/**
	 * @var Layout_Twig
	 */
	protected $layout;

	/**
	 *
	 * @param string $basePath
	 * @param string $configPath
	 */
	public function __construct($basePath, $configPath = "config.ini")
	{
		parent::__construct ( $basePath, $configPath = "config.ini" );
		$this->logger = new Logger(new Logger_Logfile("/tmp/auctionhouse.log"), LoggerInterface::DEBUG);
		$this->logger->debug("Logger initialized");
		$this->request = Request_Abstract::factory($_SERVER);
		switch( $this->getConfig('router'))
		{
			default: $this->router = new Router_Urlvar();
		}
		$this->view = new View_Twig($this, $this->getConfig("template_path"));
		$this->layout = new Layout_Twig($this, $this->getConfig("layout_path"));
		$this->layout->setTemplate("layout.html");
		$this->logger->debug("MvcApplication initialized");
	}

	public function run()
	{
		$this->logger->debug("run");
		$route = $this->router->route( $this->request);
		$result = $this->runRoute($route);
		if(!$this->controller->isLayoutEnabled()) {
			return $result;
		}
		$this->layout->View = $result;
		return $this->layout->render();
	}

	/**
	 * Exctract Controller and Action from Route and run it. Returns rendered view string
	 * @param Router_Route $route
	 * @throws Exception_404
	 * @return string
	 */
	protected function runRoute( Router_Route $route)
	{
		try {
			$this->controller = Controller_Abstract::factory($route, $this->layout, $this->view, $this->request);
			$actionMethod = Controller_Abstract::getActionMethod($route);
			if( !method_exists($this->controller, $actionMethod)) {
				throw new Exception_404("Action not found");
			}

		} catch( Exception_404 $ex) {
			return $this->fileNotFoundPage($ex);
		} catch( Exception $ex) {
			return $this->exceptionPage($ex);
		}
		try {
			Application::getInstance()->getLogger()->info("Running ".$route);
			ob_start();
			$actionOutput = $this->controller->$actionMethod();
			$actionOutput2 = ob_get_clean();
			if( $this->controller->isViewEnabled()) {
				$actionOutput = $this->view->render();
			} else {
			}
		} catch(Exception $ex) {
			Application::getInstance()->getLogger()->info("Exception in ".__METHOD__);
			return $this->exceptionPage( $ex);
		}
		return $actionOutput;
	}

	protected function fileNotFoundPage(Exception $ex)
	{
		$this->view->Exception = $ex;
		$this->view->setTemplate("404.tpl");
		return $this->render(null);
	}

	protected function exceptionPage(Exception $ex)
	{
		$this->view->Exception = $ex;
		ob_start();
		imagecreatefromstring();
		phpinfo();
		$this->view->phpinfo = ob_get_clean();
		$this->view->setTemplate("exception.tpl");
		return $this->render(null);
	}

	protected function render($actionOutput)
	{
		if(null !== $actionOutput) {
			echo $actionOutput;
		}
		$viewResult = $this->view->render();
		$this->layout->View = $viewResult;
		return $this->layout->render();
	}
}

?>