--------------------
Plugin: fastField
--------------------
Author: Kireev Vitaly <kireevvit@gmail.com>
Thanks for inspiration to Sorokin Dmitry <dimsa@dimlight.ru> and Dimlight Ltd <http://dimlight.ru>

This plugin adds new tag [[#resource_id.field]] for MODX Revolution 2.2+.
It is a simple replacement of getResourceField. It supports grabbing:
[[#1.pagetitle]] resource fields
[[#1.tv.myTV]] resource TVs (processed)
[[#1.properties.articles.articlesPerPage]] resource properties
[[#POST.name]] value of $_POST['name'] (and other global arrays as with snippet getReqParam)

You don't need to install getResourceField and getReqParam for its work. Moreover this plugin is faster than usage of
those snippets because it replace modParser by own parser and new tag becomes native.

Examples:
1) [[getResourceField? id=`1` &field=`pagetitle`]] is similarly to
   [[#1.pagetitle]]
2) [[getResourceField? id=`1` &field=`myTV` &isTV=`1` &processTV=`1`]] is similarly to
   [[#1.tv.myTV]]
3) [[#1.properties.articles.articlesPerPage]] or [[#1.property.articles.articlesPerPage]]
   or even [[#1.prop.articles.articlesPerPage]]  (isn't supported by getResourceField')
   This example makes sense for Articles extra. Namespace "core" is standard.
4) [[!#get.name]] returns value of $_GET['name']. Supported global arrays: $_GET, $_POST, $_REQUEST, $_SERVER, $_FILES,
   $_COOKIE, $_SESSION. The type of array after # is case-insensitive. The name of array element is case-sensitive.
   You should use uncached tag [[!#get.name]] for cached resources.
   CAUTION: use :stripTags output filter to prevent XSS-attacks (eg. [[!#get.name:stripTags]])!

It supports output filters, for example [[#3.pagetitle:ucase:default=`[[*pagetitle:ucase]]`]]