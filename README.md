ARBO module
==========
This module provides capability for the user to video review an item in Ding! through the VoxB service.

This module is dependent of the VoxB module ([ding_voxb] [ding_voxb]).

Installation
-----------------
1. Enable the module.

Improvements
----------------------------------
* Function calls, drupal_get_form()s, drupal_render()s, etc. should be moved to preprocess functions in order to keep the templates clean.
* Use the built-in theme_image function to render image tags.
* [Use drupal_set_session instead of $_SESSION] [drupal_set_session]
* Support adding several reviews with [updateMyRequest] [updateMyRequest]


[ding_voxb]: http://github.com/inleadmedia/ding_voxb
[drupal_set_session]: http://drupal.org/node/224333#drupal_set_session
[updateMyRequest]: https://voxb.addi.dk/1.0/doc/voxb.html#Link51