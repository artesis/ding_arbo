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
* Adjust module to work with new SOAP client in [the parent module] [ding_voxb].
* Integrate the possibility of logging requests.
* Refactoring of CSS class names to not use camelCase.

[ding_voxb]: http://github.com/ding2/ding_voxb
