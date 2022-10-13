<?php

declare(strict_types=1);

/**
 * @var \MODX\Revolution\modX $this
 *
 * @see \MODX\Revolution\modX::_initNamespaces()
 */
if ($this->getOption('modParser.class') !== 'fastFieldParser') {
    return;
}

require_once __DIR__ . '/model/fastfield/fastfieldparser.class.php';
