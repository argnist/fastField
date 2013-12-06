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
                    /** @var xPDOCache $fastFieldCache */
                    $fastFieldCache = $this->modx->cacheManager->getCacheProvider('resource');
                    if ($this->isCacheable()) {
                        $cachedResource = $fastFieldCache->get('web/resources/fastfield/' . $tag[0]);
                        if ($cachedResource && isset($cachedResource[$this->get('name')])) {
                            $this->_content = $cachedResource[$this->get('name')];
                            return $this->_content;
                        }
                    }

                    $id = intval($tag[0]);
                    /** @var modResource $resource */
                    if ($id === $this->modx->resource->id) {
                        $resource = $this->modx->resource;
                    } else {
                        $resource = $this->modx->getObject('modResource', $id);
                    }


                    $result = null;
                    if ($resource) {
                        if ($tagLength > 1) {
                            if ($tag[1] === 'content') {
                                $result = $resource->getContent($options);
                            }
                            else {
                                $result = $resource->get($tag[1]);
                                for ($i = 2; $i < $tagLength; $i++) {
                                    if (isset($result[$tag[$i]])) {
                                        $result = $result[$tag[$i]];
                                    } else {
                                        break;
                                    }
                                }

                                if (is_null($result)) {
                                    if (($tagLength == 3) && ($tag[1] === 'tv')) {
                                        $result = $resource->getTVValue($tag[2]);
                                    } else {
                                        $result = $resource->getTVValue($tag[1]);
                                    }
                                }

                                if (is_null($result) && ($tagLength == 4) && in_array($tag[1], array('properties', 'property', 'prop'))) {
                                    $result = $resource->getProperty($tag[3], $tag[2]);
                                }
                            }
                        }

                        if (is_null($result)) {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, 'fastField: Unknown tag `' . $this->get('name') . '`');
                            $this->_content = '';
                        }
                        else {
                            $this->_content = $result;
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
                            $gArray = $_POST;
                            break;
                        case 'get':
                            $gArray = $_GET;
                            break;
                        case 'request':
                            $gArray = $_REQUEST;
                            break;
                        case 'server':
                            $gArray = $_SERVER;
                            break;
                        case 'files':
                            $gArray = $_FILES;
                            break;
                        case 'cookie':
                            $gArray = $_COOKIE;
                            break;
                        case 'session':
                            $gArray = $_SESSION;
                            break;
                        default:
                            $gArray = array();
                            break;
                    }
                    $gArray = $this->modx->sanitize($gArray, $this->modx->sanitizePatterns);
                    $this->_content = isset($gArray[$tag[1]]) ? $this->stripTags($gArray[$tag[1]]) : '';
                }
            }
        }
        return $this->_content;
    }
}