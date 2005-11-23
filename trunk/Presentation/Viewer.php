<?php

class Presentation_Viewer
{

    private $INCLUDE = '.:/home/.alegra/cs411/cs411.beoba.net/Smarty/';
    private $REQUIRE = '/home/.alegra/cs411/cs411.beoba.net/Smarty/Smarty.class.php';
    private $T_DIR = '/home/.alegra/cs411/cs411.beoba.net/ACLPS/UI/Templates';
    private $CO_DIR = '/home/.alegra/cs411/cs411.beoba.net/ACLPS/UI/Config';
    private $CA_DIR = '/home/.alegra/cs411/cs411.beoba.net/ACLPS/UI/Cache';
    private $COM_DIR = '/home/.alegra/cs411/cs411.beoba.net/ACLPS/UI/Compile';
    
    public function Display($aView)
    {
	ini_set('include_path', $this->INCLUDE);
	
	// load Smarty library
	
	require_once($this->REQUIRE);
	
	$smarty = new Smarty();
	
	$smarty->template_dir = $this->T_DIR;
	$smarty->config_dir = $this->CO_DIR;
	$smarty->cache_dir = $this->CA_DIR;
	$smarty->compile_dir = $this->COM_DIR;
							    
	$smarty->assign('headerImage', $aView->DisplayHeaderImage());
	$smarty->assign('footerImage', $aView->DisplayFooterImage());
	$smarty->assign('content', $aView->DisplayContent());
	$smarty->assign('sideContent', $aView->DisplaySideContent());
	$smarty->assign('theme', $aView->DisplayTheme());
	$smarty->display('index.tpl');
    }
}

?>
