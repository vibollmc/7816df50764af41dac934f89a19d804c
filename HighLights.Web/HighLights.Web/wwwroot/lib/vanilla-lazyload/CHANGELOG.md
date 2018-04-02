# CHANGELOG

## Version 10

#### 10.5.2

Added a security check on lazy elements' parents.

#### 10.5.1

Just a refactoring over previous version.

#### 10.5.0

Added node support by merging pull request #188, "node-support" by @klarstrup.

With these changes in place, simply importing vanilla-lazyload without using it won't crash Node by itself. This is important for isomorphic/universal/server rendered setups where the same code runs on both the server and the browser.

#### 10.4.2

Fixed a bug for which sometimes images wouldn't reveal on Chrome 65 (see issue #165).

#### 10.4.1

Updated `dist` folder.

#### 10.4.0

Added the `callback_enter` callback, which is called whenevery any element managed by LazyLoad enters the viewport, as requested in #159. Thanks to @alvarotrigo.

#### 10.3.6

Fixed tests to match dataset revert made in 10.3 and 8.2 (oopsy).

#### 10.3.5

Fixed a bug that could occur on older versions of IE when trying to access an image's parent node.

#### 10.3.4

Fixed a CustomEvent bug which occurred on IE when using async object initialization.

#### 10.3.3

Fixed `supportsClassList` test to work even when the `document` object isn't yet there. Thanks to @Spone and his pull request #145.

#### 10.3.1

Introduced a workaround for an issue of Microsoft Edge documented [here](https://developer.microsoft.com/en-us/microsoft-edge/platform/issues/12156111/)

#### 10.3.0

Restored support to IE9 and IE10, as requested in #118 and #132.

#### 10.2.0

To solve cases when you can't select the elements to load using a string, added the ability to pass a `NodeList` object...

- as a second parameter in the constructor, after the regular option object, e.g. `var ll = new Lazyload({}, myNodeList)`
- as a single parameter to the `update()` method, e.g. `ll.update(myNodeList)`

#### 10.1.0

To solve cases when you can't select the elements to load using a string, added the ability to pass a `NodeList` object to the `elements_selector` option, as suggested by @SassNinja in #130.

#### 10.0.1

Solved a problem with cdnjs.com: version 10.0.0 was pointing to 9.0.0.

#### 10.0.0

- Change in default options:
  - default for `data_src` is now `src` (was `original`)
  - default for `data_srcset` is now `srcset` (was `original-set`)

## Version 9

#### 9.0.1

- Restored tests using Jest
- Squashed a bug which didn't make images inside `picture` load correctly

#### 9.0.0 

LazyLoad is now _faster_ thanks to the [Intersection Observer API](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API). 

**IMPORTANT!** Browser support changed. Find more information in the [README](README.md) file.

## Version 8

#### 8.7.0

Added node support by merging pull request #188, "node-support" by @klarstrup.

With these changes in place, simply importing vanilla-lazyload without using it won't crash Node by itself. This is important for isomorphic/universal/server rendered setups where the same code runs on both the server and the browser.

#### 8.6.0

Added the callback_enter callback, which is called whenevery any element managed by LazyLoad enters the viewport, as requested in #159. Thanks to @alvarotrigo.

#### 8.5.2

Fixed a bug that could occur on older versions of IE when trying to access an image's parent node.

#### 8.5.1

Fixed a CustomEvent bug which occured on IE when using async object initialization.

#### 8.5.0

- Change in default options, in order to be aligned with version 10
  - default for `data_src` is now `src` (was `original`)
  - default for `data_srcset` is now `srcset` (was `original-set`)

#### 8.2.1

Fixed `supportsClassList` test to work even when the `document` object isn't yet there. Thanks to @Spone and his #145.

#### 8.2.0

Restored support to IE9 and IE10, as requested in #118 and #132.

#### 8.1.0

Updated from grunt to gulp (run with gulp scripts).

#### 8.0.3

Added quotes in background image URLs, as suggested in #114 (thanks to @vseva).

#### 8.0.2

Fixed a bug that affected performance.

#### 8.0.1

Fixed reference to old names in demo files.

#### 8.0.0

- The main file to include is now **`dist/lazyload.min.js`** as you would expect, and no longer `dist/lazyload.transpiled.min.js`. 
- The non-transpiled version is now named lazyload.es2015.js

## Version 7

#### 7.2.0

- Now using `element.dataset` to read data attributes
- New readme! New website!

Bug fixes:

- Fixed #87

**IMPORTANT!** Browser support changed. Find more information in the [README](README.md) file.

#### 7.1.0

- Refactored code now using more modules
- Saving ~0.5 kb of transpiled code going back from ES2015 `class` to function's `prototype`

#### 7.0.0

Source code converted to ES2015 modules, bundled with [rollup.js](https://rollupjs.org/) and transpiled with [babel](https://babeljs.io/).

## Version 6

#### 6.3.x

Added the class initial to all images (or iframes) inside the viewport at the moment of script initialization

#### 6.2.x

* Added the ability to load LazyLoad using an async script

#### 6.1.x

SEO improvements for lazily loaded images

#### 6.0.x

* Source code migrated to ES6 / ES2015
* Serving both minified ES6 version and minified transpiled-to-ES5 version

## Version 5

* Exposed private functions for test coverage
* Test coverage

## Version 4

* Lighter constructor
* Performance improvements
* Bugfix: null on background images
* Removed code for legacy browsers - now supporting IE10+

## Version 3

* Added support to the picture tag
* Removed the "show image only when fully loaded" mode
  * Dumped the show_while_loading and placeholder options

## Version 2

* Added support to lazily load iframes and background images
* Added error management callback and error class option
* Performance improvements

## Version 1

* Added support to the srcset attribute for images
* Added typescript typings + updated dist folder files
* Performance improvements
* Stable release of LazyLoad

---

_Want more detail? Take a look at the [release history](https://github.com/verlok/lazyload/releases) on GitHub_!
