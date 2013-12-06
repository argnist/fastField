<?php
    require_once MODX_CORE_PATH . '/model/modx/modparser.class.php';

    class fastFieldParser extends modParser {

        public function processTag($tag, $processUncacheable = true) {
            // We need only # placeholders
            if (($tag[1][0] !== '#') && strpos($tag[1], '!#') === false) {
			    return parent::processTag($tag, $processUncacheable);
		    }
            
            $this->_processingTag = true;
            $element= null;
            $elementOutput= null;

            $outerTag= $tag[0];
            $innerTag= $tag[1];

            /* collect any nested element tags in the innerTag and process them */
            $this->processElementTags($outerTag, $innerTag, $processUncacheable);
            $this->_processingTag = true;
            $outerTag= '[[' . $innerTag . ']]';

            $tagParts= xPDO :: escSplit('?', $innerTag, '`', 2);
            $tagName= trim($tagParts[0]);
            $tagPropString= null;
            if (isset ($tagParts[1])) {
                $tagPropString= trim($tagParts[1]);
            }
            $token= substr($tagName, 0, 1);
            $tokenOffset= 0;
            $cacheable= true;
            if ($token === '!') {
                if (!$processUncacheable) {
                    $this->_processingTag = false;
                    return $outerTag;
                }
                $cacheable= false;
                $tokenOffset++;
                $token= substr($tagName, $tokenOffset, 1);
            }
            if ($cacheable && $token !== '+') {
                $elementOutput= $this->loadFromCache($outerTag);
            }
            if ($elementOutput === null) {
                switch ($token) {
                    case '#':
                        include_once $this->modx->getOption('core_path') . 'components/fastfield/model/fastfield/fastfield.php';
                        $tagName= substr($tagName, 1 + $tokenOffset);
                        $element= new modResourceFieldTag($this->modx);
                        $element->set('name', $tagName);
                        $element->setTag($outerTag);
                        $element->setCacheable($cacheable);
                        $elementOutput = $element->process($tagPropString);
                        break;
                }
            }
            if (($elementOutput === null || $elementOutput === false) && $outerTag !== $tag[0]) {
                $elementOutput = $outerTag;
            }
            if ($this->modx->getDebug() === true) {
                $this->modx->log(xPDO::LOG_LEVEL_DEBUG, "Processing {$outerTag} as {$innerTag} using tagname {$tagName}:\n" . print_r($elementOutput, 1) . "\n\n");
            }
            $this->_processingTag = false;
            return $elementOutput;
        }
    }
