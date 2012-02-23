<?php

class Guard extends sfGuardSecurityUser
{
	public $usuario;
	public $permissoes;
	public $opcoes;
	
    public function canAccess($module, $action)
    {
        $credentials = $this->getCredentialByModule($module, $action);

        if ($credentials === null)
            return true;

        return $this->hasCredential($credentials);
    }
    
    public function getCredentialByModule($module, $node)
    {
        $path = sfContext::getInstance()->getConfigCache()->checkConfig(sfConfig::get('sf_app_module_dir').DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'security.yml', true);
        
        if (!file_exists($path))
            return null;
        
        require($path);

        if (isset($this->security[$node]['credentials']))
        {
            $credentials = $this->security[$node]['credentials'];
        }
        else if (isset($this->security['all']['credentials']))
        {
            $credentials = $this->security['all']['credentials'];
        }
        else
        {
            $credentials = null;
        }

        return $credentials;
    }
    
    public function credentialsMenu($module, $node, $return = 'yml', $inline = 0)
    {
        $credentials = $this->getCredentialByModule($module, $node);
        
        return (!is_null($credentials) && !empty($credentials)) ? sfYaml::dump($credentials, $inline) : "[]";
    }
}
