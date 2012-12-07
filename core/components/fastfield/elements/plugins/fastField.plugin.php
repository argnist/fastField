<?php

/**
 * fastField
 *
 * Copyright 2012 by Kireev Vitaly <kireevvit@gmail.com>
 *
 * This plugin adds new tag [[#resource_id.field]] for MODX Revolution 2.2+.
 * It is a simple replacement of getResourceField. It supports grabbing:
 * [[#1.pagetitle]] resource fields
 * [[#1.tv.myTV]] resource TVs (processed)
 * [[#1.properties.articles.articlesPerPage]] resource properties
 *
 * You don't need to install getResourceField for its work.
 *
 * Examples:
 * 1) [[getResourceField? id=`1` &field=`pagetitle`]] is similarly to
 *    [[#1.pagetitle]]
 * 2) [[getResourceField? id=`1` &field=`myTV` &isTV=`1` &processTV=`1`]] is similarly to
 *    [[#1.tv.myTV]]
 * 3) [[#1.properties.articles.articlesPerPage]] or [[#1.property.articles.articlesPerPage]]
 *    or even [[#1.prop.articles.articlesPerPage]]  (isn't supported by getResourceField')
 * Last example makes sense for Articles extra. Namespace "core" is standard.
 *
 * It is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * It is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * it; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package fastfield
 */

switch ($modx->event->name) {
    case 'OnParseDocument':
        $content = $modx->documentOutput;
        $tags= array ();
        if ($collected= $modx->parser->collectElementTags($content, $tags, '[[', ']]', array('#')))
        {
            foreach ($tags as $tag) {
                $token = substr($tag[1], 0, 1);
                if ($token == '#') {
                    include_once $modx->getOption('core_path') . 'components/fastfield/model/fastfield/fastfield.php';

                    $tagParts= xPDO :: escSplit('?', $tag[1], '`', 2);
                    $tagName= substr(trim($tagParts[0]), 1);
                    $tagPropString= null;
                    if (isset ($tagParts[1])) {
                        $tagPropString= trim($tagParts[1]);
                    }

                    $element= new modResourceFieldTag($modx);
                    $element->set('name', $tagName);
                    $element->setTag('');
                    $element->setCacheable(false);
                    $modx->documentOutput= $element->process($tagPropString);
                }
            }
        }
        break;
}