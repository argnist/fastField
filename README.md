## fastField

This plugin adds new tag [[#resource_id.field]] for MODX Revolution 2.2+.
It is a simple replacement of getResourceField. It supports grabbing:
[[#1.pagetitle]] resource fields
[[#1.tv.myTV]] resource TVs (processed)
[[#1.properties.articles.articlesPerPage]] resource properties

You don't need to install getResourceField for its work.

## Examples:
1) [[getResourceField? id=`1` &field=`pagetitle`]] is similarly to
   [[#1.pagetitle]]
2) [[getResourceField? id=`1` &field=`myTV` &isTV=`1` &processTV=`1`]] is similarly to
   [[#1.tv.myTV]]
3) [[#1.properties.articles.articlesPerPage]] or [[#1.property.articles.articlesPerPage]]
   or even [[#1.prop.articles.articlesPerPage]]  (isn't supported by getResourceField')
Last example makes sense for Articles extra. Namespace "core" is standard.

It supports output filters, for example [[#3.pagetitle:ucase:default=`[[*pagetitle:ucase]]`]]