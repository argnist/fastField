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
                    $resource = $this->modx->getObject('modResource', $tag[0]);
                    if ($resource)
                    {
                        if ($tagLength == 2) {
                            if ($tag[1] == 'content') {
                                $this->_content = $resource->getContent($options);
                            }
                            else {
                                $this->_content = $resource->get($tag[1]);
                            }
                        }
                        else {
                            if (($tag[1] == 'tv') && ($tagLength == 3)) {
                                $this->_content = $resource->getTVValue($tag[2]);
                            }
                            elseif (in_array($tag[1], array('properties', 'property', 'prop')) && ($tagLength == 4)) {
                                $this->_content = $resource->getProperty($tag[3], $tag[2]);
                            }
                            else {
                                $this->_content = '';
                            }
                        }
                    }
                    else {
                        $this->_content = '';
                    }

                }
            }
        }
        return $this->_content;
    }
}