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
 * [[#POST.name]] value of $_POST['name'] (and other global arrays as with snippet getReqParam)
 *
 * You don't need to install getResourceField and getReqParam for its work.
 *
 * Examples:
 * 1) [[getResourceField? id=`1` &field=`pagetitle`]] is similarly to [[#1.pagetitle]]
 * 2) [[getResourceField? id=`1` &field=`myTV` &isTV=`1` &processTV=`1`]] is similarly to [[#1.tv.myTV]]
 * 3) [[#1.properties.articles.articlesPerPage]] or [[#1.property.articles.articlesPerPage]]
 * or even [[#1.prop.articles.articlesPerPage]]  (isn't supported by getResourceField')
 * This example makes sense for Articles extra. Namespace "core" is standard.
 * 4) [[!#get.name]] returns value of $_GET['name']. Supported global arrays: $_GET, $_POST, $_REQUEST, $_SERVER, $_FILES,
 * $_COOKIE, $_SESSION. The type of array after # is case-insensitive. The name of array element is case-sensitive.
 * You should use uncached tag [[!#get.name]] for cached resources.
 * CAUTION: use :stripTags output filter to prevent XSS-attacks (eg. [[!#get.name:stripTags]])!
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
        if (get_class($modx->services['parser']) == 'modParser') {
            unset($modx->services['parser']);
            $modx->getService('parser', 'fastFieldParser', $modx->getOption('core_path') . 'components/fastfield/model/fastfield/');
        }
    break;
}