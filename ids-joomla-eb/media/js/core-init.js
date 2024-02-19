(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define("core", [], factory);
	else if(typeof exports === 'object')
		exports["core"] = factory();
	else
		root["core"] = factory();
})(self, () => {
return /******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./node_modules/@popperjs/core/lib/popper.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  fi: () => (/* binding */ popper_createPopper)
});

// UNUSED EXPORTS: applyStyles, arrow, computeStyles, createPopperLite, defaultModifiers, detectOverflow, eventListeners, flip, hide, offset, popperGenerator, popperOffsets, preventOverflow

;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getWindow.js
function getWindow(node) {
  if (node == null) {
    return window;
  }

  if (node.toString() !== '[object Window]') {
    var ownerDocument = node.ownerDocument;
    return ownerDocument ? ownerDocument.defaultView || window : window;
  }

  return node;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/instanceOf.js


function isElement(node) {
  var OwnElement = getWindow(node).Element;
  return node instanceof OwnElement || node instanceof Element;
}

function isHTMLElement(node) {
  var OwnElement = getWindow(node).HTMLElement;
  return node instanceof OwnElement || node instanceof HTMLElement;
}

function isShadowRoot(node) {
  // IE 11 has no ShadowRoot
  if (typeof ShadowRoot === 'undefined') {
    return false;
  }

  var OwnElement = getWindow(node).ShadowRoot;
  return node instanceof OwnElement || node instanceof ShadowRoot;
}


;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/math.js
var math_max = Math.max;
var math_min = Math.min;
var round = Math.round;
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/userAgent.js
function getUAString() {
  var uaData = navigator.userAgentData;

  if (uaData != null && uaData.brands && Array.isArray(uaData.brands)) {
    return uaData.brands.map(function (item) {
      return item.brand + "/" + item.version;
    }).join(' ');
  }

  return navigator.userAgent;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/isLayoutViewport.js

function isLayoutViewport() {
  return !/^((?!chrome|android).)*safari/i.test(getUAString());
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getBoundingClientRect.js




function getBoundingClientRect(element, includeScale, isFixedStrategy) {
  if (includeScale === void 0) {
    includeScale = false;
  }

  if (isFixedStrategy === void 0) {
    isFixedStrategy = false;
  }

  var clientRect = element.getBoundingClientRect();
  var scaleX = 1;
  var scaleY = 1;

  if (includeScale && isHTMLElement(element)) {
    scaleX = element.offsetWidth > 0 ? round(clientRect.width) / element.offsetWidth || 1 : 1;
    scaleY = element.offsetHeight > 0 ? round(clientRect.height) / element.offsetHeight || 1 : 1;
  }

  var _ref = isElement(element) ? getWindow(element) : window,
      visualViewport = _ref.visualViewport;

  var addVisualOffsets = !isLayoutViewport() && isFixedStrategy;
  var x = (clientRect.left + (addVisualOffsets && visualViewport ? visualViewport.offsetLeft : 0)) / scaleX;
  var y = (clientRect.top + (addVisualOffsets && visualViewport ? visualViewport.offsetTop : 0)) / scaleY;
  var width = clientRect.width / scaleX;
  var height = clientRect.height / scaleY;
  return {
    width: width,
    height: height,
    top: y,
    right: x + width,
    bottom: y + height,
    left: x,
    x: x,
    y: y
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getWindowScroll.js

function getWindowScroll(node) {
  var win = getWindow(node);
  var scrollLeft = win.pageXOffset;
  var scrollTop = win.pageYOffset;
  return {
    scrollLeft: scrollLeft,
    scrollTop: scrollTop
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getHTMLElementScroll.js
function getHTMLElementScroll(element) {
  return {
    scrollLeft: element.scrollLeft,
    scrollTop: element.scrollTop
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getNodeScroll.js




function getNodeScroll(node) {
  if (node === getWindow(node) || !isHTMLElement(node)) {
    return getWindowScroll(node);
  } else {
    return getHTMLElementScroll(node);
  }
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getNodeName.js
function getNodeName(element) {
  return element ? (element.nodeName || '').toLowerCase() : null;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getDocumentElement.js

function getDocumentElement(element) {
  // $FlowFixMe[incompatible-return]: assume body is always available
  return ((isElement(element) ? element.ownerDocument : // $FlowFixMe[prop-missing]
  element.document) || window.document).documentElement;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getWindowScrollBarX.js



function getWindowScrollBarX(element) {
  // If <html> has a CSS width greater than the viewport, then this will be
  // incorrect for RTL.
  // Popper 1 is broken in this case and never had a bug report so let's assume
  // it's not an issue. I don't think anyone ever specifies width on <html>
  // anyway.
  // Browsers where the left scrollbar doesn't cause an issue report `0` for
  // this (e.g. Edge 2019, IE11, Safari)
  return getBoundingClientRect(getDocumentElement(element)).left + getWindowScroll(element).scrollLeft;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getComputedStyle.js

function getComputedStyle(element) {
  return getWindow(element).getComputedStyle(element);
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/isScrollParent.js

function isScrollParent(element) {
  // Firefox wants us to check `-x` and `-y` variations as well
  var _getComputedStyle = getComputedStyle(element),
      overflow = _getComputedStyle.overflow,
      overflowX = _getComputedStyle.overflowX,
      overflowY = _getComputedStyle.overflowY;

  return /auto|scroll|overlay|hidden/.test(overflow + overflowY + overflowX);
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getCompositeRect.js









function isElementScaled(element) {
  var rect = element.getBoundingClientRect();
  var scaleX = round(rect.width) / element.offsetWidth || 1;
  var scaleY = round(rect.height) / element.offsetHeight || 1;
  return scaleX !== 1 || scaleY !== 1;
} // Returns the composite rect of an element relative to its offsetParent.
// Composite means it takes into account transforms as well as layout.


function getCompositeRect(elementOrVirtualElement, offsetParent, isFixed) {
  if (isFixed === void 0) {
    isFixed = false;
  }

  var isOffsetParentAnElement = isHTMLElement(offsetParent);
  var offsetParentIsScaled = isHTMLElement(offsetParent) && isElementScaled(offsetParent);
  var documentElement = getDocumentElement(offsetParent);
  var rect = getBoundingClientRect(elementOrVirtualElement, offsetParentIsScaled, isFixed);
  var scroll = {
    scrollLeft: 0,
    scrollTop: 0
  };
  var offsets = {
    x: 0,
    y: 0
  };

  if (isOffsetParentAnElement || !isOffsetParentAnElement && !isFixed) {
    if (getNodeName(offsetParent) !== 'body' || // https://github.com/popperjs/popper-core/issues/1078
    isScrollParent(documentElement)) {
      scroll = getNodeScroll(offsetParent);
    }

    if (isHTMLElement(offsetParent)) {
      offsets = getBoundingClientRect(offsetParent, true);
      offsets.x += offsetParent.clientLeft;
      offsets.y += offsetParent.clientTop;
    } else if (documentElement) {
      offsets.x = getWindowScrollBarX(documentElement);
    }
  }

  return {
    x: rect.left + scroll.scrollLeft - offsets.x,
    y: rect.top + scroll.scrollTop - offsets.y,
    width: rect.width,
    height: rect.height
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getLayoutRect.js
 // Returns the layout rect of an element relative to its offsetParent. Layout
// means it doesn't take into account transforms.

function getLayoutRect(element) {
  var clientRect = getBoundingClientRect(element); // Use the clientRect sizes if it's not been transformed.
  // Fixes https://github.com/popperjs/popper-core/issues/1223

  var width = element.offsetWidth;
  var height = element.offsetHeight;

  if (Math.abs(clientRect.width - width) <= 1) {
    width = clientRect.width;
  }

  if (Math.abs(clientRect.height - height) <= 1) {
    height = clientRect.height;
  }

  return {
    x: element.offsetLeft,
    y: element.offsetTop,
    width: width,
    height: height
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getParentNode.js



function getParentNode(element) {
  if (getNodeName(element) === 'html') {
    return element;
  }

  return (// this is a quicker (but less type safe) way to save quite some bytes from the bundle
    // $FlowFixMe[incompatible-return]
    // $FlowFixMe[prop-missing]
    element.assignedSlot || // step into the shadow DOM of the parent of a slotted node
    element.parentNode || ( // DOM Element detected
    isShadowRoot(element) ? element.host : null) || // ShadowRoot detected
    // $FlowFixMe[incompatible-call]: HTMLElement is a Node
    getDocumentElement(element) // fallback

  );
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getScrollParent.js




function getScrollParent(node) {
  if (['html', 'body', '#document'].indexOf(getNodeName(node)) >= 0) {
    // $FlowFixMe[incompatible-return]: assume body is always available
    return node.ownerDocument.body;
  }

  if (isHTMLElement(node) && isScrollParent(node)) {
    return node;
  }

  return getScrollParent(getParentNode(node));
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/listScrollParents.js




/*
given a DOM element, return the list of all scroll parents, up the list of ancesors
until we get to the top window object. This list is what we attach scroll listeners
to, because if any of these parent elements scroll, we'll need to re-calculate the
reference element's position.
*/

function listScrollParents(element, list) {
  var _element$ownerDocumen;

  if (list === void 0) {
    list = [];
  }

  var scrollParent = getScrollParent(element);
  var isBody = scrollParent === ((_element$ownerDocumen = element.ownerDocument) == null ? void 0 : _element$ownerDocumen.body);
  var win = getWindow(scrollParent);
  var target = isBody ? [win].concat(win.visualViewport || [], isScrollParent(scrollParent) ? scrollParent : []) : scrollParent;
  var updatedList = list.concat(target);
  return isBody ? updatedList : // $FlowFixMe[incompatible-call]: isBody tells us target will be an HTMLElement here
  updatedList.concat(listScrollParents(getParentNode(target)));
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/isTableElement.js

function isTableElement(element) {
  return ['table', 'td', 'th'].indexOf(getNodeName(element)) >= 0;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getOffsetParent.js








function getTrueOffsetParent(element) {
  if (!isHTMLElement(element) || // https://github.com/popperjs/popper-core/issues/837
  getComputedStyle(element).position === 'fixed') {
    return null;
  }

  return element.offsetParent;
} // `.offsetParent` reports `null` for fixed elements, while absolute elements
// return the containing block


function getContainingBlock(element) {
  var isFirefox = /firefox/i.test(getUAString());
  var isIE = /Trident/i.test(getUAString());

  if (isIE && isHTMLElement(element)) {
    // In IE 9, 10 and 11 fixed elements containing block is always established by the viewport
    var elementCss = getComputedStyle(element);

    if (elementCss.position === 'fixed') {
      return null;
    }
  }

  var currentNode = getParentNode(element);

  if (isShadowRoot(currentNode)) {
    currentNode = currentNode.host;
  }

  while (isHTMLElement(currentNode) && ['html', 'body'].indexOf(getNodeName(currentNode)) < 0) {
    var css = getComputedStyle(currentNode); // This is non-exhaustive but covers the most common CSS properties that
    // create a containing block.
    // https://developer.mozilla.org/en-US/docs/Web/CSS/Containing_block#identifying_the_containing_block

    if (css.transform !== 'none' || css.perspective !== 'none' || css.contain === 'paint' || ['transform', 'perspective'].indexOf(css.willChange) !== -1 || isFirefox && css.willChange === 'filter' || isFirefox && css.filter && css.filter !== 'none') {
      return currentNode;
    } else {
      currentNode = currentNode.parentNode;
    }
  }

  return null;
} // Gets the closest ancestor positioned element. Handles some edge cases,
// such as table ancestors and cross browser bugs.


function getOffsetParent(element) {
  var window = getWindow(element);
  var offsetParent = getTrueOffsetParent(element);

  while (offsetParent && isTableElement(offsetParent) && getComputedStyle(offsetParent).position === 'static') {
    offsetParent = getTrueOffsetParent(offsetParent);
  }

  if (offsetParent && (getNodeName(offsetParent) === 'html' || getNodeName(offsetParent) === 'body' && getComputedStyle(offsetParent).position === 'static')) {
    return window;
  }

  return offsetParent || getContainingBlock(element) || window;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/enums.js
var enums_top = 'top';
var bottom = 'bottom';
var right = 'right';
var left = 'left';
var auto = 'auto';
var basePlacements = [enums_top, bottom, right, left];
var start = 'start';
var end = 'end';
var clippingParents = 'clippingParents';
var viewport = 'viewport';
var popper = 'popper';
var reference = 'reference';
var variationPlacements = /*#__PURE__*/basePlacements.reduce(function (acc, placement) {
  return acc.concat([placement + "-" + start, placement + "-" + end]);
}, []);
var enums_placements = /*#__PURE__*/[].concat(basePlacements, [auto]).reduce(function (acc, placement) {
  return acc.concat([placement, placement + "-" + start, placement + "-" + end]);
}, []); // modifiers that need to read the DOM

var beforeRead = 'beforeRead';
var read = 'read';
var afterRead = 'afterRead'; // pure-logic modifiers

var beforeMain = 'beforeMain';
var main = 'main';
var afterMain = 'afterMain'; // modifier with the purpose to write to the DOM (or write into a framework state)

var beforeWrite = 'beforeWrite';
var write = 'write';
var afterWrite = 'afterWrite';
var modifierPhases = [beforeRead, read, afterRead, beforeMain, main, afterMain, beforeWrite, write, afterWrite];
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/orderModifiers.js
 // source: https://stackoverflow.com/questions/49875255

function order(modifiers) {
  var map = new Map();
  var visited = new Set();
  var result = [];
  modifiers.forEach(function (modifier) {
    map.set(modifier.name, modifier);
  }); // On visiting object, check for its dependencies and visit them recursively

  function sort(modifier) {
    visited.add(modifier.name);
    var requires = [].concat(modifier.requires || [], modifier.requiresIfExists || []);
    requires.forEach(function (dep) {
      if (!visited.has(dep)) {
        var depModifier = map.get(dep);

        if (depModifier) {
          sort(depModifier);
        }
      }
    });
    result.push(modifier);
  }

  modifiers.forEach(function (modifier) {
    if (!visited.has(modifier.name)) {
      // check for visited object
      sort(modifier);
    }
  });
  return result;
}

function orderModifiers(modifiers) {
  // order based on dependencies
  var orderedModifiers = order(modifiers); // order based on phase

  return modifierPhases.reduce(function (acc, phase) {
    return acc.concat(orderedModifiers.filter(function (modifier) {
      return modifier.phase === phase;
    }));
  }, []);
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/debounce.js
function debounce(fn) {
  var pending;
  return function () {
    if (!pending) {
      pending = new Promise(function (resolve) {
        Promise.resolve().then(function () {
          pending = undefined;
          resolve(fn());
        });
      });
    }

    return pending;
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/mergeByName.js
function mergeByName(modifiers) {
  var merged = modifiers.reduce(function (merged, current) {
    var existing = merged[current.name];
    merged[current.name] = existing ? Object.assign({}, existing, current, {
      options: Object.assign({}, existing.options, current.options),
      data: Object.assign({}, existing.data, current.data)
    }) : current;
    return merged;
  }, {}); // IE11 does not support Object.values

  return Object.keys(merged).map(function (key) {
    return merged[key];
  });
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/createPopper.js









var DEFAULT_OPTIONS = {
  placement: 'bottom',
  modifiers: [],
  strategy: 'absolute'
};

function areValidElements() {
  for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
    args[_key] = arguments[_key];
  }

  return !args.some(function (element) {
    return !(element && typeof element.getBoundingClientRect === 'function');
  });
}

function popperGenerator(generatorOptions) {
  if (generatorOptions === void 0) {
    generatorOptions = {};
  }

  var _generatorOptions = generatorOptions,
      _generatorOptions$def = _generatorOptions.defaultModifiers,
      defaultModifiers = _generatorOptions$def === void 0 ? [] : _generatorOptions$def,
      _generatorOptions$def2 = _generatorOptions.defaultOptions,
      defaultOptions = _generatorOptions$def2 === void 0 ? DEFAULT_OPTIONS : _generatorOptions$def2;
  return function createPopper(reference, popper, options) {
    if (options === void 0) {
      options = defaultOptions;
    }

    var state = {
      placement: 'bottom',
      orderedModifiers: [],
      options: Object.assign({}, DEFAULT_OPTIONS, defaultOptions),
      modifiersData: {},
      elements: {
        reference: reference,
        popper: popper
      },
      attributes: {},
      styles: {}
    };
    var effectCleanupFns = [];
    var isDestroyed = false;
    var instance = {
      state: state,
      setOptions: function setOptions(setOptionsAction) {
        var options = typeof setOptionsAction === 'function' ? setOptionsAction(state.options) : setOptionsAction;
        cleanupModifierEffects();
        state.options = Object.assign({}, defaultOptions, state.options, options);
        state.scrollParents = {
          reference: isElement(reference) ? listScrollParents(reference) : reference.contextElement ? listScrollParents(reference.contextElement) : [],
          popper: listScrollParents(popper)
        }; // Orders the modifiers based on their dependencies and `phase`
        // properties

        var orderedModifiers = orderModifiers(mergeByName([].concat(defaultModifiers, state.options.modifiers))); // Strip out disabled modifiers

        state.orderedModifiers = orderedModifiers.filter(function (m) {
          return m.enabled;
        });
        runModifierEffects();
        return instance.update();
      },
      // Sync update – it will always be executed, even if not necessary. This
      // is useful for low frequency updates where sync behavior simplifies the
      // logic.
      // For high frequency updates (e.g. `resize` and `scroll` events), always
      // prefer the async Popper#update method
      forceUpdate: function forceUpdate() {
        if (isDestroyed) {
          return;
        }

        var _state$elements = state.elements,
            reference = _state$elements.reference,
            popper = _state$elements.popper; // Don't proceed if `reference` or `popper` are not valid elements
        // anymore

        if (!areValidElements(reference, popper)) {
          return;
        } // Store the reference and popper rects to be read by modifiers


        state.rects = {
          reference: getCompositeRect(reference, getOffsetParent(popper), state.options.strategy === 'fixed'),
          popper: getLayoutRect(popper)
        }; // Modifiers have the ability to reset the current update cycle. The
        // most common use case for this is the `flip` modifier changing the
        // placement, which then needs to re-run all the modifiers, because the
        // logic was previously ran for the previous placement and is therefore
        // stale/incorrect

        state.reset = false;
        state.placement = state.options.placement; // On each update cycle, the `modifiersData` property for each modifier
        // is filled with the initial data specified by the modifier. This means
        // it doesn't persist and is fresh on each update.
        // To ensure persistent data, use `${name}#persistent`

        state.orderedModifiers.forEach(function (modifier) {
          return state.modifiersData[modifier.name] = Object.assign({}, modifier.data);
        });

        for (var index = 0; index < state.orderedModifiers.length; index++) {
          if (state.reset === true) {
            state.reset = false;
            index = -1;
            continue;
          }

          var _state$orderedModifie = state.orderedModifiers[index],
              fn = _state$orderedModifie.fn,
              _state$orderedModifie2 = _state$orderedModifie.options,
              _options = _state$orderedModifie2 === void 0 ? {} : _state$orderedModifie2,
              name = _state$orderedModifie.name;

          if (typeof fn === 'function') {
            state = fn({
              state: state,
              options: _options,
              name: name,
              instance: instance
            }) || state;
          }
        }
      },
      // Async and optimistically optimized update – it will not be executed if
      // not necessary (debounced to run at most once-per-tick)
      update: debounce(function () {
        return new Promise(function (resolve) {
          instance.forceUpdate();
          resolve(state);
        });
      }),
      destroy: function destroy() {
        cleanupModifierEffects();
        isDestroyed = true;
      }
    };

    if (!areValidElements(reference, popper)) {
      return instance;
    }

    instance.setOptions(options).then(function (state) {
      if (!isDestroyed && options.onFirstUpdate) {
        options.onFirstUpdate(state);
      }
    }); // Modifiers have the ability to execute arbitrary code before the first
    // update cycle runs. They will be executed in the same order as the update
    // cycle. This is useful when a modifier adds some persistent data that
    // other modifiers need to use, but the modifier is run after the dependent
    // one.

    function runModifierEffects() {
      state.orderedModifiers.forEach(function (_ref) {
        var name = _ref.name,
            _ref$options = _ref.options,
            options = _ref$options === void 0 ? {} : _ref$options,
            effect = _ref.effect;

        if (typeof effect === 'function') {
          var cleanupFn = effect({
            state: state,
            name: name,
            instance: instance,
            options: options
          });

          var noopFn = function noopFn() {};

          effectCleanupFns.push(cleanupFn || noopFn);
        }
      });
    }

    function cleanupModifierEffects() {
      effectCleanupFns.forEach(function (fn) {
        return fn();
      });
      effectCleanupFns = [];
    }

    return instance;
  };
}
var createPopper = /*#__PURE__*/(/* unused pure expression or super */ null && (popperGenerator())); // eslint-disable-next-line import/no-unused-modules


;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/eventListeners.js
 // eslint-disable-next-line import/no-unused-modules

var passive = {
  passive: true
};

function effect(_ref) {
  var state = _ref.state,
      instance = _ref.instance,
      options = _ref.options;
  var _options$scroll = options.scroll,
      scroll = _options$scroll === void 0 ? true : _options$scroll,
      _options$resize = options.resize,
      resize = _options$resize === void 0 ? true : _options$resize;
  var window = getWindow(state.elements.popper);
  var scrollParents = [].concat(state.scrollParents.reference, state.scrollParents.popper);

  if (scroll) {
    scrollParents.forEach(function (scrollParent) {
      scrollParent.addEventListener('scroll', instance.update, passive);
    });
  }

  if (resize) {
    window.addEventListener('resize', instance.update, passive);
  }

  return function () {
    if (scroll) {
      scrollParents.forEach(function (scrollParent) {
        scrollParent.removeEventListener('scroll', instance.update, passive);
      });
    }

    if (resize) {
      window.removeEventListener('resize', instance.update, passive);
    }
  };
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const eventListeners = ({
  name: 'eventListeners',
  enabled: true,
  phase: 'write',
  fn: function fn() {},
  effect: effect,
  data: {}
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/getBasePlacement.js

function getBasePlacement(placement) {
  return placement.split('-')[0];
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/getVariation.js
function getVariation(placement) {
  return placement.split('-')[1];
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/getMainAxisFromPlacement.js
function getMainAxisFromPlacement(placement) {
  return ['top', 'bottom'].indexOf(placement) >= 0 ? 'x' : 'y';
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/computeOffsets.js




function computeOffsets(_ref) {
  var reference = _ref.reference,
      element = _ref.element,
      placement = _ref.placement;
  var basePlacement = placement ? getBasePlacement(placement) : null;
  var variation = placement ? getVariation(placement) : null;
  var commonX = reference.x + reference.width / 2 - element.width / 2;
  var commonY = reference.y + reference.height / 2 - element.height / 2;
  var offsets;

  switch (basePlacement) {
    case enums_top:
      offsets = {
        x: commonX,
        y: reference.y - element.height
      };
      break;

    case bottom:
      offsets = {
        x: commonX,
        y: reference.y + reference.height
      };
      break;

    case right:
      offsets = {
        x: reference.x + reference.width,
        y: commonY
      };
      break;

    case left:
      offsets = {
        x: reference.x - element.width,
        y: commonY
      };
      break;

    default:
      offsets = {
        x: reference.x,
        y: reference.y
      };
  }

  var mainAxis = basePlacement ? getMainAxisFromPlacement(basePlacement) : null;

  if (mainAxis != null) {
    var len = mainAxis === 'y' ? 'height' : 'width';

    switch (variation) {
      case start:
        offsets[mainAxis] = offsets[mainAxis] - (reference[len] / 2 - element[len] / 2);
        break;

      case end:
        offsets[mainAxis] = offsets[mainAxis] + (reference[len] / 2 - element[len] / 2);
        break;

      default:
    }
  }

  return offsets;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/popperOffsets.js


function popperOffsets(_ref) {
  var state = _ref.state,
      name = _ref.name;
  // Offsets are the actual position the popper needs to have to be
  // properly positioned near its reference element
  // This is the most basic placement, and will be adjusted by
  // the modifiers in the next step
  state.modifiersData[name] = computeOffsets({
    reference: state.rects.reference,
    element: state.rects.popper,
    strategy: 'absolute',
    placement: state.placement
  });
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_popperOffsets = ({
  name: 'popperOffsets',
  enabled: true,
  phase: 'read',
  fn: popperOffsets,
  data: {}
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/computeStyles.js







 // eslint-disable-next-line import/no-unused-modules

var unsetSides = {
  top: 'auto',
  right: 'auto',
  bottom: 'auto',
  left: 'auto'
}; // Round the offsets to the nearest suitable subpixel based on the DPR.
// Zooming can change the DPR, but it seems to report a value that will
// cleanly divide the values into the appropriate subpixels.

function roundOffsetsByDPR(_ref, win) {
  var x = _ref.x,
      y = _ref.y;
  var dpr = win.devicePixelRatio || 1;
  return {
    x: round(x * dpr) / dpr || 0,
    y: round(y * dpr) / dpr || 0
  };
}

function mapToStyles(_ref2) {
  var _Object$assign2;

  var popper = _ref2.popper,
      popperRect = _ref2.popperRect,
      placement = _ref2.placement,
      variation = _ref2.variation,
      offsets = _ref2.offsets,
      position = _ref2.position,
      gpuAcceleration = _ref2.gpuAcceleration,
      adaptive = _ref2.adaptive,
      roundOffsets = _ref2.roundOffsets,
      isFixed = _ref2.isFixed;
  var _offsets$x = offsets.x,
      x = _offsets$x === void 0 ? 0 : _offsets$x,
      _offsets$y = offsets.y,
      y = _offsets$y === void 0 ? 0 : _offsets$y;

  var _ref3 = typeof roundOffsets === 'function' ? roundOffsets({
    x: x,
    y: y
  }) : {
    x: x,
    y: y
  };

  x = _ref3.x;
  y = _ref3.y;
  var hasX = offsets.hasOwnProperty('x');
  var hasY = offsets.hasOwnProperty('y');
  var sideX = left;
  var sideY = enums_top;
  var win = window;

  if (adaptive) {
    var offsetParent = getOffsetParent(popper);
    var heightProp = 'clientHeight';
    var widthProp = 'clientWidth';

    if (offsetParent === getWindow(popper)) {
      offsetParent = getDocumentElement(popper);

      if (getComputedStyle(offsetParent).position !== 'static' && position === 'absolute') {
        heightProp = 'scrollHeight';
        widthProp = 'scrollWidth';
      }
    } // $FlowFixMe[incompatible-cast]: force type refinement, we compare offsetParent with window above, but Flow doesn't detect it


    offsetParent = offsetParent;

    if (placement === enums_top || (placement === left || placement === right) && variation === end) {
      sideY = bottom;
      var offsetY = isFixed && offsetParent === win && win.visualViewport ? win.visualViewport.height : // $FlowFixMe[prop-missing]
      offsetParent[heightProp];
      y -= offsetY - popperRect.height;
      y *= gpuAcceleration ? 1 : -1;
    }

    if (placement === left || (placement === enums_top || placement === bottom) && variation === end) {
      sideX = right;
      var offsetX = isFixed && offsetParent === win && win.visualViewport ? win.visualViewport.width : // $FlowFixMe[prop-missing]
      offsetParent[widthProp];
      x -= offsetX - popperRect.width;
      x *= gpuAcceleration ? 1 : -1;
    }
  }

  var commonStyles = Object.assign({
    position: position
  }, adaptive && unsetSides);

  var _ref4 = roundOffsets === true ? roundOffsetsByDPR({
    x: x,
    y: y
  }, getWindow(popper)) : {
    x: x,
    y: y
  };

  x = _ref4.x;
  y = _ref4.y;

  if (gpuAcceleration) {
    var _Object$assign;

    return Object.assign({}, commonStyles, (_Object$assign = {}, _Object$assign[sideY] = hasY ? '0' : '', _Object$assign[sideX] = hasX ? '0' : '', _Object$assign.transform = (win.devicePixelRatio || 1) <= 1 ? "translate(" + x + "px, " + y + "px)" : "translate3d(" + x + "px, " + y + "px, 0)", _Object$assign));
  }

  return Object.assign({}, commonStyles, (_Object$assign2 = {}, _Object$assign2[sideY] = hasY ? y + "px" : '', _Object$assign2[sideX] = hasX ? x + "px" : '', _Object$assign2.transform = '', _Object$assign2));
}

function computeStyles(_ref5) {
  var state = _ref5.state,
      options = _ref5.options;
  var _options$gpuAccelerat = options.gpuAcceleration,
      gpuAcceleration = _options$gpuAccelerat === void 0 ? true : _options$gpuAccelerat,
      _options$adaptive = options.adaptive,
      adaptive = _options$adaptive === void 0 ? true : _options$adaptive,
      _options$roundOffsets = options.roundOffsets,
      roundOffsets = _options$roundOffsets === void 0 ? true : _options$roundOffsets;
  var commonStyles = {
    placement: getBasePlacement(state.placement),
    variation: getVariation(state.placement),
    popper: state.elements.popper,
    popperRect: state.rects.popper,
    gpuAcceleration: gpuAcceleration,
    isFixed: state.options.strategy === 'fixed'
  };

  if (state.modifiersData.popperOffsets != null) {
    state.styles.popper = Object.assign({}, state.styles.popper, mapToStyles(Object.assign({}, commonStyles, {
      offsets: state.modifiersData.popperOffsets,
      position: state.options.strategy,
      adaptive: adaptive,
      roundOffsets: roundOffsets
    })));
  }

  if (state.modifiersData.arrow != null) {
    state.styles.arrow = Object.assign({}, state.styles.arrow, mapToStyles(Object.assign({}, commonStyles, {
      offsets: state.modifiersData.arrow,
      position: 'absolute',
      adaptive: false,
      roundOffsets: roundOffsets
    })));
  }

  state.attributes.popper = Object.assign({}, state.attributes.popper, {
    'data-popper-placement': state.placement
  });
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_computeStyles = ({
  name: 'computeStyles',
  enabled: true,
  phase: 'beforeWrite',
  fn: computeStyles,
  data: {}
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/applyStyles.js

 // This modifier takes the styles prepared by the `computeStyles` modifier
// and applies them to the HTMLElements such as popper and arrow

function applyStyles(_ref) {
  var state = _ref.state;
  Object.keys(state.elements).forEach(function (name) {
    var style = state.styles[name] || {};
    var attributes = state.attributes[name] || {};
    var element = state.elements[name]; // arrow is optional + virtual elements

    if (!isHTMLElement(element) || !getNodeName(element)) {
      return;
    } // Flow doesn't support to extend this property, but it's the most
    // effective way to apply styles to an HTMLElement
    // $FlowFixMe[cannot-write]


    Object.assign(element.style, style);
    Object.keys(attributes).forEach(function (name) {
      var value = attributes[name];

      if (value === false) {
        element.removeAttribute(name);
      } else {
        element.setAttribute(name, value === true ? '' : value);
      }
    });
  });
}

function applyStyles_effect(_ref2) {
  var state = _ref2.state;
  var initialStyles = {
    popper: {
      position: state.options.strategy,
      left: '0',
      top: '0',
      margin: '0'
    },
    arrow: {
      position: 'absolute'
    },
    reference: {}
  };
  Object.assign(state.elements.popper.style, initialStyles.popper);
  state.styles = initialStyles;

  if (state.elements.arrow) {
    Object.assign(state.elements.arrow.style, initialStyles.arrow);
  }

  return function () {
    Object.keys(state.elements).forEach(function (name) {
      var element = state.elements[name];
      var attributes = state.attributes[name] || {};
      var styleProperties = Object.keys(state.styles.hasOwnProperty(name) ? state.styles[name] : initialStyles[name]); // Set all values to an empty string to unset them

      var style = styleProperties.reduce(function (style, property) {
        style[property] = '';
        return style;
      }, {}); // arrow is optional + virtual elements

      if (!isHTMLElement(element) || !getNodeName(element)) {
        return;
      }

      Object.assign(element.style, style);
      Object.keys(attributes).forEach(function (attribute) {
        element.removeAttribute(attribute);
      });
    });
  };
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_applyStyles = ({
  name: 'applyStyles',
  enabled: true,
  phase: 'write',
  fn: applyStyles,
  effect: applyStyles_effect,
  requires: ['computeStyles']
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/offset.js

 // eslint-disable-next-line import/no-unused-modules

function distanceAndSkiddingToXY(placement, rects, offset) {
  var basePlacement = getBasePlacement(placement);
  var invertDistance = [left, enums_top].indexOf(basePlacement) >= 0 ? -1 : 1;

  var _ref = typeof offset === 'function' ? offset(Object.assign({}, rects, {
    placement: placement
  })) : offset,
      skidding = _ref[0],
      distance = _ref[1];

  skidding = skidding || 0;
  distance = (distance || 0) * invertDistance;
  return [left, right].indexOf(basePlacement) >= 0 ? {
    x: distance,
    y: skidding
  } : {
    x: skidding,
    y: distance
  };
}

function offset(_ref2) {
  var state = _ref2.state,
      options = _ref2.options,
      name = _ref2.name;
  var _options$offset = options.offset,
      offset = _options$offset === void 0 ? [0, 0] : _options$offset;
  var data = enums_placements.reduce(function (acc, placement) {
    acc[placement] = distanceAndSkiddingToXY(placement, state.rects, offset);
    return acc;
  }, {});
  var _data$state$placement = data[state.placement],
      x = _data$state$placement.x,
      y = _data$state$placement.y;

  if (state.modifiersData.popperOffsets != null) {
    state.modifiersData.popperOffsets.x += x;
    state.modifiersData.popperOffsets.y += y;
  }

  state.modifiersData[name] = data;
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_offset = ({
  name: 'offset',
  enabled: true,
  phase: 'main',
  requires: ['popperOffsets'],
  fn: offset
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/getOppositePlacement.js
var hash = {
  left: 'right',
  right: 'left',
  bottom: 'top',
  top: 'bottom'
};
function getOppositePlacement(placement) {
  return placement.replace(/left|right|bottom|top/g, function (matched) {
    return hash[matched];
  });
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/getOppositeVariationPlacement.js
var getOppositeVariationPlacement_hash = {
  start: 'end',
  end: 'start'
};
function getOppositeVariationPlacement(placement) {
  return placement.replace(/start|end/g, function (matched) {
    return getOppositeVariationPlacement_hash[matched];
  });
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getViewportRect.js




function getViewportRect(element, strategy) {
  var win = getWindow(element);
  var html = getDocumentElement(element);
  var visualViewport = win.visualViewport;
  var width = html.clientWidth;
  var height = html.clientHeight;
  var x = 0;
  var y = 0;

  if (visualViewport) {
    width = visualViewport.width;
    height = visualViewport.height;
    var layoutViewport = isLayoutViewport();

    if (layoutViewport || !layoutViewport && strategy === 'fixed') {
      x = visualViewport.offsetLeft;
      y = visualViewport.offsetTop;
    }
  }

  return {
    width: width,
    height: height,
    x: x + getWindowScrollBarX(element),
    y: y
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getDocumentRect.js




 // Gets the entire size of the scrollable document area, even extending outside
// of the `<html>` and `<body>` rect bounds if horizontally scrollable

function getDocumentRect(element) {
  var _element$ownerDocumen;

  var html = getDocumentElement(element);
  var winScroll = getWindowScroll(element);
  var body = (_element$ownerDocumen = element.ownerDocument) == null ? void 0 : _element$ownerDocumen.body;
  var width = math_max(html.scrollWidth, html.clientWidth, body ? body.scrollWidth : 0, body ? body.clientWidth : 0);
  var height = math_max(html.scrollHeight, html.clientHeight, body ? body.scrollHeight : 0, body ? body.clientHeight : 0);
  var x = -winScroll.scrollLeft + getWindowScrollBarX(element);
  var y = -winScroll.scrollTop;

  if (getComputedStyle(body || html).direction === 'rtl') {
    x += math_max(html.clientWidth, body ? body.clientWidth : 0) - width;
  }

  return {
    width: width,
    height: height,
    x: x,
    y: y
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/contains.js

function contains(parent, child) {
  var rootNode = child.getRootNode && child.getRootNode(); // First, attempt with faster native method

  if (parent.contains(child)) {
    return true;
  } // then fallback to custom implementation with Shadow DOM support
  else if (rootNode && isShadowRoot(rootNode)) {
      var next = child;

      do {
        if (next && parent.isSameNode(next)) {
          return true;
        } // $FlowFixMe[prop-missing]: need a better way to handle this...


        next = next.parentNode || next.host;
      } while (next);
    } // Give up, the result is false


  return false;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/rectToClientRect.js
function rectToClientRect(rect) {
  return Object.assign({}, rect, {
    left: rect.x,
    top: rect.y,
    right: rect.x + rect.width,
    bottom: rect.y + rect.height
  });
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/dom-utils/getClippingRect.js















function getInnerBoundingClientRect(element, strategy) {
  var rect = getBoundingClientRect(element, false, strategy === 'fixed');
  rect.top = rect.top + element.clientTop;
  rect.left = rect.left + element.clientLeft;
  rect.bottom = rect.top + element.clientHeight;
  rect.right = rect.left + element.clientWidth;
  rect.width = element.clientWidth;
  rect.height = element.clientHeight;
  rect.x = rect.left;
  rect.y = rect.top;
  return rect;
}

function getClientRectFromMixedType(element, clippingParent, strategy) {
  return clippingParent === viewport ? rectToClientRect(getViewportRect(element, strategy)) : isElement(clippingParent) ? getInnerBoundingClientRect(clippingParent, strategy) : rectToClientRect(getDocumentRect(getDocumentElement(element)));
} // A "clipping parent" is an overflowable container with the characteristic of
// clipping (or hiding) overflowing elements with a position different from
// `initial`


function getClippingParents(element) {
  var clippingParents = listScrollParents(getParentNode(element));
  var canEscapeClipping = ['absolute', 'fixed'].indexOf(getComputedStyle(element).position) >= 0;
  var clipperElement = canEscapeClipping && isHTMLElement(element) ? getOffsetParent(element) : element;

  if (!isElement(clipperElement)) {
    return [];
  } // $FlowFixMe[incompatible-return]: https://github.com/facebook/flow/issues/1414


  return clippingParents.filter(function (clippingParent) {
    return isElement(clippingParent) && contains(clippingParent, clipperElement) && getNodeName(clippingParent) !== 'body';
  });
} // Gets the maximum area that the element is visible in due to any number of
// clipping parents


function getClippingRect(element, boundary, rootBoundary, strategy) {
  var mainClippingParents = boundary === 'clippingParents' ? getClippingParents(element) : [].concat(boundary);
  var clippingParents = [].concat(mainClippingParents, [rootBoundary]);
  var firstClippingParent = clippingParents[0];
  var clippingRect = clippingParents.reduce(function (accRect, clippingParent) {
    var rect = getClientRectFromMixedType(element, clippingParent, strategy);
    accRect.top = math_max(rect.top, accRect.top);
    accRect.right = math_min(rect.right, accRect.right);
    accRect.bottom = math_min(rect.bottom, accRect.bottom);
    accRect.left = math_max(rect.left, accRect.left);
    return accRect;
  }, getClientRectFromMixedType(element, firstClippingParent, strategy));
  clippingRect.width = clippingRect.right - clippingRect.left;
  clippingRect.height = clippingRect.bottom - clippingRect.top;
  clippingRect.x = clippingRect.left;
  clippingRect.y = clippingRect.top;
  return clippingRect;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/getFreshSideObject.js
function getFreshSideObject() {
  return {
    top: 0,
    right: 0,
    bottom: 0,
    left: 0
  };
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/mergePaddingObject.js

function mergePaddingObject(paddingObject) {
  return Object.assign({}, getFreshSideObject(), paddingObject);
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/expandToHashMap.js
function expandToHashMap(value, keys) {
  return keys.reduce(function (hashMap, key) {
    hashMap[key] = value;
    return hashMap;
  }, {});
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/detectOverflow.js








 // eslint-disable-next-line import/no-unused-modules

function detectOverflow(state, options) {
  if (options === void 0) {
    options = {};
  }

  var _options = options,
      _options$placement = _options.placement,
      placement = _options$placement === void 0 ? state.placement : _options$placement,
      _options$strategy = _options.strategy,
      strategy = _options$strategy === void 0 ? state.strategy : _options$strategy,
      _options$boundary = _options.boundary,
      boundary = _options$boundary === void 0 ? clippingParents : _options$boundary,
      _options$rootBoundary = _options.rootBoundary,
      rootBoundary = _options$rootBoundary === void 0 ? viewport : _options$rootBoundary,
      _options$elementConte = _options.elementContext,
      elementContext = _options$elementConte === void 0 ? popper : _options$elementConte,
      _options$altBoundary = _options.altBoundary,
      altBoundary = _options$altBoundary === void 0 ? false : _options$altBoundary,
      _options$padding = _options.padding,
      padding = _options$padding === void 0 ? 0 : _options$padding;
  var paddingObject = mergePaddingObject(typeof padding !== 'number' ? padding : expandToHashMap(padding, basePlacements));
  var altContext = elementContext === popper ? reference : popper;
  var popperRect = state.rects.popper;
  var element = state.elements[altBoundary ? altContext : elementContext];
  var clippingClientRect = getClippingRect(isElement(element) ? element : element.contextElement || getDocumentElement(state.elements.popper), boundary, rootBoundary, strategy);
  var referenceClientRect = getBoundingClientRect(state.elements.reference);
  var popperOffsets = computeOffsets({
    reference: referenceClientRect,
    element: popperRect,
    strategy: 'absolute',
    placement: placement
  });
  var popperClientRect = rectToClientRect(Object.assign({}, popperRect, popperOffsets));
  var elementClientRect = elementContext === popper ? popperClientRect : referenceClientRect; // positive = overflowing the clipping rect
  // 0 or negative = within the clipping rect

  var overflowOffsets = {
    top: clippingClientRect.top - elementClientRect.top + paddingObject.top,
    bottom: elementClientRect.bottom - clippingClientRect.bottom + paddingObject.bottom,
    left: clippingClientRect.left - elementClientRect.left + paddingObject.left,
    right: elementClientRect.right - clippingClientRect.right + paddingObject.right
  };
  var offsetData = state.modifiersData.offset; // Offsets can be applied only to the popper element

  if (elementContext === popper && offsetData) {
    var offset = offsetData[placement];
    Object.keys(overflowOffsets).forEach(function (key) {
      var multiply = [right, bottom].indexOf(key) >= 0 ? 1 : -1;
      var axis = [enums_top, bottom].indexOf(key) >= 0 ? 'y' : 'x';
      overflowOffsets[key] += offset[axis] * multiply;
    });
  }

  return overflowOffsets;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/computeAutoPlacement.js




function computeAutoPlacement(state, options) {
  if (options === void 0) {
    options = {};
  }

  var _options = options,
      placement = _options.placement,
      boundary = _options.boundary,
      rootBoundary = _options.rootBoundary,
      padding = _options.padding,
      flipVariations = _options.flipVariations,
      _options$allowedAutoP = _options.allowedAutoPlacements,
      allowedAutoPlacements = _options$allowedAutoP === void 0 ? enums_placements : _options$allowedAutoP;
  var variation = getVariation(placement);
  var placements = variation ? flipVariations ? variationPlacements : variationPlacements.filter(function (placement) {
    return getVariation(placement) === variation;
  }) : basePlacements;
  var allowedPlacements = placements.filter(function (placement) {
    return allowedAutoPlacements.indexOf(placement) >= 0;
  });

  if (allowedPlacements.length === 0) {
    allowedPlacements = placements;
  } // $FlowFixMe[incompatible-type]: Flow seems to have problems with two array unions...


  var overflows = allowedPlacements.reduce(function (acc, placement) {
    acc[placement] = detectOverflow(state, {
      placement: placement,
      boundary: boundary,
      rootBoundary: rootBoundary,
      padding: padding
    })[getBasePlacement(placement)];
    return acc;
  }, {});
  return Object.keys(overflows).sort(function (a, b) {
    return overflows[a] - overflows[b];
  });
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/flip.js






 // eslint-disable-next-line import/no-unused-modules

function getExpandedFallbackPlacements(placement) {
  if (getBasePlacement(placement) === auto) {
    return [];
  }

  var oppositePlacement = getOppositePlacement(placement);
  return [getOppositeVariationPlacement(placement), oppositePlacement, getOppositeVariationPlacement(oppositePlacement)];
}

function flip(_ref) {
  var state = _ref.state,
      options = _ref.options,
      name = _ref.name;

  if (state.modifiersData[name]._skip) {
    return;
  }

  var _options$mainAxis = options.mainAxis,
      checkMainAxis = _options$mainAxis === void 0 ? true : _options$mainAxis,
      _options$altAxis = options.altAxis,
      checkAltAxis = _options$altAxis === void 0 ? true : _options$altAxis,
      specifiedFallbackPlacements = options.fallbackPlacements,
      padding = options.padding,
      boundary = options.boundary,
      rootBoundary = options.rootBoundary,
      altBoundary = options.altBoundary,
      _options$flipVariatio = options.flipVariations,
      flipVariations = _options$flipVariatio === void 0 ? true : _options$flipVariatio,
      allowedAutoPlacements = options.allowedAutoPlacements;
  var preferredPlacement = state.options.placement;
  var basePlacement = getBasePlacement(preferredPlacement);
  var isBasePlacement = basePlacement === preferredPlacement;
  var fallbackPlacements = specifiedFallbackPlacements || (isBasePlacement || !flipVariations ? [getOppositePlacement(preferredPlacement)] : getExpandedFallbackPlacements(preferredPlacement));
  var placements = [preferredPlacement].concat(fallbackPlacements).reduce(function (acc, placement) {
    return acc.concat(getBasePlacement(placement) === auto ? computeAutoPlacement(state, {
      placement: placement,
      boundary: boundary,
      rootBoundary: rootBoundary,
      padding: padding,
      flipVariations: flipVariations,
      allowedAutoPlacements: allowedAutoPlacements
    }) : placement);
  }, []);
  var referenceRect = state.rects.reference;
  var popperRect = state.rects.popper;
  var checksMap = new Map();
  var makeFallbackChecks = true;
  var firstFittingPlacement = placements[0];

  for (var i = 0; i < placements.length; i++) {
    var placement = placements[i];

    var _basePlacement = getBasePlacement(placement);

    var isStartVariation = getVariation(placement) === start;
    var isVertical = [enums_top, bottom].indexOf(_basePlacement) >= 0;
    var len = isVertical ? 'width' : 'height';
    var overflow = detectOverflow(state, {
      placement: placement,
      boundary: boundary,
      rootBoundary: rootBoundary,
      altBoundary: altBoundary,
      padding: padding
    });
    var mainVariationSide = isVertical ? isStartVariation ? right : left : isStartVariation ? bottom : enums_top;

    if (referenceRect[len] > popperRect[len]) {
      mainVariationSide = getOppositePlacement(mainVariationSide);
    }

    var altVariationSide = getOppositePlacement(mainVariationSide);
    var checks = [];

    if (checkMainAxis) {
      checks.push(overflow[_basePlacement] <= 0);
    }

    if (checkAltAxis) {
      checks.push(overflow[mainVariationSide] <= 0, overflow[altVariationSide] <= 0);
    }

    if (checks.every(function (check) {
      return check;
    })) {
      firstFittingPlacement = placement;
      makeFallbackChecks = false;
      break;
    }

    checksMap.set(placement, checks);
  }

  if (makeFallbackChecks) {
    // `2` may be desired in some cases – research later
    var numberOfChecks = flipVariations ? 3 : 1;

    var _loop = function _loop(_i) {
      var fittingPlacement = placements.find(function (placement) {
        var checks = checksMap.get(placement);

        if (checks) {
          return checks.slice(0, _i).every(function (check) {
            return check;
          });
        }
      });

      if (fittingPlacement) {
        firstFittingPlacement = fittingPlacement;
        return "break";
      }
    };

    for (var _i = numberOfChecks; _i > 0; _i--) {
      var _ret = _loop(_i);

      if (_ret === "break") break;
    }
  }

  if (state.placement !== firstFittingPlacement) {
    state.modifiersData[name]._skip = true;
    state.placement = firstFittingPlacement;
    state.reset = true;
  }
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_flip = ({
  name: 'flip',
  enabled: true,
  phase: 'main',
  fn: flip,
  requiresIfExists: ['offset'],
  data: {
    _skip: false
  }
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/getAltAxis.js
function getAltAxis(axis) {
  return axis === 'x' ? 'y' : 'x';
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/utils/within.js

function within(min, value, max) {
  return math_max(min, math_min(value, max));
}
function withinMaxClamp(min, value, max) {
  var v = within(min, value, max);
  return v > max ? max : v;
}
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/preventOverflow.js












function preventOverflow(_ref) {
  var state = _ref.state,
      options = _ref.options,
      name = _ref.name;
  var _options$mainAxis = options.mainAxis,
      checkMainAxis = _options$mainAxis === void 0 ? true : _options$mainAxis,
      _options$altAxis = options.altAxis,
      checkAltAxis = _options$altAxis === void 0 ? false : _options$altAxis,
      boundary = options.boundary,
      rootBoundary = options.rootBoundary,
      altBoundary = options.altBoundary,
      padding = options.padding,
      _options$tether = options.tether,
      tether = _options$tether === void 0 ? true : _options$tether,
      _options$tetherOffset = options.tetherOffset,
      tetherOffset = _options$tetherOffset === void 0 ? 0 : _options$tetherOffset;
  var overflow = detectOverflow(state, {
    boundary: boundary,
    rootBoundary: rootBoundary,
    padding: padding,
    altBoundary: altBoundary
  });
  var basePlacement = getBasePlacement(state.placement);
  var variation = getVariation(state.placement);
  var isBasePlacement = !variation;
  var mainAxis = getMainAxisFromPlacement(basePlacement);
  var altAxis = getAltAxis(mainAxis);
  var popperOffsets = state.modifiersData.popperOffsets;
  var referenceRect = state.rects.reference;
  var popperRect = state.rects.popper;
  var tetherOffsetValue = typeof tetherOffset === 'function' ? tetherOffset(Object.assign({}, state.rects, {
    placement: state.placement
  })) : tetherOffset;
  var normalizedTetherOffsetValue = typeof tetherOffsetValue === 'number' ? {
    mainAxis: tetherOffsetValue,
    altAxis: tetherOffsetValue
  } : Object.assign({
    mainAxis: 0,
    altAxis: 0
  }, tetherOffsetValue);
  var offsetModifierState = state.modifiersData.offset ? state.modifiersData.offset[state.placement] : null;
  var data = {
    x: 0,
    y: 0
  };

  if (!popperOffsets) {
    return;
  }

  if (checkMainAxis) {
    var _offsetModifierState$;

    var mainSide = mainAxis === 'y' ? enums_top : left;
    var altSide = mainAxis === 'y' ? bottom : right;
    var len = mainAxis === 'y' ? 'height' : 'width';
    var offset = popperOffsets[mainAxis];
    var min = offset + overflow[mainSide];
    var max = offset - overflow[altSide];
    var additive = tether ? -popperRect[len] / 2 : 0;
    var minLen = variation === start ? referenceRect[len] : popperRect[len];
    var maxLen = variation === start ? -popperRect[len] : -referenceRect[len]; // We need to include the arrow in the calculation so the arrow doesn't go
    // outside the reference bounds

    var arrowElement = state.elements.arrow;
    var arrowRect = tether && arrowElement ? getLayoutRect(arrowElement) : {
      width: 0,
      height: 0
    };
    var arrowPaddingObject = state.modifiersData['arrow#persistent'] ? state.modifiersData['arrow#persistent'].padding : getFreshSideObject();
    var arrowPaddingMin = arrowPaddingObject[mainSide];
    var arrowPaddingMax = arrowPaddingObject[altSide]; // If the reference length is smaller than the arrow length, we don't want
    // to include its full size in the calculation. If the reference is small
    // and near the edge of a boundary, the popper can overflow even if the
    // reference is not overflowing as well (e.g. virtual elements with no
    // width or height)

    var arrowLen = within(0, referenceRect[len], arrowRect[len]);
    var minOffset = isBasePlacement ? referenceRect[len] / 2 - additive - arrowLen - arrowPaddingMin - normalizedTetherOffsetValue.mainAxis : minLen - arrowLen - arrowPaddingMin - normalizedTetherOffsetValue.mainAxis;
    var maxOffset = isBasePlacement ? -referenceRect[len] / 2 + additive + arrowLen + arrowPaddingMax + normalizedTetherOffsetValue.mainAxis : maxLen + arrowLen + arrowPaddingMax + normalizedTetherOffsetValue.mainAxis;
    var arrowOffsetParent = state.elements.arrow && getOffsetParent(state.elements.arrow);
    var clientOffset = arrowOffsetParent ? mainAxis === 'y' ? arrowOffsetParent.clientTop || 0 : arrowOffsetParent.clientLeft || 0 : 0;
    var offsetModifierValue = (_offsetModifierState$ = offsetModifierState == null ? void 0 : offsetModifierState[mainAxis]) != null ? _offsetModifierState$ : 0;
    var tetherMin = offset + minOffset - offsetModifierValue - clientOffset;
    var tetherMax = offset + maxOffset - offsetModifierValue;
    var preventedOffset = within(tether ? math_min(min, tetherMin) : min, offset, tether ? math_max(max, tetherMax) : max);
    popperOffsets[mainAxis] = preventedOffset;
    data[mainAxis] = preventedOffset - offset;
  }

  if (checkAltAxis) {
    var _offsetModifierState$2;

    var _mainSide = mainAxis === 'x' ? enums_top : left;

    var _altSide = mainAxis === 'x' ? bottom : right;

    var _offset = popperOffsets[altAxis];

    var _len = altAxis === 'y' ? 'height' : 'width';

    var _min = _offset + overflow[_mainSide];

    var _max = _offset - overflow[_altSide];

    var isOriginSide = [enums_top, left].indexOf(basePlacement) !== -1;

    var _offsetModifierValue = (_offsetModifierState$2 = offsetModifierState == null ? void 0 : offsetModifierState[altAxis]) != null ? _offsetModifierState$2 : 0;

    var _tetherMin = isOriginSide ? _min : _offset - referenceRect[_len] - popperRect[_len] - _offsetModifierValue + normalizedTetherOffsetValue.altAxis;

    var _tetherMax = isOriginSide ? _offset + referenceRect[_len] + popperRect[_len] - _offsetModifierValue - normalizedTetherOffsetValue.altAxis : _max;

    var _preventedOffset = tether && isOriginSide ? withinMaxClamp(_tetherMin, _offset, _tetherMax) : within(tether ? _tetherMin : _min, _offset, tether ? _tetherMax : _max);

    popperOffsets[altAxis] = _preventedOffset;
    data[altAxis] = _preventedOffset - _offset;
  }

  state.modifiersData[name] = data;
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_preventOverflow = ({
  name: 'preventOverflow',
  enabled: true,
  phase: 'main',
  fn: preventOverflow,
  requiresIfExists: ['offset']
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/arrow.js








 // eslint-disable-next-line import/no-unused-modules

var toPaddingObject = function toPaddingObject(padding, state) {
  padding = typeof padding === 'function' ? padding(Object.assign({}, state.rects, {
    placement: state.placement
  })) : padding;
  return mergePaddingObject(typeof padding !== 'number' ? padding : expandToHashMap(padding, basePlacements));
};

function arrow(_ref) {
  var _state$modifiersData$;

  var state = _ref.state,
      name = _ref.name,
      options = _ref.options;
  var arrowElement = state.elements.arrow;
  var popperOffsets = state.modifiersData.popperOffsets;
  var basePlacement = getBasePlacement(state.placement);
  var axis = getMainAxisFromPlacement(basePlacement);
  var isVertical = [left, right].indexOf(basePlacement) >= 0;
  var len = isVertical ? 'height' : 'width';

  if (!arrowElement || !popperOffsets) {
    return;
  }

  var paddingObject = toPaddingObject(options.padding, state);
  var arrowRect = getLayoutRect(arrowElement);
  var minProp = axis === 'y' ? enums_top : left;
  var maxProp = axis === 'y' ? bottom : right;
  var endDiff = state.rects.reference[len] + state.rects.reference[axis] - popperOffsets[axis] - state.rects.popper[len];
  var startDiff = popperOffsets[axis] - state.rects.reference[axis];
  var arrowOffsetParent = getOffsetParent(arrowElement);
  var clientSize = arrowOffsetParent ? axis === 'y' ? arrowOffsetParent.clientHeight || 0 : arrowOffsetParent.clientWidth || 0 : 0;
  var centerToReference = endDiff / 2 - startDiff / 2; // Make sure the arrow doesn't overflow the popper if the center point is
  // outside of the popper bounds

  var min = paddingObject[minProp];
  var max = clientSize - arrowRect[len] - paddingObject[maxProp];
  var center = clientSize / 2 - arrowRect[len] / 2 + centerToReference;
  var offset = within(min, center, max); // Prevents breaking syntax highlighting...

  var axisProp = axis;
  state.modifiersData[name] = (_state$modifiersData$ = {}, _state$modifiersData$[axisProp] = offset, _state$modifiersData$.centerOffset = offset - center, _state$modifiersData$);
}

function arrow_effect(_ref2) {
  var state = _ref2.state,
      options = _ref2.options;
  var _options$element = options.element,
      arrowElement = _options$element === void 0 ? '[data-popper-arrow]' : _options$element;

  if (arrowElement == null) {
    return;
  } // CSS selector


  if (typeof arrowElement === 'string') {
    arrowElement = state.elements.popper.querySelector(arrowElement);

    if (!arrowElement) {
      return;
    }
  }

  if (!contains(state.elements.popper, arrowElement)) {
    return;
  }

  state.elements.arrow = arrowElement;
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_arrow = ({
  name: 'arrow',
  enabled: true,
  phase: 'main',
  fn: arrow,
  effect: arrow_effect,
  requires: ['popperOffsets'],
  requiresIfExists: ['preventOverflow']
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/modifiers/hide.js



function getSideOffsets(overflow, rect, preventedOffsets) {
  if (preventedOffsets === void 0) {
    preventedOffsets = {
      x: 0,
      y: 0
    };
  }

  return {
    top: overflow.top - rect.height - preventedOffsets.y,
    right: overflow.right - rect.width + preventedOffsets.x,
    bottom: overflow.bottom - rect.height + preventedOffsets.y,
    left: overflow.left - rect.width - preventedOffsets.x
  };
}

function isAnySideFullyClipped(overflow) {
  return [enums_top, right, bottom, left].some(function (side) {
    return overflow[side] >= 0;
  });
}

function hide(_ref) {
  var state = _ref.state,
      name = _ref.name;
  var referenceRect = state.rects.reference;
  var popperRect = state.rects.popper;
  var preventedOffsets = state.modifiersData.preventOverflow;
  var referenceOverflow = detectOverflow(state, {
    elementContext: 'reference'
  });
  var popperAltOverflow = detectOverflow(state, {
    altBoundary: true
  });
  var referenceClippingOffsets = getSideOffsets(referenceOverflow, referenceRect);
  var popperEscapeOffsets = getSideOffsets(popperAltOverflow, popperRect, preventedOffsets);
  var isReferenceHidden = isAnySideFullyClipped(referenceClippingOffsets);
  var hasPopperEscaped = isAnySideFullyClipped(popperEscapeOffsets);
  state.modifiersData[name] = {
    referenceClippingOffsets: referenceClippingOffsets,
    popperEscapeOffsets: popperEscapeOffsets,
    isReferenceHidden: isReferenceHidden,
    hasPopperEscaped: hasPopperEscaped
  };
  state.attributes.popper = Object.assign({}, state.attributes.popper, {
    'data-popper-reference-hidden': isReferenceHidden,
    'data-popper-escaped': hasPopperEscaped
  });
} // eslint-disable-next-line import/no-unused-modules


/* harmony default export */ const modifiers_hide = ({
  name: 'hide',
  enabled: true,
  phase: 'main',
  requiresIfExists: ['preventOverflow'],
  fn: hide
});
;// CONCATENATED MODULE: ./node_modules/@popperjs/core/lib/popper.js










var defaultModifiers = [eventListeners, modifiers_popperOffsets, modifiers_computeStyles, modifiers_applyStyles, modifiers_offset, modifiers_flip, modifiers_preventOverflow, modifiers_arrow, modifiers_hide];
var popper_createPopper = /*#__PURE__*/popperGenerator({
  defaultModifiers: defaultModifiers
}); // eslint-disable-next-line import/no-unused-modules

 // eslint-disable-next-line import/no-unused-modules

 // eslint-disable-next-line import/no-unused-modules



/***/ }),

/***/ "./node_modules/flatpickr/dist/esm/utils/polyfills.js":
/***/ (() => {

"use strict";

if (typeof Object.assign !== "function") {
    Object.assign = function (target) {
        var args = [];
        for (var _i = 1; _i < arguments.length; _i++) {
            args[_i - 1] = arguments[_i];
        }
        if (!target) {
            throw TypeError("Cannot convert undefined or null to object");
        }
        var _loop_1 = function (source) {
            if (source) {
                Object.keys(source).forEach(function (key) { return (target[key] = source[key]); });
            }
        };
        for (var _a = 0, args_1 = args; _a < args_1.length; _a++) {
            var source = args_1[_a];
            _loop_1(source);
        }
        return target;
    };
}


/***/ }),

/***/ "./node_modules/flatpickr/dist/l10n/pt.js":
/***/ (function(__unused_webpack_module, exports) {

(function (global, factory) {
   true ? factory(exports) :
  0;
}(this, (function (exports) { 'use strict';

  var fp = typeof window !== "undefined" && window.flatpickr !== undefined
      ? window.flatpickr
      : {
          l10ns: {},
      };
  var Portuguese = {
      weekdays: {
          shorthand: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
          longhand: [
              "Domingo",
              "Segunda-feira",
              "Terça-feira",
              "Quarta-feira",
              "Quinta-feira",
              "Sexta-feira",
              "Sábado",
          ],
      },
      months: {
          shorthand: [
              "Jan",
              "Fev",
              "Mar",
              "Abr",
              "Mai",
              "Jun",
              "Jul",
              "Ago",
              "Set",
              "Out",
              "Nov",
              "Dez",
          ],
          longhand: [
              "Janeiro",
              "Fevereiro",
              "Março",
              "Abril",
              "Maio",
              "Junho",
              "Julho",
              "Agosto",
              "Setembro",
              "Outubro",
              "Novembro",
              "Dezembro",
          ],
      },
      rangeSeparator: " até ",
      time_24hr: true,
  };
  fp.l10ns.pt = Portuguese;
  var pt = fp.l10ns;

  exports.Portuguese = Portuguese;
  exports.default = pt;

  Object.defineProperty(exports, '__esModule', { value: true });

})));


/***/ }),

/***/ "./node_modules/focus-visible/dist/focus-visible.js":
/***/ (function() {

(function (global, factory) {
   true ? factory() :
  0;
}(this, (function () { 'use strict';

  /**
   * Applies the :focus-visible polyfill at the given scope.
   * A scope in this case is either the top-level Document or a Shadow Root.
   *
   * @param {(Document|ShadowRoot)} scope
   * @see https://github.com/WICG/focus-visible
   */
  function applyFocusVisiblePolyfill(scope) {
    var hadKeyboardEvent = true;
    var hadFocusVisibleRecently = false;
    var hadFocusVisibleRecentlyTimeout = null;

    var inputTypesAllowlist = {
      text: true,
      search: true,
      url: true,
      tel: true,
      email: true,
      password: true,
      number: true,
      date: true,
      month: true,
      week: true,
      time: true,
      datetime: true,
      'datetime-local': true
    };

    /**
     * Helper function for legacy browsers and iframes which sometimes focus
     * elements like document, body, and non-interactive SVG.
     * @param {Element} el
     */
    function isValidFocusTarget(el) {
      if (
        el &&
        el !== document &&
        el.nodeName !== 'HTML' &&
        el.nodeName !== 'BODY' &&
        'classList' in el &&
        'contains' in el.classList
      ) {
        return true;
      }
      return false;
    }

    /**
     * Computes whether the given element should automatically trigger the
     * `focus-visible` class being added, i.e. whether it should always match
     * `:focus-visible` when focused.
     * @param {Element} el
     * @return {boolean}
     */
    function focusTriggersKeyboardModality(el) {
      var type = el.type;
      var tagName = el.tagName;

      if (tagName === 'INPUT' && inputTypesAllowlist[type] && !el.readOnly) {
        return true;
      }

      if (tagName === 'TEXTAREA' && !el.readOnly) {
        return true;
      }

      if (el.isContentEditable) {
        return true;
      }

      return false;
    }

    /**
     * Add the `focus-visible` class to the given element if it was not added by
     * the author.
     * @param {Element} el
     */
    function addFocusVisibleClass(el) {
      if (el.classList.contains('focus-visible')) {
        return;
      }
      el.classList.add('focus-visible');
      el.setAttribute('data-focus-visible-added', '');
    }

    /**
     * Remove the `focus-visible` class from the given element if it was not
     * originally added by the author.
     * @param {Element} el
     */
    function removeFocusVisibleClass(el) {
      if (!el.hasAttribute('data-focus-visible-added')) {
        return;
      }
      el.classList.remove('focus-visible');
      el.removeAttribute('data-focus-visible-added');
    }

    /**
     * If the most recent user interaction was via the keyboard;
     * and the key press did not include a meta, alt/option, or control key;
     * then the modality is keyboard. Otherwise, the modality is not keyboard.
     * Apply `focus-visible` to any current active element and keep track
     * of our keyboard modality state with `hadKeyboardEvent`.
     * @param {KeyboardEvent} e
     */
    function onKeyDown(e) {
      if (e.metaKey || e.altKey || e.ctrlKey) {
        return;
      }

      if (isValidFocusTarget(scope.activeElement)) {
        addFocusVisibleClass(scope.activeElement);
      }

      hadKeyboardEvent = true;
    }

    /**
     * If at any point a user clicks with a pointing device, ensure that we change
     * the modality away from keyboard.
     * This avoids the situation where a user presses a key on an already focused
     * element, and then clicks on a different element, focusing it with a
     * pointing device, while we still think we're in keyboard modality.
     * @param {Event} e
     */
    function onPointerDown(e) {
      hadKeyboardEvent = false;
    }

    /**
     * On `focus`, add the `focus-visible` class to the target if:
     * - the target received focus as a result of keyboard navigation, or
     * - the event target is an element that will likely require interaction
     *   via the keyboard (e.g. a text box)
     * @param {Event} e
     */
    function onFocus(e) {
      // Prevent IE from focusing the document or HTML element.
      if (!isValidFocusTarget(e.target)) {
        return;
      }

      if (hadKeyboardEvent || focusTriggersKeyboardModality(e.target)) {
        addFocusVisibleClass(e.target);
      }
    }

    /**
     * On `blur`, remove the `focus-visible` class from the target.
     * @param {Event} e
     */
    function onBlur(e) {
      if (!isValidFocusTarget(e.target)) {
        return;
      }

      if (
        e.target.classList.contains('focus-visible') ||
        e.target.hasAttribute('data-focus-visible-added')
      ) {
        // To detect a tab/window switch, we look for a blur event followed
        // rapidly by a visibility change.
        // If we don't see a visibility change within 100ms, it's probably a
        // regular focus change.
        hadFocusVisibleRecently = true;
        window.clearTimeout(hadFocusVisibleRecentlyTimeout);
        hadFocusVisibleRecentlyTimeout = window.setTimeout(function() {
          hadFocusVisibleRecently = false;
        }, 100);
        removeFocusVisibleClass(e.target);
      }
    }

    /**
     * If the user changes tabs, keep track of whether or not the previously
     * focused element had .focus-visible.
     * @param {Event} e
     */
    function onVisibilityChange(e) {
      if (document.visibilityState === 'hidden') {
        // If the tab becomes active again, the browser will handle calling focus
        // on the element (Safari actually calls it twice).
        // If this tab change caused a blur on an element with focus-visible,
        // re-apply the class when the user switches back to the tab.
        if (hadFocusVisibleRecently) {
          hadKeyboardEvent = true;
        }
        addInitialPointerMoveListeners();
      }
    }

    /**
     * Add a group of listeners to detect usage of any pointing devices.
     * These listeners will be added when the polyfill first loads, and anytime
     * the window is blurred, so that they are active when the window regains
     * focus.
     */
    function addInitialPointerMoveListeners() {
      document.addEventListener('mousemove', onInitialPointerMove);
      document.addEventListener('mousedown', onInitialPointerMove);
      document.addEventListener('mouseup', onInitialPointerMove);
      document.addEventListener('pointermove', onInitialPointerMove);
      document.addEventListener('pointerdown', onInitialPointerMove);
      document.addEventListener('pointerup', onInitialPointerMove);
      document.addEventListener('touchmove', onInitialPointerMove);
      document.addEventListener('touchstart', onInitialPointerMove);
      document.addEventListener('touchend', onInitialPointerMove);
    }

    function removeInitialPointerMoveListeners() {
      document.removeEventListener('mousemove', onInitialPointerMove);
      document.removeEventListener('mousedown', onInitialPointerMove);
      document.removeEventListener('mouseup', onInitialPointerMove);
      document.removeEventListener('pointermove', onInitialPointerMove);
      document.removeEventListener('pointerdown', onInitialPointerMove);
      document.removeEventListener('pointerup', onInitialPointerMove);
      document.removeEventListener('touchmove', onInitialPointerMove);
      document.removeEventListener('touchstart', onInitialPointerMove);
      document.removeEventListener('touchend', onInitialPointerMove);
    }

    /**
     * When the polfyill first loads, assume the user is in keyboard modality.
     * If any event is received from a pointing device (e.g. mouse, pointer,
     * touch), turn off keyboard modality.
     * This accounts for situations where focus enters the page from the URL bar.
     * @param {Event} e
     */
    function onInitialPointerMove(e) {
      // Work around a Safari quirk that fires a mousemove on <html> whenever the
      // window blurs, even if you're tabbing out of the page. ¯\_(ツ)_/¯
      if (e.target.nodeName && e.target.nodeName.toLowerCase() === 'html') {
        return;
      }

      hadKeyboardEvent = false;
      removeInitialPointerMoveListeners();
    }

    // For some kinds of state, we are interested in changes at the global scope
    // only. For example, global pointer input, global key presses and global
    // visibility change should affect the state at every scope:
    document.addEventListener('keydown', onKeyDown, true);
    document.addEventListener('mousedown', onPointerDown, true);
    document.addEventListener('pointerdown', onPointerDown, true);
    document.addEventListener('touchstart', onPointerDown, true);
    document.addEventListener('visibilitychange', onVisibilityChange, true);

    addInitialPointerMoveListeners();

    // For focus and blur, we specifically care about state changes in the local
    // scope. This is because focus / blur events that originate from within a
    // shadow root are not re-dispatched from the host element if it was already
    // the active element in its own scope:
    scope.addEventListener('focus', onFocus, true);
    scope.addEventListener('blur', onBlur, true);

    // We detect that a node is a ShadowRoot by ensuring that it is a
    // DocumentFragment and also has a host property. This check covers native
    // implementation and polyfill implementation transparently. If we only cared
    // about the native implementation, we could just check if the scope was
    // an instance of a ShadowRoot.
    if (scope.nodeType === Node.DOCUMENT_FRAGMENT_NODE && scope.host) {
      // Since a ShadowRoot is a special kind of DocumentFragment, it does not
      // have a root element to add a class to. So, we add this attribute to the
      // host element instead:
      scope.host.setAttribute('data-js-focus-visible', '');
    } else if (scope.nodeType === Node.DOCUMENT_NODE) {
      document.documentElement.classList.add('js-focus-visible');
      document.documentElement.setAttribute('data-js-focus-visible', '');
    }
  }

  // It is important to wrap all references to global window and document in
  // these checks to support server-side rendering use cases
  // @see https://github.com/WICG/focus-visible/issues/199
  if (typeof window !== 'undefined' && typeof document !== 'undefined') {
    // Make the polyfill helper globally available. This can be used as a signal
    // to interested libraries that wish to coordinate with the polyfill for e.g.,
    // applying the polyfill to a shadow root:
    window.applyFocusVisiblePolyfill = applyFocusVisiblePolyfill;

    // Notify interested libraries of the polyfill's presence, in case the
    // polyfill was loaded lazily:
    var event;

    try {
      event = new CustomEvent('focus-visible-polyfill-ready');
    } catch (error) {
      // IE11 does not support using CustomEvent as a constructor directly:
      event = document.createEvent('CustomEvent');
      event.initCustomEvent('focus-visible-polyfill-ready', false, false, {});
    }

    window.dispatchEvent(event);
  }

  if (typeof document !== 'undefined') {
    // Apply the polyfill to the global document, so that no JavaScript
    // coordination is required to use the polyfill in the top-level document:
    applyFocusVisiblePolyfill(document);
  }

})));


/***/ }),

/***/ "./src/components/accordion/accordion.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
class BRAccordion {
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  _setBehavior() {
    for (const button of this.component.querySelectorAll('button.header')) {
      button.addEventListener('click', (event) => {
        this._collapse(event)
        this._changeIcon(event)
      })
    }
  }

  // eslint-disable-next-line complexity
  _collapse(event) {
    if (this.component.hasAttribute('single')) {
      for (const field of this.component.querySelectorAll('.item')) {
        if (field === event.currentTarget.parentNode) {
          if (field.hasAttribute('active')) {
            field.removeAttribute('active')
          } else {
            field.setAttribute('active', '')
          }
        } else {
          if (field.hasAttribute('active')) {
            field.removeAttribute('active')
          }
        }
      }
    } else {
      for (const field of this.component.querySelectorAll('.item')) {
        if (field === event.currentTarget.parentNode) {
          if (field.hasAttribute('active')) {
            field.removeAttribute('active')
          } else {
            field.setAttribute('active', '')
          }
        }
      }
    }
  }

  _changeIcon() {
    for (const field of this.component.querySelectorAll('.item')) {
      if (field.hasAttribute('active')) {
        for (const icon of field.querySelectorAll('.icon')) {
          icon.children[0].classList.remove('fa-angle-down')
          icon.children[0].classList.add('fa-angle-up')
        }
      } else {
        for (const icon of field.querySelectorAll('.icon')) {
          icon.children[0].classList.remove('fa-angle-up')
          icon.children[0].classList.add('fa-angle-down')
        }
      }
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRAccordion);


/***/ }),

/***/ "./src/components/avatar/avatar.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_dropdown__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/dropdown.js");


/**
 * Classe para o exemplo do comportamento dropdown
 */
class BRAvatar {
  /**
   * Instancia um exemplo de comportamento dropdown
   * @param {string} name - Nome do componente
   * @param {object} component - Referência ao objeto do DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define os comportamentos do componente
   * @private
   */
  _setBehavior() {
    this._setDropdownBehavior()
  }

  /**
   * Define os comportamentos do dropdown
   * @private
   */
  _setDropdownBehavior() {
    if (this.component.parentElement.dataset.toggle === 'dropdown') {
      const config = {
        iconToHide: 'fa-caret-up',
        iconToShow: 'fa-caret-down',
        trigger: this.component.parentElement,
        useIcons: true,
      }
      const dropdown = new _partial_js_behavior_dropdown__WEBPACK_IMPORTED_MODULE_0__["default"](config)
      dropdown.setBehavior()
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRAvatar);


/***/ }),

/***/ "./src/components/breadcrumb/breadcrumb.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
class BRBreadcrumb {
  /**
   * Instancia um componente breadcrumb
   * @param {string} name - nome do componente
   * @param {object} component - referencia ao objeto do DOM
   **/
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define os comportamentos do componente
   * @private
   */
  _setBehavior() {
    this._setView()
    window.addEventListener('resize', () => {
      this._setView()
    })
    window.document.addEventListener('click', (event) => {
      if (
        !this.component.contains(event.target) &&
        this.component.querySelector('.br-card')
      ) {
        this.component.querySelector('.br-card').remove()
      }
    })

    // window.addEventListener('resize', (event) => {})
  }
  /**
   * Reseta a view do breadcrumb
   */
  resetBreadcrumbs() {
    this._reset()

    const brcardBreadcrumb = this.component.querySelector('.br-card')
    if (brcardBreadcrumb !== null) {
      brcardBreadcrumb.remove()
    }
    this._setView()
  }

  /**
   * Atualiza a visualização do componente
   * @private
   */
  _setView() {
    this._reset()
    for (const crumbList of this.component.querySelectorAll('.crumb-list')) {
      const crumbs = crumbList.querySelectorAll('.crumb:not([data-active])')
      if (window.innerWidth < 575) {
        if (crumbs.length !== 1) {
          crumbs.forEach((crumb) => {
            crumb.classList.add('d-none')
          })
          this._insertExpandButton()
        }
      } else {
        if (
          crumbList.scrollWidth > crumbList.offsetWidth ||
          crumbs.length > 5
        ) {
          crumbs.forEach((crumb, index) => {
            if (index > 0 && index < crumbs.length - 1) {
              crumb.classList.add('d-none')
            }
          })
          this._insertExpandButton()
        }
      }
    }
  }

  /**
   * Insere botao de expandir
   * @private
   */
  _insertExpandButton() {
    const crumb = this._createCrumb()
    const crumbList = this.component.querySelector('.crumb-list')
    const crumbs = crumbList.querySelectorAll('.crumb')
    crumbList.insertBefore(crumb, crumbs[1])
  }

  /**
   * Reinicia componente
   * @private
   */
  _reset() {
    this.component.querySelectorAll('.crumb-list .crumb').forEach((crumb) => {
      if (crumb.classList.contains('menu-mobil')) {
        crumb.remove()
      } else {
        crumb.classList.remove('d-none')
      }
    })
  }

  /**
   * Insere elementos
   * @private
   */
  _createCrumb() {
    const crumb = document.createElement('li')
    crumb.classList.add('crumb', 'menu-mobil')
    crumb.setAttribute('data-toggle', 'dropdown')

    const button = document.createElement('button')
    button.classList.add('br-button', 'circle')

    const span = document.createElement('span')
    span.classList.add('sr-only')
    span.innerHTML = 'Botão Menu'

    const folderIcon = document.createElement('i')
    folderIcon.classList.add('fas', 'fa-folder-plus')

    const chevronIcon = document.createElement('i')
    chevronIcon.classList.add('icon', 'fas', 'fa-chevron-right')

    crumb.appendChild(chevronIcon)
    crumb.appendChild(button)
    button.appendChild(span)
    button.appendChild(folderIcon)

    crumb.addEventListener('click', () => {
      let card = this.component.querySelector('.br-card')
      if (card) {
        folderIcon.classList.remove('fas', 'fa-folder-minus')
        folderIcon.classList.add('fas', 'fa-folder-plus')
        this.component.querySelector('.br-card').remove()
      } else {
        folderIcon.classList.remove('fas', 'fa-folder-plus')
        folderIcon.classList.add('fas', 'fa-folder-minus')
        card = this._createList()
        this.component.appendChild(card)
      }
    })

    return crumb
  }

  /**
   * Cria lista
   * @private
   */
  _createList() {
    const card = document.createElement('div')
    card.classList.add('br-card')

    this.component.querySelectorAll('.crumb-list .crumb').forEach((crumb) => {
      if (crumb.classList.contains('d-none')) {
        const item = document.createElement('div')
        item.classList.add('br-item')

        if (!crumb.classList.contains('home')) {
          item.appendChild(crumb.querySelector('a').cloneNode(true))
          card.appendChild(item)
        }
      }
    })

    return card
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRBreadcrumb);


/***/ }),

/***/ "./src/components/card/card.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_collapse__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/collapse.js");


/** Classe para instanciar um objeto BRCard */
class BRCard {
  /**
   * Instancia do componente
   * @property {string} name - Nome do componente em minúsculo
   * @property {object} component - Objeto referenciando a raiz do componente DOM
   * @property {string} id - nome da id do ocmponente
   */
  constructor(name, component, id) {
    this.name = name
    this.component = component
    this.component.setAttribute('id', `card${id}`)
    this._setBehavior()
  }

  /**
   * Define o comportamento do componente
   * @private
   */
  _setBehavior() {
    this._setFlipBehavior()
    // this._setExpandBehavior()
    this._setDragBehavior()
    this._setDisableBehavior()
    this._collpaseBehavior()
  }

  /**
   * Define o comportamento de comprimir (collapse)
   * @private
   */
  _collpaseBehavior() {
    this.component
      .querySelectorAll('[data-toggle="collapse"]')
      .forEach((trigger) => {
        const config = {
          iconToHide: 'fa-chevron-up',
          iconToShow: 'fa-chevron-down',
          trigger,
          useIcons: true,
        }
        const collapse = new _partial_js_behavior_collapse__WEBPACK_IMPORTED_MODULE_0__["default"](config)
        collapse.setBehavior()
      })
  }

  /**
   * Desabilita o componente
   * @private
   */
  _setDisableBehavior() {
    if (this.component.classList.contains('disabled')) {
      this.component.setAttribute('aria-hidden', 'true')
      const buttons = this.component.querySelectorAll('button')
      const inputs = this.component.querySelectorAll('input')
      const selects = this.component.querySelectorAll('select')
      const textareas = this.component.querySelectorAll('textarea')
      for (const button of buttons) {
        button.setAttribute('disabled', 'disabled')
      }
      for (const input of inputs) {
        input.setAttribute('disabled', 'disabled')
      }
      for (const select of selects) {
        select.setAttribute('disabled', 'disabled')
      }
      for (const textarea of textareas) {
        textarea.setAttribute('disabled', 'disabled')
      }
    }
  }

  /**
   * Define o comportamento de girar (flip)
   * @private
   */
  _setFlipBehavior() {
    for (const flip of this.component.querySelectorAll('button.flip')) {
      flip.addEventListener('click', () => {
        if (this.component.getAttribute('flipped') === 'off') {
          this.component.setAttribute('flipped', 'on')
        } else {
          this.component.setAttribute('flipped', 'off')
        }
      })
    }
  }

  /**
   * Define o comportamento de arrastar (drag)
   * @private
   */
  _setDragBehavior() {
    for (const img of this.component.querySelectorAll('img')) {
      img.setAttribute('draggable', 'false')
    }
    for (const link of this.component.querySelectorAll('a')) {
      link.setAttribute('draggable', 'false')
    }
    this.component.addEventListener('dragstart', (event) => {
      event.stopPropagation()
      event.dataTransfer.setData(
        'text/plain',
        this.component.getAttribute('id')
      )
      event.dropEffect = 'move'
    })
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRCard);


/***/ }),

/***/ "./src/components/carousel/carousel.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_swipe__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__("./src/partial/js/behavior/swipe.js");
/* harmony import */ var _step_step__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/components/step/step.js");


class BRCarousel {
  constructor(name, component) {
    /**
     * Instancia um componente carousel
     * @param {string} name - Nome do componente
     * @param {object} component - Objeto referenciando a raiz do componente DOM
     * @property {number} activeStageNum - Número do palco ativo
     * @property {array} DOMStrings - instancia dos elementos internos do componente
     * @property {BRStep} DOMStrings - instancia dos elemento step interno
     */
    this.name = name
    this.component = component
    this.activeStageNum = 0
    // Elementos DOM
    this.DOMstrings = {
      carouselNextBtn: this.component.querySelector('.carousel-btn-next'),
      carouselPages: this.component.querySelectorAll('.carousel-page'),
      carouselPrevBtn: this.component.querySelector('.carousel-btn-prev'),
      carouselStage: this.component.querySelector('.carousel-stage'),
      circular: this.component.hasAttribute('data-circular'),
      step: this.component.querySelector('.br-step'),
    }
    this.step = new _step_step__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z('br-step', this.DOMstrings.step)
    this._setBehavior()
  }

  /**
   * Remove os atributos de uma lista de elementos
   * @param {object} elemSet - Lista de elementos
   * @param {string} button - Nome do atributo
   */
  removeAttributes(elemSet, attrName) {
    elemSet.forEach((elem) => {
      elem.removeAttribute(attrName)
    })
  }

  /**
   * Retorna o passo ativo
   */
  getActiveStep() {
    return this.step.activeStepNum
  }

  /**
   * Define o passo ativo
   * @param {number} activeStepNum - número do passo
   */
  setActiveStep(activeStepNum) {
    this.step.setActiveStep(activeStepNum)
  }

  /**
   * Retorna o palco ativo
   */
  getActiveStage() {
    let activeStage
    this.DOMstrings.carouselPages.forEach((stage) => {
      if (stage.hasAttribute('active')) {
        activeStage = stage
      }
    })
    return activeStage
  }

  /**
   * Define o palco ativo
   * @param {number} num - número do palco
   */
  setActiveStage(num) {
    // remove active atts from all the stages
    this.removeAttributes(this.DOMstrings.carouselPages, 'active')
    // show active stage
    this.DOMstrings.carouselPages.forEach((stage, index) => {
      // motion efect
      if (index > num) {
        stage.style.left = '100%'
      } else {
        stage.style.left = '-100%'
      }
      // set active attrb
      if (index === num) {
        stage.setAttribute('active', '')
        this.activeStageNum = num
      }
    })
    this.disabledBtns()
  }

  /**
   * Desabilita os botões se o carousel não for circular
   */
  disabledBtns() {
    // Disables Carousel Buttons
    if (!this.DOMstrings.circular) {
      if (this.activeStageNum === 0) {
        this.DOMstrings.carouselPrevBtn.setAttribute('disabled', '')
      } else {
        this.DOMstrings.carouselPrevBtn.removeAttribute('disabled')
      }
      if (this.activeStageNum === this.DOMstrings.carouselPages.length - 1) {
        this.DOMstrings.carouselNextBtn.setAttribute('disabled', '')
      } else {
        this.DOMstrings.carouselNextBtn.removeAttribute('disabled')
      }
    }
  }

  /**
   * Muda a página ativa incrementalmente - ações de botões e swap
   * @param {number} num - incremento
   */
  shiftPage(num) {
    //find active stage
    const activeStage = this.getActiveStage()
    const PanelsSize = this.DOMstrings.carouselPages.length - 1
    let activeStageNum = Array.from(this.DOMstrings.carouselPages).indexOf(
      activeStage
    )
    // set active step and active stage onclick
    if (num < 0) {
      // Volta o slide
      if (this.DOMstrings.circular) {
        activeStageNum =
          activeStageNum === 0 ? PanelsSize : (activeStageNum -= 1)
      } else {
        activeStageNum -= activeStageNum === 0 ? 0 : 1
      }
    } else {
      // Passar p/ frente o slide
      if (this.DOMstrings.circular) {
        activeStageNum =
          activeStageNum === PanelsSize ? 0 : (activeStageNum += 1)
      } else {
        activeStageNum += activeStageNum === PanelsSize ? 0 : 1
      }
    }
    this.setActiveStep(activeStageNum)
    this.setActiveStage(activeStageNum)
  }

  /**
   * Define os comportamentos do componente
   * @private
   */
  _setBehavior() {
    this.DOMstrings.carouselNextBtn.addEventListener('click', () => {
      this.shiftPage(1)
    })

    this.DOMstrings.carouselPrevBtn.addEventListener('click', () => {
      this.shiftPage(-1)
    })

    this.DOMstrings.step.addEventListener('click', () => {
      this.setActiveStage(this.step.activeStepNum)
    })

    // Swipe
    const dispatcher = new _partial_js_behavior_swipe__WEBPACK_IMPORTED_MODULE_1__/* ["default"] */ .Z(this.DOMstrings.carouselStage)
    dispatcher.on('SWIPE_LEFT', () => {
      this.shiftPage(1)
    })
    dispatcher.on('SWIPE_RIGHT', () => {
      this.shiftPage(-1)
    })
    this.disabledBtns()
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRCarousel);


/***/ }),

/***/ "./src/components/checkbox/checkbox.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_checkgroup__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/checkgroup.js");


/** Classe para instanciar um objeto BRCheckbox*/
class BRCheckbox {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    this._setCheckgroupBehavior()
  }

  /**
   * Define comportamentos do checkgroup
   * @private
   */
  _setCheckgroupBehavior() {
    this.component
      .querySelectorAll('input[type="checkbox"][data-parent]')
      .forEach((trigger) => {
        const checkgroup = new _partial_js_behavior_checkgroup__WEBPACK_IMPORTED_MODULE_0__["default"](trigger)
        checkgroup.setBehavior()
      })
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRCheckbox);


/***/ }),

/***/ "./src/components/datetimepicker/datetimepicker.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  Z: () => (/* binding */ datetimepicker)
});

;// CONCATENATED MODULE: ./node_modules/flatpickr/dist/esm/types/options.js
var HOOKS = [
    "onChange",
    "onClose",
    "onDayCreate",
    "onDestroy",
    "onKeyDown",
    "onMonthChange",
    "onOpen",
    "onParseConfig",
    "onReady",
    "onValueUpdate",
    "onYearChange",
    "onPreCalendarPosition",
];
var defaults = {
    _disable: [],
    allowInput: false,
    allowInvalidPreload: false,
    altFormat: "F j, Y",
    altInput: false,
    altInputClass: "form-control input",
    animate: typeof window === "object" &&
        window.navigator.userAgent.indexOf("MSIE") === -1,
    ariaDateFormat: "F j, Y",
    autoFillDefaultTime: true,
    clickOpens: true,
    closeOnSelect: true,
    conjunction: ", ",
    dateFormat: "Y-m-d",
    defaultHour: 12,
    defaultMinute: 0,
    defaultSeconds: 0,
    disable: [],
    disableMobile: false,
    enableSeconds: false,
    enableTime: false,
    errorHandler: function (err) {
        return typeof console !== "undefined" && console.warn(err);
    },
    getWeek: function (givenDate) {
        var date = new Date(givenDate.getTime());
        date.setHours(0, 0, 0, 0);
        date.setDate(date.getDate() + 3 - ((date.getDay() + 6) % 7));
        var week1 = new Date(date.getFullYear(), 0, 4);
        return (1 +
            Math.round(((date.getTime() - week1.getTime()) / 86400000 -
                3 +
                ((week1.getDay() + 6) % 7)) /
                7));
    },
    hourIncrement: 1,
    ignoredFocusElements: [],
    inline: false,
    locale: "default",
    minuteIncrement: 5,
    mode: "single",
    monthSelectorType: "dropdown",
    nextArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z' /></svg>",
    noCalendar: false,
    now: new Date(),
    onChange: [],
    onClose: [],
    onDayCreate: [],
    onDestroy: [],
    onKeyDown: [],
    onMonthChange: [],
    onOpen: [],
    onParseConfig: [],
    onReady: [],
    onValueUpdate: [],
    onYearChange: [],
    onPreCalendarPosition: [],
    plugins: [],
    position: "auto",
    positionElement: undefined,
    prevArrow: "<svg version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' viewBox='0 0 17 17'><g></g><path d='M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z' /></svg>",
    shorthandCurrentMonth: false,
    showMonths: 1,
    static: false,
    time_24hr: false,
    weekNumbers: false,
    wrap: false,
};

;// CONCATENATED MODULE: ./node_modules/flatpickr/dist/esm/l10n/default.js
var english = {
    weekdays: {
        shorthand: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        longhand: [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ],
    },
    months: {
        shorthand: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
        longhand: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
    },
    daysInMonth: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
    firstDayOfWeek: 0,
    ordinal: function (nth) {
        var s = nth % 100;
        if (s > 3 && s < 21)
            return "th";
        switch (s % 10) {
            case 1:
                return "st";
            case 2:
                return "nd";
            case 3:
                return "rd";
            default:
                return "th";
        }
    },
    rangeSeparator: " to ",
    weekAbbreviation: "Wk",
    scrollTitle: "Scroll to increment",
    toggleTitle: "Click to toggle",
    amPM: ["AM", "PM"],
    yearAriaLabel: "Year",
    monthAriaLabel: "Month",
    hourAriaLabel: "Hour",
    minuteAriaLabel: "Minute",
    time_24hr: false,
};
/* harmony default export */ const l10n_default = (english);

;// CONCATENATED MODULE: ./node_modules/flatpickr/dist/esm/utils/index.js
var pad = function (number, length) {
    if (length === void 0) { length = 2; }
    return ("000" + number).slice(length * -1);
};
var utils_int = function (bool) { return (bool === true ? 1 : 0); };
function debounce(fn, wait) {
    var t;
    return function () {
        var _this = this;
        var args = arguments;
        clearTimeout(t);
        t = setTimeout(function () { return fn.apply(_this, args); }, wait);
    };
}
var arrayify = function (obj) {
    return obj instanceof Array ? obj : [obj];
};

;// CONCATENATED MODULE: ./node_modules/flatpickr/dist/esm/utils/dom.js
function toggleClass(elem, className, bool) {
    if (bool === true)
        return elem.classList.add(className);
    elem.classList.remove(className);
}
function createElement(tag, className, content) {
    var e = window.document.createElement(tag);
    className = className || "";
    content = content || "";
    e.className = className;
    if (content !== undefined)
        e.textContent = content;
    return e;
}
function clearNode(node) {
    while (node.firstChild)
        node.removeChild(node.firstChild);
}
function findParent(node, condition) {
    if (condition(node))
        return node;
    else if (node.parentNode)
        return findParent(node.parentNode, condition);
    return undefined;
}
function createNumberInput(inputClassName, opts) {
    var wrapper = createElement("div", "numInputWrapper"), numInput = createElement("input", "numInput " + inputClassName), arrowUp = createElement("span", "arrowUp"), arrowDown = createElement("span", "arrowDown");
    if (navigator.userAgent.indexOf("MSIE 9.0") === -1) {
        numInput.type = "number";
    }
    else {
        numInput.type = "text";
        numInput.pattern = "\\d*";
    }
    if (opts !== undefined)
        for (var key in opts)
            numInput.setAttribute(key, opts[key]);
    wrapper.appendChild(numInput);
    wrapper.appendChild(arrowUp);
    wrapper.appendChild(arrowDown);
    return wrapper;
}
function getEventTarget(event) {
    try {
        if (typeof event.composedPath === "function") {
            var path = event.composedPath();
            return path[0];
        }
        return event.target;
    }
    catch (error) {
        return event.target;
    }
}

;// CONCATENATED MODULE: ./node_modules/flatpickr/dist/esm/utils/formatting.js

var doNothing = function () { return undefined; };
var monthToStr = function (monthNumber, shorthand, locale) { return locale.months[shorthand ? "shorthand" : "longhand"][monthNumber]; };
var revFormat = {
    D: doNothing,
    F: function (dateObj, monthName, locale) {
        dateObj.setMonth(locale.months.longhand.indexOf(monthName));
    },
    G: function (dateObj, hour) {
        dateObj.setHours((dateObj.getHours() >= 12 ? 12 : 0) + parseFloat(hour));
    },
    H: function (dateObj, hour) {
        dateObj.setHours(parseFloat(hour));
    },
    J: function (dateObj, day) {
        dateObj.setDate(parseFloat(day));
    },
    K: function (dateObj, amPM, locale) {
        dateObj.setHours((dateObj.getHours() % 12) +
            12 * utils_int(new RegExp(locale.amPM[1], "i").test(amPM)));
    },
    M: function (dateObj, shortMonth, locale) {
        dateObj.setMonth(locale.months.shorthand.indexOf(shortMonth));
    },
    S: function (dateObj, seconds) {
        dateObj.setSeconds(parseFloat(seconds));
    },
    U: function (_, unixSeconds) { return new Date(parseFloat(unixSeconds) * 1000); },
    W: function (dateObj, weekNum, locale) {
        var weekNumber = parseInt(weekNum);
        var date = new Date(dateObj.getFullYear(), 0, 2 + (weekNumber - 1) * 7, 0, 0, 0, 0);
        date.setDate(date.getDate() - date.getDay() + locale.firstDayOfWeek);
        return date;
    },
    Y: function (dateObj, year) {
        dateObj.setFullYear(parseFloat(year));
    },
    Z: function (_, ISODate) { return new Date(ISODate); },
    d: function (dateObj, day) {
        dateObj.setDate(parseFloat(day));
    },
    h: function (dateObj, hour) {
        dateObj.setHours((dateObj.getHours() >= 12 ? 12 : 0) + parseFloat(hour));
    },
    i: function (dateObj, minutes) {
        dateObj.setMinutes(parseFloat(minutes));
    },
    j: function (dateObj, day) {
        dateObj.setDate(parseFloat(day));
    },
    l: doNothing,
    m: function (dateObj, month) {
        dateObj.setMonth(parseFloat(month) - 1);
    },
    n: function (dateObj, month) {
        dateObj.setMonth(parseFloat(month) - 1);
    },
    s: function (dateObj, seconds) {
        dateObj.setSeconds(parseFloat(seconds));
    },
    u: function (_, unixMillSeconds) {
        return new Date(parseFloat(unixMillSeconds));
    },
    w: doNothing,
    y: function (dateObj, year) {
        dateObj.setFullYear(2000 + parseFloat(year));
    },
};
var tokenRegex = {
    D: "",
    F: "",
    G: "(\\d\\d|\\d)",
    H: "(\\d\\d|\\d)",
    J: "(\\d\\d|\\d)\\w+",
    K: "",
    M: "",
    S: "(\\d\\d|\\d)",
    U: "(.+)",
    W: "(\\d\\d|\\d)",
    Y: "(\\d{4})",
    Z: "(.+)",
    d: "(\\d\\d|\\d)",
    h: "(\\d\\d|\\d)",
    i: "(\\d\\d|\\d)",
    j: "(\\d\\d|\\d)",
    l: "",
    m: "(\\d\\d|\\d)",
    n: "(\\d\\d|\\d)",
    s: "(\\d\\d|\\d)",
    u: "(.+)",
    w: "(\\d\\d|\\d)",
    y: "(\\d{2})",
};
var formats = {
    Z: function (date) { return date.toISOString(); },
    D: function (date, locale, options) {
        return locale.weekdays.shorthand[formats.w(date, locale, options)];
    },
    F: function (date, locale, options) {
        return monthToStr(formats.n(date, locale, options) - 1, false, locale);
    },
    G: function (date, locale, options) {
        return pad(formats.h(date, locale, options));
    },
    H: function (date) { return pad(date.getHours()); },
    J: function (date, locale) {
        return locale.ordinal !== undefined
            ? date.getDate() + locale.ordinal(date.getDate())
            : date.getDate();
    },
    K: function (date, locale) { return locale.amPM[utils_int(date.getHours() > 11)]; },
    M: function (date, locale) {
        return monthToStr(date.getMonth(), true, locale);
    },
    S: function (date) { return pad(date.getSeconds()); },
    U: function (date) { return date.getTime() / 1000; },
    W: function (date, _, options) {
        return options.getWeek(date);
    },
    Y: function (date) { return pad(date.getFullYear(), 4); },
    d: function (date) { return pad(date.getDate()); },
    h: function (date) { return (date.getHours() % 12 ? date.getHours() % 12 : 12); },
    i: function (date) { return pad(date.getMinutes()); },
    j: function (date) { return date.getDate(); },
    l: function (date, locale) {
        return locale.weekdays.longhand[date.getDay()];
    },
    m: function (date) { return pad(date.getMonth() + 1); },
    n: function (date) { return date.getMonth() + 1; },
    s: function (date) { return date.getSeconds(); },
    u: function (date) { return date.getTime(); },
    w: function (date) { return date.getDay(); },
    y: function (date) { return String(date.getFullYear()).substring(2); },
};

;// CONCATENATED MODULE: ./node_modules/flatpickr/dist/esm/utils/dates.js



var createDateFormatter = function (_a) {
    var _b = _a.config, config = _b === void 0 ? defaults : _b, _c = _a.l10n, l10n = _c === void 0 ? english : _c, _d = _a.isMobile, isMobile = _d === void 0 ? false : _d;
    return function (dateObj, frmt, overrideLocale) {
        var locale = overrideLocale || l10n;
        if (config.formatDate !== undefined && !isMobile) {
            return config.formatDate(dateObj, frmt, locale);
        }
        return frmt
            .split("")
            .map(function (c, i, arr) {
            return formats[c] && arr[i - 1] !== "\\"
                ? formats[c](dateObj, locale, config)
                : c !== "\\"
                    ? c
                    : "";
        })
            .join("");
    };
};
var createDateParser = function (_a) {
    var _b = _a.config, config = _b === void 0 ? defaults : _b, _c = _a.l10n, l10n = _c === void 0 ? english : _c;
    return function (date, givenFormat, timeless, customLocale) {
        if (date !== 0 && !date)
            return undefined;
        var locale = customLocale || l10n;
        var parsedDate;
        var dateOrig = date;
        if (date instanceof Date)
            parsedDate = new Date(date.getTime());
        else if (typeof date !== "string" &&
            date.toFixed !== undefined)
            parsedDate = new Date(date);
        else if (typeof date === "string") {
            var format = givenFormat || (config || defaults).dateFormat;
            var datestr = String(date).trim();
            if (datestr === "today") {
                parsedDate = new Date();
                timeless = true;
            }
            else if (config && config.parseDate) {
                parsedDate = config.parseDate(date, format);
            }
            else if (/Z$/.test(datestr) ||
                /GMT$/.test(datestr)) {
                parsedDate = new Date(date);
            }
            else {
                var matched = void 0, ops = [];
                for (var i = 0, matchIndex = 0, regexStr = ""; i < format.length; i++) {
                    var token = format[i];
                    var isBackSlash = token === "\\";
                    var escaped = format[i - 1] === "\\" || isBackSlash;
                    if (tokenRegex[token] && !escaped) {
                        regexStr += tokenRegex[token];
                        var match = new RegExp(regexStr).exec(date);
                        if (match && (matched = true)) {
                            ops[token !== "Y" ? "push" : "unshift"]({
                                fn: revFormat[token],
                                val: match[++matchIndex],
                            });
                        }
                    }
                    else if (!isBackSlash)
                        regexStr += ".";
                }
                parsedDate =
                    !config || !config.noCalendar
                        ? new Date(new Date().getFullYear(), 0, 1, 0, 0, 0, 0)
                        : new Date(new Date().setHours(0, 0, 0, 0));
                ops.forEach(function (_a) {
                    var fn = _a.fn, val = _a.val;
                    return (parsedDate = fn(parsedDate, val, locale) || parsedDate);
                });
                parsedDate = matched ? parsedDate : undefined;
            }
        }
        if (!(parsedDate instanceof Date && !isNaN(parsedDate.getTime()))) {
            config.errorHandler(new Error("Invalid date provided: " + dateOrig));
            return undefined;
        }
        if (timeless === true)
            parsedDate.setHours(0, 0, 0, 0);
        return parsedDate;
    };
};
function compareDates(date1, date2, timeless) {
    if (timeless === void 0) { timeless = true; }
    if (timeless !== false) {
        return (new Date(date1.getTime()).setHours(0, 0, 0, 0) -
            new Date(date2.getTime()).setHours(0, 0, 0, 0));
    }
    return date1.getTime() - date2.getTime();
}
function compareTimes(date1, date2) {
    return (3600 * (date1.getHours() - date2.getHours()) +
        60 * (date1.getMinutes() - date2.getMinutes()) +
        date1.getSeconds() -
        date2.getSeconds());
}
var isBetween = function (ts, ts1, ts2) {
    return ts > Math.min(ts1, ts2) && ts < Math.max(ts1, ts2);
};
var calculateSecondsSinceMidnight = function (hours, minutes, seconds) {
    return hours * 3600 + minutes * 60 + seconds;
};
var parseSeconds = function (secondsSinceMidnight) {
    var hours = Math.floor(secondsSinceMidnight / 3600), minutes = (secondsSinceMidnight - hours * 3600) / 60;
    return [hours, minutes, secondsSinceMidnight - hours * 3600 - minutes * 60];
};
var duration = {
    DAY: 86400000,
};
function getDefaultHours(config) {
    var hours = config.defaultHour;
    var minutes = config.defaultMinute;
    var seconds = config.defaultSeconds;
    if (config.minDate !== undefined) {
        var minHour = config.minDate.getHours();
        var minMinutes = config.minDate.getMinutes();
        var minSeconds = config.minDate.getSeconds();
        if (hours < minHour) {
            hours = minHour;
        }
        if (hours === minHour && minutes < minMinutes) {
            minutes = minMinutes;
        }
        if (hours === minHour && minutes === minMinutes && seconds < minSeconds)
            seconds = config.minDate.getSeconds();
    }
    if (config.maxDate !== undefined) {
        var maxHr = config.maxDate.getHours();
        var maxMinutes = config.maxDate.getMinutes();
        hours = Math.min(hours, maxHr);
        if (hours === maxHr)
            minutes = Math.min(maxMinutes, minutes);
        if (hours === maxHr && minutes === maxMinutes)
            seconds = config.maxDate.getSeconds();
    }
    return { hours: hours, minutes: minutes, seconds: seconds };
}

// EXTERNAL MODULE: ./node_modules/flatpickr/dist/esm/utils/polyfills.js
var polyfills = __webpack_require__("./node_modules/flatpickr/dist/esm/utils/polyfills.js");
;// CONCATENATED MODULE: ./node_modules/flatpickr/dist/esm/index.js
var __assign = (undefined && undefined.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __spreadArrays = (undefined && undefined.__spreadArrays) || function () {
    for (var s = 0, i = 0, il = arguments.length; i < il; i++) s += arguments[i].length;
    for (var r = Array(s), k = 0, i = 0; i < il; i++)
        for (var a = arguments[i], j = 0, jl = a.length; j < jl; j++, k++)
            r[k] = a[j];
    return r;
};







var DEBOUNCED_CHANGE_MS = 300;
function FlatpickrInstance(element, instanceConfig) {
    var self = {
        config: __assign(__assign({}, defaults), flatpickr.defaultConfig),
        l10n: l10n_default,
    };
    self.parseDate = createDateParser({ config: self.config, l10n: self.l10n });
    self._handlers = [];
    self.pluginElements = [];
    self.loadedPlugins = [];
    self._bind = bind;
    self._setHoursFromDate = setHoursFromDate;
    self._positionCalendar = positionCalendar;
    self.changeMonth = changeMonth;
    self.changeYear = changeYear;
    self.clear = clear;
    self.close = close;
    self.onMouseOver = onMouseOver;
    self._createElement = createElement;
    self.createDay = createDay;
    self.destroy = destroy;
    self.isEnabled = isEnabled;
    self.jumpToDate = jumpToDate;
    self.updateValue = updateValue;
    self.open = open;
    self.redraw = redraw;
    self.set = set;
    self.setDate = setDate;
    self.toggle = toggle;
    function setupHelperFunctions() {
        self.utils = {
            getDaysInMonth: function (month, yr) {
                if (month === void 0) { month = self.currentMonth; }
                if (yr === void 0) { yr = self.currentYear; }
                if (month === 1 && ((yr % 4 === 0 && yr % 100 !== 0) || yr % 400 === 0))
                    return 29;
                return self.l10n.daysInMonth[month];
            },
        };
    }
    function init() {
        self.element = self.input = element;
        self.isOpen = false;
        parseConfig();
        setupLocale();
        setupInputs();
        setupDates();
        setupHelperFunctions();
        if (!self.isMobile)
            build();
        bindEvents();
        if (self.selectedDates.length || self.config.noCalendar) {
            if (self.config.enableTime) {
                setHoursFromDate(self.config.noCalendar ? self.latestSelectedDateObj : undefined);
            }
            updateValue(false);
        }
        setCalendarWidth();
        var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        if (!self.isMobile && isSafari) {
            positionCalendar();
        }
        triggerEvent("onReady");
    }
    function getClosestActiveElement() {
        var _a;
        return (((_a = self.calendarContainer) === null || _a === void 0 ? void 0 : _a.getRootNode())
            .activeElement || document.activeElement);
    }
    function bindToInstance(fn) {
        return fn.bind(self);
    }
    function setCalendarWidth() {
        var config = self.config;
        if (config.weekNumbers === false && config.showMonths === 1) {
            return;
        }
        else if (config.noCalendar !== true) {
            window.requestAnimationFrame(function () {
                if (self.calendarContainer !== undefined) {
                    self.calendarContainer.style.visibility = "hidden";
                    self.calendarContainer.style.display = "block";
                }
                if (self.daysContainer !== undefined) {
                    var daysWidth = (self.days.offsetWidth + 1) * config.showMonths;
                    self.daysContainer.style.width = daysWidth + "px";
                    self.calendarContainer.style.width =
                        daysWidth +
                            (self.weekWrapper !== undefined
                                ? self.weekWrapper.offsetWidth
                                : 0) +
                            "px";
                    self.calendarContainer.style.removeProperty("visibility");
                    self.calendarContainer.style.removeProperty("display");
                }
            });
        }
    }
    function updateTime(e) {
        if (self.selectedDates.length === 0) {
            var defaultDate = self.config.minDate === undefined ||
                compareDates(new Date(), self.config.minDate) >= 0
                ? new Date()
                : new Date(self.config.minDate.getTime());
            var defaults = getDefaultHours(self.config);
            defaultDate.setHours(defaults.hours, defaults.minutes, defaults.seconds, defaultDate.getMilliseconds());
            self.selectedDates = [defaultDate];
            self.latestSelectedDateObj = defaultDate;
        }
        if (e !== undefined && e.type !== "blur") {
            timeWrapper(e);
        }
        var prevValue = self._input.value;
        setHoursFromInputs();
        updateValue();
        if (self._input.value !== prevValue) {
            self._debouncedChange();
        }
    }
    function ampm2military(hour, amPM) {
        return (hour % 12) + 12 * utils_int(amPM === self.l10n.amPM[1]);
    }
    function military2ampm(hour) {
        switch (hour % 24) {
            case 0:
            case 12:
                return 12;
            default:
                return hour % 12;
        }
    }
    function setHoursFromInputs() {
        if (self.hourElement === undefined || self.minuteElement === undefined)
            return;
        var hours = (parseInt(self.hourElement.value.slice(-2), 10) || 0) % 24, minutes = (parseInt(self.minuteElement.value, 10) || 0) % 60, seconds = self.secondElement !== undefined
            ? (parseInt(self.secondElement.value, 10) || 0) % 60
            : 0;
        if (self.amPM !== undefined) {
            hours = ampm2military(hours, self.amPM.textContent);
        }
        var limitMinHours = self.config.minTime !== undefined ||
            (self.config.minDate &&
                self.minDateHasTime &&
                self.latestSelectedDateObj &&
                compareDates(self.latestSelectedDateObj, self.config.minDate, true) ===
                    0);
        var limitMaxHours = self.config.maxTime !== undefined ||
            (self.config.maxDate &&
                self.maxDateHasTime &&
                self.latestSelectedDateObj &&
                compareDates(self.latestSelectedDateObj, self.config.maxDate, true) ===
                    0);
        if (self.config.maxTime !== undefined &&
            self.config.minTime !== undefined &&
            self.config.minTime > self.config.maxTime) {
            var minBound = calculateSecondsSinceMidnight(self.config.minTime.getHours(), self.config.minTime.getMinutes(), self.config.minTime.getSeconds());
            var maxBound = calculateSecondsSinceMidnight(self.config.maxTime.getHours(), self.config.maxTime.getMinutes(), self.config.maxTime.getSeconds());
            var currentTime = calculateSecondsSinceMidnight(hours, minutes, seconds);
            if (currentTime > maxBound && currentTime < minBound) {
                var result = parseSeconds(minBound);
                hours = result[0];
                minutes = result[1];
                seconds = result[2];
            }
        }
        else {
            if (limitMaxHours) {
                var maxTime = self.config.maxTime !== undefined
                    ? self.config.maxTime
                    : self.config.maxDate;
                hours = Math.min(hours, maxTime.getHours());
                if (hours === maxTime.getHours())
                    minutes = Math.min(minutes, maxTime.getMinutes());
                if (minutes === maxTime.getMinutes())
                    seconds = Math.min(seconds, maxTime.getSeconds());
            }
            if (limitMinHours) {
                var minTime = self.config.minTime !== undefined
                    ? self.config.minTime
                    : self.config.minDate;
                hours = Math.max(hours, minTime.getHours());
                if (hours === minTime.getHours() && minutes < minTime.getMinutes())
                    minutes = minTime.getMinutes();
                if (minutes === minTime.getMinutes())
                    seconds = Math.max(seconds, minTime.getSeconds());
            }
        }
        setHours(hours, minutes, seconds);
    }
    function setHoursFromDate(dateObj) {
        var date = dateObj || self.latestSelectedDateObj;
        if (date && date instanceof Date) {
            setHours(date.getHours(), date.getMinutes(), date.getSeconds());
        }
    }
    function setHours(hours, minutes, seconds) {
        if (self.latestSelectedDateObj !== undefined) {
            self.latestSelectedDateObj.setHours(hours % 24, minutes, seconds || 0, 0);
        }
        if (!self.hourElement || !self.minuteElement || self.isMobile)
            return;
        self.hourElement.value = pad(!self.config.time_24hr
            ? ((12 + hours) % 12) + 12 * utils_int(hours % 12 === 0)
            : hours);
        self.minuteElement.value = pad(minutes);
        if (self.amPM !== undefined)
            self.amPM.textContent = self.l10n.amPM[utils_int(hours >= 12)];
        if (self.secondElement !== undefined)
            self.secondElement.value = pad(seconds);
    }
    function onYearInput(event) {
        var eventTarget = getEventTarget(event);
        var year = parseInt(eventTarget.value) + (event.delta || 0);
        if (year / 1000 > 1 ||
            (event.key === "Enter" && !/[^\d]/.test(year.toString()))) {
            changeYear(year);
        }
    }
    function bind(element, event, handler, options) {
        if (event instanceof Array)
            return event.forEach(function (ev) { return bind(element, ev, handler, options); });
        if (element instanceof Array)
            return element.forEach(function (el) { return bind(el, event, handler, options); });
        element.addEventListener(event, handler, options);
        self._handlers.push({
            remove: function () { return element.removeEventListener(event, handler, options); },
        });
    }
    function triggerChange() {
        triggerEvent("onChange");
    }
    function bindEvents() {
        if (self.config.wrap) {
            ["open", "close", "toggle", "clear"].forEach(function (evt) {
                Array.prototype.forEach.call(self.element.querySelectorAll("[data-" + evt + "]"), function (el) {
                    return bind(el, "click", self[evt]);
                });
            });
        }
        if (self.isMobile) {
            setupMobile();
            return;
        }
        var debouncedResize = debounce(onResize, 50);
        self._debouncedChange = debounce(triggerChange, DEBOUNCED_CHANGE_MS);
        if (self.daysContainer && !/iPhone|iPad|iPod/i.test(navigator.userAgent))
            bind(self.daysContainer, "mouseover", function (e) {
                if (self.config.mode === "range")
                    onMouseOver(getEventTarget(e));
            });
        bind(self._input, "keydown", onKeyDown);
        if (self.calendarContainer !== undefined) {
            bind(self.calendarContainer, "keydown", onKeyDown);
        }
        if (!self.config.inline && !self.config.static)
            bind(window, "resize", debouncedResize);
        if (window.ontouchstart !== undefined)
            bind(window.document, "touchstart", documentClick);
        else
            bind(window.document, "mousedown", documentClick);
        bind(window.document, "focus", documentClick, { capture: true });
        if (self.config.clickOpens === true) {
            bind(self._input, "focus", self.open);
            bind(self._input, "click", self.open);
        }
        if (self.daysContainer !== undefined) {
            bind(self.monthNav, "click", onMonthNavClick);
            bind(self.monthNav, ["keyup", "increment"], onYearInput);
            bind(self.daysContainer, "click", selectDate);
        }
        if (self.timeContainer !== undefined &&
            self.minuteElement !== undefined &&
            self.hourElement !== undefined) {
            var selText = function (e) {
                return getEventTarget(e).select();
            };
            bind(self.timeContainer, ["increment"], updateTime);
            bind(self.timeContainer, "blur", updateTime, { capture: true });
            bind(self.timeContainer, "click", timeIncrement);
            bind([self.hourElement, self.minuteElement], ["focus", "click"], selText);
            if (self.secondElement !== undefined)
                bind(self.secondElement, "focus", function () { return self.secondElement && self.secondElement.select(); });
            if (self.amPM !== undefined) {
                bind(self.amPM, "click", function (e) {
                    updateTime(e);
                });
            }
        }
        if (self.config.allowInput) {
            bind(self._input, "blur", onBlur);
        }
    }
    function jumpToDate(jumpDate, triggerChange) {
        var jumpTo = jumpDate !== undefined
            ? self.parseDate(jumpDate)
            : self.latestSelectedDateObj ||
                (self.config.minDate && self.config.minDate > self.now
                    ? self.config.minDate
                    : self.config.maxDate && self.config.maxDate < self.now
                        ? self.config.maxDate
                        : self.now);
        var oldYear = self.currentYear;
        var oldMonth = self.currentMonth;
        try {
            if (jumpTo !== undefined) {
                self.currentYear = jumpTo.getFullYear();
                self.currentMonth = jumpTo.getMonth();
            }
        }
        catch (e) {
            e.message = "Invalid date supplied: " + jumpTo;
            self.config.errorHandler(e);
        }
        if (triggerChange && self.currentYear !== oldYear) {
            triggerEvent("onYearChange");
            buildMonthSwitch();
        }
        if (triggerChange &&
            (self.currentYear !== oldYear || self.currentMonth !== oldMonth)) {
            triggerEvent("onMonthChange");
        }
        self.redraw();
    }
    function timeIncrement(e) {
        var eventTarget = getEventTarget(e);
        if (~eventTarget.className.indexOf("arrow"))
            incrementNumInput(e, eventTarget.classList.contains("arrowUp") ? 1 : -1);
    }
    function incrementNumInput(e, delta, inputElem) {
        var target = e && getEventTarget(e);
        var input = inputElem ||
            (target && target.parentNode && target.parentNode.firstChild);
        var event = createEvent("increment");
        event.delta = delta;
        input && input.dispatchEvent(event);
    }
    function build() {
        var fragment = window.document.createDocumentFragment();
        self.calendarContainer = createElement("div", "flatpickr-calendar");
        self.calendarContainer.tabIndex = -1;
        if (!self.config.noCalendar) {
            fragment.appendChild(buildMonthNav());
            self.innerContainer = createElement("div", "flatpickr-innerContainer");
            if (self.config.weekNumbers) {
                var _a = buildWeeks(), weekWrapper = _a.weekWrapper, weekNumbers = _a.weekNumbers;
                self.innerContainer.appendChild(weekWrapper);
                self.weekNumbers = weekNumbers;
                self.weekWrapper = weekWrapper;
            }
            self.rContainer = createElement("div", "flatpickr-rContainer");
            self.rContainer.appendChild(buildWeekdays());
            if (!self.daysContainer) {
                self.daysContainer = createElement("div", "flatpickr-days");
                self.daysContainer.tabIndex = -1;
            }
            buildDays();
            self.rContainer.appendChild(self.daysContainer);
            self.innerContainer.appendChild(self.rContainer);
            fragment.appendChild(self.innerContainer);
        }
        if (self.config.enableTime) {
            fragment.appendChild(buildTime());
        }
        toggleClass(self.calendarContainer, "rangeMode", self.config.mode === "range");
        toggleClass(self.calendarContainer, "animate", self.config.animate === true);
        toggleClass(self.calendarContainer, "multiMonth", self.config.showMonths > 1);
        self.calendarContainer.appendChild(fragment);
        var customAppend = self.config.appendTo !== undefined &&
            self.config.appendTo.nodeType !== undefined;
        if (self.config.inline || self.config.static) {
            self.calendarContainer.classList.add(self.config.inline ? "inline" : "static");
            if (self.config.inline) {
                if (!customAppend && self.element.parentNode)
                    self.element.parentNode.insertBefore(self.calendarContainer, self._input.nextSibling);
                else if (self.config.appendTo !== undefined)
                    self.config.appendTo.appendChild(self.calendarContainer);
            }
            if (self.config.static) {
                var wrapper = createElement("div", "flatpickr-wrapper");
                if (self.element.parentNode)
                    self.element.parentNode.insertBefore(wrapper, self.element);
                wrapper.appendChild(self.element);
                if (self.altInput)
                    wrapper.appendChild(self.altInput);
                wrapper.appendChild(self.calendarContainer);
            }
        }
        if (!self.config.static && !self.config.inline)
            (self.config.appendTo !== undefined
                ? self.config.appendTo
                : window.document.body).appendChild(self.calendarContainer);
    }
    function createDay(className, date, _dayNumber, i) {
        var dateIsEnabled = isEnabled(date, true), dayElement = createElement("span", className, date.getDate().toString());
        dayElement.dateObj = date;
        dayElement.$i = i;
        dayElement.setAttribute("aria-label", self.formatDate(date, self.config.ariaDateFormat));
        if (className.indexOf("hidden") === -1 &&
            compareDates(date, self.now) === 0) {
            self.todayDateElem = dayElement;
            dayElement.classList.add("today");
            dayElement.setAttribute("aria-current", "date");
        }
        if (dateIsEnabled) {
            dayElement.tabIndex = -1;
            if (isDateSelected(date)) {
                dayElement.classList.add("selected");
                self.selectedDateElem = dayElement;
                if (self.config.mode === "range") {
                    toggleClass(dayElement, "startRange", self.selectedDates[0] &&
                        compareDates(date, self.selectedDates[0], true) === 0);
                    toggleClass(dayElement, "endRange", self.selectedDates[1] &&
                        compareDates(date, self.selectedDates[1], true) === 0);
                    if (className === "nextMonthDay")
                        dayElement.classList.add("inRange");
                }
            }
        }
        else {
            dayElement.classList.add("flatpickr-disabled");
        }
        if (self.config.mode === "range") {
            if (isDateInRange(date) && !isDateSelected(date))
                dayElement.classList.add("inRange");
        }
        if (self.weekNumbers &&
            self.config.showMonths === 1 &&
            className !== "prevMonthDay" &&
            i % 7 === 6) {
            self.weekNumbers.insertAdjacentHTML("beforeend", "<span class='flatpickr-day'>" + self.config.getWeek(date) + "</span>");
        }
        triggerEvent("onDayCreate", dayElement);
        return dayElement;
    }
    function focusOnDayElem(targetNode) {
        targetNode.focus();
        if (self.config.mode === "range")
            onMouseOver(targetNode);
    }
    function getFirstAvailableDay(delta) {
        var startMonth = delta > 0 ? 0 : self.config.showMonths - 1;
        var endMonth = delta > 0 ? self.config.showMonths : -1;
        for (var m = startMonth; m != endMonth; m += delta) {
            var month = self.daysContainer.children[m];
            var startIndex = delta > 0 ? 0 : month.children.length - 1;
            var endIndex = delta > 0 ? month.children.length : -1;
            for (var i = startIndex; i != endIndex; i += delta) {
                var c = month.children[i];
                if (c.className.indexOf("hidden") === -1 && isEnabled(c.dateObj))
                    return c;
            }
        }
        return undefined;
    }
    function getNextAvailableDay(current, delta) {
        var givenMonth = current.className.indexOf("Month") === -1
            ? current.dateObj.getMonth()
            : self.currentMonth;
        var endMonth = delta > 0 ? self.config.showMonths : -1;
        var loopDelta = delta > 0 ? 1 : -1;
        for (var m = givenMonth - self.currentMonth; m != endMonth; m += loopDelta) {
            var month = self.daysContainer.children[m];
            var startIndex = givenMonth - self.currentMonth === m
                ? current.$i + delta
                : delta < 0
                    ? month.children.length - 1
                    : 0;
            var numMonthDays = month.children.length;
            for (var i = startIndex; i >= 0 && i < numMonthDays && i != (delta > 0 ? numMonthDays : -1); i += loopDelta) {
                var c = month.children[i];
                if (c.className.indexOf("hidden") === -1 &&
                    isEnabled(c.dateObj) &&
                    Math.abs(current.$i - i) >= Math.abs(delta))
                    return focusOnDayElem(c);
            }
        }
        self.changeMonth(loopDelta);
        focusOnDay(getFirstAvailableDay(loopDelta), 0);
        return undefined;
    }
    function focusOnDay(current, offset) {
        var activeElement = getClosestActiveElement();
        var dayFocused = isInView(activeElement || document.body);
        var startElem = current !== undefined
            ? current
            : dayFocused
                ? activeElement
                : self.selectedDateElem !== undefined && isInView(self.selectedDateElem)
                    ? self.selectedDateElem
                    : self.todayDateElem !== undefined && isInView(self.todayDateElem)
                        ? self.todayDateElem
                        : getFirstAvailableDay(offset > 0 ? 1 : -1);
        if (startElem === undefined) {
            self._input.focus();
        }
        else if (!dayFocused) {
            focusOnDayElem(startElem);
        }
        else {
            getNextAvailableDay(startElem, offset);
        }
    }
    function buildMonthDays(year, month) {
        var firstOfMonth = (new Date(year, month, 1).getDay() - self.l10n.firstDayOfWeek + 7) % 7;
        var prevMonthDays = self.utils.getDaysInMonth((month - 1 + 12) % 12, year);
        var daysInMonth = self.utils.getDaysInMonth(month, year), days = window.document.createDocumentFragment(), isMultiMonth = self.config.showMonths > 1, prevMonthDayClass = isMultiMonth ? "prevMonthDay hidden" : "prevMonthDay", nextMonthDayClass = isMultiMonth ? "nextMonthDay hidden" : "nextMonthDay";
        var dayNumber = prevMonthDays + 1 - firstOfMonth, dayIndex = 0;
        for (; dayNumber <= prevMonthDays; dayNumber++, dayIndex++) {
            days.appendChild(createDay("flatpickr-day " + prevMonthDayClass, new Date(year, month - 1, dayNumber), dayNumber, dayIndex));
        }
        for (dayNumber = 1; dayNumber <= daysInMonth; dayNumber++, dayIndex++) {
            days.appendChild(createDay("flatpickr-day", new Date(year, month, dayNumber), dayNumber, dayIndex));
        }
        for (var dayNum = daysInMonth + 1; dayNum <= 42 - firstOfMonth &&
            (self.config.showMonths === 1 || dayIndex % 7 !== 0); dayNum++, dayIndex++) {
            days.appendChild(createDay("flatpickr-day " + nextMonthDayClass, new Date(year, month + 1, dayNum % daysInMonth), dayNum, dayIndex));
        }
        var dayContainer = createElement("div", "dayContainer");
        dayContainer.appendChild(days);
        return dayContainer;
    }
    function buildDays() {
        if (self.daysContainer === undefined) {
            return;
        }
        clearNode(self.daysContainer);
        if (self.weekNumbers)
            clearNode(self.weekNumbers);
        var frag = document.createDocumentFragment();
        for (var i = 0; i < self.config.showMonths; i++) {
            var d = new Date(self.currentYear, self.currentMonth, 1);
            d.setMonth(self.currentMonth + i);
            frag.appendChild(buildMonthDays(d.getFullYear(), d.getMonth()));
        }
        self.daysContainer.appendChild(frag);
        self.days = self.daysContainer.firstChild;
        if (self.config.mode === "range" && self.selectedDates.length === 1) {
            onMouseOver();
        }
    }
    function buildMonthSwitch() {
        if (self.config.showMonths > 1 ||
            self.config.monthSelectorType !== "dropdown")
            return;
        var shouldBuildMonth = function (month) {
            if (self.config.minDate !== undefined &&
                self.currentYear === self.config.minDate.getFullYear() &&
                month < self.config.minDate.getMonth()) {
                return false;
            }
            return !(self.config.maxDate !== undefined &&
                self.currentYear === self.config.maxDate.getFullYear() &&
                month > self.config.maxDate.getMonth());
        };
        self.monthsDropdownContainer.tabIndex = -1;
        self.monthsDropdownContainer.innerHTML = "";
        for (var i = 0; i < 12; i++) {
            if (!shouldBuildMonth(i))
                continue;
            var month = createElement("option", "flatpickr-monthDropdown-month");
            month.value = new Date(self.currentYear, i).getMonth().toString();
            month.textContent = monthToStr(i, self.config.shorthandCurrentMonth, self.l10n);
            month.tabIndex = -1;
            if (self.currentMonth === i) {
                month.selected = true;
            }
            self.monthsDropdownContainer.appendChild(month);
        }
    }
    function buildMonth() {
        var container = createElement("div", "flatpickr-month");
        var monthNavFragment = window.document.createDocumentFragment();
        var monthElement;
        if (self.config.showMonths > 1 ||
            self.config.monthSelectorType === "static") {
            monthElement = createElement("span", "cur-month");
        }
        else {
            self.monthsDropdownContainer = createElement("select", "flatpickr-monthDropdown-months");
            self.monthsDropdownContainer.setAttribute("aria-label", self.l10n.monthAriaLabel);
            bind(self.monthsDropdownContainer, "change", function (e) {
                var target = getEventTarget(e);
                var selectedMonth = parseInt(target.value, 10);
                self.changeMonth(selectedMonth - self.currentMonth);
                triggerEvent("onMonthChange");
            });
            buildMonthSwitch();
            monthElement = self.monthsDropdownContainer;
        }
        var yearInput = createNumberInput("cur-year", { tabindex: "-1" });
        var yearElement = yearInput.getElementsByTagName("input")[0];
        yearElement.setAttribute("aria-label", self.l10n.yearAriaLabel);
        if (self.config.minDate) {
            yearElement.setAttribute("min", self.config.minDate.getFullYear().toString());
        }
        if (self.config.maxDate) {
            yearElement.setAttribute("max", self.config.maxDate.getFullYear().toString());
            yearElement.disabled =
                !!self.config.minDate &&
                    self.config.minDate.getFullYear() === self.config.maxDate.getFullYear();
        }
        var currentMonth = createElement("div", "flatpickr-current-month");
        currentMonth.appendChild(monthElement);
        currentMonth.appendChild(yearInput);
        monthNavFragment.appendChild(currentMonth);
        container.appendChild(monthNavFragment);
        return {
            container: container,
            yearElement: yearElement,
            monthElement: monthElement,
        };
    }
    function buildMonths() {
        clearNode(self.monthNav);
        self.monthNav.appendChild(self.prevMonthNav);
        if (self.config.showMonths) {
            self.yearElements = [];
            self.monthElements = [];
        }
        for (var m = self.config.showMonths; m--;) {
            var month = buildMonth();
            self.yearElements.push(month.yearElement);
            self.monthElements.push(month.monthElement);
            self.monthNav.appendChild(month.container);
        }
        self.monthNav.appendChild(self.nextMonthNav);
    }
    function buildMonthNav() {
        self.monthNav = createElement("div", "flatpickr-months");
        self.yearElements = [];
        self.monthElements = [];
        self.prevMonthNav = createElement("span", "flatpickr-prev-month");
        self.prevMonthNav.innerHTML = self.config.prevArrow;
        self.nextMonthNav = createElement("span", "flatpickr-next-month");
        self.nextMonthNav.innerHTML = self.config.nextArrow;
        buildMonths();
        Object.defineProperty(self, "_hidePrevMonthArrow", {
            get: function () { return self.__hidePrevMonthArrow; },
            set: function (bool) {
                if (self.__hidePrevMonthArrow !== bool) {
                    toggleClass(self.prevMonthNav, "flatpickr-disabled", bool);
                    self.__hidePrevMonthArrow = bool;
                }
            },
        });
        Object.defineProperty(self, "_hideNextMonthArrow", {
            get: function () { return self.__hideNextMonthArrow; },
            set: function (bool) {
                if (self.__hideNextMonthArrow !== bool) {
                    toggleClass(self.nextMonthNav, "flatpickr-disabled", bool);
                    self.__hideNextMonthArrow = bool;
                }
            },
        });
        self.currentYearElement = self.yearElements[0];
        updateNavigationCurrentMonth();
        return self.monthNav;
    }
    function buildTime() {
        self.calendarContainer.classList.add("hasTime");
        if (self.config.noCalendar)
            self.calendarContainer.classList.add("noCalendar");
        var defaults = getDefaultHours(self.config);
        self.timeContainer = createElement("div", "flatpickr-time");
        self.timeContainer.tabIndex = -1;
        var separator = createElement("span", "flatpickr-time-separator", ":");
        var hourInput = createNumberInput("flatpickr-hour", {
            "aria-label": self.l10n.hourAriaLabel,
        });
        self.hourElement = hourInput.getElementsByTagName("input")[0];
        var minuteInput = createNumberInput("flatpickr-minute", {
            "aria-label": self.l10n.minuteAriaLabel,
        });
        self.minuteElement = minuteInput.getElementsByTagName("input")[0];
        self.hourElement.tabIndex = self.minuteElement.tabIndex = -1;
        self.hourElement.value = pad(self.latestSelectedDateObj
            ? self.latestSelectedDateObj.getHours()
            : self.config.time_24hr
                ? defaults.hours
                : military2ampm(defaults.hours));
        self.minuteElement.value = pad(self.latestSelectedDateObj
            ? self.latestSelectedDateObj.getMinutes()
            : defaults.minutes);
        self.hourElement.setAttribute("step", self.config.hourIncrement.toString());
        self.minuteElement.setAttribute("step", self.config.minuteIncrement.toString());
        self.hourElement.setAttribute("min", self.config.time_24hr ? "0" : "1");
        self.hourElement.setAttribute("max", self.config.time_24hr ? "23" : "12");
        self.hourElement.setAttribute("maxlength", "2");
        self.minuteElement.setAttribute("min", "0");
        self.minuteElement.setAttribute("max", "59");
        self.minuteElement.setAttribute("maxlength", "2");
        self.timeContainer.appendChild(hourInput);
        self.timeContainer.appendChild(separator);
        self.timeContainer.appendChild(minuteInput);
        if (self.config.time_24hr)
            self.timeContainer.classList.add("time24hr");
        if (self.config.enableSeconds) {
            self.timeContainer.classList.add("hasSeconds");
            var secondInput = createNumberInput("flatpickr-second");
            self.secondElement = secondInput.getElementsByTagName("input")[0];
            self.secondElement.value = pad(self.latestSelectedDateObj
                ? self.latestSelectedDateObj.getSeconds()
                : defaults.seconds);
            self.secondElement.setAttribute("step", self.minuteElement.getAttribute("step"));
            self.secondElement.setAttribute("min", "0");
            self.secondElement.setAttribute("max", "59");
            self.secondElement.setAttribute("maxlength", "2");
            self.timeContainer.appendChild(createElement("span", "flatpickr-time-separator", ":"));
            self.timeContainer.appendChild(secondInput);
        }
        if (!self.config.time_24hr) {
            self.amPM = createElement("span", "flatpickr-am-pm", self.l10n.amPM[utils_int((self.latestSelectedDateObj
                ? self.hourElement.value
                : self.config.defaultHour) > 11)]);
            self.amPM.title = self.l10n.toggleTitle;
            self.amPM.tabIndex = -1;
            self.timeContainer.appendChild(self.amPM);
        }
        return self.timeContainer;
    }
    function buildWeekdays() {
        if (!self.weekdayContainer)
            self.weekdayContainer = createElement("div", "flatpickr-weekdays");
        else
            clearNode(self.weekdayContainer);
        for (var i = self.config.showMonths; i--;) {
            var container = createElement("div", "flatpickr-weekdaycontainer");
            self.weekdayContainer.appendChild(container);
        }
        updateWeekdays();
        return self.weekdayContainer;
    }
    function updateWeekdays() {
        if (!self.weekdayContainer) {
            return;
        }
        var firstDayOfWeek = self.l10n.firstDayOfWeek;
        var weekdays = __spreadArrays(self.l10n.weekdays.shorthand);
        if (firstDayOfWeek > 0 && firstDayOfWeek < weekdays.length) {
            weekdays = __spreadArrays(weekdays.splice(firstDayOfWeek, weekdays.length), weekdays.splice(0, firstDayOfWeek));
        }
        for (var i = self.config.showMonths; i--;) {
            self.weekdayContainer.children[i].innerHTML = "\n      <span class='flatpickr-weekday'>\n        " + weekdays.join("</span><span class='flatpickr-weekday'>") + "\n      </span>\n      ";
        }
    }
    function buildWeeks() {
        self.calendarContainer.classList.add("hasWeeks");
        var weekWrapper = createElement("div", "flatpickr-weekwrapper");
        weekWrapper.appendChild(createElement("span", "flatpickr-weekday", self.l10n.weekAbbreviation));
        var weekNumbers = createElement("div", "flatpickr-weeks");
        weekWrapper.appendChild(weekNumbers);
        return {
            weekWrapper: weekWrapper,
            weekNumbers: weekNumbers,
        };
    }
    function changeMonth(value, isOffset) {
        if (isOffset === void 0) { isOffset = true; }
        var delta = isOffset ? value : value - self.currentMonth;
        if ((delta < 0 && self._hidePrevMonthArrow === true) ||
            (delta > 0 && self._hideNextMonthArrow === true))
            return;
        self.currentMonth += delta;
        if (self.currentMonth < 0 || self.currentMonth > 11) {
            self.currentYear += self.currentMonth > 11 ? 1 : -1;
            self.currentMonth = (self.currentMonth + 12) % 12;
            triggerEvent("onYearChange");
            buildMonthSwitch();
        }
        buildDays();
        triggerEvent("onMonthChange");
        updateNavigationCurrentMonth();
    }
    function clear(triggerChangeEvent, toInitial) {
        if (triggerChangeEvent === void 0) { triggerChangeEvent = true; }
        if (toInitial === void 0) { toInitial = true; }
        self.input.value = "";
        if (self.altInput !== undefined)
            self.altInput.value = "";
        if (self.mobileInput !== undefined)
            self.mobileInput.value = "";
        self.selectedDates = [];
        self.latestSelectedDateObj = undefined;
        if (toInitial === true) {
            self.currentYear = self._initialDate.getFullYear();
            self.currentMonth = self._initialDate.getMonth();
        }
        if (self.config.enableTime === true) {
            var _a = getDefaultHours(self.config), hours = _a.hours, minutes = _a.minutes, seconds = _a.seconds;
            setHours(hours, minutes, seconds);
        }
        self.redraw();
        if (triggerChangeEvent)
            triggerEvent("onChange");
    }
    function close() {
        self.isOpen = false;
        if (!self.isMobile) {
            if (self.calendarContainer !== undefined) {
                self.calendarContainer.classList.remove("open");
            }
            if (self._input !== undefined) {
                self._input.classList.remove("active");
            }
        }
        triggerEvent("onClose");
    }
    function destroy() {
        if (self.config !== undefined)
            triggerEvent("onDestroy");
        for (var i = self._handlers.length; i--;) {
            self._handlers[i].remove();
        }
        self._handlers = [];
        if (self.mobileInput) {
            if (self.mobileInput.parentNode)
                self.mobileInput.parentNode.removeChild(self.mobileInput);
            self.mobileInput = undefined;
        }
        else if (self.calendarContainer && self.calendarContainer.parentNode) {
            if (self.config.static && self.calendarContainer.parentNode) {
                var wrapper = self.calendarContainer.parentNode;
                wrapper.lastChild && wrapper.removeChild(wrapper.lastChild);
                if (wrapper.parentNode) {
                    while (wrapper.firstChild)
                        wrapper.parentNode.insertBefore(wrapper.firstChild, wrapper);
                    wrapper.parentNode.removeChild(wrapper);
                }
            }
            else
                self.calendarContainer.parentNode.removeChild(self.calendarContainer);
        }
        if (self.altInput) {
            self.input.type = "text";
            if (self.altInput.parentNode)
                self.altInput.parentNode.removeChild(self.altInput);
            delete self.altInput;
        }
        if (self.input) {
            self.input.type = self.input._type;
            self.input.classList.remove("flatpickr-input");
            self.input.removeAttribute("readonly");
        }
        [
            "_showTimeInput",
            "latestSelectedDateObj",
            "_hideNextMonthArrow",
            "_hidePrevMonthArrow",
            "__hideNextMonthArrow",
            "__hidePrevMonthArrow",
            "isMobile",
            "isOpen",
            "selectedDateElem",
            "minDateHasTime",
            "maxDateHasTime",
            "days",
            "daysContainer",
            "_input",
            "_positionElement",
            "innerContainer",
            "rContainer",
            "monthNav",
            "todayDateElem",
            "calendarContainer",
            "weekdayContainer",
            "prevMonthNav",
            "nextMonthNav",
            "monthsDropdownContainer",
            "currentMonthElement",
            "currentYearElement",
            "navigationCurrentMonth",
            "selectedDateElem",
            "config",
        ].forEach(function (k) {
            try {
                delete self[k];
            }
            catch (_) { }
        });
    }
    function isCalendarElem(elem) {
        return self.calendarContainer.contains(elem);
    }
    function documentClick(e) {
        if (self.isOpen && !self.config.inline) {
            var eventTarget_1 = getEventTarget(e);
            var isCalendarElement = isCalendarElem(eventTarget_1);
            var isInput = eventTarget_1 === self.input ||
                eventTarget_1 === self.altInput ||
                self.element.contains(eventTarget_1) ||
                (e.path &&
                    e.path.indexOf &&
                    (~e.path.indexOf(self.input) ||
                        ~e.path.indexOf(self.altInput)));
            var lostFocus = !isInput &&
                !isCalendarElement &&
                !isCalendarElem(e.relatedTarget);
            var isIgnored = !self.config.ignoredFocusElements.some(function (elem) {
                return elem.contains(eventTarget_1);
            });
            if (lostFocus && isIgnored) {
                if (self.config.allowInput) {
                    self.setDate(self._input.value, false, self.config.altInput
                        ? self.config.altFormat
                        : self.config.dateFormat);
                }
                if (self.timeContainer !== undefined &&
                    self.minuteElement !== undefined &&
                    self.hourElement !== undefined &&
                    self.input.value !== "" &&
                    self.input.value !== undefined) {
                    updateTime();
                }
                self.close();
                if (self.config &&
                    self.config.mode === "range" &&
                    self.selectedDates.length === 1)
                    self.clear(false);
            }
        }
    }
    function changeYear(newYear) {
        if (!newYear ||
            (self.config.minDate && newYear < self.config.minDate.getFullYear()) ||
            (self.config.maxDate && newYear > self.config.maxDate.getFullYear()))
            return;
        var newYearNum = newYear, isNewYear = self.currentYear !== newYearNum;
        self.currentYear = newYearNum || self.currentYear;
        if (self.config.maxDate &&
            self.currentYear === self.config.maxDate.getFullYear()) {
            self.currentMonth = Math.min(self.config.maxDate.getMonth(), self.currentMonth);
        }
        else if (self.config.minDate &&
            self.currentYear === self.config.minDate.getFullYear()) {
            self.currentMonth = Math.max(self.config.minDate.getMonth(), self.currentMonth);
        }
        if (isNewYear) {
            self.redraw();
            triggerEvent("onYearChange");
            buildMonthSwitch();
        }
    }
    function isEnabled(date, timeless) {
        var _a;
        if (timeless === void 0) { timeless = true; }
        var dateToCheck = self.parseDate(date, undefined, timeless);
        if ((self.config.minDate &&
            dateToCheck &&
            compareDates(dateToCheck, self.config.minDate, timeless !== undefined ? timeless : !self.minDateHasTime) < 0) ||
            (self.config.maxDate &&
                dateToCheck &&
                compareDates(dateToCheck, self.config.maxDate, timeless !== undefined ? timeless : !self.maxDateHasTime) > 0))
            return false;
        if (!self.config.enable && self.config.disable.length === 0)
            return true;
        if (dateToCheck === undefined)
            return false;
        var bool = !!self.config.enable, array = (_a = self.config.enable) !== null && _a !== void 0 ? _a : self.config.disable;
        for (var i = 0, d = void 0; i < array.length; i++) {
            d = array[i];
            if (typeof d === "function" &&
                d(dateToCheck))
                return bool;
            else if (d instanceof Date &&
                dateToCheck !== undefined &&
                d.getTime() === dateToCheck.getTime())
                return bool;
            else if (typeof d === "string") {
                var parsed = self.parseDate(d, undefined, true);
                return parsed && parsed.getTime() === dateToCheck.getTime()
                    ? bool
                    : !bool;
            }
            else if (typeof d === "object" &&
                dateToCheck !== undefined &&
                d.from &&
                d.to &&
                dateToCheck.getTime() >= d.from.getTime() &&
                dateToCheck.getTime() <= d.to.getTime())
                return bool;
        }
        return !bool;
    }
    function isInView(elem) {
        if (self.daysContainer !== undefined)
            return (elem.className.indexOf("hidden") === -1 &&
                elem.className.indexOf("flatpickr-disabled") === -1 &&
                self.daysContainer.contains(elem));
        return false;
    }
    function onBlur(e) {
        var isInput = e.target === self._input;
        var valueChanged = self._input.value.trimEnd() !== getDateStr();
        if (isInput &&
            valueChanged &&
            !(e.relatedTarget && isCalendarElem(e.relatedTarget))) {
            self.setDate(self._input.value, true, e.target === self.altInput
                ? self.config.altFormat
                : self.config.dateFormat);
        }
    }
    function onKeyDown(e) {
        var eventTarget = getEventTarget(e);
        var isInput = self.config.wrap
            ? element.contains(eventTarget)
            : eventTarget === self._input;
        var allowInput = self.config.allowInput;
        var allowKeydown = self.isOpen && (!allowInput || !isInput);
        var allowInlineKeydown = self.config.inline && isInput && !allowInput;
        if (e.keyCode === 13 && isInput) {
            if (allowInput) {
                self.setDate(self._input.value, true, eventTarget === self.altInput
                    ? self.config.altFormat
                    : self.config.dateFormat);
                self.close();
                return eventTarget.blur();
            }
            else {
                self.open();
            }
        }
        else if (isCalendarElem(eventTarget) ||
            allowKeydown ||
            allowInlineKeydown) {
            var isTimeObj = !!self.timeContainer &&
                self.timeContainer.contains(eventTarget);
            switch (e.keyCode) {
                case 13:
                    if (isTimeObj) {
                        e.preventDefault();
                        updateTime();
                        focusAndClose();
                    }
                    else
                        selectDate(e);
                    break;
                case 27:
                    e.preventDefault();
                    focusAndClose();
                    break;
                case 8:
                case 46:
                    if (isInput && !self.config.allowInput) {
                        e.preventDefault();
                        self.clear();
                    }
                    break;
                case 37:
                case 39:
                    if (!isTimeObj && !isInput) {
                        e.preventDefault();
                        var activeElement = getClosestActiveElement();
                        if (self.daysContainer !== undefined &&
                            (allowInput === false ||
                                (activeElement && isInView(activeElement)))) {
                            var delta_1 = e.keyCode === 39 ? 1 : -1;
                            if (!e.ctrlKey)
                                focusOnDay(undefined, delta_1);
                            else {
                                e.stopPropagation();
                                changeMonth(delta_1);
                                focusOnDay(getFirstAvailableDay(1), 0);
                            }
                        }
                    }
                    else if (self.hourElement)
                        self.hourElement.focus();
                    break;
                case 38:
                case 40:
                    e.preventDefault();
                    var delta = e.keyCode === 40 ? 1 : -1;
                    if ((self.daysContainer &&
                        eventTarget.$i !== undefined) ||
                        eventTarget === self.input ||
                        eventTarget === self.altInput) {
                        if (e.ctrlKey) {
                            e.stopPropagation();
                            changeYear(self.currentYear - delta);
                            focusOnDay(getFirstAvailableDay(1), 0);
                        }
                        else if (!isTimeObj)
                            focusOnDay(undefined, delta * 7);
                    }
                    else if (eventTarget === self.currentYearElement) {
                        changeYear(self.currentYear - delta);
                    }
                    else if (self.config.enableTime) {
                        if (!isTimeObj && self.hourElement)
                            self.hourElement.focus();
                        updateTime(e);
                        self._debouncedChange();
                    }
                    break;
                case 9:
                    if (isTimeObj) {
                        var elems = [
                            self.hourElement,
                            self.minuteElement,
                            self.secondElement,
                            self.amPM,
                        ]
                            .concat(self.pluginElements)
                            .filter(function (x) { return x; });
                        var i = elems.indexOf(eventTarget);
                        if (i !== -1) {
                            var target = elems[i + (e.shiftKey ? -1 : 1)];
                            e.preventDefault();
                            (target || self._input).focus();
                        }
                    }
                    else if (!self.config.noCalendar &&
                        self.daysContainer &&
                        self.daysContainer.contains(eventTarget) &&
                        e.shiftKey) {
                        e.preventDefault();
                        self._input.focus();
                    }
                    break;
                default:
                    break;
            }
        }
        if (self.amPM !== undefined && eventTarget === self.amPM) {
            switch (e.key) {
                case self.l10n.amPM[0].charAt(0):
                case self.l10n.amPM[0].charAt(0).toLowerCase():
                    self.amPM.textContent = self.l10n.amPM[0];
                    setHoursFromInputs();
                    updateValue();
                    break;
                case self.l10n.amPM[1].charAt(0):
                case self.l10n.amPM[1].charAt(0).toLowerCase():
                    self.amPM.textContent = self.l10n.amPM[1];
                    setHoursFromInputs();
                    updateValue();
                    break;
            }
        }
        if (isInput || isCalendarElem(eventTarget)) {
            triggerEvent("onKeyDown", e);
        }
    }
    function onMouseOver(elem, cellClass) {
        if (cellClass === void 0) { cellClass = "flatpickr-day"; }
        if (self.selectedDates.length !== 1 ||
            (elem &&
                (!elem.classList.contains(cellClass) ||
                    elem.classList.contains("flatpickr-disabled"))))
            return;
        var hoverDate = elem
            ? elem.dateObj.getTime()
            : self.days.firstElementChild.dateObj.getTime(), initialDate = self.parseDate(self.selectedDates[0], undefined, true).getTime(), rangeStartDate = Math.min(hoverDate, self.selectedDates[0].getTime()), rangeEndDate = Math.max(hoverDate, self.selectedDates[0].getTime());
        var containsDisabled = false;
        var minRange = 0, maxRange = 0;
        for (var t = rangeStartDate; t < rangeEndDate; t += duration.DAY) {
            if (!isEnabled(new Date(t), true)) {
                containsDisabled =
                    containsDisabled || (t > rangeStartDate && t < rangeEndDate);
                if (t < initialDate && (!minRange || t > minRange))
                    minRange = t;
                else if (t > initialDate && (!maxRange || t < maxRange))
                    maxRange = t;
            }
        }
        var hoverableCells = Array.from(self.rContainer.querySelectorAll("*:nth-child(-n+" + self.config.showMonths + ") > ." + cellClass));
        hoverableCells.forEach(function (dayElem) {
            var date = dayElem.dateObj;
            var timestamp = date.getTime();
            var outOfRange = (minRange > 0 && timestamp < minRange) ||
                (maxRange > 0 && timestamp > maxRange);
            if (outOfRange) {
                dayElem.classList.add("notAllowed");
                ["inRange", "startRange", "endRange"].forEach(function (c) {
                    dayElem.classList.remove(c);
                });
                return;
            }
            else if (containsDisabled && !outOfRange)
                return;
            ["startRange", "inRange", "endRange", "notAllowed"].forEach(function (c) {
                dayElem.classList.remove(c);
            });
            if (elem !== undefined) {
                elem.classList.add(hoverDate <= self.selectedDates[0].getTime()
                    ? "startRange"
                    : "endRange");
                if (initialDate < hoverDate && timestamp === initialDate)
                    dayElem.classList.add("startRange");
                else if (initialDate > hoverDate && timestamp === initialDate)
                    dayElem.classList.add("endRange");
                if (timestamp >= minRange &&
                    (maxRange === 0 || timestamp <= maxRange) &&
                    isBetween(timestamp, initialDate, hoverDate))
                    dayElem.classList.add("inRange");
            }
        });
    }
    function onResize() {
        if (self.isOpen && !self.config.static && !self.config.inline)
            positionCalendar();
    }
    function open(e, positionElement) {
        if (positionElement === void 0) { positionElement = self._positionElement; }
        if (self.isMobile === true) {
            if (e) {
                e.preventDefault();
                var eventTarget = getEventTarget(e);
                if (eventTarget) {
                    eventTarget.blur();
                }
            }
            if (self.mobileInput !== undefined) {
                self.mobileInput.focus();
                self.mobileInput.click();
            }
            triggerEvent("onOpen");
            return;
        }
        else if (self._input.disabled || self.config.inline) {
            return;
        }
        var wasOpen = self.isOpen;
        self.isOpen = true;
        if (!wasOpen) {
            self.calendarContainer.classList.add("open");
            self._input.classList.add("active");
            triggerEvent("onOpen");
            positionCalendar(positionElement);
        }
        if (self.config.enableTime === true && self.config.noCalendar === true) {
            if (self.config.allowInput === false &&
                (e === undefined ||
                    !self.timeContainer.contains(e.relatedTarget))) {
                setTimeout(function () { return self.hourElement.select(); }, 50);
            }
        }
    }
    function minMaxDateSetter(type) {
        return function (date) {
            var dateObj = (self.config["_" + type + "Date"] = self.parseDate(date, self.config.dateFormat));
            var inverseDateObj = self.config["_" + (type === "min" ? "max" : "min") + "Date"];
            if (dateObj !== undefined) {
                self[type === "min" ? "minDateHasTime" : "maxDateHasTime"] =
                    dateObj.getHours() > 0 ||
                        dateObj.getMinutes() > 0 ||
                        dateObj.getSeconds() > 0;
            }
            if (self.selectedDates) {
                self.selectedDates = self.selectedDates.filter(function (d) { return isEnabled(d); });
                if (!self.selectedDates.length && type === "min")
                    setHoursFromDate(dateObj);
                updateValue();
            }
            if (self.daysContainer) {
                redraw();
                if (dateObj !== undefined)
                    self.currentYearElement[type] = dateObj.getFullYear().toString();
                else
                    self.currentYearElement.removeAttribute(type);
                self.currentYearElement.disabled =
                    !!inverseDateObj &&
                        dateObj !== undefined &&
                        inverseDateObj.getFullYear() === dateObj.getFullYear();
            }
        };
    }
    function parseConfig() {
        var boolOpts = [
            "wrap",
            "weekNumbers",
            "allowInput",
            "allowInvalidPreload",
            "clickOpens",
            "time_24hr",
            "enableTime",
            "noCalendar",
            "altInput",
            "shorthandCurrentMonth",
            "inline",
            "static",
            "enableSeconds",
            "disableMobile",
        ];
        var userConfig = __assign(__assign({}, JSON.parse(JSON.stringify(element.dataset || {}))), instanceConfig);
        var formats = {};
        self.config.parseDate = userConfig.parseDate;
        self.config.formatDate = userConfig.formatDate;
        Object.defineProperty(self.config, "enable", {
            get: function () { return self.config._enable; },
            set: function (dates) {
                self.config._enable = parseDateRules(dates);
            },
        });
        Object.defineProperty(self.config, "disable", {
            get: function () { return self.config._disable; },
            set: function (dates) {
                self.config._disable = parseDateRules(dates);
            },
        });
        var timeMode = userConfig.mode === "time";
        if (!userConfig.dateFormat && (userConfig.enableTime || timeMode)) {
            var defaultDateFormat = flatpickr.defaultConfig.dateFormat || defaults.dateFormat;
            formats.dateFormat =
                userConfig.noCalendar || timeMode
                    ? "H:i" + (userConfig.enableSeconds ? ":S" : "")
                    : defaultDateFormat + " H:i" + (userConfig.enableSeconds ? ":S" : "");
        }
        if (userConfig.altInput &&
            (userConfig.enableTime || timeMode) &&
            !userConfig.altFormat) {
            var defaultAltFormat = flatpickr.defaultConfig.altFormat || defaults.altFormat;
            formats.altFormat =
                userConfig.noCalendar || timeMode
                    ? "h:i" + (userConfig.enableSeconds ? ":S K" : " K")
                    : defaultAltFormat + (" h:i" + (userConfig.enableSeconds ? ":S" : "") + " K");
        }
        Object.defineProperty(self.config, "minDate", {
            get: function () { return self.config._minDate; },
            set: minMaxDateSetter("min"),
        });
        Object.defineProperty(self.config, "maxDate", {
            get: function () { return self.config._maxDate; },
            set: minMaxDateSetter("max"),
        });
        var minMaxTimeSetter = function (type) { return function (val) {
            self.config[type === "min" ? "_minTime" : "_maxTime"] = self.parseDate(val, "H:i:S");
        }; };
        Object.defineProperty(self.config, "minTime", {
            get: function () { return self.config._minTime; },
            set: minMaxTimeSetter("min"),
        });
        Object.defineProperty(self.config, "maxTime", {
            get: function () { return self.config._maxTime; },
            set: minMaxTimeSetter("max"),
        });
        if (userConfig.mode === "time") {
            self.config.noCalendar = true;
            self.config.enableTime = true;
        }
        Object.assign(self.config, formats, userConfig);
        for (var i = 0; i < boolOpts.length; i++)
            self.config[boolOpts[i]] =
                self.config[boolOpts[i]] === true ||
                    self.config[boolOpts[i]] === "true";
        HOOKS.filter(function (hook) { return self.config[hook] !== undefined; }).forEach(function (hook) {
            self.config[hook] = arrayify(self.config[hook] || []).map(bindToInstance);
        });
        self.isMobile =
            !self.config.disableMobile &&
                !self.config.inline &&
                self.config.mode === "single" &&
                !self.config.disable.length &&
                !self.config.enable &&
                !self.config.weekNumbers &&
                /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        for (var i = 0; i < self.config.plugins.length; i++) {
            var pluginConf = self.config.plugins[i](self) || {};
            for (var key in pluginConf) {
                if (HOOKS.indexOf(key) > -1) {
                    self.config[key] = arrayify(pluginConf[key])
                        .map(bindToInstance)
                        .concat(self.config[key]);
                }
                else if (typeof userConfig[key] === "undefined")
                    self.config[key] = pluginConf[key];
            }
        }
        if (!userConfig.altInputClass) {
            self.config.altInputClass =
                getInputElem().className + " " + self.config.altInputClass;
        }
        triggerEvent("onParseConfig");
    }
    function getInputElem() {
        return self.config.wrap
            ? element.querySelector("[data-input]")
            : element;
    }
    function setupLocale() {
        if (typeof self.config.locale !== "object" &&
            typeof flatpickr.l10ns[self.config.locale] === "undefined")
            self.config.errorHandler(new Error("flatpickr: invalid locale " + self.config.locale));
        self.l10n = __assign(__assign({}, flatpickr.l10ns.default), (typeof self.config.locale === "object"
            ? self.config.locale
            : self.config.locale !== "default"
                ? flatpickr.l10ns[self.config.locale]
                : undefined));
        tokenRegex.D = "(" + self.l10n.weekdays.shorthand.join("|") + ")";
        tokenRegex.l = "(" + self.l10n.weekdays.longhand.join("|") + ")";
        tokenRegex.M = "(" + self.l10n.months.shorthand.join("|") + ")";
        tokenRegex.F = "(" + self.l10n.months.longhand.join("|") + ")";
        tokenRegex.K = "(" + self.l10n.amPM[0] + "|" + self.l10n.amPM[1] + "|" + self.l10n.amPM[0].toLowerCase() + "|" + self.l10n.amPM[1].toLowerCase() + ")";
        var userConfig = __assign(__assign({}, instanceConfig), JSON.parse(JSON.stringify(element.dataset || {})));
        if (userConfig.time_24hr === undefined &&
            flatpickr.defaultConfig.time_24hr === undefined) {
            self.config.time_24hr = self.l10n.time_24hr;
        }
        self.formatDate = createDateFormatter(self);
        self.parseDate = createDateParser({ config: self.config, l10n: self.l10n });
    }
    function positionCalendar(customPositionElement) {
        if (typeof self.config.position === "function") {
            return void self.config.position(self, customPositionElement);
        }
        if (self.calendarContainer === undefined)
            return;
        triggerEvent("onPreCalendarPosition");
        var positionElement = customPositionElement || self._positionElement;
        var calendarHeight = Array.prototype.reduce.call(self.calendarContainer.children, (function (acc, child) { return acc + child.offsetHeight; }), 0), calendarWidth = self.calendarContainer.offsetWidth, configPos = self.config.position.split(" "), configPosVertical = configPos[0], configPosHorizontal = configPos.length > 1 ? configPos[1] : null, inputBounds = positionElement.getBoundingClientRect(), distanceFromBottom = window.innerHeight - inputBounds.bottom, showOnTop = configPosVertical === "above" ||
            (configPosVertical !== "below" &&
                distanceFromBottom < calendarHeight &&
                inputBounds.top > calendarHeight);
        var top = window.pageYOffset +
            inputBounds.top +
            (!showOnTop ? positionElement.offsetHeight + 2 : -calendarHeight - 2);
        toggleClass(self.calendarContainer, "arrowTop", !showOnTop);
        toggleClass(self.calendarContainer, "arrowBottom", showOnTop);
        if (self.config.inline)
            return;
        var left = window.pageXOffset + inputBounds.left;
        var isCenter = false;
        var isRight = false;
        if (configPosHorizontal === "center") {
            left -= (calendarWidth - inputBounds.width) / 2;
            isCenter = true;
        }
        else if (configPosHorizontal === "right") {
            left -= calendarWidth - inputBounds.width;
            isRight = true;
        }
        toggleClass(self.calendarContainer, "arrowLeft", !isCenter && !isRight);
        toggleClass(self.calendarContainer, "arrowCenter", isCenter);
        toggleClass(self.calendarContainer, "arrowRight", isRight);
        var right = window.document.body.offsetWidth -
            (window.pageXOffset + inputBounds.right);
        var rightMost = left + calendarWidth > window.document.body.offsetWidth;
        var centerMost = right + calendarWidth > window.document.body.offsetWidth;
        toggleClass(self.calendarContainer, "rightMost", rightMost);
        if (self.config.static)
            return;
        self.calendarContainer.style.top = top + "px";
        if (!rightMost) {
            self.calendarContainer.style.left = left + "px";
            self.calendarContainer.style.right = "auto";
        }
        else if (!centerMost) {
            self.calendarContainer.style.left = "auto";
            self.calendarContainer.style.right = right + "px";
        }
        else {
            var doc = getDocumentStyleSheet();
            if (doc === undefined)
                return;
            var bodyWidth = window.document.body.offsetWidth;
            var centerLeft = Math.max(0, bodyWidth / 2 - calendarWidth / 2);
            var centerBefore = ".flatpickr-calendar.centerMost:before";
            var centerAfter = ".flatpickr-calendar.centerMost:after";
            var centerIndex = doc.cssRules.length;
            var centerStyle = "{left:" + inputBounds.left + "px;right:auto;}";
            toggleClass(self.calendarContainer, "rightMost", false);
            toggleClass(self.calendarContainer, "centerMost", true);
            doc.insertRule(centerBefore + "," + centerAfter + centerStyle, centerIndex);
            self.calendarContainer.style.left = centerLeft + "px";
            self.calendarContainer.style.right = "auto";
        }
    }
    function getDocumentStyleSheet() {
        var editableSheet = null;
        for (var i = 0; i < document.styleSheets.length; i++) {
            var sheet = document.styleSheets[i];
            if (!sheet.cssRules)
                continue;
            try {
                sheet.cssRules;
            }
            catch (err) {
                continue;
            }
            editableSheet = sheet;
            break;
        }
        return editableSheet != null ? editableSheet : createStyleSheet();
    }
    function createStyleSheet() {
        var style = document.createElement("style");
        document.head.appendChild(style);
        return style.sheet;
    }
    function redraw() {
        if (self.config.noCalendar || self.isMobile)
            return;
        buildMonthSwitch();
        updateNavigationCurrentMonth();
        buildDays();
    }
    function focusAndClose() {
        self._input.focus();
        if (window.navigator.userAgent.indexOf("MSIE") !== -1 ||
            navigator.msMaxTouchPoints !== undefined) {
            setTimeout(self.close, 0);
        }
        else {
            self.close();
        }
    }
    function selectDate(e) {
        e.preventDefault();
        e.stopPropagation();
        var isSelectable = function (day) {
            return day.classList &&
                day.classList.contains("flatpickr-day") &&
                !day.classList.contains("flatpickr-disabled") &&
                !day.classList.contains("notAllowed");
        };
        var t = findParent(getEventTarget(e), isSelectable);
        if (t === undefined)
            return;
        var target = t;
        var selectedDate = (self.latestSelectedDateObj = new Date(target.dateObj.getTime()));
        var shouldChangeMonth = (selectedDate.getMonth() < self.currentMonth ||
            selectedDate.getMonth() >
                self.currentMonth + self.config.showMonths - 1) &&
            self.config.mode !== "range";
        self.selectedDateElem = target;
        if (self.config.mode === "single")
            self.selectedDates = [selectedDate];
        else if (self.config.mode === "multiple") {
            var selectedIndex = isDateSelected(selectedDate);
            if (selectedIndex)
                self.selectedDates.splice(parseInt(selectedIndex), 1);
            else
                self.selectedDates.push(selectedDate);
        }
        else if (self.config.mode === "range") {
            if (self.selectedDates.length === 2) {
                self.clear(false, false);
            }
            self.latestSelectedDateObj = selectedDate;
            self.selectedDates.push(selectedDate);
            if (compareDates(selectedDate, self.selectedDates[0], true) !== 0)
                self.selectedDates.sort(function (a, b) { return a.getTime() - b.getTime(); });
        }
        setHoursFromInputs();
        if (shouldChangeMonth) {
            var isNewYear = self.currentYear !== selectedDate.getFullYear();
            self.currentYear = selectedDate.getFullYear();
            self.currentMonth = selectedDate.getMonth();
            if (isNewYear) {
                triggerEvent("onYearChange");
                buildMonthSwitch();
            }
            triggerEvent("onMonthChange");
        }
        updateNavigationCurrentMonth();
        buildDays();
        updateValue();
        if (!shouldChangeMonth &&
            self.config.mode !== "range" &&
            self.config.showMonths === 1)
            focusOnDayElem(target);
        else if (self.selectedDateElem !== undefined &&
            self.hourElement === undefined) {
            self.selectedDateElem && self.selectedDateElem.focus();
        }
        if (self.hourElement !== undefined)
            self.hourElement !== undefined && self.hourElement.focus();
        if (self.config.closeOnSelect) {
            var single = self.config.mode === "single" && !self.config.enableTime;
            var range = self.config.mode === "range" &&
                self.selectedDates.length === 2 &&
                !self.config.enableTime;
            if (single || range) {
                focusAndClose();
            }
        }
        triggerChange();
    }
    var CALLBACKS = {
        locale: [setupLocale, updateWeekdays],
        showMonths: [buildMonths, setCalendarWidth, buildWeekdays],
        minDate: [jumpToDate],
        maxDate: [jumpToDate],
        positionElement: [updatePositionElement],
        clickOpens: [
            function () {
                if (self.config.clickOpens === true) {
                    bind(self._input, "focus", self.open);
                    bind(self._input, "click", self.open);
                }
                else {
                    self._input.removeEventListener("focus", self.open);
                    self._input.removeEventListener("click", self.open);
                }
            },
        ],
    };
    function set(option, value) {
        if (option !== null && typeof option === "object") {
            Object.assign(self.config, option);
            for (var key in option) {
                if (CALLBACKS[key] !== undefined)
                    CALLBACKS[key].forEach(function (x) { return x(); });
            }
        }
        else {
            self.config[option] = value;
            if (CALLBACKS[option] !== undefined)
                CALLBACKS[option].forEach(function (x) { return x(); });
            else if (HOOKS.indexOf(option) > -1)
                self.config[option] = arrayify(value);
        }
        self.redraw();
        updateValue(true);
    }
    function setSelectedDate(inputDate, format) {
        var dates = [];
        if (inputDate instanceof Array)
            dates = inputDate.map(function (d) { return self.parseDate(d, format); });
        else if (inputDate instanceof Date || typeof inputDate === "number")
            dates = [self.parseDate(inputDate, format)];
        else if (typeof inputDate === "string") {
            switch (self.config.mode) {
                case "single":
                case "time":
                    dates = [self.parseDate(inputDate, format)];
                    break;
                case "multiple":
                    dates = inputDate
                        .split(self.config.conjunction)
                        .map(function (date) { return self.parseDate(date, format); });
                    break;
                case "range":
                    dates = inputDate
                        .split(self.l10n.rangeSeparator)
                        .map(function (date) { return self.parseDate(date, format); });
                    break;
                default:
                    break;
            }
        }
        else
            self.config.errorHandler(new Error("Invalid date supplied: " + JSON.stringify(inputDate)));
        self.selectedDates = (self.config.allowInvalidPreload
            ? dates
            : dates.filter(function (d) { return d instanceof Date && isEnabled(d, false); }));
        if (self.config.mode === "range")
            self.selectedDates.sort(function (a, b) { return a.getTime() - b.getTime(); });
    }
    function setDate(date, triggerChange, format) {
        if (triggerChange === void 0) { triggerChange = false; }
        if (format === void 0) { format = self.config.dateFormat; }
        if ((date !== 0 && !date) || (date instanceof Array && date.length === 0))
            return self.clear(triggerChange);
        setSelectedDate(date, format);
        self.latestSelectedDateObj =
            self.selectedDates[self.selectedDates.length - 1];
        self.redraw();
        jumpToDate(undefined, triggerChange);
        setHoursFromDate();
        if (self.selectedDates.length === 0) {
            self.clear(false);
        }
        updateValue(triggerChange);
        if (triggerChange)
            triggerEvent("onChange");
    }
    function parseDateRules(arr) {
        return arr
            .slice()
            .map(function (rule) {
            if (typeof rule === "string" ||
                typeof rule === "number" ||
                rule instanceof Date) {
                return self.parseDate(rule, undefined, true);
            }
            else if (rule &&
                typeof rule === "object" &&
                rule.from &&
                rule.to)
                return {
                    from: self.parseDate(rule.from, undefined),
                    to: self.parseDate(rule.to, undefined),
                };
            return rule;
        })
            .filter(function (x) { return x; });
    }
    function setupDates() {
        self.selectedDates = [];
        self.now = self.parseDate(self.config.now) || new Date();
        var preloadedDate = self.config.defaultDate ||
            ((self.input.nodeName === "INPUT" ||
                self.input.nodeName === "TEXTAREA") &&
                self.input.placeholder &&
                self.input.value === self.input.placeholder
                ? null
                : self.input.value);
        if (preloadedDate)
            setSelectedDate(preloadedDate, self.config.dateFormat);
        self._initialDate =
            self.selectedDates.length > 0
                ? self.selectedDates[0]
                : self.config.minDate &&
                    self.config.minDate.getTime() > self.now.getTime()
                    ? self.config.minDate
                    : self.config.maxDate &&
                        self.config.maxDate.getTime() < self.now.getTime()
                        ? self.config.maxDate
                        : self.now;
        self.currentYear = self._initialDate.getFullYear();
        self.currentMonth = self._initialDate.getMonth();
        if (self.selectedDates.length > 0)
            self.latestSelectedDateObj = self.selectedDates[0];
        if (self.config.minTime !== undefined)
            self.config.minTime = self.parseDate(self.config.minTime, "H:i");
        if (self.config.maxTime !== undefined)
            self.config.maxTime = self.parseDate(self.config.maxTime, "H:i");
        self.minDateHasTime =
            !!self.config.minDate &&
                (self.config.minDate.getHours() > 0 ||
                    self.config.minDate.getMinutes() > 0 ||
                    self.config.minDate.getSeconds() > 0);
        self.maxDateHasTime =
            !!self.config.maxDate &&
                (self.config.maxDate.getHours() > 0 ||
                    self.config.maxDate.getMinutes() > 0 ||
                    self.config.maxDate.getSeconds() > 0);
    }
    function setupInputs() {
        self.input = getInputElem();
        if (!self.input) {
            self.config.errorHandler(new Error("Invalid input element specified"));
            return;
        }
        self.input._type = self.input.type;
        self.input.type = "text";
        self.input.classList.add("flatpickr-input");
        self._input = self.input;
        if (self.config.altInput) {
            self.altInput = createElement(self.input.nodeName, self.config.altInputClass);
            self._input = self.altInput;
            self.altInput.placeholder = self.input.placeholder;
            self.altInput.disabled = self.input.disabled;
            self.altInput.required = self.input.required;
            self.altInput.tabIndex = self.input.tabIndex;
            self.altInput.type = "text";
            self.input.setAttribute("type", "hidden");
            if (!self.config.static && self.input.parentNode)
                self.input.parentNode.insertBefore(self.altInput, self.input.nextSibling);
        }
        if (!self.config.allowInput)
            self._input.setAttribute("readonly", "readonly");
        updatePositionElement();
    }
    function updatePositionElement() {
        self._positionElement = self.config.positionElement || self._input;
    }
    function setupMobile() {
        var inputType = self.config.enableTime
            ? self.config.noCalendar
                ? "time"
                : "datetime-local"
            : "date";
        self.mobileInput = createElement("input", self.input.className + " flatpickr-mobile");
        self.mobileInput.tabIndex = 1;
        self.mobileInput.type = inputType;
        self.mobileInput.disabled = self.input.disabled;
        self.mobileInput.required = self.input.required;
        self.mobileInput.placeholder = self.input.placeholder;
        self.mobileFormatStr =
            inputType === "datetime-local"
                ? "Y-m-d\\TH:i:S"
                : inputType === "date"
                    ? "Y-m-d"
                    : "H:i:S";
        if (self.selectedDates.length > 0) {
            self.mobileInput.defaultValue = self.mobileInput.value = self.formatDate(self.selectedDates[0], self.mobileFormatStr);
        }
        if (self.config.minDate)
            self.mobileInput.min = self.formatDate(self.config.minDate, "Y-m-d");
        if (self.config.maxDate)
            self.mobileInput.max = self.formatDate(self.config.maxDate, "Y-m-d");
        if (self.input.getAttribute("step"))
            self.mobileInput.step = String(self.input.getAttribute("step"));
        self.input.type = "hidden";
        if (self.altInput !== undefined)
            self.altInput.type = "hidden";
        try {
            if (self.input.parentNode)
                self.input.parentNode.insertBefore(self.mobileInput, self.input.nextSibling);
        }
        catch (_a) { }
        bind(self.mobileInput, "change", function (e) {
            self.setDate(getEventTarget(e).value, false, self.mobileFormatStr);
            triggerEvent("onChange");
            triggerEvent("onClose");
        });
    }
    function toggle(e) {
        if (self.isOpen === true)
            return self.close();
        self.open(e);
    }
    function triggerEvent(event, data) {
        if (self.config === undefined)
            return;
        var hooks = self.config[event];
        if (hooks !== undefined && hooks.length > 0) {
            for (var i = 0; hooks[i] && i < hooks.length; i++)
                hooks[i](self.selectedDates, self.input.value, self, data);
        }
        if (event === "onChange") {
            self.input.dispatchEvent(createEvent("change"));
            self.input.dispatchEvent(createEvent("input"));
        }
    }
    function createEvent(name) {
        var e = document.createEvent("Event");
        e.initEvent(name, true, true);
        return e;
    }
    function isDateSelected(date) {
        for (var i = 0; i < self.selectedDates.length; i++) {
            var selectedDate = self.selectedDates[i];
            if (selectedDate instanceof Date &&
                compareDates(selectedDate, date) === 0)
                return "" + i;
        }
        return false;
    }
    function isDateInRange(date) {
        if (self.config.mode !== "range" || self.selectedDates.length < 2)
            return false;
        return (compareDates(date, self.selectedDates[0]) >= 0 &&
            compareDates(date, self.selectedDates[1]) <= 0);
    }
    function updateNavigationCurrentMonth() {
        if (self.config.noCalendar || self.isMobile || !self.monthNav)
            return;
        self.yearElements.forEach(function (yearElement, i) {
            var d = new Date(self.currentYear, self.currentMonth, 1);
            d.setMonth(self.currentMonth + i);
            if (self.config.showMonths > 1 ||
                self.config.monthSelectorType === "static") {
                self.monthElements[i].textContent =
                    monthToStr(d.getMonth(), self.config.shorthandCurrentMonth, self.l10n) + " ";
            }
            else {
                self.monthsDropdownContainer.value = d.getMonth().toString();
            }
            yearElement.value = d.getFullYear().toString();
        });
        self._hidePrevMonthArrow =
            self.config.minDate !== undefined &&
                (self.currentYear === self.config.minDate.getFullYear()
                    ? self.currentMonth <= self.config.minDate.getMonth()
                    : self.currentYear < self.config.minDate.getFullYear());
        self._hideNextMonthArrow =
            self.config.maxDate !== undefined &&
                (self.currentYear === self.config.maxDate.getFullYear()
                    ? self.currentMonth + 1 > self.config.maxDate.getMonth()
                    : self.currentYear > self.config.maxDate.getFullYear());
    }
    function getDateStr(specificFormat) {
        var format = specificFormat ||
            (self.config.altInput ? self.config.altFormat : self.config.dateFormat);
        return self.selectedDates
            .map(function (dObj) { return self.formatDate(dObj, format); })
            .filter(function (d, i, arr) {
            return self.config.mode !== "range" ||
                self.config.enableTime ||
                arr.indexOf(d) === i;
        })
            .join(self.config.mode !== "range"
            ? self.config.conjunction
            : self.l10n.rangeSeparator);
    }
    function updateValue(triggerChange) {
        if (triggerChange === void 0) { triggerChange = true; }
        if (self.mobileInput !== undefined && self.mobileFormatStr) {
            self.mobileInput.value =
                self.latestSelectedDateObj !== undefined
                    ? self.formatDate(self.latestSelectedDateObj, self.mobileFormatStr)
                    : "";
        }
        self.input.value = getDateStr(self.config.dateFormat);
        if (self.altInput !== undefined) {
            self.altInput.value = getDateStr(self.config.altFormat);
        }
        if (triggerChange !== false)
            triggerEvent("onValueUpdate");
    }
    function onMonthNavClick(e) {
        var eventTarget = getEventTarget(e);
        var isPrevMonth = self.prevMonthNav.contains(eventTarget);
        var isNextMonth = self.nextMonthNav.contains(eventTarget);
        if (isPrevMonth || isNextMonth) {
            changeMonth(isPrevMonth ? -1 : 1);
        }
        else if (self.yearElements.indexOf(eventTarget) >= 0) {
            eventTarget.select();
        }
        else if (eventTarget.classList.contains("arrowUp")) {
            self.changeYear(self.currentYear + 1);
        }
        else if (eventTarget.classList.contains("arrowDown")) {
            self.changeYear(self.currentYear - 1);
        }
    }
    function timeWrapper(e) {
        e.preventDefault();
        var isKeyDown = e.type === "keydown", eventTarget = getEventTarget(e), input = eventTarget;
        if (self.amPM !== undefined && eventTarget === self.amPM) {
            self.amPM.textContent =
                self.l10n.amPM[utils_int(self.amPM.textContent === self.l10n.amPM[0])];
        }
        var min = parseFloat(input.getAttribute("min")), max = parseFloat(input.getAttribute("max")), step = parseFloat(input.getAttribute("step")), curValue = parseInt(input.value, 10), delta = e.delta ||
            (isKeyDown ? (e.which === 38 ? 1 : -1) : 0);
        var newValue = curValue + step * delta;
        if (typeof input.value !== "undefined" && input.value.length === 2) {
            var isHourElem = input === self.hourElement, isMinuteElem = input === self.minuteElement;
            if (newValue < min) {
                newValue =
                    max +
                        newValue +
                        utils_int(!isHourElem) +
                        (utils_int(isHourElem) && utils_int(!self.amPM));
                if (isMinuteElem)
                    incrementNumInput(undefined, -1, self.hourElement);
            }
            else if (newValue > max) {
                newValue =
                    input === self.hourElement ? newValue - max - utils_int(!self.amPM) : min;
                if (isMinuteElem)
                    incrementNumInput(undefined, 1, self.hourElement);
            }
            if (self.amPM &&
                isHourElem &&
                (step === 1
                    ? newValue + curValue === 23
                    : Math.abs(newValue - curValue) > step)) {
                self.amPM.textContent =
                    self.l10n.amPM[utils_int(self.amPM.textContent === self.l10n.amPM[0])];
            }
            input.value = pad(newValue);
        }
    }
    init();
    return self;
}
function _flatpickr(nodeList, config) {
    var nodes = Array.prototype.slice
        .call(nodeList)
        .filter(function (x) { return x instanceof HTMLElement; });
    var instances = [];
    for (var i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        try {
            if (node.getAttribute("data-fp-omit") !== null)
                continue;
            if (node._flatpickr !== undefined) {
                node._flatpickr.destroy();
                node._flatpickr = undefined;
            }
            node._flatpickr = FlatpickrInstance(node, config || {});
            instances.push(node._flatpickr);
        }
        catch (e) {
            console.error(e);
        }
    }
    return instances.length === 1 ? instances[0] : instances;
}
if (typeof HTMLElement !== "undefined" &&
    typeof HTMLCollection !== "undefined" &&
    typeof NodeList !== "undefined") {
    HTMLCollection.prototype.flatpickr = NodeList.prototype.flatpickr = function (config) {
        return _flatpickr(this, config);
    };
    HTMLElement.prototype.flatpickr = function (config) {
        return _flatpickr([this], config);
    };
}
var flatpickr = function (selector, config) {
    if (typeof selector === "string") {
        return _flatpickr(window.document.querySelectorAll(selector), config);
    }
    else if (selector instanceof Node) {
        return _flatpickr([selector], config);
    }
    else {
        return _flatpickr(selector, config);
    }
};
flatpickr.defaultConfig = {};
flatpickr.l10ns = {
    en: __assign({}, l10n_default),
    default: __assign({}, l10n_default),
};
flatpickr.localize = function (l10n) {
    flatpickr.l10ns.default = __assign(__assign({}, flatpickr.l10ns.default), l10n);
};
flatpickr.setDefaults = function (config) {
    flatpickr.defaultConfig = __assign(__assign({}, flatpickr.defaultConfig), config);
};
flatpickr.parseDate = createDateParser({});
flatpickr.formatDate = createDateFormatter({});
flatpickr.compareDates = compareDates;
if (typeof jQuery !== "undefined" && typeof jQuery.fn !== "undefined") {
    jQuery.fn.flatpickr = function (config) {
        return _flatpickr(this, config);
    };
}
Date.prototype.fp_incr = function (days) {
    return new Date(this.getFullYear(), this.getMonth(), this.getDate() + (typeof days === "string" ? parseInt(days, 10) : days));
};
if (typeof window !== "undefined") {
    window.flatpickr = flatpickr;
}
/* harmony default export */ const esm = (flatpickr);

;// CONCATENATED MODULE: ./src/components/datetimepicker/datetimepicker.js

const Brazilian = (__webpack_require__("./node_modules/flatpickr/dist/l10n/pt.js")["default"].pt)

/** Classe para instanciar um objeto BRDateTimePicker*/
class BRDateTimePicker {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   * @param {string} config - atributo de configuração do componente
   * @param {object} language arquivo de linguagem do flatpick por padrão nesse componente vem portugues
   */
  constructor(name, component, config, language = Brazilian) {
    this.name = name
    this.component = component
    this.language = language

    this.component.addEventListener('blur', () => {
      if (!isNaN(new Date(this.component.value))) {
        fp.setDate(this.component.value)
      }
    })

    this.component.addEventListener('keyup', () => {
      if (!isNaN(new Date(this.component.value))) {
        // if the cursor is at the end of the edit and we have a full sized date, allow the date to immediately change, otherwise just move to the correct month without actually changing it
        if (this.component.selectionStart >= 10)
          fp.setDate(this.component.value)
        else fp.jumpToDate(this.component.value)
      }
    })
    // localization global
    esm.localize(language)

    this.configAttribute = this.component.getAttribute('datetimepicker-config')

    if (this.configAttribute) {
      // Transforma o atributo em um objeto
      const properties = this.configAttribute.split(',')
      this.obj = []
      properties.forEach((element) => {
        const tup = element.split(':')
        this.obj[tup[0]] = tup[1].replaceAll("'", '').trim() // eslint-disable-line

        this.saida = this.obj
      }, this)

      this.config = this.obj
    } else {
      this.config = config
    }

    this._buildDateTimePicker()
  }
  /**
   * Adiciona máscara de data no input
   * @param {*} elm
   */
  _dateInputMask(elm) {
    elm.setAttribute('maxlength', 10)
    elm.addEventListener('keypress', (e) => {
      if (e.keyCode < 47 || e.keyCode > 57) {
        e.preventDefault()
      }

      const len = elm.value.length

      if (len !== 1 || len !== 3) {
        if (e.keyCode == 47) {
          e.preventDefault()
        }
      }

      if (len === 2) {
        elm.value += '/'
      }

      if (len === 5) {
        elm.value += '/'
      }
    })
  }
  /**
   *  Adiciona máscara de hora no input
   * @param {*} elm * Dom do elemento input
   */
  _dateTimeInputMask(elm) {
    elm.setAttribute('maxlength', 16)
    elm.addEventListener('keypress', (e) => {
      if (e.keyCode < 47 || e.keyCode > 57) {
        e.preventDefault()
      }

      const len = elm.value.length

      if (len !== 1 || len !== 3) {
        if (e.keyCode == 47) {
          e.preventDefault()
        }
      }
      switch (len) {
        case 2:
          elm.value += '/'
          break
        case 5:
          elm.value += '/'
          break
        case 10:
          elm.value += ' '
          break
        case 13:
          elm.value += ':'
          break

        default:
          break
      }
    })
  }
  /**
   * Coloca máscara com range de data no input
   * @param {*} elm * Dom do elemento input
   */
  _dateRangeInputMask(elm) {
    elm.setAttribute('maxlength', 25)
    elm.addEventListener('keypress', (e) => {
      if (e.keyCode < 47 || e.keyCode > 57) {
        e.preventDefault()
      }

      const len = elm.value.length

      if (len !== 1 || len !== 3) {
        if (e.keyCode === 47) {
          e.preventDefault()
        }
      }
      this._positionRangeMask(elm, len)
    })
  }
  /**
   * Insere a máscara na Dom
   * @param {*} elm Dom do elemento input
   * @param {*} len Tamanho do elemento inserido
   */
  _positionRangeMask(elm, len) {
    const tamSeparator = this.language.rangeSeparator.length
    const daySeparator = 10 + tamSeparator + 2
    const monthSeparator = 10 + tamSeparator + 5
    elm.setAttribute('maxlength', 20 + tamSeparator)

    switch (len) {
      case 2:
        elm.value += '/'
        break
      case 5:
        elm.value += '/'
        break
      case 10:
        elm.value += this.language.rangeSeparator
        break
      case daySeparator:
        elm.value += '/'
        break
      case monthSeparator:
        elm.value += '/'
        break

      default:
        break
    }
  }
  /**
   * Insere máscara de hora
   *
   * @param {*} elm dom do elemento input
   */
  _timeInputMask(elm) {
    elm.setAttribute('maxlength', 5)
    elm.addEventListener('keypress', (e) => {
      if (e.keyCode < 47 || e.keyCode > 57) {
        e.preventDefault()
      }

      const len = elm.value.length

      if (len !== 1 || len !== 3) {
        if (e.keyCode === 47) {
          e.preventDefault()
        }
      }

      if (len === 2) {
        elm.value += ':'
      }
    })
  }

  /**
   * Formata o componente e monta instância flatpickr
   * @private
   */
  _buildDateTimePicker() {
    let format = 'd/m/Y'
    let time = false
    let noCalendar = false

    switch (this.component.getAttribute('data-type')) {
      case 'date':
        format = 'd/m/Y'
        time = false
        noCalendar = false
        this._dateInputMask(this.component.querySelectorAll('input')[0])
        break

      case 'time':
        format = 'H:i'
        time = true
        noCalendar = true
        this._timeInputMask(this.component.querySelectorAll('input')[0])
        break
      case 'datetime-local':
        format = 'd/m/Y H:i'
        time = true
        noCalendar = false
        this._dateTimeInputMask(this.component.querySelectorAll('input')[0])
        break
      case 'datetime-range':
        format = 'd/m/Y'
        time = false
        noCalendar = false
        this._dateRangeInputMask(this.component.querySelectorAll('input')[0])
        break
      default:
        format = 'd/m/Y'
        time = false
        noCalendar = false
        if (this.component.getAttribute('data-mode') === 'range') {
          this._dateRangeInputMask(this.component.querySelectorAll('input')[0])
        } else {
          this._dateInputMask(this.component.querySelectorAll('input')[0])
        }
        break
    }

    this.config_native = {
      allowInput: true,
      dateFormat: format,
      disableMobile: 'true',
      enableTime: time,
      minuteIncrement: 1,

      mode: this.component.getAttribute('data-mode'),
      nextArrow:
        '<button class="br-button circle small" type="button"><i class="fas fa-chevron-right"></i></button>',
      noCalendar: noCalendar,
      prevArrow:
        '<button class="br-button circle small" type="button"><i class="fas fas fa-chevron-left"></i></button>',
      time_24hr: true,
      wrap: true,
    }
    this.config_flatpick = Object.assign(this.config, this.config_native)

    this.calendar = esm(
      this.component,
      Object.assign(this.config, this.config_native)
    )

    this.calendar.config.onOpen.push(() => {
      document.querySelectorAll('.arrowUp').forEach((element) => {
        element.classList.add('fas', 'fa-chevron-up')
      })
      document.querySelectorAll('.arrowDown').forEach((element) => {
        element.classList.add('fas', 'fa-chevron-down')
      })
    })
  }
}

/* harmony default export */ const datetimepicker = (BRDateTimePicker);


/***/ }),

/***/ "./src/components/footer/footer.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Classe do componente BRFooter
 */
class BRFooter {
  /**
   * Instancia um componente BRFooter
   * @param {string} name - Nome do componente (br-footer)
   * @param {object} component - Objeto que referencia o elemento DOM do componente
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setUp()
    this._setBehavior()
  }

  /**
   * Controla a configuração inicial do footer
   */
  _setUp() {
    this.list = this.component.querySelector('.br-list.horizontal')
  }

  /**
   * Controla os comportamentos do footer
   * @private
   */
  _setBehavior() {
    this._setCollapseBehavior()

    window.onresize = function () {
      if (window.matchMedia('(min-width: 992px)').matches) {
        window.document
          .querySelectorAll('.br-footer .br-list:not(.horizontal)')
          .forEach((trigger) => {
            trigger.style.display = 'block'
          })
      } else {
        window.document
          .querySelectorAll('.br-footer .br-list:not(.horizontal)')
          .forEach((trigger) => {
            trigger.style.display = 'none'
          })

        window.document
          .querySelectorAll('.br-footer i')
          .forEach((iconComponent) => {
            iconComponent.classList.remove('fa-angle-up')
            iconComponent.classList.add('fa-angle-down')
          })
      }
    }
  }

  /**
   * Trata do comportamento de collapse do Footer
   * @private
   */
  _setCollapseBehavior() {
    this.britems = []
    if (this.list) {
      this.list.querySelectorAll('.br-list').forEach((trigger) => {
        if (window.matchMedia('(max-width: 992px)').matches) {
          trigger.style.display = 'none'
        }
      })

      this.list.querySelectorAll('.br-item').forEach((trigger) => {
        trigger.addEventListener('click', (e) => {
          if (window.matchMedia('(max-width: 992px)').matches) {
            this._showList(e)
          }
        })
        this.britems.push(trigger)
      })
    }
  }

  /**
   * Controla a abertura e fachamento da lista
   * @param {object} e - Objeto Event
   * @private
   */
  _showList(e) {
    parent = e.target.parentElement

    parent = parent.classList.contains('col-2')
      ? e.target.parentElement
      : e.target.parentElement.parentElement
    parent = parent.classList.contains('col-2')
      ? parent
      : e.target.parentElement.parentElement.parentElement
    // debugger
    this._closeAllColumns(parent)

    parent.querySelectorAll('.br-list ').forEach((trigger) => {
      trigger.style.display =
        trigger.style.display === 'block' ? 'none' : 'block'

      const iconComponent = parent.querySelector('i')

      trigger.style.display === 'block'
        ? this._iconAngleUP(iconComponent)
        : this._iconAngleDOWN(iconComponent)
    })
  }

  /**
   * Fecha todas colunas do Footer
   */
  _closeAllColumns(target) {
    this.component
      .querySelectorAll('.br-list:not(.horizontal)')
      .forEach((trigger) => {
        if (target !== trigger.parentElement) {
          trigger.style.display = 'none'
          this.component
            .querySelectorAll('.header i')
            .forEach((iconComponent) => {
              this._iconAngleDOWN(iconComponent)
            })
        }
      })
  }

  /**
   *Inclui ícone 'fa-angle-up'
   * @param {objetc} iconComponent - Elemento DOM que representa um ícone
   * @private
   */
  _iconAngleUP(iconComponent) {
    iconComponent.classList.remove('fa-angle-down')
    iconComponent.classList.add('fa-angle-up')
  }

  /**
   * Inclui ícone 'fa-angle-down'
   * @param {object} iconComponent - Elemento DOM que representa um ícone
   * @private
   */
  _iconAngleDOWN(iconComponent) {
    iconComponent.classList.remove('fa-angle-up')
    iconComponent.classList.add('fa-angle-down')
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRFooter);


/***/ }),

/***/ "./src/components/header/header.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/tooltip.js");


/** Classe para instanciar um objeto BRHeader*/
class BRHeader {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this.componentSearch = this.component.querySelector('.header-search')
    this.componentSearchInput = this.component.querySelector(
      '.header-search input'
    )
    this.componentSearchTrigger = this.component.querySelector(
      '[data-toggle="search"]'
    )
    this.componentSearchDismiss = this.component.querySelector(
      '[data-dismiss="search"]'
    )
    this.hideDrop = null
    this.menuTrigger = this.component.querySelector(
      '[data-target="#main-navigation"]'
    )
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    this._setLogoutBehavior()
    this._setSearchBehaviors()
    this._setKeyboardBehaviors()
    this._setDropdownBehavior()
    this._setSticky()
  }

  /**
   * Define comportamentos de login
   * @private
   */
  _setLoginBehavior() {
    for (const login of this.component.querySelectorAll(
      '[data-trigger="login"]'
    )) {
      login.addEventListener('click', () => {
        const loginParent = login.closest('.header-login')
        loginParent.querySelector('.header-sign-in').classList.add('d-none')
        loginParent.querySelector('.header-avatar').classList.remove('d-none')
      })
    }
  }

  /**
   * Define comportamentos de logout
   * @private
   */
  _setLogoutBehavior() {
    for (const logout of this.component.querySelectorAll(
      '[data-trigger="logout"]'
    )) {
      logout.addEventListener('click', () => {
        const logoutParent = logout.closest('.header-login')
        logoutParent.querySelector('.avatar').classList.remove('show')
        logoutParent
          .querySelector('[data-toggle="dropdown"]')
          .classList.remove('active')
        logoutParent.querySelector('.header-sign-in').classList.remove('d-none')
        logoutParent.querySelector('.header-avatar').classList.add('d-none')
      })
    }
  }

  /**
   * Define comportamentos de busca
   * @private
   */
  _setSearchBehaviors() {
    // Abrir busca
    if (this.componentSearchTrigger) {
      this.componentSearchTrigger.addEventListener('focus', () => {
        this._cleanDropDownHeader()
      })
      this.componentSearchTrigger.addEventListener('click', () => {
        this._openSearch()
      })
    }

    // Fechar busca
    if (this.componentSearchDismiss) {
      this.componentSearchDismiss.addEventListener('click', () => {
        this._closeSearch()
      })
    }
  }

  /**
   * Define comportamentos do teclado
   * @private
   */
  _setKeyboardBehaviors() {
    if (this.componentSearchInput) {
      this.componentSearchInput.addEventListener('keydown', (event) => {
        switch (event.keyCode) {
          // Tecla ESC
          case 27:
            this._closeSearch()
            break
          default:
            break
        }
      })
    }
    for (const trigger of this.component.querySelectorAll(
      '.dropdown [data-toggle="dropdown"]'
    )) {
      trigger.addEventListener('keydown', (event) => {
        switch (event.keyCode) {
          // Tecla ESC
          case 32:
            if (event.target.parentNode.classList.contains('show')) {
              event.target.parentNode.click()
              event.target.parentNode.classList.remove('show')
              event.target.classList.remove('active')
              event.stopPropagation()
            }
            break
          default:
            break
        }
      })
    }
  }

  /**
   * Visualização da busca
   * @private
   */
  _openSearch() {
    if (this.componentSearch) {
      this.componentSearch.classList.add('active')
      this.componentSearch.querySelector('input').focus()
    }
  }

  /**
   * Esconde a busca
   * @private
   */
  _closeSearch() {
    if (this.componentSearch) {
      this.componentSearch.classList.remove('active')
      //this.componentSearchTrigger.focus()
      this._nextFocusElement().focus()
    }
  }

  /**
   * Exibe notificação
   * @param {event} event - referencia ao evento
   */
  handleEvent(event) {
    const hasNotficiationElemeent = this.component
      .querySelector('.br-notification')
      .contains(event.target)
    if (!hasNotficiationElemeent) {
      if (this.activateTr !== event.target.parentNode) {
        this._cleanDropDownHeaderRef(this.component)
      }
    }
  }

  /**
   * Define comportamentos do dropdown
   * @private
   */
  _setDropdownBehavior() {
    // TODO: Trocar o código abaixo pelo utilitário dropdown
    this.cleaned = false
    let hideDrop
    this.activateTr = 'teste'

    for (const trigger of this.component.querySelectorAll(
      '.dropdown [data-toggle="dropdown"]'
    )) {
      // eslint-disable-next-line no-loop-func
      trigger.addEventListener('click', (event) => {
        this._headerTooltip()
        clearTimeout(hideDrop)

        this.activateTr = trigger
        document.addEventListener('mousedown', this, false)
        event.stopImmediatePropagation()
        // Toggle de abrir / fechar
        const hasShow = trigger.classList.contains('active')

        if (hasShow) {
          trigger.classList.remove('active')
          trigger.closest('.dropdown').classList.remove('show')
        } else {
          this._cleanDropDownHeader()
          trigger.classList.add('active')
          trigger.closest('.dropdown').classList.add('show')

          // Evita que o componente feche o drop ao navegar pelo teclado
          const next = this._nextFocusElement()
          next.addEventListener('focus', () => {
            clearTimeout(hideDrop)
          })
        }
        event.stopPropagation()
        return ''
      })

      // Faz o drop fechar ao clicar fora
      // eslint-disable-next-line no-loop-func
    }
    this.menuTrigger.addEventListener('focus', () => {
      this._cleanDropDownHeader()
    })
  }

  /**
   * Define comportamentos do tooltip
   * @private
   */
  _headerTooltip() {
    if (this.TooltipExampleList) {
      this.TooltipExampleList.forEach((tooltipElem) => {
        tooltipElem.component.remove()
        tooltipElem.popperInstance.destroy()
      })
    }
    this.TooltipExampleList = []

    this.component
      .querySelectorAll('.notification-tooltip')
      .forEach((TooltipExample) => {
        const texttooltip = TooltipExample.getAttribute('data-tooltip-text')
        const config = {
          activator: TooltipExample,
          placement: 'top',
          textTooltip: texttooltip,
        }
        const x = new _partial_js_behavior_tooltip__WEBPACK_IMPORTED_MODULE_0__["default"](config)

        this.TooltipExampleList.push(x)
      })
  }

  /**
   * Limpa referencias do dorpdown
   * @private
   * @param {object} ref - Referência ao componente header
   */
  _cleanDropDownHeaderRef(ref) {
    if (this.cleaned === false) {
      for (const trigger of ref.querySelectorAll('.dropdown.show')) {
        trigger.classList.remove('show')
        trigger.parentNode.classList.remove('show')
        for (const button of ref.querySelectorAll('.br-button')) {
          button.classList.remove('active')
        }
      }
    }
    this.cleaned = false
  }

  /**
   * Limpa referencias do dorpdown
   * @private
   */
  _cleanDropDownHeader() {
    this._cleanDropDownHeaderRef(this.component)
  }

  /**
   * Define modo sticky ao header
   * @private
   */
  _setSticky() {
    if (this.component.hasAttribute('data-sticky')) {
      window.onscroll = () => {
        if (window.pageYOffset > this.component.offsetHeight) {
          this.component.classList.add('sticky', 'compact')
        } else {
          this.component.classList.remove('sticky', 'compact')
        }
      }
    }
  }

  /**
   * Foca no próximo elmento de ação
   * @private
   */
  _nextFocusElement() {
    //lista de elementos de ação
    const focussableElements =
      'a:not([disabled]), button:not([disabled]), input[type=text]:not([disabled]), [tabindex]:not([disabled]):not([tabindex="-1"])'
    if (document.activeElement) {
      const focussable = Array.prototype.filter.call(
        document.body.querySelectorAll(focussableElements),
        (element) => {
          //check for visibility while always include the current activeElement
          return (
            element.offsetWidth > 0 ||
            element.offsetHeight > 0 ||
            element === document.activeElement
          )
        }
      )
      const index = focussable.indexOf(document.activeElement)
      if (index > -1) {
        const nextElement = focussable[index + 1] || focussable[0]
        //nextElement.focus();
        return nextElement
      }
    }
    return null
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRHeader);


/***/ }),

/***/ "./src/components/input/input.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRInput*/
class BRInput {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._currentFocus = -1
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    this._setPasswordViewBehavior()
    this._setAutocompleteBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setPasswordViewBehavior() {
    for (const inputPassword of this.component.querySelectorAll(
      'input[type="password"]'
    )) {
      if (!inputPassword.disabled) {
        for (const buttonIcon of inputPassword.parentNode.querySelectorAll(
          '.br-button'
        )) {
          buttonIcon.addEventListener(
            'click',
            (event) => {
              this._toggleShowPassword(event)
            },
            false
          )
        }
      }
    }
  }

  /**
   * Define comportamentos do componente
   * @private
   * @param {event} event - referência ao elemento que dispara a ação
   */
  _toggleShowPassword(event) {
    for (const icon of event.currentTarget.querySelectorAll('.fas')) {
      if (icon.classList.contains('fa-eye')) {
        icon.classList.remove('fa-eye')
        icon.classList.add('fa-eye-slash')
        for (const input of this.component.querySelectorAll(
          'input[type="password"]'
        )) {
          input.setAttribute('type', 'text')
        }
      } else if (icon.classList.contains('fa-eye-slash')) {
        icon.classList.remove('fa-eye-slash')
        icon.classList.add('fa-eye')
        for (const input of this.component.querySelectorAll(
          'input[type="text"]'
        )) {
          input.setAttribute('type', 'password')
        }
      }
    }
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setAutocompleteBehavior() {
    for (const inputAutocomplete of this.component.querySelectorAll(
      'input.search-autocomplete'
    )) {
      inputAutocomplete.addEventListener(
        'input',
        (event) => {
          this._clearSearchItems()
          this._buildSearchItems(event.currentTarget)
        },
        false
      )
      inputAutocomplete.addEventListener(
        'keydown',
        (event) => {
          this._handleArrowKeys(event)
        },
        false
      )
    }
  }

  /**
   * Monta os items de busca para o elemento input
   * @private
   * @param {object} element - referencia ao elemento input
   */
  _buildSearchItems(element) {
    const searchList = window.document.createElement('div')
    searchList.setAttribute('class', 'search-items')
    this.component.appendChild(searchList)
    if (element.value !== '') {
      for (const data of this.dataList) {
        if (
          data.substr(0, element.value.length).toUpperCase() ===
          element.value.toUpperCase()
        ) {
          const item = window.document.createElement('div')
          item.innerHTML = `<strong>${data.substr(
            0,
            element.value.length
          )}</strong>`
          item.innerHTML += data.substr(element.value.length)
          item.innerHTML += `<input type="hidden" value="${data}">`
          item.addEventListener(
            'click',
            (event) => {
              for (const input of event.currentTarget.querySelectorAll(
                'input[type="hidden"]'
              )) {
                element.value = input.value
              }
              this._clearSearchItems()
            },
            false
          )
          searchList.appendChild(item)
        }
      }
    } else {
      this._clearSearchItems()
    }
  }

  /**
   * Limpa elementos da busca
   * @private
   */
  _clearSearchItems() {
    for (const searchItems of this.component.querySelectorAll(
      '.search-items'
    )) {
      for (const item of searchItems.querySelectorAll('div')) {
        searchItems.removeChild(item)
      }
      this.component.removeChild(searchItems)
    }
  }

  /**
   * Define comportamentos do teclado
   * @private
   * @param {event} event - referência ao elemento que dispara a ação do teclado
   */
  _handleArrowKeys(event) {
    switch (event.keyCode) {
      case 13:
        if (this._currentFocus > -1) {
          event.preventDefault()
          for (const searchItems of this.component.querySelectorAll(
            '.search-items'
          )) {
            for (const itemActive of searchItems.querySelectorAll(
              'div.is-active'
            )) {
              itemActive.click()
            }
          }
          this._currentFocus = -1
        }
        break
      case 38:
        if (this._currentFocus > 0) {
          this._currentFocus -= 1
        }
        this._switchFocus()
        break
      case 40:
        for (const searchItems of this.component.querySelectorAll(
          '.search-items'
        )) {
          if (
            this._currentFocus <
            searchItems.querySelectorAll('div').length - 1
          ) {
            this._currentFocus += 1
          }
        }
        this._switchFocus()
        break
      default:
        break
    }
  }

  /**
   * Muda o foco do item de busca
   * @private
   */
  _switchFocus() {
    for (const searchItems of this.component.querySelectorAll(
      '.search-items'
    )) {
      for (const [index, item] of searchItems
        .querySelectorAll('div')
        .entries()) {
        if (index === this._currentFocus) {
          item.classList.add('is-active')
        }
        if (index !== this._currentFocus) {
          item.classList.remove('is-active')
        }
      }
    }
  }

  /**
   * Preenche lista de dados
   * @private
   * @param {string[]} dataList - Lista de dados
   */
  setAutocompleteData(dataList) {
    this.dataList = dataList
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRInput);


/***/ }),

/***/ "./src/components/item/item.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRItem*/

class BRItem {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    this._setCheckboxSelection()
    this._setRadioSelection()
  }

  /**
   * Define comportamentos do checkbox
   * @private
   */
  _setCheckboxSelection() {
    for (const checkbox of this.component.querySelectorAll(
      '.br-checkbox input[type="checkbox"]'
    )) {
      if (checkbox.checked) {
        this.component.classList.add('selected')
      }
      checkbox.addEventListener('click', (event) => {
        if (event.currentTarget.checked) {
          this.component.classList.add('selected')
        } else {
          this.component.classList.remove('selected')
        }
      })
    }
  }

  /**
   * Define comportamentos do radio
   * @private
   */
  _setRadioSelection() {
    for (const radio of this.component.querySelectorAll(
      '.br-radio input[type="radio"]'
    )) {
      if (radio.checked) {
        radio.setAttribute('checked', '')
        this.component.classList.add('selected')
      }
      radio.addEventListener('click', (event) => {
        for (const item of this.component.parentElement.querySelectorAll(
          '.br-item'
        )) {
          for (const radioItem of item.querySelectorAll(
            '.br-radio input[type="radio"]'
          )) {
            if (radioItem === event.currentTarget) {
              radioItem.setAttribute('checked', '')
              item.classList.add('selected')
            } else {
              radioItem.removeAttribute('checked')
              item.classList.remove('selected')
            }
          }
        }
      })
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRItem);


/***/ }),

/***/ "./src/components/list/list.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_collapse__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/collapse.js");

/**
 * Classe do componente BRList
 */
class BRList {
  /**
   * Instancia um componente BRList
   * @param {string} name - Nome do componente (br-list)
   * @param {object} component - Objeto que referencia o elemento DOM do componente
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Controla os comportamentos da list
   * @private
   */
  _setBehavior() {
    this._setCollapseBehavior()
  }

  /**
   * Trata do comportamento de collapse da list
   * @private
   */
  _setCollapseBehavior() {
    // data-toggle="data-toggle"
    // debugger

    // this.component.querySelectorAll('.br-list').forEach((trigger) => {
    //   // trigger.style.display = 'none'
    // })
    this.component
      .querySelectorAll('[data-toggle="collapse"]')
      .forEach((trigger) => {
        const config = {
          iconToHide: 'fa-chevron-up',
          iconToShow: 'fa-chevron-down',
          trigger,
          useIcons: true,
        }
        const collapse = new _partial_js_behavior_collapse__WEBPACK_IMPORTED_MODULE_0__["default"](config)
        collapse.setBehavior()
      })
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRList);


/***/ }),

/***/ "./src/components/menu/menu.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRMenu*/
class BRMenu {
	/**
	 * Instancia do objeto
	 * @param {string} name - Nome do componente em minúsculo
	 * @param {object} component - Objeto referenciando a raiz do componente DOM
	 */
	constructor(name, component) {
		this.name = name
		this.component = component
		this.id = this.component.id
		this.breakpoints = this.component.dataset.breakpoints
			? this.component.dataset.breakpoints.split(' ')
			: ['col-sm-4', 'col-lg-3']
		this.pushShadow = 'shadow-lg-right'
		this.trigger = document.querySelector(`[data-target="#${this.id}"]`)
		this.contextual = this.component.querySelector(
			'[data-toggle="contextual"]'
		)
		this.dismiss = this.component.querySelectorAll('[data-dismiss="menu"]')
		this.scrim = this.component.querySelector('.menu-scrim')
		this.componentFolders = this.component.querySelectorAll('.menu-folder')
		this.componentItems = this.component.querySelectorAll('.menu-item')
		this._setBehavior()
	}

	/**
	 * Define comportamentos do componente
	 * @private
	 */
	_setBehavior() {
		this._toggleMenu()
		this._setDropMenu()
		this._setSideMenu()
		this._setKeyboardBehaviors()
		this._setBreakpoints()
		this._setView()
		window.addEventListener('resize', () => {
			this._setView()
		})
	}

	/**
	 * Define visual do componente
	 * @private
	 */
	_setView() {
		const template = document.querySelector('body')
		const menuContextual = document.querySelector('.menu-trigger')
		// const panel = document.querySelector('.menu-panel')
		if (menuContextual && window.innerWidth < 992) {
			template.classList.add('mb-5')
		} else {
			template.classList.remove('mb-5')
		}
	}

	/**
	 * Define breakpoints do menu
	 * @private
	 */
	_setBreakpoints() {
		if (!this.component.classList.contains('push') && !this.contextual) {
			this.component
				.querySelector('.menu-panel')
				.classList.add(...this.breakpoints)
		}
	}

	/**
	 * Define ações do teclado
	 * @private
	 */
	_setKeyboardBehaviors() {
		// Fechar com tecla ESC
		this.component.addEventListener('keyup', (event) => {
			switch (event.code) {
				case 'Escape':
					this._closeMenu()
				default:
					break
			}
		})
		// Fechar com Tab fora do menu
		if (this.scrim) {
			this.scrim.addEventListener('keyup', () => {
				return this._closeMenu()
			})
		}
	}

	/**
	 * Define comportamentos de abrir/fechar menu
	 * @private
	 */
	_toggleMenu() {
		const trigger = this.contextual ? this.contextual : this.trigger
		// Clicar no trigger
		if (trigger) {
			trigger.addEventListener('click', () => {
				// Fechar Menu caso esteja aberto
				if (this.component.classList.contains('active')) {
					this._closeMenu()
					return
				}
				// Abre Menu
				this._openMenu()
				this._focusNextElement()
			})
		}
		// Clicar no dismiss
		for (const close of this.dismiss) {
			close.addEventListener('click', () => {
				return this._closeMenu()
			})
		}
	}

	/**
	 * Define visual do menu aberto
	 * @private
	 */
	_openMenu() {
		this.component.classList.add('active')
		if (this.component.classList.contains('push')) {
			this.component.classList.add(...this.breakpoints, 'px-0')
		}
		this.component.focus()
	}

	/**
	 * Define visual do menu fechado
	 * @private
	 */
	_closeMenu() {
		this.component.classList.remove('active')
		if (this.component.classList.contains('push')) {
			this.component.classList.remove(...this.breakpoints, 'px-0')
		}
		this._focusNextElement()
	}

	/**
	 * Configura Drop Menu para filho imediato de ".menu-folder"
	 * @private
	 */
	_setDropMenu() {
		for (const item of this.component.querySelectorAll(
			'.menu-folder > a.menu-item'
		)) {
			// Inclui ícone de Drop Menu
			this._createIcon(item, 'fa-chevron-down')
			// Configura como Drop Menu
			item.parentNode.classList.add('drop-menu')
			// Inicializa Drop Menu
			this._toggleDropMenu(item)
		}
	}

	/**
	 * Foca no próximo elemento
	 * @private
	 */
	_focusNextElement() {
		//lista de elementos que desejamos focar
		const focussableElements =
			'a:not([disabled]), button:not([disabled]), input[type=text]:not([disabled]), [tabindex]:not([disabled]):not([tabindex="-1"])'
		if (document.activeElement) {
			const focussable = Array.prototype.filter.call(
				document.body.querySelectorAll(focussableElements),
				(element) => {
					// testa a visibilidade e inclui o elemento ativo
					return (
						element.offsetWidth > 0 ||
						element.offsetHeight > 0 ||
						element === document.activeElement
					)
				}
			)
			const index = focussable.indexOf(document.activeElement)
			if (index > -1) {
				const nextElement = focussable[index + 1] || focussable[0]
				nextElement.focus()
			}
		}
	}

	/**
	 * Configura Side Menu para quem não for filho imediato de ".menu-folder"
	 * @private
	 */
	_setSideMenu() {
		for (const ul of this.component.querySelectorAll('a.menu-item + ul')) {
			if (!ul.parentNode.classList.contains('menu-folder')) {
				// Inclui ícone de Side Menu
				this._createIcon(ul.previousElementSibling, 'fa-angle-right')
				// Configura como Side Menu
				ul.parentNode.classList.add('side-menu')
				// Inicializa Side Menu
				this._toggleSideMenu(ul.previousElementSibling)
			}
		}
	}

	/**
	 * Muda estado do Drop Menu - aberto/fechado
	 * @private
	 * @param {object} element - referência ao Objeto que fará a ação
	 */
	_toggleDropMenu(element) {
		// Verifica se o elemento já possui click listener através de um atributo especial
		if (!element.hasAttribute('data-click-listener')) {
			element.addEventListener('click', () => {
				// Fecha Drop Menu caso esteja aberto
				if (element.parentNode.classList.contains('active')) {
					element.parentNode.classList.remove('active')
					return
				}

				// Abre Drop Menu
				element.parentNode.classList.add('active')
			})
			// Adiciona atributo especial para indicar que o elemento já possui click listener
			element.setAttribute('data-click-listener', 'true')
		}
	}

	/**
	 * Muda estado do Side Menu - aberto/fechado
	 * @private
	 * @param {object} element - referência ao Objeto que fará a ação
	 */
	_toggleSideMenu(element) {
		// Verifica se o elemento já possui click listener através de um atributo especial
		if (!element.hasAttribute('data-click-listener')) {
			element.addEventListener('click', () => {
				// Esconde todos os itens
				this._hideItems(element)

				// Mostra itens do Side Menu ativo
				this._showItems(element.parentNode)

				// Fecha Side Menu caso esteja aberto
				if (element.parentNode.classList.contains('active')) {
					this._closeSideMenu(element)
					element.focus()
					return
				}

				// Abre Side Menu
				element.parentNode.classList.add('active')
				element.focus()
			})
			// Adiciona atributo especial para indicar que o elemento já possui click listener
			element.setAttribute('data-click-listener', 'true')
		}
	}

	/**
	 * Fecha Side Menu
	 * @private
	 * @param {object} element - referência ao Objeto que fará a ação
	 */
	_closeSideMenu(element) {
		element.parentNode.classList.remove('active')
		// Verifica se existe Side Menu anterior, caso contrário mostra todos os itens de volta
		const parentFolder = element.parentNode.closest('.side-menu.active')
			? element.parentNode.closest('.side-menu.active')
			: element.closest('.menu-body')
		this._showItems(parentFolder)
	}

	/**
	 * Esconde os elementos proximos a referencia
	 * @private
	 * @param {object} element - referencia ao Objeto que fará a ação
	 */
	_hideItems(element) {
		for (const item of element
			.closest('.menu-body')
			.querySelectorAll('.menu-item')) {
			item.setAttribute('hidden', '')
		}
	}

	/**
	 * Mostra os elementos proximos a referencia
	 * @private
	 * @param {object} element - referência ao Objeto que fará a ação
	 */
	_showItems(element) {
		for (const item of element.querySelectorAll('.menu-item')) {
			item.removeAttribute('hidden')
		}
	}

	/**
	 * Cria icone filho a referencia
	 * @private
	 * @param {object} element - referência ao Objeto pai
	 * @param {string} icon - nome da classe font awesome do ícone
	 */
	_createIcon(element, icon) {
		// Verifica se já existe container para o ícone
		if (!element.querySelectorAll('span.support').length) {
			const menuIconContainer = document.createElement('span')
			menuIconContainer.classList.add('support')

			const menuIcon = document.createElement('i')
			menuIcon.classList.add('fas')
			menuIcon.classList.add(icon)
			menuIcon.setAttribute('aria-hidden', 'true')

			menuIconContainer.appendChild(menuIcon)
			element.appendChild(menuIconContainer)
		}
	}
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRMenu);


/***/ }),

/***/ "./src/components/message/message.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRAlert */
class BRAlert {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    for (const button of this.component.querySelectorAll(
      '.br-message .close'
    )) {
      button.addEventListener('click', () => {
        this._dismiss(this.component)
      })
    }
  }

  /**
   * Desvincula a instancia do objeto
   * @private
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  _dismiss(component) {
    component.parentNode.removeChild(component)
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRAlert);


/***/ }),

/***/ "./src/components/modal/modal.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _scrim_scrim__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/components/scrim/scrim.js");


/** Classe para instanciar um objeto BRModal*/
class BRModal {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    for (const brScrim of window.document.querySelectorAll('.br-scrim')) {
      const scrim = new _scrim_scrim__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z('br-scrim', brScrim)
      for (const button of window.document.querySelectorAll(
        '.br-scrim + button'
      )) {
        button.addEventListener('click', () => {
          scrim.showScrim()
        })
      }
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRModal);


/***/ }),

/***/ "./src/components/notification/notification.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/tooltip.js");

/** Classe para instanciar um objeto BRNotification*/
class BRNotification {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this.menuBtns = component.querySelectorAll('.contextual-btn')
    this.hideEvents = ['mouseleave', 'blur']
    this._setBehavior()
  }

  /**
   * Esconde a notificação relativa a referência
   * @private
   * @property {object} action - Referência ao Objeto que dispara a ação
   */
  _hideNotification(action) {
    const notification = action.parentNode.parentNode
    notification.setAttribute('hidden', '')
  }

  /**
   * Esconde todas as notificações relativa a referência
   * @private
   * @property {object} action - Referência ao Objeto que dispara a ação
   */
  _hideAllNotifications(action) {
    const notifications =
      action.parentNode.parentNode.parentNode.querySelectorAll('.br-item')
    notifications.forEach((notification) => {
      notification.setAttribute('hidden', '')
    })
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    for (const button of this.component.querySelectorAll(
      '.br-notification .close'
    )) {
      button.addEventListener('click', () => {
        this._dismiss(this.component)
      })
    }
    this._notificationTooltip()
  }

  /**
   * Define tooltip para a notificação
   * @private
   */
  _notificationTooltip() {
    const TooltipExampleList = []

    window.document
      .querySelectorAll(':not(.br-header) .notification-tooltip')
      .forEach((TooltipNotification) => {
        const texttooltip =
          TooltipNotification.getAttribute('data-tooltip-text')
        const config = {
          activator: TooltipNotification,
          placement: 'top',
          textTooltip: texttooltip,
        }
        for (
          parent = TooltipNotification.parentNode;
          parent;
          parent = parent.parentNode
        ) {
          if (parent.classList)
            if (parent.classList.contains('header-avatar')) {
              return ''
            }
        }
        TooltipExampleList.push(new _partial_js_behavior_tooltip__WEBPACK_IMPORTED_MODULE_0__["default"](config))
        return ''
      })
  }

  /**
   * Adiciona classe para refletir comportamento de fechar
   * @private
   * @property {object} componente - Referência ao Objeto
   */
  _dismiss(component) {
    component.classList.add('close')
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRNotification);


/***/ }),

/***/ "./src/components/pagination/pagination.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRPagination*/
class BRPagination {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this.currentPage = 1
    this._setBehaviors()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehaviors() {
    this._setActive()
    this._dropdownBehavior()
  }

  /**
   * Define visual do componente
   * @private
   */
  // eslint-disable-next-line complexity
  _setLayout() {
    const ul = this.component.querySelector('ul')
    const pages = this.component.querySelectorAll('.page')
    pages.forEach((page) => {
      if (page.classList.contains('active')) {
        this.currentPage = parseInt(page.querySelector('a'))
      }
      page.classList.remove('d-none')
    })

    if (this.currentPage === 1) {
      ul.querySelector('[data-previous-page]').setAttribute('disabled', '')
    } else {
      ul.querySelector('[data-previous-page]').removeAttribute('disabled')
    }

    if (this.currentPage === pages.length) {
      ul.querySelector('[data-next-page').setAttribute('disabled', '')
    } else {
      ul.querySelector('[data-next-page]').removeAttribute('disabled')
    }

    if (pages.length > 9) {
      if (this.currentPage < 6) {
        if (ul.querySelector('[data-previous-interval]')) {
          ul.querySelector('[data-previous-interval]').remove()
        }
        for (let page = 7; page < pages.length - 1; page++) {
          pages[page].classList.add('d-none')
        }
        if (!ul.querySelector('[data-next-interval]')) {
          ul.insertBefore(
            this._createIntervalElement('next'),
            ul.children[ul.children.length - 2]
          )
        }
      }
      if (this.currentPage >= 6 && this.currentPage < pages.length - 4) {
        for (let page = this.currentPage - 4; page > 0; page--) {
          pages[page].classList.add('d-none')
        }
        if (!ul.querySelector('[data-previous-interval]')) {
          ul.insertBefore(
            this._createIntervalElement('previous'),
            ul.children[2]
          )
        }
        for (let page = this.currentPage + 2; page < pages.length - 1; page++) {
          pages[page].classList.add('d-none')
        }
        if (!ul.querySelector('[data-next-interval]')) {
          ul.insertBefore(
            this._createIntervalElement('next'),
            ul.children[ul.children.length - 2]
          )
        }
      }
      if (this.currentPage >= pages.length - 4) {
        if (ul.querySelector('[data-next-interval]')) {
          ul.querySelector('[data-next-interval]').remove()
        }
        for (let page = pages.length - 8; page > 0; page--) {
          pages[page].classList.add('d-none')
        }
        if (!ul.querySelector('[data-previous-interval]')) {
          ul.insertBefore(
            this._createIntervalElement('previous'),
            ul.children[2]
          )
        }
      }
    }
  }

  /**
   * Cria elemento no intervalo
   * @param {string} type - nome do tipo do elmento
   * @private
   */
  _createIntervalElement(type) {
    const interval = document.createElement('li')
    interval.setAttribute(`data-${type}-interval`, '')

    const a = document.createElement('a')
    a.setAttribute('href', 'javascript:void(0)')

    const icon = document.createElement('i')
    icon.classList.add('fas', 'fa-ellipsis-h')

    a.appendChild(icon)
    interval.appendChild(a)

    return interval
  }

  /**
   * Cria ação de clique na página
   * @private
   */
  _setActive() {
    for (const page of this.component.querySelectorAll('.page')) {
      page.addEventListener('click', (event) => {
        this._selectPage(event.currentTarget)
      })
    }
  }

  /**
   * Cria comportamentos do dropdown
   * @private
   */
  _dropdownBehavior() {
    for (const dropdown of this.component.querySelectorAll(
      '[data-toggle="dropdown"]'
    )) {
      this._dropdownInit(dropdown)
      this._dropdownToggle(dropdown)
    }
  }

  /**
   * Cria ação de clique no dropdown
   * @param {object} element - referencia ao objeto DOM
   * @private
   */
  _dropdownToggle(element) {
    element.addEventListener('click', () => {
      if (element.getAttribute('aria-expanded') === 'false') {
        this._dropdownOpen(element)
        return
      }
      this._dropdownClose(element)
    })
    window.document.addEventListener('click', (event) => {
      if (!this.component.contains(event.target)) {
        this._dropdownClose(element)
      }
    })
  }

  /**
   * Inicializa elemento de dropdown
   * @param {object} element - referencia ao objeto DOM
   * @private
   */
  _dropdownInit(element) {
    element.parentElement.classList.add('dropdown')
    element.nextElementSibling.setAttribute('role', 'menu')
    element.setAttribute('aria-haspopup', 'true')
    this._dropdownClose(element)
  }

  /**
   * Ação de abrir dropdown
   * @param {object} element - referencia ao objeto DOM
   * @private
   */
  _dropdownOpen(element) {
    element.setAttribute('aria-expanded', 'true')
    element.nextElementSibling.removeAttribute('hidden')
  }

  /**
   * Ação de fechar dropdown
   * @param {object} element - referencia ao objeto DOM
   * @private
   */
  _dropdownClose(element) {
    element.setAttribute('aria-expanded', 'false')
    element.nextElementSibling.setAttribute('hidden', '')
  }

  /**
   * Define página ativa
   * @param {object} currentPage - referencia ao objeto DOM
   * @private
   */
  _selectPage(currentPage) {
    this.component.querySelectorAll('.page').forEach((page) => {
      page.classList.remove('active')
    })
    currentPage.classList.add('active')
    this._setLayout()
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRPagination);


/***/ }),

/***/ "./src/components/scrim/scrim.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
class BRScrim {
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setType()
    this._setBehavior()
  }

  _setType() {
    if (this.component.classList.contains('foco')) {
      this._type = 'foco'
    }
    if (this.component.classList.contains('legibilidade')) {
      this._type = 'legibilidade'
    }
    if (this.component.classList.contains('inibicao')) {
      this._type = 'inibicao'
    }
  }

  _setBehavior() {
    if (this.component.classList.contains('foco')) {
      this.component.addEventListener('click', (event) => {
        this.outsideclick = true
        if (event.target.classList.contains('br-scrim')) {
          this.hideScrim(event)
        }
      })

      const allComp = this.component.querySelectorAll(
        `[data-dismiss=${this.component.id}]`
      )

      for (const buttonComponent of allComp) {
        buttonComponent.addEventListener('click', () => {
          this.component.classList.remove('active')
        })
      }
    }
  }

  hideScrim(event) {
    event.currentTarget.classList.remove('active')
  }

  showScrim() {
    if (this._type === 'foco') {
      this.component.classList.add('active')
    }
  }
}
// const scrimList = []
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRScrim);
for (const buttonBloco1 of window.document.querySelectorAll(
  '.scrimexemplo button'
)) {
  buttonBloco1.addEventListener('click', () => {
    const scrscrim = window.document.querySelector('#scrimexample')
    const scrimfoco = new BRScrim('br-scrim', scrscrim)
    scrimfoco.showScrim()
  })
}
/**
 * Exemplo de scrim com muito texto
 */
for (const scrimexamplebig of window.document.querySelectorAll(
  '#scrimexemplo-big'
)) {
  scrimexamplebig.addEventListener('click', () => {
    const scrscrim = window.document.querySelector('#scrimfocobig')
    const scrimfoco = new BRScrim('br-scrim', scrscrim)
    scrimfoco.showScrim()
  })
}

//Exemplo de scrim close


/***/ }),

/***/ "./src/components/select/select.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Classe do componente BRSelect
 */

class BRSelect {
  /**
   * Instancia o componente
   * @property {string} name - Nome do componente em minúsculo
   * @property {object} component - Objeto referenciando a raiz do componente DOM
   * @property {string} notFound - (Opcional) String contendo código html para informar que a busca não encontrou nenhuma opção.
   *                                Se não informado, será usado um código html padrão.
   */

  constructor(name, component, notFound) {
    this.name = name
    this.component = component
    this.notFound = notFound
    this.multiple = component.hasAttribute('multiple')
    this._setOptionsList()
    this._setBehavior()
  }
  /**
   * Retorna os valores dos elementos selecionados
   * @returns {Object[]} elementos selecionados
   *
   */
  get selected() {
    return this._optionSelected('value')
  }
  /**
   * Retorna os elementos selecionados pelo inputValue
   * @param {Object[]} elementos selecionados
   * @returns
   */
  get selectedValue() {
    return this._optionSelected('inputValue')
  }

  /**
   * Retorna os elementos options selecionados
   * @param {Object[]} elementos selecionados
   * @returns
   */
  _optionSelected(strOption) {
    let selected = []
    for (const [index, option] of this.optionsList.entries()) {
      if (!this.multiple) {
        if (option.selected) {
          selected = option[strOption]
          break
        }
      } else {
        if (index > 0 && option.selected) {
          selected.push(option[strOption])
        }
      }
    }
    return selected
  }

  /**
   * Remove o elemento que indica item não encontrado
   * @private
   */
  _removeNotFoundElement() {
    const list = this.component.querySelector('.br-list')
    if (list.querySelector('.br-item.not-found')) {
      list.removeChild(list.querySelector('.br-item.not-found'))
    }
  }

  /**
   * Coloca o texto Item não encontrado no select
   * @private
   */
  _addNotFoundElement() {
    const image = `data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGYAAABCCAYAAACl4qNCAAAABHNCSVQICAgIfAhkiAAAGMxJREFUeF7NXQl8FOXZf2bPbLKbbLIJARJChEQSCFc4IqAY/CiIgMZardYD0R9SW/3EVlAkSoAPQaAai1awleKnrVYR0wattVKxthWUI4AYCCEkMeQix26OPWdn+jwzzGaz2WM22YDv78dv1sx7/+e5n/eVgctU4mesH+9WqlZyttaZjEIdxznNsTzn1oDbYVaoY5qAUdWDSnuKc3Xtsh7fcewyTet7Owwz2DPT5z72Y85lfRrHmSB3LIZh6kAdvZ9RGbd0HSr6Vm67cOrlN5/S1/9m92xQKrN5HjI4u2OY22ZLUGg00YxaFc27ORfPslZ1QnwrA0wb73ZdAKX6qzFLbv5b6fCpVjljGQv+mg7ApptLFh+QU9+7zqABE59XNMHpbHuDZ22Twp1UrwlqYv+mYJSPdR7ecmYg/UypPxzt+PM/7nS0mhdxVnueu7t7eH/7U8UZWhil8jgDijcr1m56I1A/xoIPjApQFwPw5raSxSvCGW9QgDHkrbnWbWv5K/CcPpzJBK7Ls0qdaR/HxP60+9CapnD6zHn7tZmu2gvPuFra5nJOpyqctrLqKhiLIipqP+eyb6/etiMoZRAFmUsWVMvpN+LARI+9ewHPqN9DORIjZwLh1EEWZ2FUmuVdR1/+U6h241/91TJbQ8NK1tKZGapupN5zTtc3mnjjjsrnXnjFX58JBft249/b20oWPR5qzIgCo8+4eyyvi/mSBz421MD9f89zjEb/VvfiF5dCEcP59jPhzR13Wc9VbWLbLSP7P8bAWqKcatEMSXz59Mq163x7QnBWIDDI3oKXSAITFTNheQXPcyNCDRqJ9wqlujIqKfv6lr8/Wk/9TXt/16iOqu/2OurqJ0ai/0j0gbKoQpuaes+ph1Z87dufqaC0oLVkcUmgcSIGjGHaqp1ue/tDkViQ3D4Ynm9TqQ3zRzycPtVWXVOMAl0rt+3lqsdo1G5NcuIjZ54o2uE9ZkJBKVENKQVF/uYSEWBiZzyewXZ2fAsoWC7XgqVx4iZqWa3JFXmhHuGFaFNSnjmz6tn/6w1OYLYWEWD0k/+3hGNtt0R4LUG7Y5QAcRMUoIm/nKMOaCxek2haWfHMc7+S08uAgYkf9VCc06Bs4jm2/2yERxnOKFDdxyfvBkDC4912YJRR+HTiKyIIVCnQEhR+M06InxIF6kFUMeRsXrh1cPqsOinhtspnNv1Famss+HCSkuGLWj9YVODd34CBiZ35TCHb2bgh3En2AoG1oUdGh39yCQChpS38ZpTaXgCBQgmEizFXBWrDgKce9pQj0YBRKDrxA5tQXbyjWuqP1GgOmGJzycIy6W8DXl3M5EeP86xdtrtFogQgEBAAAsIDggSQQCVIQYDveARIgQCxVlBoo8E4GZBS6J1Ymkv/JnTjvNgKQxbNA01SYiT2b1D7UETryqo2FeNKApcBAxOd84ADP21NsEF4ZE+oQeE+K5ECqDqxKJFVeQAS3iEgRCUCQFiHqIaEiQCgC9mXDtRxvUcyHzwM5kNHEDQNpC79CT77z1EHFQ2fznWpw58tX7nWw2nIfQOgQc+ASDUDAkY//dnbOVvDuwEXxLEIhkoEgxQ2kh9UpL8FBKg3eDznhLjxGohK7qEUaczGPX8BtqMT2M4uGHrbYohK7bcL7HLiAoxW27bk+ZeSihjRSCZ3jZJhd7d+sDh/4MBMeeIVzmn5mb8VEZWQHCFARApAGSJQCxIXRwARBV0CzQMQUguBSVLGi4KihrIQO9Y/URIoRC30VMcbcRwVcA5HnykRJaG3WHj/fSnapMQnzxRu3CLNB22bIsmuGRDFGPJWf+i2ttzUa6E+VIK6VHgAscjiVMiOqB+GAaVeCQnTkJ3hf9OmSptLT6mgjwoBR6VBSezQDfTfvMsFbpsdbk1pgHP/PAa1bTzE58+AC7oMT70rzfbwgzpXtWV7hr8PZUDA6HNXfMu5urOljkUqQXmi0PSmEoGNcaTwhqYgFqmK5AvKIFAwYLoGf7s6PJup1NE7Djg7vVcA+qWQWjoAPbwC1TBqtaA4EFDZfCN8ev2/waxkIP0xpF5crXbeYlDqtCLlcJzQTqHBNleIkniNbkrN1uKj0h5KrpqBATP9qUaMSCZ7gEHhTewJlV2BJfUI8ktsTAZAInjEjixgGBsDSlUbgop/ww0XVGyyd6jgb84lUg2BRF8/UYwgwhA8VUw0zNPVwG+zDqAlqoD8jRx8fpKHmKxMiMkcDUp8T0VtjBUoTGWIUIQiTD6p1Ol+d25z8TIPMLeWHnDzbMGAgNGNW9KJm6jn8cujzfQYhYI2JW6gByCPwSgpAv4oCKmAYEWqURvdEDtGATkoa0ZpUXsjuYNUIPRJGp74C4OKGvHvgnrduyidnbAx+22o6GBh8moOpmapICV2InS53KCMMwhs8UxSAnAom4gSrwTVKPUxp85tfCHHA0zBh/cj3ykbEDDROQ/akX1pySAU7RGiEi82JgOgHhmEwLqsuOkIssoOxglINVYrfJU9AeKQ3fSnvHrwIOz8+mNIT0awkZjKqnioWVkEIzdvBguxQiw3T5wAh3OuFijuSoCDMtFR8/Lv+ixwYMCMX8b7szn6GoxEJaI2FJiCiP05UcY7wJRHcqVTYDFnJk7rDyZCm5ONjXDdjh6nbprRCCdWrIBFu3fDv6qrhTr35ObCJ6nJaB8ZBPZ2JRQClckws/LZbV9KCyU3zYCA0Y27vxv5V7Rfg9Hbou/l9xLlgK8M4pxdAmsyZCkgOlUHbFc3sOYOODN5ugcY+tL33X8/rP74Y1iYleXZ3D/ceWdA8D48fRqIcojqnsrPh/FDh/YB5q2jR8H0g3zQjUi5IrImZlTa3aceW/NHDztDORMeMPmfqeKY47cwLDvBbW8eynY13IeUgMwZ1VT82kXfFtkqZI+gnPF1uQQASAAK1WTtEHJOirqEo6EJ++DgdE6uZ9Npk69NTxcogb5+qXj/lkNevhRDwOiuSoPEufmgjNbJ6SKidXTpIx4vf7zQE9U03bqvRBYwyfcej7FV7dvhtjX9kHfZRHWGNlPYaNEoFEDo5XIJDpDodEDej0BSiRrConPSJAhkKi6zRVCLy7Nku+Fkb5Y3MHdePRo+rDwHDpNJ8BxQ8WZnl8MoVekNxZUbt/XKAwgJTPycV2e5rLWlnLVFjHwgCJJK29vvdckoFFbma9Ff8ntJFITUQSAS63JbWyE6TYeWvcGzIW6rDeWLU6CYG8uroBMteQMKZ3qqUSV2oREplcSYGGjp7oZ2m02gIjtZ9/jSiXWkNlQ3JS4OHPiuA4W+mf5ZzPCYrRXeRUN0TzdAXoIS6hbfLthA5Ekg1ZvYqUofI8g6snXIeKW/R1p70yQlbK4o3LRaWhO5Z4ICY7rh1RRHW3k557YZeqhEVHd5oNiJaHP0ACS5XC5Z5cI7icUhGAxZ7na0UdrxN7I/dNXEjI6CmBF6wdCTFk7sRPpSq1/aKZsSwqm4DFe+Fh2ibozpzGoAqENn95QFM6DBlCoYrQJHQNBIDZc8CgTSYICjHzf24W8eesyjpZhCyRj9tFUnOXu7R8cWJuvxeyEbI3c8L36hvYNbIkAEgmAs4pNn0fikisj+WEsN6NKG4z8NqHRiNFraDKIU0RoXN+e73/5/OPstu+56nMtSBIbyeW5vBTiEVDPdxMC53OtRQ+uJwBHVkhpN7h16DoZarRuZdlf5L9a8I00+qIwxzt2x1Nl0ZFevlaK7RbAzBMck+bTwK7/k26KnwMLoSa4ZpAjMA0M20CUoAgqNAdzdTaBO0ELqyGnAMX7c82Q4kt9ECBGgwYibsvPn46Cx6aIwDT2yLSpdyLqoDE0eAl3Ibs7X1MJVI9OEv3vXoffnzldDclKSUJ/qJSWaYNOWX0Nq+Wn4HeoPbdjltZhn04EMYNykBWA1TRQUADEehLLO3gUXvz1AnySoVGqImpyAXgUMPyRELqZtys0deWTJ8lrvvQ7IyvTXFB7hupt6VCKamuC+v+SdJQ+xUgRIMC7R/qBN5Z0W+vyRDXQJHmUOrW+VwYguew7ZViKCYwGn4wHZX/aZ7aMhPt4nCCO7tf+KM/MXwqGvj8G9SJTnVQpoww8AUORVj1oGuoy+qQuWL1YD23JS6GzI+DmgnpQUMR+bKtbQXLlhm8etJc3YLzBjbz+lqan4jQ1V4F5+Dg8bw2iiGKOnjxuVAaQKRh0t+LcE1uWyoJZlAE1iFGiTdKAx6QWZQU5GEuwubrnsrW16M1cgokiWG268DT7/QrTnpuai5X/0hPDbOO91NDL77BF4A5MwOg8gVQX67MyIGKPqBOP+s2ufnyutTwoz+11y0k8OZnaffL2i12aQdxhZEh6TELzHVIhVsZ11+IsFXWoqUoYDooab0M+F3lqf3aQYCWk3rnYz1n5Y9j4PBjCz594C//5SzMGbOmUyHC2/ADHjl4Fm+Ay/8+oFTNoUaG84ASMevDsiGlpUyrDlp1cVveYB5tbSsrYPFvu3/OOv27rQYa7Y5z1LohZM6BNZGcoQztYC0VeNQKOQR1AScJLBP2uiGBKgrtZ2YBV+Y2t+N2UwgJFYGQ2YPX4qNGcUBf1QfIFpqz0CKffeMWAXDio55qrNxbh5jOSVFSKZlHjudzcTF72Ta63Zf4RmS3JFVHkp7lEDSm0MRKUAGLKHgzJKdK/ILc4WVH+wtB8JmiLQq7vOw1v7UJ/c8QLV86aYUaMzoEHd4/bx14a1VKHMFBWO+GgeLtZ+AylL7hQ0R19PgfNiC6DcQLbuFJ7BiibJ9FpF4XMevk5xf3PJrWZq4xeYoUuPJlkOFTcTIFJhLedBn2EEQ04agtPXxR5qs0i2UECLxedFymyRWdiuC4MKzDXTc+HgV544VchZpYy4Ci58d14ARlKfpUb2unpofL9UAIRAG373jwL3p1B2q5JSr6p8+mlB5RTO0jCqA8TGAgJDL3RZd7dwzg4T/kP10AmmWZkoQ/rvRyJg3OjGZzu6MOXo45Ab4PkgBgEYb1Y2NXciCv/jsucjAUObTsE1b08AydG63/9RoBZj3hQwXjM1YL+YNLIOTwMUSRXoFAD9lk4CCBQjHQ3wPiKgn/b0HmfTodvUqOsnzh4dUoaEWhlNmkAhA3LNQvnq8sO3TxtUirnu2hlwx4NPBp3+S5sLofLMN0KdURnZUFVZ7mFl3mFpYmONe0o9mTpDFs/32y/aQHVnn92U5i1bfCsKwJgwasbxfCEqUv9CxO4XSGv2psmgqDpqnBg+2/I3G0ovouJsboEPNr8VCkfP+7x0lG8B9OWyE9/A/yy4HVovlPvt7423/gRvvPUu/OPj93u995YxeXlT4YXX/xp0Po8svQWOff0foU7mmBw4iyANu/OHaAbEh+03I71Jmzr0+oqn1v1LGtRYUJrve05TopjddEAU118pATPm+Y1z7HW1n6K5MmBkiFqI5B2NzWiYaeG91a9GBBjqpATZYsHiG/32Z0YPddmJU5A/e2ZAYGbNuga27CwNGxhJxoTr1DQf48Bljb2v+/Cv3hQIQMhd5oqlfDJpIr1YmfdhmozCXzbhVz5E9g4GqUiqMtkwJGNI1uzdsFt2t8EoxreTA//8j0BdI9NSIX1k4PNT3hQza9YMBMaT4+13Xv4oRlKXwwGmo5wHewOPjvWoz7rKtt9Ag5H4QG9cie/ZTP+W//bNW62V55+QvXsyKkqufHe3Fd4vel1GC7GKHGCIMqbMnAfVNd95+t2180VYcs+P/Y7jLfynoIH56zc+CZ9i0I4hf5nceI0EijAQBrKs3+wiZ5uYeOCn+AVm1FMrzJzNFlEHFbEzBzkj8Yveu/73EQfm8VVrEYg7BEohuZI+MjUgMN4UM3fuDbCuOPhZW38Uk/qAaPnLiXh2nubBVu+xIcW1M+5brCd2BSTVPsBM/P2vf2gpO9VbWsrexsAVSfgT1ZDHeM+zv5XdoxyKkd3ZpYreFJOXNw2F/0dhUwwJf8pJC5a8gdF2sJxEmYJ+Xd+CeRJbu8u2r5JNMVdvWPOas6XFk4AW7qID1RdYGUYOyS0TaVYW7hy9nZj9ZmV+DEzvedibeOiqwHAinTbxU1DOvItyxj+vJYKiaJm3RpC1bf179u8uBDFZw92GnvquNvSToYyJNCsLd0berOz666+D517ZGzbFjFh2n182Ro73ztNIJYJjJXDBWzW+6D65c3ZAivEFJvvFjR/Yqmt7HTsLd+H+6gsGJrIzSrDYUyg/XDzYrKy/FJP20/t7Wf0EiLUata5GH1kSYPOU0YkfdR7atDAgML7XaFy9/umdztbWsI+Ft33+HzzNZQL7hQZImD2jF+8lUKREcMo3vtLAeLOy/soYEv5k9XNuLXSf48FxUR4gEhCKmKEvdx3c8KhsGZO1df0j9roL28OlkpZPPoOu8gqP887bs0oqJSUxkKr8fZAx06+dD0eOiRHJ/mplI5Yvh+7zol3Sn6IakntXx/6HPXF+3z4YOiyDhxHQwBGPmOWfPx9V85sXLLzTKd83j+3oZBdRCxU6cucLjGRgkoPvSgv/SAATnbMEJXRYW+TZe4VGzyaOv9lQvXtOYDuGrH3EfJL3DQ2ZRas+cbVbfhDOl9D17RkBDHLk6ceO6ZM0R5QiHCrC55VWl71Z2fTpU+HFXeH7yqLG3CUko3hyIMLYLJUx88uOL1b19hP5tBfsGF85M+b5wjH2uqaTmCkSsZsuSPBTAp1CrYqoryyM/fBU9bZjbr55ITz53O6g3fgzMHXjlvYLFMpc1abPn4+EENTdEDAenLlxzeuu5hb5/vkQO0Qpr8TGKC3ovacj58TsDzDe6vKCBfOhcGtwb7c/YIhikCWFPbzalHXccuCXIS/X8wCTcCkJwGukqIxnnqjDzBZT2KP7aeCx/PFdOFpZZmwTmEwJkZiCp4/4YWOgAzN2qNx0042wZovg6A1YIkUxiqh4u370rdmN78yoDrWgHmBQCUCjp7q1ZKGHrq9a8/h0sNo/xWzE4MHrUKPgeymCGa53+Z5FUwRvcagiRRZD1WNRQ6QkQSpulHm5194IDz26Jnxgxi4RT2DLLMTClPHZSzo++7ms1NKQGVuji56c6e7o/ARXMaAb+0hldmKGDD3D0cpm5YhZlKGKFMAKVc/3/QM/WwkP/iygy0qoHgmKUSdPWG359NHNcufXBxgxPuDaLWVrUEc5L27Itrd3/oW1WPwefZYzmBSTcba2heWS+ef+j4SIob/yUck70Fgvuvr9AUObHqrMvmEBZGaND5tiojEPTW5RGkdu6/yiMPRkvDrsA0ygiBq1ydq27mV03S/nneHfDyYZmS48JRaOjAm2+CW3zfHE4tPSR0Nt9TkwzpwuXMTg7uqC0lf+LHfvBgUYgX1pTes7Dq1bG+5E/LKyYLed5hRvTHZYbZvwVtY7wmVvUtLfjOQMiLVgCGAApbPD4qEkt5uFE0cPCb0lF9wknm/Bc/sTjSkwnB/Y3TIOhx1OnRBS7ISE+rLDYmptKIphlGqX0jRuacf+n/+hP8sMKmPElBomjgPnS96sjQZacPastmbfuw852s33smZLLoZ0ZWX/ucxmwW9W/9Z7/ZlvyDbSfTKkBVqra6HtH1+EbNOfCsGAUeqHN2uTx93UUnKHiGg/SkjhTxk0GN0yB7tYc17j8ZiGvZ/c4Wgz34JG5GSMfqb5m4t3GLb+D3tAyszsx7z9NpGS7MgDISQYIjiNe/dh0ntP4qJ3Q0Ybj1eipAjZpm7zWdnTYKISQJd5W5/6eLqhWxWb/pLl818EV/NkjBQSGO8+6EANZtO0Y/LAulAXO2duWL2IYd0LkM3McXf2XGsi9UceZ7pjTCrkrhHYBV0jgrdgcHiaS3qKV5D0EKR0qst3fQSIb1oqnTAghYP6onbOdgVYayl6hTnYeLmQQiseUuKszTK2S6yi0JmEs6dSQbXZjWC9x3DsI51fbepZlOwe+1YMCxhqTsoBZodVE2uTLnhGoJDVBb6Be8HZg7HVHx/4KbKwH7Ft7dNYS8cApty/phRJpNh7uO75YKMhIKwyZtinamPaL1o/vM9/clv/pjuw+8pEoErz8RhGma8MCjSfPASJr2u8y15V+2NXV/cMW1V1/669CGPBlAjRXYlh3p4Lm8Jo3beqQhNzSqE2vKPL/NGLTW9OFK3VCJewKSbC48Oc82Xz2786ci/vcM5zNF9MwrB2xIZwtCIgeE0JK3pfBlSUUabT+H/o2KtwXNxlObL93IA6k9H4igPjPcc5NWXjUIbd6LhQv9je0DwVlYUYcnxSKIGKHNDo2gCFZjiYj9WFjLsH2h/UqlCGRHUoVNHHeIXm7zHJ03bWvz1VnMRlKt8rYHzXnF93PJVnmfFoQEzCsz14EwMzDEHT2OsbNXgAyum2Odqs56tsaoOh1tGm1LJdw9pdzkkuRqUfhflro7Hd1e7uRk82KV7hhQ6yntv/MJbSiZpZG9onuOmuemVcxmHgmQoVz59sfHv6qcuEgd9h/gsitjFaPKd5sQAAAABJRU5ErkJggg==`
    const notFoundElement = `
      <div class="br-item not-found">
        <div class="container pl-0 pr-0">
          <div class="row">
            <div class="col-auto">
              <img src="${image}">
            </div>
            <div class="col">
              <p><strong>Ops!</strong> Não encontramos o que você está procurando!</p>
            </div>
          </div>
        </div>
      </div>
    `
    const list = this.component.querySelector('.br-list')
    list.insertAdjacentHTML(
      'beforeend',
      this.notFound ? this.notFound : notFoundElement
    )
  }
  /**
   * Cria listagem de elementos do select
   * @private
   */
  _setOptionsList() {
    this.optionsList = []
    for (const item of this.component.querySelectorAll('.br-list .br-item')) {
      for (const input of item.querySelectorAll(
        '.br-radio input, .br-checkbox input'
      )) {
        const option = {
          element: item,
          focus: false,
          inputValue: input.value,
          selected: false,
          value: input.nextElementSibling.innerText,
          visible: true,
        }
        this.optionsList.push(option)
      }
    }
  }
  /**
   * Reseta estado da lista
   * @private
   */
  resetOptionsList() {
    this._unsetSelectionBehavior()
    this._setOptionsList()
    this._setSelectionBehavior()
  }
  /**
   * Define o comportamento do componente
   * @private
   */
  _setBehavior() {
    this._setSearchIcon()
    this._setDropdownBehavior()
    this._setKeyboardBehavior()
    this._setSelectionBehavior()
    this._setFilterBehavior()
  }
  /**
   * Define o comportamento de dropdown
   * @private
   */

  _setDropdownBehavior() {
    for (const input of this.component.querySelectorAll(
      '.br-input input[type="text"]'
    )) {
      input.addEventListener('focus', () => {
        this._openSelect()
        this._resetFocus()
      })
    }
    for (const trigger of this.component.querySelectorAll(
      '.br-input .br-button[data-trigger]'
    )) {
      trigger.addEventListener('click', () => {
        for (const list of this.component.querySelectorAll('.br-list')) {
          if (list.hasAttribute('expanded')) {
            this._closeSelect()
          } else {
            this._openSelect()
          }
        }
      })
    }
    window.document.addEventListener('click', (event) => {
      if (!this.component.contains(event.target)) {
        this._closeSelect()
      }
    })
  }
  /**
   * Define o comportamento de teclado
   * @private
   */

  _setKeyboardBehavior() {
    for (const input of this.component.querySelectorAll(
      '.br-input input[type="text"]'
    )) {
      input.addEventListener('keydown', this._handleKeydownOnInput.bind(this))
    }
    for (const list of this.component.querySelectorAll('.br-list')) {
      // eslint-disable-next-line complexity
      list.addEventListener('keydown', this._handleKeydownOnList.bind(this))
    }
  }
  /**
   * Retira o comportamento de teclado
   * @private
   */

  _unsetKeyboardBehavior() {
    for (const input of this.component.querySelectorAll(
      '.br-input input[type="text"]'
    )) {
      input.removeEventListener('keydown', this._handleKeydownOnInput, false)
    }
    for (const list of this.component.querySelectorAll('.br-list')) {
      // eslint-disable-next-line complexity
      list.addEventListener('keydown', this._handleKeydownOnList.bind(this))
    }
  }

  /**
   * Verifica a navegação
   * @param {object} event evento que foi ativado
   * @private
   */
  _handleKeydownOnInput(event) {
    //Close Select
    if (event.shiftKey && event.key === 'Tab') {
      this._closeSelect()
      this._resetFocus()
    }
    if (event.key === 'Tab' && !event.shiftKey) {
      event.target.parentNode.querySelector('.br-button').focus()
    }
    if (event.keyCode === 40) {
      event.preventDefault()
      for (const list of this.component.querySelectorAll('.br-list')) {
        list.focus()
        if (list === document.activeElement) {
          this._getNextItem().focus()
        }
      }
    }
  }
  /**
   * Define comportamentos de teclado na lista
   * @private
   */

  _handleKeydownOnList(event) {
    event.preventDefault()
    switch (event.keyCode) {
      case 9:
        this._closeSelect()
        this._resetFocus()
        break
      case 27:
        this._closeSelect()
        break
      case 32:
        this._setKeyClickOnOption(event.currentTarget)
        break
      case 38:
        this._getPreviousItem().focus()
        break
      case 40:
        this._getNextItem().focus()
        break
      default:
        break
    }
  }
  /**
   * Define comportamentos de teclado no option
   * @private
   */
  _setKeyClickOnOption(list) {
    for (const [index, item] of list.querySelectorAll('.br-item').entries()) {
      if (this.optionsList[index].focus) {
        for (const check of item.querySelectorAll(
          '.br-radio input[type="radio"], .br-checkbox input[type="checkbox"]'
        )) {
          check.click()
          this._sendEvent()
        }
      }
    }
  }

  /**
   * Envia o evento onchange
   * @private
   */
  _sendEvent() {
    const clickEvent = new CustomEvent('onChange', {
      bubbles: true,
      detail: this.component,
    })
    this.component.dispatchEvent(clickEvent)
  }

  /**
   * preseleciona o elemento apartir da classe css .selected
   * @private
   */
  _setDefaultSelected() {
    const selectedItems = this.component.querySelectorAll('.br-list .selected')

    const iterable = typeof selectedItems[Symbol.iterator]
    if (selectedItems !== null && iterable === 'function') {
      for (const item of selectedItems) {
        this._setSelected(this._positionSelected(item), item)
      }
    }
  }

  /**
   * Retorna posição do elemento no select
   * @param {element} component elemento que vai ser pesquisado
   * @returns {integer} valor da posição
   */
  _positionSelected(component) {
    for (const [index, componente] of this.component
      .querySelectorAll('.br-list .br-item')
      .entries()) {
      if (componente === component) {
        return index
      }
    }
    return 0
  }
  /**
   * Desfine comportamento do clique no checkbox
   * @param {int} index
   * @param {object} item -  Objeto do item clicado
   * @param {object} event  -  Objeto do evento do clique
   * @private
   */
  _handleClickOnCheck(index, item, event) {
    if (!this.multiple) {
      for (const [index2, item2] of this.component
        .querySelectorAll('.br-list .br-item')
        .entries()) {
        this._removeSelected(index2, item2)
      }
      this._setSelected(index, item)
      this._closeSelect()
    } else if (event.currentTarget.hasAttribute('checked')) {
      this._removeSelected(index, item)
    } else {
      this._setSelected(index, item)
    }
    if (item.hasAttribute('data-all')) {
      for (const check of item.querySelectorAll(
        '.br-checkbox input[type="checkbox"]'
      )) {
        if (!check.hasAttribute('checked')) {
          this._setAttribute()
          item.querySelectorAll('label')[0].innerText = 'Selecionar Todos'
        } else {
          for (const item2 of this.component.querySelectorAll(
            '.br-list .br-item'
          )) {
            for (const check2 of item2.querySelectorAll(
              '.br-checkbox input[type="checkbox"]'
            )) {
              if (!check2.hasAttribute('checked')) {
                check2.click()
              }
            }
          }
          item.querySelectorAll('label')[0].innerText = 'Deselecionar Todos'
        }
      }
    }
    this._sendEvent()
  }
  /**
   * Define comportamentos na seleção
   * @private
   */
  _setSelectionBehavior() {
    this.selectionHandler = []
    this._setDefaultSelected()
    for (const [index, item] of this.component
      .querySelectorAll('.br-list .br-item')
      .entries()) {
      for (const check of item.querySelectorAll(
        '.br-radio input[type="radio"], .br-checkbox input[type="checkbox"]'
      )) {
        this.selectionHandler.push({
          element: check,
          handler: this._handleClickOnCheck.bind(this, index, item),
        })
        check.addEventListener('click', this.selectionHandler[index].handler)
      }
    }
  }
  /**
   * retira comportamento  de clique na seleção
   * @private
   */
  _unsetSelectionBehavior() {
    this.selectionHandler.forEach((item) => {
      item.element.removeEventListener('click', item.handler, false)
    })
  }
  /**
   * Define comportamentos no filtro do input
   * @private
   */
  _setFilterBehavior() {
    for (const input of this.component.querySelectorAll(
      '.br-input input[type="text"]'
    )) {
      input.addEventListener('input', (event) => {
        let allHidden = true
        this._filter(event.currentTarget.value)
        for (const option of this.optionsList) {
          if (option.visible) {
            allHidden = false
          }
        }

        if (allHidden) {
          // event.currentTarget.value = event.currentTarget.value.slice(0, -1)
          this._filter(event.currentTarget.value)
        }
      })
    }
  }
  /**
   * Define filtro no list
   * @private
   */
  _filter(value) {
    let hasVisible = false
    for (const [index, item] of this.component
      .querySelectorAll('.br-list .br-item')
      .entries()) {
      this._removeNotFoundElement()
      if (!this.optionsList[index]) {
        continue
      }
      if (
        this.optionsList[index].value
          .toUpperCase()
          .indexOf(value.toUpperCase()) === -1
      ) {
        item.classList.add('d-none')
        this.optionsList[index].visible = false
      } else {
        item.classList.remove('d-none')
        this.optionsList[index].visible = true
        hasVisible = true
      }
    }
    if (hasVisible === false) {
      this._addNotFoundElement()
    }
  }
  /**
   * Define atributo checked com click
   * @private
   */
  _setAttribute() {
    for (const item2 of this.component.querySelectorAll('.br-list .br-item')) {
      for (const check2 of item2.querySelectorAll(
        '.br-checkbox input[type="checkbox"]'
      )) {
        if (check2.hasAttribute('checked')) {
          check2.click()
        }
      }
    }
  }

  /**
   * Seleciona o elemento e retira checked dos outros elementos
   * @param {integer} index Posição do elemento na lista
   * @param {*} item elemento em que vai ser selecionado
   * @private
   */
  _setSelected(index, item) {
    item.classList.add('selected')
    for (const check of item.querySelectorAll('.br-radio, .br-checkbox')) {
      for (const input of check.querySelectorAll(
        'input[type="radio"], input[type="checkbox"]'
      )) {
        input.setAttribute('checked', '')
      }
    }
    this.optionsList[index].selected = true
    this._setInput()
  }

  /**
   * Retira o estado selecionado do elemento
   * @param {integer} index Posição do elemento na lista
   * @param {*} item elemento em que vai ser desselecionado
   * @private
   */
  _removeSelected(index, item) {
    item.classList.remove('selected')
    for (const check of item.querySelectorAll('.br-radio, .br-checkbox')) {
      for (const input of check.querySelectorAll(
        'input[type="radio"], input[type="checkbox"'
      )) {
        input.removeAttribute('checked')
      }
      this.optionsList[index].selected = false
      this._setInput()
    }
  }
  /**
   * Determina o input
   * @private
   */
  _setInput() {
    for (const input of this.component.querySelectorAll(
      '.br-input input[type="text"]'
    )) {
      if (!this.multiple) {
        input.value = this.selected
      } else if (this.selected.length === 0) {
        input.value = ''
      } else {
        this.mountSelectedValues(input)
      }
    }
  }
  /**
   * Monta a string apresentada no campo input do multiselect
   * @param {object} input Referencia o elemento input do select
   */
  mountSelectedValues(input) {
    const GAP = 0.4
    let amount = 1
    let value = this.selected.toString().replaceAll(',', ', ')
    const tempSpan = document.createElement('span')
    tempSpan.innerHTML = value
    document.querySelector('body').insertAdjacentElement('beforeend', tempSpan)

    while (tempSpan.offsetWidth > input.offsetWidth - input.offsetWidth * GAP) {
      value = this.selected
        .slice(0, this.selected.length - amount)
        .toString()
        .replaceAll(',', ', ')
        .concat(` + (${amount})`)
      tempSpan.innerHTML = value
      amount++
    }
    input.value = value

    tempSpan.remove()
  }
  /**
   * Retorna elemento posterior ao focado
   * @returns {object}
   * @private
   */
  // eslint-disable-next-line complexity
  _getNextItem() {
    const list = this.component.querySelectorAll('.br-list .br-item')
    let iFocused
    let iVisible
    for (iFocused = 0; iFocused < this.optionsList.length; iFocused++) {
      if (this.optionsList[iFocused].focus) {
        for (
          iVisible = iFocused + 1;
          iVisible < this.optionsList.length;
          iVisible++
        ) {
          if (this.optionsList[iVisible].visible) {
            break
          }
        }
        break
      }
    }
    if (iFocused === this.optionsList.length) {
      for (const [index, option] of this.optionsList.entries()) {
        if (option.visible) {
          option.focus = true
          return list[index]
        }
      }
    } else if (iVisible < this.optionsList.length) {
      this.optionsList[iFocused].focus = false
      this.optionsList[iVisible].focus = true
      return list[iVisible]
    } else {
      return list[iFocused]
    }
    return ''
  }
  /**
   * Retorna elemento anterior ao focado
   * @returns {object}
   * @private
   */
  _getPreviousItem() {
    const list = this.component.querySelectorAll('.br-list .br-item')
    let iFocused
    let iVisible
    for (iFocused = 0; iFocused < this.optionsList.length; iFocused++) {
      if (this.optionsList[iFocused].focus) {
        for (iVisible = iFocused - 1; iVisible > 0; iVisible--) {
          if (this.optionsList[iVisible].visible) {
            break
          }
        }
        break
      }
    }
    if (iFocused === 0) {
      return list[iFocused]
    } else {
      this.optionsList[iFocused].focus = false
      this.optionsList[iVisible].focus = true
      return list[iVisible]
    }
  }
  /**
   * Reseta valor do input
   * @private
   */
  _resetInput() {
    for (const input of this.component.querySelectorAll(
      '.br-input input[type="text"]'
    )) {
      input.value = ''
    }
  }
  /**
   * Reseta o focus dos elementos
   * @private
   */
  _resetFocus() {
    for (const option of this.optionsList) {
      option.focus = false
    }
  }
  /**
   * Reseta o focus dos elementos visiveis
   * @private
   */

  _resetVisible() {
    const list = this.component.querySelectorAll('.br-list .br-item')
    for (const [index, option] of this.optionsList.entries()) {
      option.visible = true
      list[index].classList.remove('d-none')
    }
  }
  /**
   * Abre o select aberto
   * @private
   */
  _openSelect() {
    for (const list of this.component.querySelectorAll('.br-list')) {
      list.setAttribute('expanded', '')
    }
    for (const icon of this.component.querySelectorAll(
      '.br-input .br-button i'
    )) {
      icon.classList.remove('fa-angle-down')
      icon.classList.add('fa-angle-up')
    }
    this._resetInput()
  }
  /**
   * Fecha o select aberto
   * @private
   */
  _closeSelect() {
    for (const list of this.component.querySelectorAll('.br-list')) {
      list.removeAttribute('expanded')
    }
    for (const icon of this.component.querySelectorAll(
      '.br-input .br-button i'
    )) {
      icon.classList.remove('fa-angle-up')
      icon.classList.add('fa-angle-down')
    }
    this._setInput()
    this._resetFocus()
    this._resetVisible()
  }
  /**
   * Adiciona ícone de busca
   * @private
   */
  _setSearchIcon() {
    const brInput = this.component.querySelector('.br-input')
    const dropButton = this.component.querySelector('[data-trigger]')
    // Ícone de busca
    const searchIcon = document.createElement('i')
    searchIcon.classList.add('fas', 'fa-search')
    searchIcon.setAttribute('aria-hidden', 'true')
    // Container para o ícone
    const inputIcon = document.createElement('div')
    inputIcon.classList.add('input-icon')
    inputIcon.appendChild(searchIcon)
    // Estrutura para input com ícone
    const inputGroup = document.createElement('div')
    inputGroup.classList.add('input-group')
    inputGroup.appendChild(inputIcon)
    inputGroup.appendChild(brInput.querySelector('input'))
    brInput.appendChild(inputGroup)
    brInput.appendChild(dropButton)
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRSelect);


/***/ }),

/***/ "./src/components/step/step.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRStep */
class BRStep {
  constructor(name, component) {
    /**
     * Instancia do componente
     * @param {string} name - Nome do componente em minúsculo
     * @param {object} component - Objeto referenciando a raiz do componente DOM
     * @property {number} activeStepNum - Número do palco ativo
     * @property {array} DOMStrings - instancia dos elementos internos do componente
     */
    this.name = name
    this.component = component
    this.activeStepNum = 0
    this.DOMstrings = {
      // stepsBar: this.component.querySelector('.step-progress'),
      stepsBar: this.component,
      stepsBarClass: 'step-progress',
      stepsBtnClass: 'step-progress-btn',
      stepsBtns: this.component.querySelectorAll('.step-progress-btn'),
    }

    /**
     * Remove uma classe css de uma lista de elementos
     * @param {object} elemSet - Lista de elementos
     * @param {string} button - Nome do atributo
     */
    this.removeAttributes = (elemSet, attrName) => {
      elemSet.forEach((elem) => {
        elem.removeAttribute(attrName)
      })
    }

    /**
     * Retorna o nó pai do elemento com o nome da classe específica
     * @param {object} elem - Referência ao elemento
     * @param {string} parentClass - Nome da classe pai
     * @returns {object} Referência ao elemento pai
     */
    this.findParent = (elem, parentClass) => {
      let currentNode = elem
      while (!currentNode.classList.contains(parentClass)) {
        currentNode = currentNode.parentNode
      }
      return currentNode
    }

    /** Retorna o número do passo de referencia
     * @param {object} elem - Referência ao botão de passo
     * @returns {number} Número do passo
     */
    this.getActiveStep = (elem) => {
      return Array.from(this.DOMstrings.stepsBtns).indexOf(elem)
    }

    /** Define o número do passo de referência como ativo
     * @param {number} num - numero do passo de referência
     */
    this.setActiveStep = function (num) {
      this.removeAttributes(this.DOMstrings.stepsBtns, 'active')
      this.DOMstrings.stepsBtns.forEach((elem, index) => {
        if (index === num) {
          elem.removeAttribute('disabled')
          elem.setAttribute('active', '')
        }
      })
      this.activeStepNum = num
    }

    /**
     * Mostra os números dos rótulos dos passos
     */
    this.setStepsNum = () => {
      this.DOMstrings.stepsBtns.forEach((elem, index) => {
        const img = elem.querySelector('.step-icon')
        const text = this.component.getAttribute('data-type') === 'text'
        if (text) {
          elem.setAttribute(
            'step-num',
            `${index + 1}/${this.DOMstrings.stepsBtns.length}`
          )
        } else if (img) {
          elem.setAttribute('step-num', '')
        } else elem.setAttribute('step-num', index + 1)
      })
    }

    /** Testa se o passo está dentro do escopo e o define como ativo
     * @param {number} num - step number
     */
    this.setStep = (num) => {
      const activeStep = num <= this.DOMstrings.stepsBtns.length ? num - 1 : 0
      this.setActiveStep(activeStep)
    }

    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    this.DOMstrings.stepsBar.addEventListener('click', (e) => {
      const eventTarget = e.target
      if (!eventTarget.classList.contains(`${this.DOMstrings.stepsBtnClass}`)) {
        e.target.parentNode.click()
        return
      }
      const activeStepNum = this.getActiveStep(eventTarget)
      this.setActiveStep(activeStepNum)
    })

    this.setStepsNum()
    if (this.component.hasAttribute('data-initial')) {
      this.setStep(this.component.getAttribute('data-initial'))
    } else this.setStep(1)

    if (
      !this.component.classList.contains('vertical') &&
      !this.component.hasAttribute('data-scroll')
    ) {
      // const stepsWidth = Math.round(100 / this.DOMstrings.stepsBtns.length) - 0.5
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRStep);


/***/ }),

/***/ "./src/components/tab/tab.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRTab*/

class BRTab {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    for (const ancor of this.component.querySelectorAll('.tab-nav')) {
      this.height = ancor.clientHeight
      this.scollsizes = ancor.scrollHeight - ancor.clientHeight

      this.scrollHeight = Math.max(
        this.component.scrollWidth,
        document.documentElement.scrollWidth,
        this.component.offsetWidth,
        document.documentElement.offsetWidth,
        this.component.clientWidth,
        document.documentElement.clientWidth
      )
      this.leftPosition = this.component.offsetWidth - 1
      // debugger
      ancor.style.setProperty('--height-nav', `${this.height}px`)
      ancor.style.setProperty('--right-gradient-nav', `${this.leftPosition}px`)

      this.positionScroll(ancor)

      this.navigationRight = this.navigationRight + 4

      if (this.navigationRight <= this.lastItempos - 5) {
        ancor.classList.add('tab-nav-right')
      }

      ancor.onscroll = () => {
        this.positionScroll(ancor)

        if (this.navigationLeft <= 0) {
          ancor.classList.add('tab-nav-left')
        } else {
          ancor.classList.remove('tab-nav-left')
        }

        if (this.navigationRight <= this.lastItempos - 5) {
          ancor.classList.add('tab-nav-right')
        } else {
          ancor.classList.remove('tab-nav-right')
        }
      }
    }

    for (const ancor of this.component.querySelectorAll(
      '.tab-nav .tab-item:not([not-tab="true"]) button'
    )) {
      ancor.addEventListener(
        'click',
        (event) => {
          this._switchTab(event.currentTarget.parentElement)
          this._switchContent(event.currentTarget.parentElement)
        },
        false
      )
      ancor.addEventListener('keyup', (e) => {
        e.preventDefault()
        this.positionKeyboard(e)
      })

      ancor.addEventListener('blur', () => {
        this.hiddenTooltips()
      })
    }
    this.tabitems = this.component.querySelectorAll('tab-item')
  }

  /**
   * Mapeia as teclas pressionadas
   * @param {event} event - evento que disparou a ação
   */
  positionKeyboard(event) {
    const keys = {
      end: 35,
      home: 36,
      left: 37,
      right: 39,
      space: 32,
      tab: 9,
    }
    const key = event.keyCode
    this.tabitems = this.component.querySelectorAll('.tab-item')
    this.buttons = this.component.querySelectorAll('button')

    switch (key) {
      case keys.end:
        event.preventDefault()
        this.clean()
        this._switchTab(this.tabitems[this.tabitems.length - 1])
        this._switchContent(this.tabitems[this.tabitems.length - 1])
        this.tabitems[this.tabitems.length - 1].focus()
        break
      case keys.home:
        event.preventDefault()
        this.clean()
        this._switchTab(this.tabitems[0])
        this._switchContent(this.tabitems[0])
        this.tabitems[0].focus()
        event.stopPropagation()
        break
      case keys.left:
        event.preventDefault()
        this.position(event.target, -1)
        event.stopPropagation()
        break
      case keys.right:
        event.preventDefault()
        this.position(event.target, 1)
        event.stopPropagation()
        break
      case keys.tab:
        event.preventDefault()
        this.position(event.target, 0)
        event.stopPropagation()
        break
      case 32:
        event.preventDefault()
        this.hiddenTooltips()
        event.target.click()

        event.stopPropagation()
        break
      default:
        break
    }
  }

  /**
   *  Esconde todos tooltip
   *
   */
  hiddenTooltips() {
    const tooltips = document.querySelectorAll('.br-tooltip')

    tooltips.forEach((element) => {
      element.hidden = true
      element.style.visbility = 'hidden'
      element.removeAttribute('data-show')
    })
  }

  /**
   * Define o deslocamento da posição ativa
   * @property {object} target - referencia ao objeto DOM
   * @property {number} direction - incremento à posição
   */
  positionActive(target, direction) {
    let contComponent = 0
    let contComponentActive = 0

    const tabItems2 =
      target.parentElement.parentElement.querySelectorAll('.tab-item')
    for (const component of tabItems2) {
      if (
        component.classList.contains('is-active') ||
        component.classList.contains('active')
      ) {
        contComponentActive = contComponent
      }
      contComponent += 1
    }
    if (
      tabItems2.length > contComponentActive + direction &&
      contComponentActive + direction >= 0
    ) {
      this._switchTab(tabItems2[contComponentActive + direction])
      tabItems2[contComponentActive + direction].focus()
      let x = ''
      if (tabItems2[contComponentActive + direction].querySelector('button')) {
        x = tabItems2[contComponentActive + direction].querySelector('button')
        x.focus()
      }
    }
  }

  /**
   * Define o deslocamento da posição ativa do tab-item interno
   * @property {object} target - referencia ao objeto DOM
   * @property {number} direction - incremento à posição
   */
  position(target, direction) {
    this.positionQuery(target, direction, '.tab-item')
  }

  /**
   * Define o deslocamento da posição ativa do elemento referenciado
   * @property {object} target - referencia ao objeto DOM
   * @property {number} direction - incremento à posição
   * @property {string} query - string de referencia
   */
  positionQuery(target, direction, query) {
    let contComponent = 0
    // const contComponentActive = 0
    let contComponentFocus = 0
    const tabItems2 = target.parentElement.parentElement.querySelectorAll(query)
    for (const component of tabItems2) {
      if (component.querySelector('.focus-visible')) {
        contComponentFocus = contComponent
      }
      contComponent += 1
    }
    tabItems2[contComponentFocus + direction].querySelector('button').focus()
    if (
      tabItems2.length > contComponentFocus + direction &&
      contComponentFocus + direction >= 0
    ) {
      if (tabItems2[contComponentFocus + direction].querySelector('button')) {
        tabItems2[contComponentFocus + direction]
          .querySelector('button')
          .focus()
      }
    }
  }

  /**
   * Define a posição ativa do elemento referenciado
   * @property {object} target - referencia ao objeto DOM
   */
  setPosition(target) {
    let contComponent = 0
    let contComponentActive = 0

    const tabItems2 =
      target.parentElement.parentElement.querySelectorAll('.tab-item')
    for (const component of tabItems2) {
      if (
        component.classList.contains('is-active') ||
        component.classList.contains('active')
      ) {
        contComponentActive = contComponent
      }
      contComponent += 1
    }
    if (tabItems2.length > contComponentActive && contComponentActive >= 0) {
      this.clean()
      this._switchContent(tabItems2[contComponentActive])
    }
  }

  /**
   * Reinicia estados
   */
  clean() {
    for (const ancor of event.currentTarget.parentElement.querySelectorAll(
      'button'
    )) {
      ancor.classList.remove('focus-visible')
      ancor.classList.remove('is-active')
      ancor.classList.remove('active')
    }
    for (const ancor of event.currentTarget.parentElement.querySelectorAll(
      'tab-item'
    )) {
      ancor.classList.remove('is-active')
      ancor.classList.remove('active')
    }
  }

  /**
   * Realiza rolagem até a âncora
   * @property {object} ancor - referencia ao objeto DOM
   */
  positionScroll(ancor) {
    this.navItems = ancor.querySelectorAll('.tab-item')
    this.lastItempos = Math.ceil(
      this.navItems[this.navItems.length - 1].getBoundingClientRect().right
    )
    this.navigationLeft = Math.floor(
      this.navItems[0].getBoundingClientRect().left
    )
    this.navigationRight = Math.floor(ancor.getBoundingClientRect().right)
  }

  /**
   * Muda para a aba referenciada
   * @property {object} currentTab - referencia ao objeto DOM
   */
  _switchTab(currentTab) {
    for (const tabItem of this.component.querySelectorAll(
      '.tab-nav .tab-item:not([not-tab="true"])'
    )) {
      if (tabItem === currentTab) {
        tabItem.classList.add('active')
      } else {
        tabItem.classList.remove('is-active')
        tabItem.classList.remove('active')
      }
    }
  }

  /**
   * Muda para o conteudo da aba referenciada
   * @property {object} currentTab - referencia ao objeto DOM
   */
  _switchContent(currentTab) {
    for (const button of currentTab.querySelectorAll('button')) {
      for (const tabPanel of this.component.querySelectorAll(
        '.tab-content .tab-panel'
      )) {
        if (
          button.getAttribute('data-panel') === tabPanel.getAttribute('id') ||
          button.getAttribute('data-target') === tabPanel.getAttribute('id')
        ) {
          tabPanel.classList.add('active')
        } else {
          tabPanel.classList.remove('is-active')
          tabPanel.classList.remove('active')
        }
      }
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRTab);


/***/ }),

/***/ "./src/components/table/table.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_collapse__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__("./src/partial/js/behavior/collapse.js");
/* harmony import */ var _partial_js_behavior_dropdown__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/dropdown.js");



/** Classe para instanciar um objeto BRTable*/
/* eslint-disable complexity */
class BRTable {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   * @param {number} sequence - 'índice do componente para sobreposição'
   */
  constructor(name, component, sequence) {
    this.name = name
    this.component = component
    this._header = this.component.querySelector('.header, .table-header')
    this._table = this.component.querySelector('table')
    this._sequence = sequence
    this._setBehaviors()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehaviors() {
    this._makeResponsiveTable()
    this._setHeaderWidth()
    this._searchBehavior()
    this._dropdownBehavior()
    this._collpaseBehavior()
    this._densityBehavior()
    this._setClickActions()
    this._getBRHeaderHeight()
  }

  /**
   * Configura a altura do cabeçalho
   * @private
   */
  _getBRHeaderHeight() {
    const BRHeader = document.querySelector('.br-header')
    if (BRHeader) {
      window.addEventListener('scroll', () => {
        this._header.style.top = `${BRHeader.clientHeight}px`
      })
    }
  }

  /**
   * Configura reponsividade da tabela
   * @private
   */
  _makeResponsiveTable() {
    const responsiveClass = 'responsive'
    if (!this.component.querySelector(`.${responsiveClass}`)) {
      const responsiveElement = document.createElement('div')
      responsiveElement.classList.add(responsiveClass)
      responsiveElement.appendChild(this._table)
      this._header.after(responsiveElement)
    }
  }

  /**
   * Configura rolagem
   * @private
   */
  _makeScroller() {
    const scrollerTag = document.createElement('div')
    scrollerTag.classList.add('scroller')
    for (const header of this._table.querySelectorAll('thead tr th')) {
      const clonedHeader = document.createElement('div')
      clonedHeader.classList.add('item')
      clonedHeader.innerHTML = header.innerHTML
      if (header.offsetWidth) {
        clonedHeader.style.flex = `1 0 ${header.offsetWidth}px`
      }
      scrollerTag.appendChild(clonedHeader)
      const checkbox = clonedHeader.querySelector('.br-checkbox')
      if (checkbox) {
        const input = checkbox.querySelector('input')
        const label = checkbox.querySelector('label')
        input.id = `${input.id}-clone`
        label.setAttribute('for', input.id)
      }
    }
    return scrollerTag
  }

  /**
   * Configura largura do cabeçalho
   * @private
   */
  _setHeaderWidth() {
    for (const clonedHeader of this.component.querySelectorAll(
      '.headers > div'
    )) {
      for (const [index, header] of this.component
        .querySelectorAll('table thead tr th')
        .entries()) {
        clonedHeader.children[index].style.flex = `1 0 ${header.offsetWidth}px`
      }
    }
  }

  /**
   * Configura coportamento do dropdown
   * @private
   */
  _dropdownBehavior() {
    this.component
      .querySelectorAll('[data-toggle="dropdown"]')
      .forEach((trigger) => {
        const config = {
          iconToHide: 'fa-chevron-up',
          iconToShow: 'fa-chevron-down',
          trigger,
          useIcons: true,
        }
        const dropdown = new _partial_js_behavior_dropdown__WEBPACK_IMPORTED_MODULE_0__["default"](config)
        dropdown.setBehavior()
      })
  }

  /**
   * Configura comportamento de colapsar
   * @private
   */
  _collpaseBehavior() {
    this.component
      .querySelectorAll('[data-toggle="collapse"]')
      .forEach((trigger) => {
        const config = {
          iconToHide: 'fa-chevron-up',
          iconToShow: 'fa-chevron-down',
          trigger,
          useIcons: true,
        }
        const collapse = new _partial_js_behavior_collapse__WEBPACK_IMPORTED_MODULE_1__["default"](config)
        collapse.setBehavior()
      })
  }

  /**
   * Configura comportamento da busca
   * @private
   */
  _searchBehavior() {
    if (this.component.dataset.search) {
      const trigger = this.component.querySelector('[data-toggle="search"]')
      const target = this.component.querySelector('.search-bar')
      const dismiss = this.component.querySelector('[data-dismiss="search"]')

      // Inicializar
      this._searchInit(trigger)

      // Abre busca
      trigger.addEventListener('click', () => {
        return this._searchOpen(trigger, target)
      })

      // Fecha busca
      dismiss.addEventListener('click', () => {
        return this._searchClose(trigger, target)
      })
      target.querySelector('input').addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          this._searchClose(trigger, target)
        }
      })
    }
  }

  /**
   * Inicializa a busca
   * @private
   * @param {object} trigger - Objeto referente ao elemento que dispara a ação
   */
  _searchInit(trigger) {
    trigger.setAttribute('aria-expanded', 'false')
  }

  /**
   * Abre a busca
   * @private
   * @param {object} trigger - Objeto referente ao elemento que dispara a ação
   * @param {object} target - Objeto referente ao alvo da ação
   */
  _searchOpen(trigger, target) {
    trigger.setAttribute('aria-expanded', 'true')
    target.classList.add('show')
    target.parentElement.classList.add('show')
    target.querySelector('input').focus()
  }

  /**
   * Fecha a busca
   * @private
   * @param {object} trigger - Objeto referente ao elemento que dispara a ação
   * @param {object} target - Objeto referente ao alvo da ação
   */
  _searchClose(trigger, target) {
    target.querySelector('input').value = ''
    target.classList.remove('show')
    target.parentElement.classList.remove('show')
    trigger.focus()
    trigger.setAttribute('aria-expanded', 'false')
  }

  /**
   * Configura densidades
   * @private
   */
  _densityBehavior() {
    const desityTriggers = this.component.querySelectorAll('[data-density]')
    for (const desityTrigger of desityTriggers) {
      desityTrigger.addEventListener('click', () => {
        this.component.classList.remove('small', 'medium', 'large')
        this.component.classList.add(desityTrigger.dataset.density)
        this._dropdownClose(
          desityTrigger
            .closest('.dropdown')
            .querySelector('[data-toggle="dropdown"]')
        )
      })
    }
  }

  /**
   * Configura ações de clique
   * @private
   */
  _setClickActions() {
    const headerCheckbox = this.component.querySelector(
      '.headers [type="checkbox"]'
    )
    const tableCheckboxes = this.component.querySelectorAll(
      'tbody [type="checkbox"]'
    )
    const selectedBar = this.component.querySelector('.selected-bar')
    const checkAlls = this.component.querySelectorAll(
      '[data-toggle="check-all"]'
    )
    for (const checkAll of checkAlls) {
      checkAll.addEventListener('click', () => {
        this._checkAllTable(selectedBar, tableCheckboxes, headerCheckbox)
        if (checkAll.parentElement.classList.contains('br-list')) {
          this._dropdownClose(
            checkAll
              .closest('.dropdown')
              .querySelector('[data-toggle="dropdown"]')
          )
        }
      })
    }
    if (tableCheckboxes) {
      for (const checkbox of tableCheckboxes) {
        checkbox.addEventListener('change', () => {
          this._checkRow(checkbox, selectedBar, tableCheckboxes, headerCheckbox)
        })
      }
    }
  }

  /**
   * Configura seleção da linha
   * @private
   * @param {object} checkbox - Objeto referente ao checkbox
   * @param {boolean} check - define se a linha deve ser selecionada
   */
  _setRow(checkbox, check) {
    const tr = checkbox.parentNode.parentNode.parentNode
    if (check) {
      tr.classList.add('is-selected')
      checkbox.parentNode.classList.add('is-inverted')
      checkbox.checked = true
    } else {
      tr.classList.remove('is-selected')
      checkbox.parentNode.classList.remove('is-inverted')
      checkbox.checked = false
    }
  }

  /**
   * Configura seleção da linha
   * @private
   * @param {object} checkbox - Objeto referente ao checkbox
   * @param {object} selectedBar - Objeto referente a barra contextual
   * @param {object} tableCheckboxes - Objeto referente a lista de checkboxes
   * @param {object} headerCheckbox - Objeto referente ao checkbox do header
   */
  _checkRow(checkbox, selectedBar, tableCheckboxes, headerCheckbox) {
    const check = checkbox.checked
    this._setRow(checkbox, check)
    this._setSelectedBar(
      check ? 1 : -1,
      selectedBar,
      tableCheckboxes,
      headerCheckbox
    )
  }

  /**
   * Seleciona todas as linhas
   * @private
   * @param {object} tableCheckboxes - Objeto referente a lista de checkboxes
   */
  _checkAllRows(tableCheckboxes) {
    for (const checkbox of tableCheckboxes) {
      this._setRow(checkbox, true)
    }
  }

  /**
   * Desseleciona todas as linhas
   * @private
   * @param {object} tableCheckboxes - Objeto referente a lista de checkboxes
   */
  _uncheckAllRows(tableCheckboxes) {
    for (const checkbox of tableCheckboxes) {
      this._setRow(checkbox, false)
    }
  }

  /**
   * Seleciona toda a tabela
   * @private
   * @param {object} selectedBar - Objeto referente a barra contextual
   * @param {object} tableCheckboxes - Objeto referente a lista de checkboxes
   * @param {object} headerCheckbox - Objeto referente ao checkbox do header
   */
  _checkAllTable(selectedBar, tableCheckboxes, headerCheckbox) {
    let count = tableCheckboxes.length
    const infoCount = selectedBar.querySelector('.info .count')
    const total = parseInt(infoCount.innerHTML, 10)
    if (total === count) {
      this._uncheckAllRows(tableCheckboxes)
      count = -1 * count
    } else {
      this._checkAllRows(tableCheckboxes)
    }
    this._setSelectedBar(count, selectedBar, tableCheckboxes, headerCheckbox)
  }

  /**
   * Define visualização dos itens selecionados na barra contextual
   * @private
   * @param {number} count - número de itens selecionados
   * @param {object} selectedBar - Objeto referente a barra contextual
   * @param {object} tableCheckboxes - Objeto referente a lista de checkboxes
   * @param {object} headerCheckbox - Objeto referente ao checkbox do header
   */
  _setSelectedBar(count, selectedBar, tableCheckboxes, headerCheckbox) {
    const infoCount = selectedBar.querySelector('.info .count')
    const infoText = selectedBar.querySelector('.info .text')
    const total = count < 2 ? parseInt(infoCount.innerHTML, 10) + count : count
    if (total > 0) {
      selectedBar.classList.add('show')
      infoCount.innerHTML = total
      infoText.innerHTML = total > 1 ? 'itens selecionados' : 'item selecionado'
      if (headerCheckbox) headerCheckbox.parentNode.classList.add('is-checking')
      if (total === tableCheckboxes.length) {
        if (headerCheckbox) {
          headerCheckbox.checked = true
          headerCheckbox.parentNode.classList.remove('is-checking')
        }
      }
    } else {
      infoCount.innerHTML = 0
      if (headerCheckbox) {
        headerCheckbox.checked = false
        headerCheckbox.parentNode.classList.remove('is-checking')
      }
      selectedBar.classList.remove('show')
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRTable);


/***/ }),

/***/ "./src/components/tag/tag.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRTag*/
class BRTag {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    if (this.component.classList.contains('interaction-select')) {
      // Inicializa selecionado
      if (this.component.querySelector('input').getAttribute('checked')) {
        this.component.classList.add('selected')
      }
      this._setSelection()
    }
    this._closeTag()
    this._dismissTag()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setSelection() {
    const label = this.component.querySelector('label')
    const input = this.component.querySelector('input')
    // const tagRadio = input.getAttribute('type') === 'radio' ? true : false

    label.addEventListener('click', (event) => {
      this._toggleSelection(input, event)
    })
    input.addEventListener('keydown', (event) => {
      if (event.code === 'Space' || event.code === 'Enter') {
        this._toggleSelection(input, event)
      }
    })
  }

  /**
   * Muda estado do radio
   * @private
   * @param {object} input - referencia DOM ao input
   */
  _toggleRadio(input) {
    if (this.component.querySelector('[type="radio"')) {
      const nameTag = input.getAttribute('name')
      for (const tagRadio of window.document.querySelectorAll(
        `[name=${nameTag}]`
      )) {
        this._removeCheck(tagRadio)
      }
    }
  }

  /**
   * Muda estado do input
   * @private
   * @param {object} input - referencia DOM ao input
   * @param {event} event - ação que disparou o evento
   */
  _toggleSelection(input, event) {
    event.preventDefault()
    this._toggleRadio(input)
    if (input.getAttribute('checked')) {
      this._removeCheck(input)
      return
    }

    this._setCheck(input)
  }

  /**
   * Define estado do input para selecionado
   * @private
   * @param {object} input - referencia DOM ao input
   */
  _setCheck(input) {
    input.setAttribute('checked', 'checked')
    input.parentElement.classList.add('selected')
  }

  /**
   * Define estado do input para desselecionado
   * @private
   * @param {object} input - referencia DOM ao input
   */
  _removeCheck(input) {
    input.removeAttribute('checked')
    input.parentElement.classList.remove('selected')
  }

  /**
   * Define comportamento do botão de fechar usando classe (compatibilidade)
   * @private
   */
  _closeTag() {
    const closeBtn = this.component.querySelector('.br-button.close')

    if (closeBtn) {
      const brTag = closeBtn.closest('.br-tag')

      brTag.addEventListener('click', () => {
        closeBtn.closest('.br-tag').remove()
      })
    }
  }

  /**
   * Define comportamento do botão de fechar usando data-dismiss
   * @private
   */
  _dismissTag() {
    this.component.querySelectorAll('[data-dismiss]').forEach((closeBtn) => {
      closeBtn.addEventListener('click', () => {
        const target = document.querySelector(
          `#${closeBtn.getAttribute('data-dismiss')}`
        )
        if (target) target.remove()
      })
    })
  }
}

const tagList = []
for (const brTag of window.document.querySelectorAll('.br-tag')) {
  tagList.push(new BRTag('br-tab', brTag))
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRTag);


/***/ }),

/***/ "./src/components/textarea/textarea.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/** Classe para instanciar um objeto BRTextArea*/
class BRTextArea {
  /**
   * Instancia do objeto
   * @param {string} name - Nome do componente em minúsculo
   * @param {object} component - Objeto referenciando a raiz do componente DOM
   */
  constructor(name, component) {
    this.name = name
    this.component = component
    this._setBehavior()
    this._setKeyup()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    this.limit = this.component.querySelector('.limit')
    this.current = this.component.querySelector('.current')
    if (this.component.querySelector('textarea') !== null) {
      this.maximum = this.component
        .querySelector('textarea')
        .getAttribute('maxlength')
    }

    this.characters = this.component.querySelector('.characters')
    this.currentValue = this.component.querySelector('.current')
  }

  /**
   * Define ações do teclado
   * @private
   */
  _setKeyup() {
    this.component.addEventListener('keyup', () => {
      const characterCount = this.component.querySelector('textarea').textLength
      if (characterCount <= this.maximum && !this.characters) {
        this.limit.innerHTML = ''
        this.currentValue.innerHTML = `Restam <strong>${
          this.maximum - characterCount
        }</strong> caracteres`
      }
      // Com limite de caracteres
      if (!this.characters) {
        if (characterCount === 0 && this.limit.innerHTML === '') {
          this.limit.innerHTML = `Limite máximo de <strong>${this.maximum}</strong> caracteres`
          this.currentValue.innerHTML = ''
        }
      }
      // Sem limite de caracteres
      else {
        this.characters.innerHTML = `<strong>${characterCount}</strong> caracteres digitados`
      }
    })
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRTextArea);


/***/ }),

/***/ "./src/components/tooltip/tooltip.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _popperjs_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./node_modules/@popperjs/core/lib/popper.js");

class BRTooltip {
  constructor(name, component) {
    this.name = name
    this.component = component
    this.activator = component.previousSibling.previousSibling
    const place = component.getAttribute('place')
    const positions = ['top', 'right', 'bottom', 'left']
    this.popover = component.hasAttribute('popover')
    this.notification = component.classList.contains('br-notification')
    this.timer = component.getAttribute('timer')
    let positionNotification = this.notification
    ? 'bottom'
    : 'top'
    this.active = component.hasAttribute('active')
    this.placement = positions.includes(place)
      ? place
      : positionNotification
    this.popperInstance = null
    this.showEvents = ['mouseenter', 'click', 'focus']
    this.hideEvents = ['mouseleave', 'blur']
    this.closeTimer = null

    this._create()
    this._setBehavior()
  }

  _setBehavior() {
    // Ação de abrir padrao ao entrar no ativador

    if (this.activator) {
      this.showEvents.forEach((event) => {
        this.activator.addEventListener(event, (otherEvent) => {
          this._show(otherEvent)
        })
      })
      // }
    }
    // Adiciona ação de fechar ao botao do popover
    // if (this.popover || this.notification) {
    if (this.popover) {
      const closeBtn = this.component.querySelector('.close')
      closeBtn.addEventListener('click', (event) => {
        this._hide(event, this.component)
        this._toggleActivatorIcon()
      })
      // Ação de fechar padrao ao sair do ativador
    } else {
      this.hideEvents.forEach((event) => {
        this.activator.addEventListener(event, (otherEvent) => {
          this._hide(otherEvent, this.component)
        })
      })
    }
   
  }

  _create() {
    this._setLayout()


    // Cria a instancia do popper
    if (this.notification) {
      this.component.setAttribute('notification', '')

      this.popperInstance = (0,_popperjs_core__WEBPACK_IMPORTED_MODULE_0__/* .createPopper */ .fi)(this.activator, this.component, {
        modifiers: [
          {
            name: 'offset',
            options: {
              offset: [0, 10],
            },
          },
          {
            name: 'preventOverflow',
            options: {
              altAxis: false, 
              mainAxis: true, 
              
            },
          },
        ],
        placement: this.placement,
        
        strategy: 'fixed',
      })
    } else {
      let ac = new DOMRect()
      const tt = new DOMRect()

      
      
      
     
        ac = this.activator.getBoundingClientRect()
      

      const bw = document.body.clientWidth
      if (this.placement === 'right') {
        this.placement =
          ac.x + ac.width + tt.width > bw ? 'top' : this.placement
      }
      if (this.placement === 'left') {
        this.placement = ac.x - tt.width > 0 ? this.placement : 'top'
      }

      this.popperInstance = (0,_popperjs_core__WEBPACK_IMPORTED_MODULE_0__/* .createPopper */ .fi)(this.activator, this.component, {
        modifiers: [
          {
            name: 'offset',
            options: {
              offset: [0, 8],
            },
          },
          {
            name: 'preventOverflow',
            options: {
              altAxis: true, 
              mainAxis: true,
              tether: false, 
            },
          },
        ],
        placement: this.placement,
      })
    }
  }

  _show(event) {
    this.component.style.display = 'unset'
    this.component.setAttribute('data-show', '')
    this.component.style.zIndex = 99
    this._fixPosition()
    
    // Importante pois "display: none" conflitua com a instancia do componente e precisa ser setado aqui já que pelo css ativa o efeito fade no primeiro carregamento

    this.component.style.visibility = 'visible'
    if (this.timer) {
      clearTimeout(this.closeTimer)
      console.log("Timer",this.timer)
      this.closeTimer = setTimeout(
        this._hide,
        this.timer,
        event,
        this.component
      )
    }
  }

  _hide(event, component) {
    component.removeAttribute('data-show')
    component.style.zIndex = -1
    component.style.visibility = 'hidden'
    clearTimeout(component.closeTimer)
  }

  _setLayout() {
    // Cria a setinha que aponta para o item que criou o tooltip
    const arrow = document.createElement('div')
    arrow.setAttribute('data-popper-arrow', '')

    if (this.component.querySelectorAll('.arrow').length < 1) {
      arrow.classList.add('arrow')
    }
    this.component.appendChild(arrow)
    // Cria o icone de fechar do po over
    if (this.popover) {
      const close = document.createElement('button')
      close.setAttribute('type', 'button')
      close.classList.add('close')
      const ico = document.createElement('i')
      ico.classList.add('fas', 'fa-times')
      close.appendChild(ico)
      this.component.appendChild(close)
    }
  }

  _toggleActivatorIcon() {
    const icon = this.activator.querySelector('button svg')
    if (icon) {
      icon.classList.toggle('fa-angle-down')
      icon.classList.toggle('fa-angle-up')
    }
    this.activator.toggleAttribute('active')
  }

  _fixPosition() {
    if (this.notification) {
      setTimeout(() => {
        const ac = this.activator.getBoundingClientRect()
        this.component.style = `position: fixed !important; top: ${
          ac.top + ac.height + 10
        }px !important; left: auto; right: 8px; display: unset; bottom: auto;`
        this.component.querySelector(
          '.arrow'
        ).style = `position: absolute; left: auto; right: ${
          document.body.clientWidth - ac.right + ac.width / 5
        }px !important;`
      }, 10)
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRTooltip);


/***/ }),

/***/ "./src/components/upload/upload.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _components_tooltip_tooltip__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/components/tooltip/tooltip.js");

/** Classe para instanciar um objeto BRUpload */
class BRUpload {
  /**
   * @param {*} name nome do componente
   * @param {*} component componente
   * @param {*} uploadFiles  promisse de status do upload
   */
  constructor(name, component, uploadFiles) {
    this.name = name
    this.component = component
    this._inputElement = this.component.querySelector('.upload-input')
    this._fileList = this.component.querySelector('.upload-list')
    this._btnUpload = this.component.querySelector('.upload-button')
    this._label = this.component.querySelector('label')
    this._textHelp = document.querySelector('.text-base')
    this._fileArray = []
    this._uploadFiles = uploadFiles
    this._setBehavior()
  }

  /**
   * Define comportamentos do componente
   * @private
   */
  _setBehavior() {
    if (this._inputElement) {
      const button = document.createElement('button')
      button.className = 'upload-button'
      button.setAttribute('type', 'button')

      if (this._inputElement.getAttribute('multiple'))
        button.innerHTML =
          '<i class="fas fa-upload" aria-hidden="true"></i><span>Selecione o(s) arquivo(s)</span>'
      else
        button.innerHTML =
          '<i class="fas fa-upload" aria-hidden="true"></i><span>Selecione o arquivo</span>'

      this.component.append(this._label)
      this.component.append(this._inputElement)
      this.component.appendChild(button)
      this.component.append(this._fileList)
      this._btnUpload = this.component.querySelector('.upload-button')
      this._btnUpload.addEventListener(
        'click',
        () => {
          this._clickUpload()
        },
        false
      )
      if (this.component.getAttribute('disabled')) {
        const message = document.createElement('span')
        message.classList.add('feedback', 'warning', 'mt-1')
        message.setAttribute('role', 'alert')
        message.innerHTML =
          '<i class="fas fa-exclamation-triangle" aria-hidden="true"></i>Upload desabilitado'
        this.component.after(message)
      }

      this._fileArray = Array.from(this._inputElement.files)
      this._inputElement.addEventListener(
        'change',
        (event) => {
          this._handleFiles(event)
        },
        false
      )
    }

    this._setDragAndDropBehavior()
  }

  /**
   * Define comportamento de arrastar(drag) e posicionar(drop)
   * @private
   */
  _setDragAndDropBehavior() {
    const uploadButton = this.component.querySelector('.upload-button')

    ;['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
      uploadButton.addEventListener(eventName, this._preventDefaults)
    })
    ;['dragenter', 'dragover'].forEach((eventName) => {
      uploadButton.addEventListener(eventName, this._hightLight.bind(this))
    })
    ;['dragleave', 'drop'].forEach((eventName) => {
      uploadButton.addEventListener(eventName, this._unHightLight.bind(this))
    })

    uploadButton.addEventListener('drop', this._handleDrop.bind(this))
  }

  /**
   * Evita os eventos padrao e propagação de evento
   * @private
   * @param {event} event - referencia ao evento
   */
  _preventDefaults(event) {
    event.preventDefault()
    event.stopPropagation()
  }

  _hightLight() {
    this.component.classList.add('dragging')
  }

  _unHightLight() {
    this.component.classList.remove('dragging')
  }

  /**
   * Ação de posicionar (drop)
   * @private
   * @param {event} event - referencia ao evento
   */
  _handleDrop(event) {
    this.component.classList.remove('dragging')
    const dt = event.dataTransfer
    const { files } = dt
    this._handleFiles(files)
  }

  /**
   * Testa se o objeto que disparou a ação está desabilitado
   * @private
   * @param {event} event - referencia ao evento
   */
  _isDisabled(event) {
    const isDisabled = event.target.getAttribute('disabled')
    if (isDisabled) {
      return true
    } else {
      return false
    }
  }

  /**
   * Simula ação de clicar no input
   * @private
   */
  _clickUpload() {
    this._inputElement.click()
  }

  /**
   * Limpa mensagem de feedback
   * @private
   */
  _removeMessage() {
    for (const message of this.component.querySelectorAll('.feedback')) {
      message.parentNode.removeChild(message)
      message.innerHTML = ''
    }
  }

  /**
   * Remove mensagem de estado
   * @private
   */
  _removeStatus() {
    const remStatus = ['danger', 'warning', 'info', 'success']
    remStatus.forEach((el) => {
      if (this.component.dataset.hasOwnProperty(el))
        this.component.removeAttribute(`data-${el}`)
    })
  }

  /**
   * Define mensagem de feedback
   * @private
   * @param {string} status - estados: danger / info / success / warning
   */
  _feedback(status, text) {
    const icone = `<i class="fas fa-times-circle" aria-hidden="true"></i>${text}`
    const dataStatus = `data-${status}`
    const message = document.createElement('span')
    message.classList.add('feedback', status, 'mt-1')
    message.setAttribute('role', 'alert')
    switch (status) {
      case 'danger':
        message.innerHTML = icone
        break
      case 'info':
        message.innerHTML = icone.replace('fa-times-circle', 'fa-info-circle')
        break
      case 'success':
        message.innerHTML = icone.replace('fa-times-circle', 'fa-check-circle')
        break
      case 'warning':
        message.innerHTML = icone.replace(
          'fa-times-circle',
          'fa-exclamation-triangle'
        )
        break
      default:
        message.innerHTML = ''
    }
    this._removeStatus()
    this.component.setAttribute(dataStatus, dataStatus)
    this._fileList.before(message)
  }

  /**
   * Adiciona arquivos a lista
   * @private
   * @param {File[]} files - lista de arquivos
   */
  _concatFiles(files) {
    const newFiles = !files.length
      ? Array.from(this._inputElement.files)
      : Array.from(files)
    this._fileArray = this._fileArray.concat(newFiles)
  }

  /**
   * Adiciona arquivos a lista
   * @private
   * @param {File[]} files - lista de arquivos
   */
  _handleFiles(files) {
    this._removeMessage()
    if (!this._inputElement.multiple && files.length > 1) {
      this._feedback('danger', 'É permitido o envio de somente 1 arquivo.')
    } else if (!this._inputElement.multiple && this._fileArray.length > 0) {
      this._fileArray = []
      this._concatFiles(files)
      this._updateFileList()
      this._feedback(
        'warning',
        'O arquivo enviado anteriormente foi substituído'
      )
    } else {
      this._concatFiles(files)
      this._updateFileList()
    }
  }

  /**
   * Atualiza lista de arquivos eliminando redundâncias
   * @private
   */
  _updateFileList() {
    this._removeStatus()
    if (this.component.nextElementSibling === this._textHelp) {
      this._textHelp.style.display = 'none'
    }
    if (!this._fileArray.length) {
      this._fileList.innerHTML = ''
      if (this.component.nextElementSibling === this._textHelp) {
        this._textHelp.style.display = ''
      }
    } else {
      this._fileList.innerHTML = ''

      for (let i = 0; i < this._fileArray.length; i++) {
        if ('nowait' in this._fileArray[i]) {
          if (this._fileArray[i].nowait) {
            this._renderItem(i)
          }
        } else if (!this._fileArray[i].requested) {
          this.uploadLoading()
          this.uploadingFile(i)
        }
      }
    }
  }

  /**
   * Mostra mensagem de carregamento
   * @private
   */
  uploadLoading() {
    const loading = document.createElement('div')
    const carga = document.createElement('span')
    carga.classList.add('cargas')
    carga.innerText = 'Carregando...'
    loading.setAttribute('sm', '')
    loading.classList.add('my-3')
    loading.setAttribute('loading', '')
    loading.appendChild(carga)
    this._fileList.appendChild(loading)
  }

  /**
   * Faz upload de arquivo na posição definida
   * @private
   * @param {Number} position - numero do ínidice da posição
   */
  uploadingFile(position) {
    if (this._uploadFiles) {
      this._fileArray[position].requested = true
      this._uploadFiles().then(() => {
        this._fileArray[position].nowait = true
        this._updateFileList()
      })
    }
  }

  /**
   * Renderiza item na posição definida
   * @private
   * @param {Number} position - numero do ínidice da posição
   */
  _renderItem(position) {
    const li = document.createElement('div')
    li.className = 'br-item d-flex'
    this._fileList.appendChild(li)
    li.innerHTML = ''
    const name = document.createElement('div')
    name.className = 'name'
    li.appendChild(name)
    this._fileList.appendChild(li)
    const info = document.createElement('div')
    info.className = 'content'
    info.innerHTML = this._fileArray[position].name
    const tooltip = document.createElement('div')
    tooltip.classList.add('br-tooltip')
    tooltip.setAttribute('role', 'tooltip')
    tooltip.setAttribute('place', 'top')
    tooltip.setAttribute('info', 'info')
    const textTooltip = document.createElement('span')
    textTooltip.classList.add('text')
    textTooltip.setAttribute('role', 'tooltip')
    textTooltip.innerHTML = this._fileArray[position].name
    tooltip.appendChild(textTooltip)
    li.appendChild(info)
    li.appendChild(name)
    li.appendChild(tooltip)
    info.classList.add('text-primary-default', 'mr-auto')
    const del = document.createElement('div')
    del.className = 'support mr-n2'
    const btndel = document.createElement('button')
    const spanSize = document.createElement('span')
    spanSize.className = 'mr-1'
    spanSize.innerHTML = this._calcSize(this._fileArray[position].size)
    del.appendChild(spanSize)
    btndel.className = 'br-button'
    btndel.type = 'button'
    btndel.setAttribute('circle', '')
    btndel.addEventListener(
      'click',
      (event) => {
        this._removeFile(position, event)
      },
      false
    )
    const img = document.createElement('i')
    img.className = 'fa fa-trash'
    btndel.appendChild(img)
    del.appendChild(btndel)
    li.appendChild(del)
    this._fileArray[position].nowait = true
    const tooltipList = []
    for (const brTooltip of window.document.querySelectorAll('.br-tooltip')) {
      tooltipList.push(new _components_tooltip_tooltip__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z('br-tooltip', brTooltip))
    }
  }

  /**
   * Formata tamanho do arquivo
   * @private
   * @param {Number} nBytes - quantidade de bytes do arquivo
   */
  _calcSize(nBytes) {
    let sOutput = ''
    for (
      let aMultiples = ['KB', 'MB', 'GB', 'TB'],
        nMultiple = 0,
        nApprox = nBytes / 1024;
      nApprox > 1;
      nApprox /= 1024, nMultiple++
    ) {
      sOutput = `${nApprox.toFixed(2)} ${aMultiples[nMultiple]}`
    }
    return sOutput
  }

  /**
   * Remove arquivo na posição definida
   * @private
   * @param {Number} index - numero do ínidice da posição
   * @param {event} event - evento que disparou a ação
   */
  _removeFile(index, event) {
    event.stopPropagation()
    event.preventDefault()
    this._removeStatus()
    this._removeMessage()
    this._fileArray.splice(index, 1)
    this._updateFileList()

    if (this._inputElement.multiple)
      this._inputElement.files = this._updateFileListItems(this._fileArray)
    if (!this._inputElement.multiple) this._inputElement.value = ''
  }

  /**
   * Atualiza arquivos da lista
   * @private
   * @param {File[]} files - lista de arquivos
   */
  _updateFileListItems(files) {
    const fileInput = new ClipboardEvent('').clipboardData || new DataTransfer()
    for (let i = 0, len = files.length; i < len; i++)
      fileInput.items.add(files[i])
    return fileInput.files
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRUpload);


/***/ }),

/***/ "./src/components/wizard/wizard.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _partial_js_behavior_swipe__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/swipe.js");


/** Classe para instanciar um objeto BRWizard*/
class BRWizard {
  /**
   * Instância do componente
   * @param {string} name - nome do componente
   * @param {object} component - referencia ao objeto do DOM
   **/
  constructor(name, component) {
    this.name = name
    this.component = component
    this.DOMstrings = {
      stepFormPanelClass: 'wizard-panel',
      stepFormPanels: this.component.querySelectorAll('.wizard-panel'),
      stepNextBtnClass: 'wizard-btn-next',
      stepPrevBtnClass: 'wizard-btn-prev',
      stepsBar: this.component.querySelector('.wizard-progress'),
      stepsBarClass: 'wizard-progress',
      stepsBtnClass: 'wizard-progress-btn',
      stepsBtns: this.component.querySelectorAll('.wizard-progress-btn'),
      stepsForm: this.component.querySelector('.wizard-form'),
    }
    /**
     * Retira o atributo de uma lista de elementos
     * @param {object[]} elementSet - Lista de objetos
     * @param {string} attrName - Nome do atribbuto
     */
    this.removeAttributes = (elemSet, attrName) => {
      elemSet.forEach((elem) => {
        elem.removeAttribute(attrName)
      })
    }

    /**
     * Retorna o elemento pai do objeto com a classe de referência
     * @param {object} elem - Lista de objetos
     * @param {string} parentClass - nome da classe de referência
     * @returns {object}
     */
    this.findParent = (elem, parentClass) => {
      let currentNode = elem
      while (!currentNode.classList.contains(parentClass)) {
        currentNode = currentNode.parentNode
      }
      return currentNode
    }

    /**
     * Retorna o índice do elemento botão de passo
     * @param {object} elem - botão de passo
     * @returns {number}
     */
    this.getActiveStep = (elem) => {
      return Array.from(this.DOMstrings.stepsBtns).indexOf(elem)
    }

    /**
     * Define o estado do botão ativo e limpa os demais estados dos botões
     * @param {number} activeStepNum - número do botão ativo
     */
    this.setActiveStep = function (activeStepNum) {
      this.removeAttributes(this.DOMstrings.stepsBtns, 'active')
      this.DOMstrings.stepsBtns.forEach((elem, index) => {
        if (index === activeStepNum) {
          elem.removeAttribute('disabled')
          elem.setAttribute('active', '')
        }
      })
    }

    /**
     * Retorna o índice do painel ativo
     * @returns {number}
     */
    this.getActivePanel = () => {
      let activePanel
      this.DOMstrings.stepFormPanels.forEach((elem) => {
        if (elem.hasAttribute('active')) {
          activePanel = elem
        }
      })
      return activePanel
    }

    /**
     * Abre o painel ativo e fecha paineis inativos
     * @param {number} activePanelNum - numero do painel
     */
    this.setActivePanel = (activePanelNum) => {
      // remove active class from all the panels
      this.removeAttributes(this.DOMstrings.stepFormPanels, 'active')
      // show active panel
      this.DOMstrings.stepFormPanels.forEach((elem, index) => {
        if (index === activePanelNum) {
          elem.setAttribute('active', '')
        }
      })
    }

    /**
     * Define números dos passos
     */
    this.setStepsNum = () => {
      this.DOMstrings.stepsBtns.forEach((elem, index) => {
        elem.setAttribute('step', index + 1)
      })
    }

    /**
     * Define passo e painel ativo
     * @param {number} num - numero do passo
     */
    this.setStep = (num) => {
      const activeStep = num <= this.DOMstrings.stepsBtns.length ? num - 1 : 0
      this.setActiveStep(activeStep)
      this.setActivePanel(activeStep)
    }

    /**
     * Retrai painel de passos
     */
    this.collapseSteps = () => {
      this.component.setAttribute('collapsed', '')
    }

    /**
     * Expande painel de passos
     */
    this.expandSteps = () => {
      this.component.removeAttribute('collapsed')
    }

    this._setBehavior()
  }

  /**
   * Define os comportamentos do componente
   * @private
   */
  _setBehavior() {
    /**
     * Mapeia clique na barra de passos
     */
    this.DOMstrings.stepsBar.addEventListener('click', (e) => {
      const eventTarget = e.target
      if (!eventTarget.classList.contains(`${this.DOMstrings.stepsBtnClass}`)) {
        e.target.parentNode.click()
        return
      }
      const activeStep = this.getActiveStep(eventTarget)
      this.setActiveStep(activeStep)
      this.setActivePanel(activeStep)
    })

    /**
     * Mapeia clique nos botões de navegação
     */
    this.DOMstrings.stepsForm.addEventListener('click', (e) => {
      const eventTarget = e.target
      if (
        !(
          eventTarget.classList.contains(
            `${this.DOMstrings.stepPrevBtnClass}`
          ) ||
          eventTarget.classList.contains(`${this.DOMstrings.stepNextBtnClass}`)
        )
      ) {
        return
      }
      const activePanel = this.findParent(
        eventTarget,
        `${this.DOMstrings.stepFormPanelClass}`
      )
      let activePanelNum = Array.from(this.DOMstrings.stepFormPanels).indexOf(
        activePanel
      )
      if (
        eventTarget.classList.contains(`${this.DOMstrings.stepPrevBtnClass}`)
      ) {
        activePanelNum -= 1
        activePanel.style.left = '1%'
      } else {
        activePanelNum += 1
        activePanel.style.left = '-1%'
      }
      this.setActiveStep(activePanelNum)
      this.setActivePanel(activePanelNum)
    })

    this.setStepsNum()

    if (this.component.hasAttribute('step')) {
      this.setStep(this.component.getAttribute('step'))
    }

    if (
      this.component.hasAttribute('scroll') &&
      !this.component.hasAttribute('vertical')
    ) {
      const stepsWidth =
        Math.round(100 / this.DOMstrings.stepsBtns.length) - 0.5
      this.DOMstrings.stepsBar.style.gridTemplateColumns = `repeat(auto-fit, minmax(100px, ${stepsWidth}% ))`
    }

    /**
     * Configura gestos (swipe)
     */
    const dispatcher = new _partial_js_behavior_swipe__WEBPACK_IMPORTED_MODULE_0__/* ["default"] */ .Z(this.DOMstrings.stepsBar)
    if (this.component.hasAttribute('vertical')) {
      dispatcher.on('SWIPE_LEFT', () => {
        this.collapseSteps()
      })
      dispatcher.on('SWIPE_RIGHT', () => {
        this.expandSteps()
      })
      this.DOMstrings.stepsForm.ontouchstart = () => {
        this.collapseSteps()
      }
    } else {
      this.DOMstrings.stepsBar.ontouchstart = () => {
        this.expandSteps()
      }
      this.DOMstrings.stepsForm.ontouchstart = () => {
        this.collapseSteps()
      }
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (BRWizard);


/***/ }),

/***/ "./src/partial/js/behavior/accordion.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Accordion)
/* harmony export */ });
/* harmony import */ var _collapse__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/collapse.js");


/**
 * Classe para o comportamento Accordion.
 * O Comportamento Accordion é um agrupador de comportamentos Collapse
 */
class Accordion extends _collapse__WEBPACK_IMPORTED_MODULE_0__["default"] {
  /**
   * Instancia um comportamento accordion
   * @param {object} - Objeto de configuração inicial para destructuring
   * @property {object} trigger - Elemento DOM que representa o acionador do comportmento accordion
   * @property {string} iconToShow - Classe que representa o ícone para mostrar o conteúdo (padrão: fa-chevron-down)
   * @property {string} iconToHide - Classe que representa o ícone para esconder o conteúdo (padrão: fa-chevron-up)
   * @property {boolean} useIcons - true: com ícone | false: sem ícone (padrão: true)
   */
  constructor({
    trigger,
    iconToShow = 'fa-chevron-down',
    iconToHide = 'fa-chevron-up',
    useIcons = true,
  }) {
    super({ iconToHide, iconToShow, trigger, useIcons })
    this._setUp()
  }

  /**
   * Trata a configuração inicial do comportamento accordion
   * @private
   */
  _setUp() {
    super._setUp()
    this._setPriorityVisibility()
  }

  /**
   * Determina qual acionador vai estar visivel, caso mais de 1 acionador esteja visivel no grupo.
   * Prioridade de cima para baixo
   * @private
   */
  _setPriorityVisibility() {
    for (let i = 0; i < this._getGroup().length; i += 1) {
      if (this._getGroup()[i].dataset.visible === 'true') {
        this._synchronizeAccordion(this._getGroup()[i])
        break
      }
    }
  }

  /**
   * Obtém todos os acionadores pertencentes ao grupo do comportamento accordion
   * @returns {array} - Conjunto de elementos DOM representando os acionadores pertencentes ao grupo do comportamento accordion
   * @private
   */
  _getGroup() {
    return document.querySelectorAll(
      `[data-group="${this.trigger.getAttribute('data-group')}"]`
    )
  }

  /**
   * Handler para o evento 'change' do acionador
   * @param {object} event - Objeto do tipo Event
   * @private
   */
  _handleTriggerChangeBehavior(event) {
    if (!event.currentTarget.hasAttribute('data-sync')) {
      this._synchronizeAccordion(event.currentTarget)
    } else {
      event.currentTarget.removeAttribute('data-sync')
    }
  }

  /**
   * Sincroniza o grupo de accordion mostrando 1 elemento aberto por vez
   * @param {object} currentTrigger - Elemento DOM representando um acionador do comportamento accordion
   * @private
   */
  _synchronizeAccordion(currentTrigger) {
    this._getGroup().forEach((trigger) => {
      if (trigger !== currentTrigger && trigger.dataset.visible === 'true') {
        trigger.setAttribute('data-sync', '')
        trigger.click()
      }
    })
  }

  /**
   * Configura o comportamento accordion
   * @public
   */
  setBehavior() {
    super.setBehavior()
    this.trigger.addEventListener(
      'change',
      this._handleTriggerChangeBehavior.bind(this)
    )
  }
}


/***/ }),

/***/ "./src/partial/js/behavior/checkgroup.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Checkgroup)
/* harmony export */ });
/**
 * Comportamento do grupo de checkboxes
 *
 * Cada checkbox parent em uma cadeia de checkbox terá um objeto próprio para tratar
 * o seu comportamento e de seus filhos. Os eventListeners são declarados em cada objeto,
 * de modo que a execução passa por objetos diferentes quando um checkbox é pai e filho ao mesmo tempo
 *
 * O comportamento é uma sincronia entre os eventos e o objeto que executa o evento
 */
class Checkgroup {
  /**
   * Instancia um comportamento de grupo de checkbox
   * @param {object} trigger - Elemento DOM que representa o checkbox acionador do comportamento
   */
  constructor(trigger) {
    this.parent = trigger
    // this.checkedLabel = trigger.dataset.checkedLabel
    // this.uncheckedLabel = trigger.dataset.uncheckedLabel
    this.children = this._setChildren(trigger.dataset.parent)
  }

  /**
   * Pega dinamincamente o valor do atributo data-checked-label
   * @type {string} - O valor do atributo data-checked-label ou o valor da label, caso não tenha o atributo
   * @readonly
   * @public
   */
  get checkedLabel() {
    return (
      this.parent.dataset.checkedLabel ||
      this.parent.nextElementSibling.innerText
    )
  }

  /**
   * Pega dinamincamente o valor do atributo data-unchecked-label
   * @type {string} - O valor do atributo data-unchecked-label ou o valor da label, caso não tenha o atributo
   * @readonly
   * @public
   */
  get uncheckedLabel() {
    return (
      this.parent.dataset.uncheckedLabel ||
      this.parent.nextElementSibling.innerText
    )
  }

  /**
   * Configurar o comportamento do grupo de checkbox
   * @public
   */
  setBehavior() {
    this._setParentBehavior()
    this._setChildBehavior()
  }

  /**
   * Obtem todos os checkboxes filhos do checkbox pai
   * @param {string} tag - Nome que indica a relação entre checkbox pai e checkbox filho
   * @returns {array} - Array contendo os elementos DOM que representam os checkboxes filhos.
   */
  _setChildren(tag) {
    return document.querySelectorAll(`[data-child="${tag}"]`)
  }

  /**
   * Trata do comportamento do checkbox pai
   * @private
   */
  _setParentBehavior() {
    this.parent.addEventListener('click', this._handleParentClick.bind(this))
    this.parent.addEventListener('change', this._handleParentChange.bind(this))
  }

  /**
   * Handler que trata o evento click no parent
   * @private
   */
  _handleParentClick() {
    if (!this.parent.hasAttribute('data-parent-sync')) {
      this._setIndeterminateStateOnClick()
    }
    this._setParentCheckboxLabel()
  }

  /**
   * Trata o estado indeterminado no evento click da etapa de interação do usuário
   * @private
   */
  _setIndeterminateStateOnClick() {
    if (this.parent.hasAttribute('indeterminate')) {
      this.parent.removeAttribute('indeterminate')
      this.parent.checked = true
    }
  }

  /**
   * Handler que trata o evento change no parent
   * @private
   */
  _handleParentChange() {
    if (!this.parent.hasAttribute('data-parent-sync')) {
      this._changeChildState()
      this._setParentCheckboxLabel()
    } else {
      this.parent.removeAttribute('data-parent-sync')
    }
  }

  /**
   * Trata do estado do checkboxes filhos
   * @private
   */
  _changeChildState() {
    this.children.forEach((child) => {
      if (
        child.checked !== this.parent.checked ||
        child.hasAttribute('indeterminate')
      ) {
        child.removeAttribute('indeterminate')
        child.checked = this.parent.checked
        child.dispatchEvent(new Event('change'))
      }
    })
  }

  /**
   * Trata do comportamento dos checkboxes filhos
   * @private
   */
  _setChildBehavior() {
    this.children.forEach((child) => {
      child.addEventListener('click', this._handleChildClick.bind(this))
      child.addEventListener('change', this._handleChildChange.bind(this))
    })
  }

  /**
   * Handler que trata do evento click no filho
   * @param {object} event - Objeto Event
   * @private
   */
  _handleChildClick(event) {
    event.currentTarget.setAttribute('data-child-sync', '')
  }

  /**
   * Handler que trata do evento change no filho
   * @param {object} event - Objeto Event
   * @private
   */
  _handleChildChange(event) {
    if (event.currentTarget.hasAttribute('data-child-sync')) {
      this._setIndeterminateStateOnChildChange()
      this.parent.setAttribute('data-parent-sync', '')
      this.parent.dispatchEvent(new Event('click'))
      this.parent.dispatchEvent(new Event('change'))
      event.currentTarget.removeAttribute('data-child-sync')
    }
  }

  /**
   * Trata o estado indeterminado no evento click da etapa de sincronia
   * @private
   */
  _setIndeterminateStateOnChildChange() {
    if (this._isAllChildrenChecked()) {
      this.parent.removeAttribute('indeterminate')
      this.parent.checked = true
    } else if (this._isAllChildrenUnchecked()) {
      this.parent.removeAttribute('indeterminate')
      this.parent.checked = false
    } else {
      this.parent.setAttribute('indeterminate', '')
      this.parent.checked = true
    }
  }

  /**
   * Verifica se todos os checkboxes filhos estão selecionados
   * @returns {boolean} - true se todos os checkboxes filhos estão selecionados
   *                      false se pelo menos 1 checkbox filho não está selecionado
   * @private
   */
  _isAllChildrenChecked() {
    let allChildChecked = true
    this.children.forEach((child) => {
      if (!child.checked || child.hasAttribute('indeterminate')) {
        allChildChecked = false
      }
    })
    return allChildChecked
  }

  /**
   * Verifica se todos so checkboxes filhos estão desselecionados
   * @returns {boolean} - true se todos os checkboxes filhos estão desselecionados
   *                      false se pelo menos 1 checkbox filho está selecionado
   * @private
   */
  _isAllChildrenUnchecked() {
    let allChildUnchecked = true
    this.children.forEach((child) => {
      if (child.checked) {
        allChildUnchecked = false
      }
    })
    return allChildUnchecked
  }

  /**
   * Configura a label do checkbox pai
   * @private
   */
  _setParentCheckboxLabel() {
    if (this.parent.checked && !this.parent.hasAttribute('indeterminate')) {
      this.parent.nextElementSibling.innerHTML = this.uncheckedLabel
      this.parent.setAttribute('aria-label', this.uncheckedLabel)
    } else {
      this.parent.nextElementSibling.innerHTML = this.checkedLabel
      this.parent.setAttribute('aria-label', this.checkedLabel)
    }
  }
}


/***/ }),

/***/ "./src/partial/js/behavior/collapse.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Collapse)
/* harmony export */ });
/** Classe para o comportamento Collapse */
class Collapse {
  /**
   * Instancia um comportamento collapse
   * @param {object} - Objeto de configuração inicial para destructuring
   * @property {object} trigger - Elemento DOM que representa o acionador do comportmento collapse
   * @property {string} iconToShow - Classe que representa o ícone para mostrar o conteúdo (padrão: fa-chevron-down)
   * @property {string} iconToHide - Classe que representa o ícone para esconder o conteúdo (padrão: fa-chevron-up)
   * @property {boolean} useIcons - true: com ícone | false: sem ícone (padrão: true)
   */
  constructor({
    trigger,
    iconToShow = 'fa-chevron-down',
    iconToHide = 'fa-chevron-up',
    useIcons = true,
  }) {
    this.trigger = trigger
    this.useIcons = useIcons
    this.breakpoint = trigger.getAttribute('data-breakpoint')
    this.setIconToShow(iconToShow)
    this.setIconToHide(iconToHide)
    this._setTarget()
    this._setUp()
  }

  /**
   * Determina qual elemento DOM é o alvo do comportamento collapse
   * @private
   */
  _setTarget() {
    this.target = document.querySelector(
      `#${this.trigger.getAttribute('data-target')}`
    )
  }

  // TODO: Melhorar a solução
  _checkBreakpoint() {
    if (this.breakpoint) {
      if (window.matchMedia('(min-width: 977px)').matches) {
        this.target.removeAttribute('hidden')
      }
    }
  }

  /**
   * Trata a configuração inicial do comportamento collapse
   * @private
   */
  _setUp() {
    this._setVisibilityStatus()
    if (this.useIcons) {
      this._toggleIcon()
    }
    this.trigger.setAttribute(
      'aria-controls',
      `${this.trigger.getAttribute('data-target')}`
    )
    this._checkBreakpoint()
  }

  /**
   * Configura o estado de visualização do comportamento collapse
   * @private
   */
  _setVisibilityStatus() {
    this._setTriggerVisibilityStatus()
    this._setTargetVisibilityStatus()
  }

  /**
   * Trata o estado de visualização do acionador
   * @private
   */
  _setTriggerVisibilityStatus() {
    if (this.target) {
      if (this.target.hasAttribute('hidden')) {
        this.trigger.setAttribute('data-visible', false)
        this.trigger.setAttribute('aria-expanded', false)
      } else {
        this.trigger.setAttribute('data-visible', true)
        this.trigger.setAttribute('aria-expanded', true)
      }
    }
  }

  /**
   * Trata o estado de visualização do alvo
   * @private
   */
  _setTargetVisibilityStatus() {
    if (this.target) {
      if (this.target.hasAttribute('hidden')) {
        this.target.setAttribute('aria-hidden', true)
      } else {
        this.target.setAttribute('aria-hidden', false)
      }
    }
  }

  /**
   * Handler para o evento de click no acionador do comportamento collapse
   * Lança um evento 'change' a cada troca
   * @private
   */
  _handleTriggerClickBehavior() {
    if (this.breakpoint) {
      if (window.matchMedia('(max-width: 977px)').matches) {
        this._toggleVisibility()
        if (this.useIcons) {
          this._toggleIcon()
        }
        this.trigger.dispatchEvent(new Event('change'))
      }
    } else {
      this._toggleVisibility()
      if (this.useIcons) {
        this._toggleIcon()
      }
      this.trigger.dispatchEvent(new Event('change'))
    }
  }

  /**
   * Alterna o estado de visualização do comportamento collapse
   * @private
   */
  _toggleVisibility() {
    if (this.target) {
      this.target.hasAttribute('hidden')
        ? this.target.removeAttribute('hidden')
        : this.target.setAttribute('hidden', '')

      this._setVisibilityStatus()
    }
  }

  /**
   * Troca o icone do acionador após uma mudança no estado de visualização do alvo
   * Para o estado 'hidden' usa o iconToShow e para o estado 'shown' usa o iconToHide
   * @public
   */
  _toggleIcon() {
    this.trigger.querySelectorAll('i.fas').forEach((icon) => {
      if (this.target) {
        icon.classList.remove(
          this.target.hasAttribute('hidden') ? this.iconToHide : this.iconToShow
        )
        icon.classList.add(
          this.target.hasAttribute('hidden') ? this.iconToShow : this.iconToHide
        )
      }
    })
  }

  /**
   * Configura o comportamento collapse
   * @public
   */
  setBehavior() {
    this.trigger.addEventListener(
      'click',
      this._handleTriggerClickBehavior.bind(this)
    )
  }

  /**
   * Determina a classe do icone para mostrar o conteúdo
   * @param {string} iconToShow - Classe que representa o ícone para mostrar o conteúdo
   * @public
   */
  setIconToShow(iconToShow) {
    this.iconToShow = iconToShow
  }

  /**
   * Determina a classe do ícone para esconder o conteúdo
   * @param {string} iconToHide - Classe que representa o ícone para esconder o conteúdo
   * @public
   */
  setIconToHide(iconToHide) {
    this.iconToHide = iconToHide
  }
}


/***/ }),

/***/ "./src/partial/js/behavior/dropdown.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Dropdown)
/* harmony export */ });
/* harmony import */ var _collapse__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./src/partial/js/behavior/collapse.js");


/**
 * Classe para o comportamento Dropdown.
 * O Comportamento Dropdown é um caso especial do comportamentos Collapse
 * em que o alvo aparece flutuando na tela.
 * Nesse caso o alvo precisa filho de um elemento pai
 */
class Dropdown extends _collapse__WEBPACK_IMPORTED_MODULE_0__["default"] {
  /**
   * Instancia um comportamento accordion
   * @param {object} - Objeto de configuração inicial para destructuring
   * @property {object} trigger - Elemento DOM que representa o acionador do comportmento accordion
   * @property {string} iconToShow - Classe que representa o ícone para mostrar o conteúdo (padrão: fa-chevron-down)
   * @property {string} iconToHide - Classe que representa o ícone para esconder o conteúdo (padrão: fa-chevron-up)
   * @property {boolean} useIcons - true: com ícone | false: sem ícone (padrão: true)
   */
  constructor({
    trigger,
    iconToShow = 'fa-chevron-down',
    iconToHide = 'fa-chevron-up',
    useIcons = true,
  }) {
    super({ iconToHide, iconToShow, trigger, useIcons })
  }

  /**
   * Trata a configuração inicial do comportamento dropdown
   * @override
   * @private
   */
  _setUp() {
    super._setUp()
    this._hideDropdown()
  }

  /**
   * Oculta o dropdown ao clicar fora do acionador e do alvo
   * @private
   */
  _hideDropdown() {
    document.addEventListener('mousedown', (event) => {
      if (
        !this.trigger.contains(event.target) &&
        !this.target.hasAttribute('hidden') &&
        !this.target.contains(event.target)
      ) {
        this.trigger.click()
      }
    })
  }

  /**
   * Marca o alvo com o atributo data-dropdown
   * @override
   * @private
   */
  _setTargetVisibilityStatus() {
    super._setTargetVisibilityStatus()
    if (this.target.hasAttribute('hidden')) {
      this.target.removeAttribute('data-dropdown')
    } else {
      this.target.setAttribute('data-dropdown', '')
    }
  }

  /**
   * Trata o estado de visualização do pai do alvo
   * @private
   */
  _setParentsTargetVisibilityStatus() {
    if (this.target.hasAttribute('hidden')) {
      this.target.parentElement.classList.remove('dropdown')
    } else {
      this.target.parentElement.classList.add('dropdown')
    }
  }

  /**
   * Handler para o evento de click no acionador do comportamento dropdown
   * Lança um evento 'change' a cada troca
   * @override
   * @private
   */
  _handleTriggerClickBehavior() {
    super._handleTriggerClickBehavior()
    this._setParentsTargetVisibilityStatus()
  }

  /**
   * Configura o comportamento dropdown
   * @override
   * @public
   */
  setBehavior() {
    super.setBehavior()
  }
}


/***/ }),

/***/ "./src/partial/js/behavior/scrim.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Scrim)
/* harmony export */ });
/** Classe para o comportamento scrim */
class Scrim {
  /**
   * Instancia um comportamento scrim
   * @param {object} - Objeto de configuração inicial para destructuring
   * @property {object} trigger - Elemento DOM que representa o acionador do comportmento scrim
   * @property {string} closeElement - Elemento Dom do trigger que fecha o scrim
   */
  constructor({ trigger, closeElement }) {
    this.trigger = trigger
    this.closeElement = this.elementHideScrim(closeElement)
    if (this.trigger) {
      this.setBehavior()
    }
  }

  /**
   * Alterna o estado de visualização do comportamento scrim
   * @private
   */
  showScrim() {
    if (this.trigger) {
      this.trigger.classList.add('active')
      this.trigger.setAttribute('data-visible', true)
      this.trigger.setAttribute('aria-expanded', true)
    }
  }
  /**
   * Alterna o estado de escondido do comportamento scrim
   * @private
   */
  hideScrim() {
    this.trigger.classList.remove('active')
    this.trigger.setAttribute('data-visible', false)
    this.trigger.setAttribute('aria-expanded', false)
  }

  /**
   * Seta o elemento Dom que vai fechar o scrim
   * @public
   */
  elementHideScrim(element) {
    if (this.trigger.querySelectorAll(element)) {
      this.trigger.querySelectorAll(element).forEach((element) => {
        this.closeElement = element
        this._setCloseClick()
      })
    }
  }

  /**
   * Adiciona listener de fechamento no elemento que fecha o scrim
   * @private
   */
  _setCloseClick() {
    this.closeElement.addEventListener('click', () => {
      this.hideScrim()
    })
  }
  /**
   * Configura o comportamento scrim
   * @public
   */
  setBehavior() {
    this.trigger.addEventListener('click', (event) => {
      if (event.target.getAttribute('data-scrim')) {
        this.hideScrim()
      }
    })
  }
}


/***/ }),

/***/ "./src/partial/js/behavior/swipe.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Z: () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* unused harmony export Swipe */
class Swipe {
  constructor(element) {
    this.evtMap = {
      SWIPE_DOWN: [],
      SWIPE_LEFT: [],
      SWIPE_RIGHT: [],
      SWIPE_UP: [],
    }
    this.xDown = null
    this.yDown = null
    this.element = element

    element.addEventListener(
      'touchstart',
      (evt) => {
        return this.handleTouchStart(evt)
      },
      false
    )
    element.addEventListener(
      'touchend',
      (evt) => {
        return this.handleTouchEnd(evt)
      },
      false
    )
  }

  on(evt, cb) {
    this.evtMap[evt].push(cb)
  }

  off(evt, lcb) {
    this.evtMap[evt] = this.evtMap[evt].filter((cb) => {
      return cb !== lcb
    })
  }

  trigger(evt, data) {
    this.evtMap[evt].map((handler) => {
      return handler(data)
    })
  }

  handleTouchStart(evt) {
    this.xDown = evt.touches[0].clientX
    this.yDown = evt.touches[0].clientY
  }

  handleTouchEnd(evt) {
    const deltaX = evt.changedTouches[0].clientX - this.xDown
    const deltaY = evt.changedTouches[0].clientY - this.yDown
    if (Math.abs(deltaX) > Math.abs(deltaY)) {
      deltaX < 0 ? this.trigger('SWIPE_LEFT') : this.trigger('SWIPE_RIGHT')
    } else {
      deltaY > 0 ? this.trigger('SWIPE_DOWN') : this.trigger('SWIPE_UP')
    }
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Swipe);


/***/ }),

/***/ "./src/partial/js/behavior/tooltip.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _popperjs_core__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__("./node_modules/@popperjs/core/lib/popper.js");

// import 'tippy.js/dist/tippy.css' // optional for styling
class Tooltip {
  /**
   * Instancia um comportamento Tooltip
   * @param {object} - Objeto de configuração inicial para tooltip
   * @property {object} component - Elemento DOM do tooltip (opcional se tiver o textTooltip)
   * @property {object} activator - Elemento DOM que representa o acionador do comportmento tooltip
   * @property {string} place -Local onde vai aparecer o tooltip ('top', 'right', 'bottom', 'left')
   * @property {string} timer - Tempo em que vai aparecer o tooltip em milisegunto (opcional)
   * @property {string} textTooltip - Texto que vai ser mostrado quando não estiver instaciado o atributo component
   * @property {string} type - Tipo de tooltip (info, warning) padrão info
   * @property {boolean} onActivator - Adiciona o tooltip dentro do elemento ativador padrão false
   */
  
  constructor({
    component,
    activator,
    place = 'top',
    timer,
    active,
    textTooltip,
    type = 'info',
    onActivator = false
  }) {
    
    const text_tooltip = textTooltip ? textTooltip : component
    this.onActivator = onActivator 
    this.activator = activator
    this.component = component
      ? component
      : this._setContent(text_tooltip, type)

    this.place =
      this.component.getAttribute('place') === null
        ? this.component.getAttribute('place')
        : place
    const positions = ['top', 'right', 'bottom', 'left']
    this.popover = this.component.hasAttribute('popover')
    this.notification = this.component.classList.contains('br-notification')
    this.timer = this.component.getAttribute('timer')
      ? this.component.getAttribute('timer')
      : timer
    this.active = this.component.hasAttribute('active')
      ? this.component.hasAttribute('active')
      : active
    this.placement = positions.includes(place)
      ? place
      : this.notification

      
    this.popperInstance = null
    this.showEvents = ['mouseenter', 'click', 'focus']
    this.hideEvents = ['mouseleave', 'blur']
    this.closeTimer = null
    this._create()
    this._setBehavior()
  }
  /**
   * Instancializa o behavior
   */
  _setBehavior() {
    // Ação de abrir padrao ao entrar no ativador
    if (this.activator) {
      this.showEvents.forEach((event) => {
        this.activator.addEventListener(event, (otherEvent) => {
          this._show(otherEvent)
        })
      })
    }
    // Adiciona ação de fechar ao botao do popover
    
    if (this.popover) {
      const closeBtn = this.component.querySelector('.close')
      closeBtn.addEventListener('click', (event) => {
        this._hide(event, this.component)
        this._toggleActivatorIcon()
      })
      // Ação de fechar padrao ao sair do ativador
    } else {
      this.hideEvents.forEach((event) => {
        this.activator.addEventListener(event, (otherEvent) => {
          this._hide(otherEvent, this.component)
        })
      })
    }
  }
  /**
   * Inclui o conteudo do tooltip
   * @param {*} contentText conteudo do texto do tooltip
   * @param {*} type tipo de tooltip
   * @returns  - retorna o objeto com tooltip
   */
  _setContent(contentText, type) {
    const text_tooltip = document.createElement('div')
    text_tooltip.setAttribute('role', 'tooltip')
    text_tooltip.setAttribute('place', 'top')
    text_tooltip.setAttribute(type, type)
    text_tooltip.innerText = `${contentText}`
    text_tooltip.classList.add('br-tooltip')
    //TODO: Retirar sample que utiliza não conflita com tooltip componente que está sendo depreciado
    text_tooltip.classList.add('sample')
    if (this.activator && this.onActivator) {
      this.activator.appendChild(text_tooltip)
    }else{
      document.body.appendChild(text_tooltip)
    }

    return text_tooltip
  }

  /**
   * Cria a instancia do popper
   * @private
   */

  _create() {
    this._setLayout()

    if (this.notification) {
      this.component.setAttribute('notification', '')
      this.popperInstance = (0,_popperjs_core__WEBPACK_IMPORTED_MODULE_0__/* .createPopper */ .fi)(this.activator, this.component, {
        modifiers: [
          {
            name: 'offset',
            options: {
              offset: [0, 8],
            },
          },
          {
            name: 'preventOverflow',
            options: {
              altAxis: false, // false by default
              mainAxis: true, // true by default
              
            },
          },
          {name: 'flip',options: {fallbackPlacements: ['top', 'right'],},},
        ],
        // placement: this.placement,
        placement: 'bottom',
        strategy: 'fixed',
      })
    } else {
      
    
      
      this.popperInstance = (0,_popperjs_core__WEBPACK_IMPORTED_MODULE_0__/* .createPopper */ .fi)(this.activator, this.component, {
        modifiers: [
          {
            name: 'offset',
            options: {
              offset: [0, 8],
            },
          },
          {
            name: 'preventOverflow',
            options: {
              altAxis: true, 
              mainAxis: true, 
              
              
            },
          },
          {name: 'flip',options: {fallbackPlacements: ['top', 'right',"bottom","left"],},},
        ],
        placement: this.placement,
      })
    }
  }
  /**
   * Mostra o tooltip e define o timeout se tiver
   * @param {object} event  evento javascript
   */
  _show(event) {
    
    this.component.style.display = 'unset'
    this.component.setAttribute('data-show', '')
    this.component.style.zIndex = 9999
    this.popperInstance.update()
    // Importante pois "display: none" conflitua com a instancia do componente e precisa ser setado aqui já que pelo css ativa o efeito fade no primeiro carregamento
    this.component.style.visibility = 'visible'
    if (this.timer) {
      console.log("timer",this.timer)
      clearTimeout(this.closeTimer)
      this.closeTimer = setTimeout(
        this._hide,
        this.timer,
        event,
        this.component
      )
    }
  }

  

  /**
   * Esconde o componente
   * @private
   */
  _hide(event, component) {
    component.removeAttribute('data-show')
    component.style.zIndex = -1
    component.style.visibility = 'hidden'
    clearTimeout(component.closeTimer)
  }
  /**
   * Cria o laytout do tooltip
   * @private
   */
  _setLayout() {
    // Cria a setinha que aponta para o item que criou o tooltip
    const arrow = document.createElement('div')
    arrow.setAttribute('data-popper-arrow', '')

    if (this.component.querySelectorAll('.arrow').length < 1) {
      arrow.classList.add('arrow')
    }
    this.component.appendChild(arrow)
    // Cria o icone de fechar do po over
    if (this.popover) {
      const close = document.createElement('button')
      close.setAttribute('type', 'button')
      close.classList.add('close')
      const ico = document.createElement('i')
      ico.classList.add('fas', 'fa-times')
      close.appendChild(ico)
      this.component.appendChild(close)
    }
  }
  /**
   * Muda icone da seta
   * @private
   */
  _toggleActivatorIcon() {
    const icon = this.activator.querySelector('button svg')
    if (icon) {
      icon.classList.toggle('fa-angle-down')
      icon.classList.toggle('fa-angle-up')
    }
    this.activator.toggleAttribute('active')
  }
  
  
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Tooltip);



/***/ }),

/***/ "./src/partial/js/globals-class.js":
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  Dropdown: () => (/* reexport */ dropdown["default"]),
  Globals: () => (/* binding */ Globals)
});

// UNUSED EXPORTS: Accordion, BRAccordion, BRAvatar, BRBreadcrumb, BRCard, BRCheckbox, BRDateTimePicker, BRFooter, BRHeader, BRInput, BRItem, BRList, BRMenu, BRMessage, BRModal, BRNotification, BRPagination, BRScrim, BRSelect, BRTab, BRTable, BRTag, BRTextarea, BRTooltip, BRUpload, BRWizard, Checkgroup, Collapse, Swipe, default

// EXTERNAL MODULE: ./src/components/accordion/accordion.js
var accordion = __webpack_require__("./src/components/accordion/accordion.js");
// EXTERNAL MODULE: ./src/components/avatar/avatar.js
var avatar = __webpack_require__("./src/components/avatar/avatar.js");
// EXTERNAL MODULE: ./src/components/breadcrumb/breadcrumb.js
var breadcrumb = __webpack_require__("./src/components/breadcrumb/breadcrumb.js");
// EXTERNAL MODULE: ./src/components/card/card.js
var card = __webpack_require__("./src/components/card/card.js");
// EXTERNAL MODULE: ./src/components/carousel/carousel.js
var carousel = __webpack_require__("./src/components/carousel/carousel.js");
// EXTERNAL MODULE: ./src/components/checkbox/checkbox.js
var checkbox_checkbox = __webpack_require__("./src/components/checkbox/checkbox.js");
// EXTERNAL MODULE: ./src/components/datetimepicker/datetimepicker.js + 7 modules
var datetimepicker = __webpack_require__("./src/components/datetimepicker/datetimepicker.js");
// EXTERNAL MODULE: ./src/components/footer/footer.js
var footer = __webpack_require__("./src/components/footer/footer.js");
// EXTERNAL MODULE: ./src/components/header/header.js
var header = __webpack_require__("./src/components/header/header.js");
// EXTERNAL MODULE: ./src/components/input/input.js
var input = __webpack_require__("./src/components/input/input.js");
;// CONCATENATED MODULE: ./src/components/input/input-data.js
/**
 * Dados para popular o input de exemplo
 * @constant {string[]} */
const inputData = [
  'Afeganistão',
  'África do Sul',
  'Albânia',
  'Alemanha',
  'Andorra',
  'Angola',
  'Anguilla',
  'Antártida',
  'Antígua e Barbuda',
  'Antilhas Holandesas',
  'Arábia Saudita',
  'Argélia',
  'Argentina',
  'Armênia',
  'Aruba',
  'Austrália',
  'Áustria',
  'Azerbaijão',
  'Bahamas',
  'Bahrein',
  'Bangladesh',
  'Barbados',
  'Belarus',
  'Bélgica',
  'Belize',
  'Benin',
  'Bermudas',
  'Bolívia',
  'Bósnia-Herzegóvina',
  'Botsuana',
  'Brasil',
  'Brunei',
  'Bulgária',
  'Burkina Fasso',
  'Burundi',
  'Butão',
  'Cabo Verde',
  'Camarões',
  'Camboja',
  'Canadá',
  'Cazaquistão',
  'Chade',
  'Chile',
  'China',
  'Chipre',
  'Cingapura',
  'Colômbia',
  'Congo',
  'Coréia do Norte',
  'Coréia do Sul',
  'Costa do Marfim',
  'Costa Rica',
  'Croácia (Hrvatska)',
  'Cuba',
  'Dinamarca',
  'Djibuti',
  'Dominica',
  'Egito',
  'El Salvador',
  'Emirados Árabes Unidos',
  'Equador',
  'Eritréia',
  'Eslováquia',
  'Eslovênia',
  'Espanha',
  'Estados Unidos',
  'Estônia',
  'Etiópia',
  'Fiji',
  'Filipinas',
  'Finlândia',
  'França',
  'Gabão',
  'Gâmbia',
  'Gana',
  'Geórgia',
  'Gibraltar',
  'Grã-Bretanha (Reino Unido, UK)',
  'Granada',
  'Grécia',
  'Groelândia',
  'Guadalupe',
  'Guam (Território dos Estados Unidos)',
  'Guatemala',
  'Guernsey',
  'Guiana',
  'Guiana Francesa',
  'Guiné',
  'Guiné Equatorial',
  'Guiné-Bissau',
  'Haiti',
  'Holanda',
  'Honduras',
  'Hong Kong',
  'Hungria',
  'Iêmen',
  'Ilha Bouvet (Território da Noruega)',
  'Ilha do Homem',
  'Ilha Natal',
  'Ilha Pitcairn',
  'Ilha Reunião',
  'Ilhas Aland',
  'Ilhas Cayman',
  'Ilhas Cocos',
  'Ilhas Comores',
  'Ilhas Cook',
  'Ilhas Faroes',
  'Ilhas Falkland (Malvinas)',
  'Ilhas Geórgia do Sul e Sandwich do Sul',
  'Ilhas Heard e McDonald (Território da Austrália)',
  'Ilhas Marianas do Norte',
  'Ilhas Marshall',
  'Ilhas Menores dos Estados Unidos',
  'Ilhas Norfolk',
  'Ilhas Seychelles',
  'Ilhas Solomão',
  'Ilhas Svalbard e Jan Mayen',
  'Ilhas Tokelau',
  'Ilhas Turks e Caicos',
  'Ilhas Virgens (Estados Unidos)',
  'Ilhas Virgens (Inglaterra)',
  'Ilhas Wallis e Futuna',
  'índia',
  'Indonésia',
  'Irã',
  'Iraque',
  'Irlanda',
  'Islândia',
  'Israel',
  'Itália',
  'Jamaica',
  'Japão',
  'Jersey',
  'Jordânia',
  'Kênia',
  'Kiribati',
  'Kuait',
  'Laos',
  'Látvia',
  'Lesoto',
  'Líbano',
  'Libéria',
  'Líbia',
  'Liechtenstein',
  'Lituânia',
  'Luxemburgo',
  'Macau',
  'Macedônia (República Yugoslava)',
  'Madagascar',
  'Malásia',
  'Malaui',
  'Maldivas',
  'Mali',
  'Malta',
  'Marrocos',
  'Martinica',
  'Maurício',
  'Mauritânia',
  'Mayotte',
  'México',
  'Micronésia',
  'Moçambique',
  'Moldova',
  'Mônaco',
  'Mongólia',
  'Montenegro',
  'Montserrat',
  'Myanma',
  'Namíbia',
  'Nauru',
  'Nepal',
  'Nicarágua',
  'Níger',
  'Nigéria',
  'Niue',
  'Noruega',
  'Nova Caledônia',
  'Nova Zelândia',
  'Omã',
  'Palau',
  'Panamá',
  'Papua-Nova Guiné',
  'Paquistão',
  'Paraguai',
  'Peru',
  'Polinésia Francesa',
  'Polônia',
  'Porto Rico',
  'Portugal',
  'Qatar',
  'Quirguistão',
  'República Centro-Africana',
  'República Democrática do Congo',
  'República Dominicana',
  'República Tcheca',
  'Romênia',
  'Ruanda',
  'Rússia (antiga URSS) - Federação Russa',
  'Saara Ocidental',
  'Saint Vincente e Granadinas',
  'Samoa Americana',
  'Samoa Ocidental',
  'San Marino',
  'Santa Helena',
  'Santa Lúcia',
  'São Bartolomeu',
  'São Cristóvão e Névis',
  'São Martim',
  'São Tomé e Príncipe',
  'Senegal',
  'Serra Leoa',
  'Sérvia',
  'Síria',
  'Somália',
  'Sri Lanka',
  'St. Pierre and Miquelon',
  'Suazilândia',
  'Sudão',
  'Suécia',
  'Suíça',
  'Suriname',
  'Tadjiquistão',
  'Tailândia',
  'Taiwan',
  'Tanzânia',
  'Território Britânico do Oceano índico',
  'Territórios do Sul da França',
  'Territórios Palestinos Ocupados',
  'Timor Leste',
  'Togo',
  'Tonga',
  'Trinidad and Tobago',
  'Tunísia',
  'Turcomenistão',
  'Turquia',
  'Tuvalu',
  'Ucrânia',
  'Uganda',
  'Uruguai',
  'Uzbequistão',
  'Vanuatu',
  'Vaticano',
  'Venezuela',
  'Vietnã',
  'Zâmbia',
  'Zimbábue',
]

/* harmony default export */ const input_data = (inputData);

// EXTERNAL MODULE: ./src/components/item/item.js
var item = __webpack_require__("./src/components/item/item.js");
// EXTERNAL MODULE: ./src/components/list/list.js
var list = __webpack_require__("./src/components/list/list.js");
// EXTERNAL MODULE: ./src/components/menu/menu.js
var menu = __webpack_require__("./src/components/menu/menu.js");
// EXTERNAL MODULE: ./src/components/message/message.js
var message = __webpack_require__("./src/components/message/message.js");
// EXTERNAL MODULE: ./src/components/modal/modal.js
var modal = __webpack_require__("./src/components/modal/modal.js");
// EXTERNAL MODULE: ./src/components/notification/notification.js
var notification = __webpack_require__("./src/components/notification/notification.js");
// EXTERNAL MODULE: ./src/components/pagination/pagination.js
var pagination = __webpack_require__("./src/components/pagination/pagination.js");
// EXTERNAL MODULE: ./src/components/scrim/scrim.js
var scrim_scrim = __webpack_require__("./src/components/scrim/scrim.js");
// EXTERNAL MODULE: ./src/components/select/select.js
var select_select = __webpack_require__("./src/components/select/select.js");
// EXTERNAL MODULE: ./src/components/step/step.js
var step = __webpack_require__("./src/components/step/step.js");
// EXTERNAL MODULE: ./src/components/tab/tab.js
var tab = __webpack_require__("./src/components/tab/tab.js");
// EXTERNAL MODULE: ./src/components/table/table.js
var table = __webpack_require__("./src/components/table/table.js");
// EXTERNAL MODULE: ./src/components/tag/tag.js
var tag = __webpack_require__("./src/components/tag/tag.js");
// EXTERNAL MODULE: ./src/components/textarea/textarea.js
var textarea_textarea = __webpack_require__("./src/components/textarea/textarea.js");
// EXTERNAL MODULE: ./src/components/tooltip/tooltip.js
var tooltip = __webpack_require__("./src/components/tooltip/tooltip.js");
// EXTERNAL MODULE: ./src/components/upload/upload.js
var upload = __webpack_require__("./src/components/upload/upload.js");
// EXTERNAL MODULE: ./src/components/wizard/wizard.js
var wizard = __webpack_require__("./src/components/wizard/wizard.js");
// EXTERNAL MODULE: ./src/partial/js/behavior/dropdown.js
var dropdown = __webpack_require__("./src/partial/js/behavior/dropdown.js");
;// CONCATENATED MODULE: ./src/partial/js/globals-class.js
// Script Templates










 // Dados para o autocomplete do input























class Globals {
  initInstanceAll() {
    this.initInstanceAccordion()
    this.initInstanceAvatar()
    this.initInstanceBreadcrumb()
    this.initInstanceCard()
    this.initInstanceCarousel()
    this.initInstanceCheckbox()
    this.initInstanceDateTimePicker()
    this.initInstanceFooter()
    this.initInstanceInput()
    this.initInstanceHeader()
    this.initInstanceItem()
    this.initInstanceList()
    this.initInstanceMenu()
    this.initInstanceMessage()
    this.initInstanceModal()
    this.initInstanceNotification()
    this.initInstancePagination()
    this.initInstanceScrim()
    this.initInstanceSelect()
    this.initInstanceStep()
    this.initInstanceTable()
    this.initInstanceTabs()
    this.initInstanceTextarea()
    this.initInstanceUpload()
    this.initInstanceWizard()
    this.initInstanceTooltip()
  }

  initInstanceAccordion() {
    const accordionList = []
    for (const brAccordion of window.document.querySelectorAll(
      '.br-accordion'
    )) {
      accordionList.push(new accordion/* default */.Z('br-accordion', brAccordion))
    }
  }

  initInstanceAvatar() {
    const avatarList = []
    for (const brAvatar of window.document.querySelectorAll('.br-avatar')) {
      avatarList.push(new avatar/* default */.Z('br-avatar', brAvatar))
    }
  }

  initInstanceBreadcrumb() {
    const breadcrumbList = []
    for (const brBreadcrumb of window.document.querySelectorAll(
      '.br-breadcrumb'
    )) {
      breadcrumbList.push(new breadcrumb/* default */.Z('br-breadcrumb', brBreadcrumb))
    }
  }

  initInstanceDateTimePicker() {
    const datetimepickerList = []
    for (const brDateTimePicker of window.document.querySelectorAll(
      '.br-datetimepicker'
    )) {
      datetimepickerList.push(
        new datetimepicker/* default */.Z('br-datetimepicker', brDateTimePicker, {})
      )
    }
  }

  initInstanceHeader() {
    const headerList = []
    for (const brHeader of window.document.querySelectorAll('.br-header')) {
      headerList.push(new header/* default */.Z('br-header', brHeader))
    }
  }
  initInstanceFooter() {
    const listFooter = []
    for (const brFooter of window.document.querySelectorAll('.br-footer')) {
      listFooter.push(new footer/* default */.Z('br-footer', brFooter))
    }
  }

  initInstanceInput() {
    const inputList = []
    for (const brInput of window.document.querySelectorAll('.br-input')) {
      inputList.push(new input/* default */.Z('br-input', brInput))
    }
    for (const brInput of inputList) {
      brInput.component
        .querySelectorAll('input.search-autocomplete')
        .forEach(() => {
          brInput.setAutocompleteData(input_data)
        })
    }
  }

  initInstanceItem() {
    const itemList = []
    for (const brItem of window.document.querySelectorAll('.br-item')) {
      itemList.push(new item/* default */.Z('br-item', brItem))
    }
  }

  initInstanceList() {
    const listList = []
    for (const brList of window.document.querySelectorAll(
      '.br-list:not([data-sub])'
    )) {
      listList.push(new list/* default */.Z('br-list', brList))
    }
  }

  initInstanceMenu() {
    const menuList = []
    for (const brMenu of window.document.querySelectorAll('.br-menu')) {
      menuList.push(new menu/* default */.Z('br-menu', brMenu))
    }
  }

  initInstanceMessage() {
    const alertList = []
    for (const brMessage of window.document.querySelectorAll('.br-message')) {
      alertList.push(new message/* default */.Z('br-message', brMessage))
    }
  }

  initInstanceModal() {
    const modalList = []
    for (const brModal of window.document.querySelectorAll('.br-modal')) {
      modalList.push(new modal/* default */.Z('br-modal', brModal))
    }
    for (const brScrim of window.document.querySelectorAll('.br-scrim')) {
      const scrim = new scrim_scrim/* default */.Z('br-scrim', brScrim)
      for (const button of window.document.querySelectorAll(
        '.br-scrim + button'
      )) {
        button.addEventListener('click', () => {
          scrim.showScrim()
        })
      }
    }
  }

  initInstanceNotification() {
    const notificationList = []
    for (const brNotification of window.document.querySelectorAll(
      '.br-notification'
    )) {
      notificationList.push(
        new notification/* default */.Z('br-notification', brNotification)
      )
    }
  }

  initInstancePagination() {
    const paginationList = []
    for (const brPagination of window.document.querySelectorAll(
      '.br-pagination'
    )) {
      paginationList.push(new pagination/* default */.Z('br-pagination', brPagination))
    }
  }

  initInstanceScrim() {
    const scrimList = []
    for (const brScrim of window.document.querySelectorAll('.br-scrim')) {
      scrimList.push(new scrim_scrim/* default */.Z('br-scrim', brScrim))
    }
    for (const buttonBloco1 of window.document.querySelectorAll(
      '.bloco1 button'
    )) {
      buttonBloco1.addEventListener('click', () => {
        for (const brScrim of scrimList) {
          brScrim.showScrim()
        }
      })
    }
  }

  initInstanceSelect() {
    const selectList = []
    for (const brSelect of window.document.querySelectorAll('.br-select')) {
      const brselect = new select_select/* default */.Z('br-select', brSelect)
      //Exemplo de uso de listener do select
      // brSelect.addEventListener('onChange', () => {})

      selectList.push(brselect)
    }
  }

  initInstanceTable() {
    const tableList = []
    for (const [index, brTable] of window.document
      .querySelectorAll('.br-table')
      .entries()) {
      tableList.push(new table/* default */.Z('br-table', brTable, index))
    }
  }

  initInstanceTag() {
    const tagList = []
    for (const brTag of window.document.querySelectorAll('.br-tag')) {
      tagList.push(new tag/* default */.Z('br-tag', brTag))
    }
  }

  initInstanceTabs() {
    const abasList = []
    for (const brTab of window.document.querySelectorAll('.br-tab')) {
      abasList.push(new tab/* default */.Z('br-tab', brTab))
    }
  }

  initInstanceTooltip() {
    const tooltipList = []
    for (const brTooltip of window.document.querySelectorAll('.br-tooltip')) {
      tooltipList.push(new tooltip/* default */.Z('br-tooltip', brTooltip))
    }
  }

  initInstanceUpload() {
    const uploadList = []
    function uploadTimeout() {
      return new Promise((resolve) => {
        // Colocar aqui um upload para o servidor e retirar o timeout
        return setTimeout(resolve, 500)
      })
    }
    for (const brUpload of window.document.querySelectorAll('.br-upload')) {
      uploadList.push(new upload/* default */.Z('br-upload', brUpload, uploadTimeout))
    }
  }

  initInstanceStep() {
    const stepList = []
    for (const brStep of window.document.querySelectorAll('.br-step')) {
      stepList.push(new step/* default */.Z('br-step', brStep))
    }
  }

  initInstanceWizard() {
    const wizardList = []
    for (const brWizard of window.document.querySelectorAll('.br-wizard')) {
      wizardList.push(new wizard/* default */.Z('br-wizard', brWizard))
    }
  }

  initInstanceCard() {
    const listCard = []
    for (const brCard of window.document.querySelectorAll('.br-card')) {
      listCard.push(new card/* default */.Z('br-card', brCard))
    }
  }
  initInstanceCarousel() {
    const carouselList = []
    for (const brCarousel of window.document.querySelectorAll('.br-carousel')) {
      carouselList.push(new carousel/* default */.Z('br-carousel', brCarousel))
    }
  }

  initInstanceCheckbox() {
    const checkboxList = []
    for (const brCheckbox of window.document.querySelectorAll('.br-checkbox')) {
      checkboxList.push(new checkbox_checkbox/* default */.Z('br-checkbox', brCheckbox))
    }
  }

  initInstanceTextarea() {
    const textareaList = []
    for (const brTextarea of window.document.querySelectorAll('.br-textarea')) {
      textareaList.push(new textarea_textarea/* default */.Z('br-textarea', brTextarea))
    }
  }
  initInstanceWizard() {
    const wizardList = []
    for (const brWizard of window.document.querySelectorAll('.br-wizard')) {
      wizardList.push(new wizard/* default */.Z('br-wizard', brWizard))
    }
  }
}





/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  Accordion: () => (/* reexport */ behavior_accordion["default"]),
  BRAccordion: () => (/* reexport */ accordion/* default */.Z),
  BRAlert: () => (/* reexport */ message/* default */.Z),
  BRBreadcrumb: () => (/* reexport */ breadcrumb/* default */.Z),
  BRCard: () => (/* reexport */ card/* default */.Z),
  BRCarousel: () => (/* reexport */ carousel/* default */.Z),
  BRCheckbox: () => (/* reexport */ checkbox_checkbox/* default */.Z),
  BRDateTimePicker: () => (/* reexport */ datetimepicker/* default */.Z),
  BRFooter: () => (/* reexport */ footer/* default */.Z),
  BRHeader: () => (/* reexport */ header/* default */.Z),
  BRInput: () => (/* reexport */ input/* default */.Z),
  BRItem: () => (/* reexport */ item/* default */.Z),
  BRList: () => (/* reexport */ list/* default */.Z),
  BRMenu: () => (/* reexport */ menu/* default */.Z),
  BRModal: () => (/* reexport */ modal/* default */.Z),
  BRNotification: () => (/* reexport */ notification/* default */.Z),
  BRPagination: () => (/* reexport */ pagination/* default */.Z),
  BRScrim: () => (/* reexport */ scrim/* default */.Z),
  BRSelect: () => (/* reexport */ select_select/* default */.Z),
  BRStep: () => (/* reexport */ step/* default */.Z),
  BRTab: () => (/* reexport */ tab/* default */.Z),
  BRTable: () => (/* reexport */ table/* default */.Z),
  BRTag: () => (/* reexport */ tag/* default */.Z),
  BRTextarea: () => (/* reexport */ textarea_textarea/* default */.Z),
  BRTooltip: () => (/* reexport */ tooltip/* default */.Z),
  BRUpload: () => (/* reexport */ upload/* default */.Z),
  BRWizard: () => (/* reexport */ wizard/* default */.Z),
  Checkgroup: () => (/* reexport */ behavior_checkgroup["default"]),
  Collapse: () => (/* reexport */ behavior_collapse["default"]),
  Dropdown: () => (/* reexport */ globals_class.Dropdown),
  Globals: () => (/* reexport */ globals_class.Globals),
  Swipe: () => (/* reexport */ swipe/* default */.Z),
  Tooltip: () => (/* reexport */ behavior_tooltip["default"]),
  behavior: () => (/* binding */ behavior),
  globals: () => (/* binding */ globals)
});

// EXTERNAL MODULE: ./node_modules/focus-visible/dist/focus-visible.js
var focus_visible = __webpack_require__("./node_modules/focus-visible/dist/focus-visible.js");
// EXTERNAL MODULE: ./src/components/accordion/accordion.js
var accordion = __webpack_require__("./src/components/accordion/accordion.js");
// EXTERNAL MODULE: ./src/components/breadcrumb/breadcrumb.js
var breadcrumb = __webpack_require__("./src/components/breadcrumb/breadcrumb.js");
// EXTERNAL MODULE: ./src/components/card/card.js
var card = __webpack_require__("./src/components/card/card.js");
// EXTERNAL MODULE: ./src/components/carousel/carousel.js
var carousel = __webpack_require__("./src/components/carousel/carousel.js");
// EXTERNAL MODULE: ./src/components/checkbox/checkbox.js
var checkbox_checkbox = __webpack_require__("./src/components/checkbox/checkbox.js");
// EXTERNAL MODULE: ./src/components/datetimepicker/datetimepicker.js + 7 modules
var datetimepicker = __webpack_require__("./src/components/datetimepicker/datetimepicker.js");
// EXTERNAL MODULE: ./src/components/footer/footer.js
var footer = __webpack_require__("./src/components/footer/footer.js");
// EXTERNAL MODULE: ./src/components/header/header.js
var header = __webpack_require__("./src/components/header/header.js");
// EXTERNAL MODULE: ./src/components/input/input.js
var input = __webpack_require__("./src/components/input/input.js");
// EXTERNAL MODULE: ./src/components/item/item.js
var item = __webpack_require__("./src/components/item/item.js");
// EXTERNAL MODULE: ./src/components/list/list.js
var list = __webpack_require__("./src/components/list/list.js");
// EXTERNAL MODULE: ./src/components/menu/menu.js
var menu = __webpack_require__("./src/components/menu/menu.js");
// EXTERNAL MODULE: ./src/components/message/message.js
var message = __webpack_require__("./src/components/message/message.js");
// EXTERNAL MODULE: ./src/components/modal/modal.js
var modal = __webpack_require__("./src/components/modal/modal.js");
// EXTERNAL MODULE: ./src/components/notification/notification.js
var notification = __webpack_require__("./src/components/notification/notification.js");
// EXTERNAL MODULE: ./src/components/pagination/pagination.js
var pagination = __webpack_require__("./src/components/pagination/pagination.js");
// EXTERNAL MODULE: ./src/components/scrim/scrim.js
var scrim = __webpack_require__("./src/components/scrim/scrim.js");
// EXTERNAL MODULE: ./src/components/select/select.js
var select_select = __webpack_require__("./src/components/select/select.js");
// EXTERNAL MODULE: ./src/components/step/step.js
var step = __webpack_require__("./src/components/step/step.js");
// EXTERNAL MODULE: ./src/components/tab/tab.js
var tab = __webpack_require__("./src/components/tab/tab.js");
// EXTERNAL MODULE: ./src/components/table/table.js
var table = __webpack_require__("./src/components/table/table.js");
// EXTERNAL MODULE: ./src/components/tag/tag.js
var tag = __webpack_require__("./src/components/tag/tag.js");
// EXTERNAL MODULE: ./src/components/textarea/textarea.js
var textarea_textarea = __webpack_require__("./src/components/textarea/textarea.js");
// EXTERNAL MODULE: ./src/components/tooltip/tooltip.js
var tooltip = __webpack_require__("./src/components/tooltip/tooltip.js");
// EXTERNAL MODULE: ./src/components/upload/upload.js
var upload = __webpack_require__("./src/components/upload/upload.js");
// EXTERNAL MODULE: ./src/components/wizard/wizard.js
var wizard = __webpack_require__("./src/components/wizard/wizard.js");
// EXTERNAL MODULE: ./src/partial/js/behavior/accordion.js
var behavior_accordion = __webpack_require__("./src/partial/js/behavior/accordion.js");
// EXTERNAL MODULE: ./src/partial/js/behavior/checkgroup.js
var behavior_checkgroup = __webpack_require__("./src/partial/js/behavior/checkgroup.js");
// EXTERNAL MODULE: ./src/partial/js/behavior/collapse.js
var behavior_collapse = __webpack_require__("./src/partial/js/behavior/collapse.js");
// EXTERNAL MODULE: ./src/partial/js/behavior/swipe.js
var swipe = __webpack_require__("./src/partial/js/behavior/swipe.js");
// EXTERNAL MODULE: ./src/partial/js/behavior/tooltip.js
var behavior_tooltip = __webpack_require__("./src/partial/js/behavior/tooltip.js");
;// CONCATENATED MODULE: ./src/util/accordion/accordion.js


/**
 * Classe para o exemplo do comportamento accordion
 */
class AccordionExample {
  /**
   * Instancia um exemplo de comportamento accordion
   * @param {object} element - Elemento DOM que representa um componente contento um comportamento de accordion
   */
  constructor(element) {
    this.element = element
    this._setBehavior()
  }
  /**
   * Inicia comportamento do exemplo
   */
  _setBehavior() {
    this._setAccordionBehavior()
  }
  /**
   * Encontra os data-toggle accordion  e coloca util Accordion
   */
  _setAccordionBehavior() {
    this.element
      .querySelectorAll('[data-toggle="accordion"]')
      .forEach((trigger) => {
        const config = {
          iconToHide: 'fa-chevron-up',
          iconToShow: 'fa-chevron-down',
          trigger,
          useIcons: true,
        }
        const accordion = new behavior_accordion["default"](config)
        accordion.setBehavior()
      })
  }
}

/* harmony default export */ const accordion_accordion = (AccordionExample);

;// CONCATENATED MODULE: ./src/util/checkgroup/checkgroup.js


/**
 * Classe para o exemplo do comportamento checkgroup
 */
class CheckgroupExample {
  /**
   * Instancia um exemplo de comportamento checkgroup
   * @param {object} element - Elemento DOM que representa um componente contento um comportamento de checkgroup
   */
  constructor(element) {
    this.element = element
    this._setBehavior()
  }
  /**
   * Inicia comportamento do exemplo
   * @private
   */
  _setBehavior() {
    this._setCheckgroupBehavior()
  }

  /**
   * Encontra os data-toggle checkbox  com data-parent e coloca util Checkgroup
   * @private
   */

  _setCheckgroupBehavior() {
    this.element
      .querySelectorAll('input[type="checkbox"][data-parent]')
      .forEach((trigger) => {
        const checkgroup = new behavior_checkgroup["default"](trigger)
        checkgroup.setBehavior()
      })
  }
}

/* harmony default export */ const checkgroup = (CheckgroupExample);

;// CONCATENATED MODULE: ./src/util/collapse/collapse.js


/**
 * Classe para o exemplo do comportamento collapse
 */
class CollapseExample {
  /**
   * Instancia um exemplo de comportamento collapse
   * @param {object} element - Elemento DOM que representa um componente contento um comportamento de collapse
   */
  constructor(element) {
    this.element = element
    this._setBehavior()
  }
  /**
   * Instancia o utilitario
   * @private
   */
  _setBehavior() {
    this._setCollapseBehavior()
  }
  /**
   * Instancia o utilitario no data-toggle collapse
   * @private
   */
  _setCollapseBehavior() {
    this.element
      .querySelectorAll('[data-toggle="collapse"]')
      .forEach((trigger) => {
        const config = {
          iconToHide: 'fa-chevron-up',
          iconToShow: 'fa-chevron-down',
          trigger,
          useIcons: true,
        }
        const collapse = new behavior_collapse["default"](config)
        collapse.setBehavior()
      })
  }
}

/* harmony default export */ const collapse = (CollapseExample);

// EXTERNAL MODULE: ./src/partial/js/behavior/dropdown.js
var behavior_dropdown = __webpack_require__("./src/partial/js/behavior/dropdown.js");
;// CONCATENATED MODULE: ./src/util/dropdown/dropdown.js


/**
 * Classe para o exemplo do comportamento dropdown
 */
class DropdownExample {
  /**
   * Instancia um exemplo de comportamento dropdown
   * @param {object} element - Elemento DOM que representa um componente contento um comportamento de dropdown
   */
  constructor(element) {
    this.element = element
    this._setBehavior()
  }
  /**
   * Inicia comportamento do exemplo
   */
  _setBehavior() {
    this._setDropdownBehavior()
  }

  /**
   * Encontra os data-toggle dropdown  e coloca util Dropdown
   */
  _setDropdownBehavior() {
    this.element
      .querySelectorAll('[data-toggle="dropdown"]')
      .forEach((trigger) => {
        const config = {
          iconToHide: 'fa-chevron-up',
          iconToShow: 'fa-chevron-down',
          trigger,
          useIcons: true,
        }
        const dropdown = new behavior_dropdown["default"](config)
        dropdown.setBehavior()
      })
  }
}

/* harmony default export */ const dropdown = (DropdownExample);

// EXTERNAL MODULE: ./src/partial/js/behavior/scrim.js
var behavior_scrim = __webpack_require__("./src/partial/js/behavior/scrim.js");
;// CONCATENATED MODULE: ./src/util/tooltip/tooltip.js


/**
 * Classe de exemplo do comportamento tooltip
 */
class TooltipExample {
  /**
   * Instancia um exemplo de comportamento tooltip
   * @param {object} element - Elemento sDOM que representa um componente contento um comportamento de tooltip
   */
  constructor(element) {
    this.element = element
    
  }
  /**
   * Inicia comportamento do exemplo
   * @private
   */
  _setBehavior() {
    
  }

  /**
   * Encontra os data-tooltip-text  e coloca util Tooltip
   * @private
   */

  run() {
    const TooltipExampleList = []

    window.document
      .querySelectorAll('[data-tooltip-text]:not(.notification-tooltip)')
      .forEach((TooltipExample) => {
        const texttooltip = TooltipExample.getAttribute('data-tooltip-text')
        const config = {
          activator: TooltipExample,
          place: 'left',
          textTooltip: texttooltip,
          
        }
        TooltipExampleList.push(new behavior_tooltip["default"](config))
      })

    document.querySelectorAll('[data-tooltip-target]').forEach((trigger) => {
      const targets = document.querySelectorAll(
        trigger.getAttribute('data-tooltip-target')
      )

      targets.forEach((target) => {
        const place =
          target.getAttribute('place') !== null
            ? target.getAttribute('place')
            : 'left'

        const config = {
          activator: trigger,
          component: target,
          place: place,
          type: 'warning',
          
        }
        // const tooltip = new Tooltip(config)
        new behavior_tooltip["default"](config)
      })
    })
  }
}

/* harmony default export */ const tooltip_tooltip = (TooltipExample);

;// CONCATENATED MODULE: ./src/partial/js/core.behavior.js
/**
 * Classe que instancia os exemplos de uso dos utilitários
 */

// import TooltipExample from '../../util/tooltip/tooltip'





// import Tooltip from './behavior/tooltip'
// import Tooltip from './behavior/tooltip'


class Behavior {
  initInstanceAll() {
    this._initInstanceAccordionExample()
    this._initInstanceCheckGroupExemple()
    this._initInstanceCollapseExample()
    this._initInstanceDropdownExample()
    this._initInstanceTooltipExample()
    this._initInstanceScrimExample()
  }

  _initInstanceCollapseExample() {
    const collapseExampleList = []
    window.document
      .querySelectorAll('.collapse-example')
      .forEach((collapseExample) => {
        collapseExampleList.push(new collapse(collapseExample))
      })
  }

  _initInstanceAccordionExample() {
    const accordionExampleList = []
    window.document
      .querySelectorAll('.accordion-example')
      .forEach((accordionExample) => {
        accordionExampleList.push(new accordion_accordion(accordionExample))
      })
  }

  _initInstanceCheckGroupExemple() {
    const checkgroupExampleList = []
    window.document
      .querySelectorAll('.checkgroup-example')
      .forEach((checkgroupExample) => {
        checkgroupExampleList.push(new checkgroup(checkgroupExample))
      })
  }

  _initInstanceDropdownExample() {
    const dropdownExampleList = []
    window.document
      .querySelectorAll('.dropdown-example')
      .forEach((dropdownExample) => {
        dropdownExampleList.push(new dropdown(dropdownExample))
      })
  }

  _initInstanceScrimExample() {
    for (const buttonBloco1 of window.document.querySelectorAll(
      '.scrimutilexemplo button'
    )) {
      buttonBloco1.addEventListener('click', () => {
        const scrscrim = window.document.querySelector('#scrimutilexample')
        const scrimfoco = new behavior_scrim["default"]({
          closeElement: '#scrimfechar',
          trigger: scrscrim,
        })
        scrimfoco.showScrim()
      })
    }
  }
  _initInstanceTooltipExample() {
    const x = new tooltip_tooltip()
    x.run()
    
  }
}

// EXTERNAL MODULE: ./src/partial/js/globals-class.js + 1 modules
var globals_class = __webpack_require__("./src/partial/js/globals-class.js");
;// CONCATENATED MODULE: ./src/partial/js/core-init.js
/* eslint-disable no-unused-vars */



































const globals = new globals_class.Globals()
globals.initInstanceAll()

const behavior = new Behavior()
behavior.initInstanceAll()




})();

/******/ 	return __webpack_exports__;
/******/ })()
;
});
//# sourceMappingURL=core-init.js.map