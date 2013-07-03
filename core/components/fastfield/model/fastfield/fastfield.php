<?php

class modResourceFieldTag extends modFieldTag {

    /**
     * Overrides modTag::__construct to set the Field Tag token
     * {@inheritdoc}
     */
    function __construct(modX & $modx) {
        parent :: __construct($modx);
        $this->setToken('#');
    }


    /* Strip MODX tags from global arrays for versions prior to 2.2.6-pl */
    protected function stripTags($content) {
        return str_replace(array('[[', ']]'), '', $content);
    }

    /**
     * Get the raw source content of the field.
     *
     * {@inheritdoc}
     */
    public function getContent(array $options = array()) {
        if (!$this->isCacheable() || !is_string($this->_content) || $this->_content === '') {
            if (isset($options['content']) && !empty($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $tag = explode('.', $this->get('name'));
                $tagLength = count($tag);
                if (is_numeric($tag[0])) {
                    $fastFieldCache = $this->modx->cacheManager->getCacheProvider('resource');
                    if ($this->isCacheable()) {
                        $cachedResource = $fastFieldCache->get('web/resources/fastfield/' . $tag[0]);
                        if ($cachedResource && isset($cachedResource[$this->get('name')])) {
                            $this->_content = $cachedResource[$this->get('name')];
                            return $this->_content;
                        }
                    }
                    
                    $resource = $this->modx->getObject('modResource', $tag[0]);

                    if ($resource)
                    {
                        if ($tagLength == 2) {
                            if ($tag[1] == 'content') {
                                $this->_content = $resource->getContent($options);
                            }
                            else {
                                $this->_content = $resource->get($tag[1]);

                                if(!array_key_exists($tag[1], $resource->_fields)) {
                                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'fastField: Unknown field `' . $tag[1] . '`');
                                }
                            }
                        }
                        else {
                            if (($tagLength == 3) && ($tag[1] == 'tv')) {
                                $this->_content = $resource->getTVValue($tag[2]);
                            }
                            elseif (($tagLength == 4) && in_array($tag[1], array('properties', 'property', 'prop'))) {
                                $this->_content = $resource->getProperty($tag[3], $tag[2]);
                                if (is_null($this->_content)) {
                                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'fastField: Unknown property `' . $tag[2] . '.' . $tag[3] . '`');
                                }
                            }
                            else {
                                $this->_content = '';
                            }
                        }
                        
                        if ($this->isCacheable()) {
                            $cachedResource[$this->get('name')] = $this->_content;
                            $fastFieldCache->set('web/resources/fastfield/' . $tag[0] , $cachedResource);
                        }    
                    }
                    else {
                        $this->_content = '';
                        $this->modx->log(modX::LOG_LEVEL_ERROR, 'fastField: Resource `' . $tag[0] . '` doesn\'t exist');
                    }

                }
                else {
                    $type = strtolower($tag[0]);
                    switch ($type) {
                        case 'post':
                            $this->_content = isset($_POST[$tag[1]]) ? $this->stripTags($_POST[$tag[1]]) : '';
                            break;
                        case 'get':
                            $this->_content = isset($_GET[$tag[1]]) ? $this->stripTags($_GET[$tag[1]]) : '';
                            break;
                        case 'request':
                            $this->_content = isset($_REQUEST[$tag[1]]) ? $this->stripTags($_REQUEST[$tag[1]]) : '';
                            break;
                        case 'server':
                            $this->_content = isset($_SERVER[$tag[1]]) ? $this->stripTags($_SERVER[$tag[1]]) : '';
                            break;
                        case 'files':
                            $this->_content = isset($_FILES[$tag[1]]) ? $this->stripTags($_FILES[$tag[1]]) : '';
                            break;
                        case 'cookie':
                            $this->_content = isset($_COOKIE[$tag[1]]) ? $this->stripTags($_COOKIE[$tag[1]]) : '';
                            break;
                        case 'session':
                            $this->_content = isset($_SESSION[$tag[1]]) ? $this->stripTags($_SESSION[$tag[1]]) : '';
                            break;
                    }
                }
            }
        }
        return $this->_content;
    }
}