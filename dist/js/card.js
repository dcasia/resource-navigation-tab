(()=>{var e={57:(e,t,r)=>{"use strict";r.r(t),r.d(t,{default:()=>a});var n=r(645),o=r.n(n)()((function(e){return e[1]}));o.push([e.id,".card-panel[data-v-10714866]{height:auto}.card a[data-v-10714866]:first-child{border-bottom-left-radius:.5rem}.card a[data-v-10714866]:last-child{border-bottom-right-radius:.5rem}.card a.router-link-exact-active[data-v-10714866]{border-color:var(--primary)}",""]);const a=o},645:e=>{"use strict";e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var r=e(t);return t[2]?"@media ".concat(t[2]," {").concat(r,"}"):r})).join("")},t.i=function(e,r,n){"string"==typeof e&&(e=[[null,e,""]]);var o={};if(n)for(var a=0;a<this.length;a++){var i=this[a][0];null!=i&&(o[i]=!0)}for(var s=0;s<e.length;s++){var u=[].concat(e[s]);n&&o[u[0]]||(r&&(u[2]?u[2]="".concat(r," and ").concat(u[2]):u[2]=r),t.push(u))}},t}},126:(e,t,r)=>{var n=r(57);n.__esModule&&(n=n.default),"string"==typeof n&&(n=[[e.id,n,""]]),n.locals&&(e.exports=n.locals);(0,r(346).Z)("626f4714",n,!0,{})},346:(e,t,r)=>{"use strict";function n(e,t){for(var r=[],n={},o=0;o<t.length;o++){var a=t[o],i=a[0],s={id:e+":"+o,css:a[1],media:a[2],sourceMap:a[3]};n[i]?n[i].parts.push(s):r.push(n[i]={id:i,parts:[s]})}return r}r.d(t,{Z:()=>v});var o="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!o)throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var a={},i=o&&(document.head||document.getElementsByTagName("head")[0]),s=null,u=0,c=!1,d=function(){},l=null,f="data-vue-ssr-id",p="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function v(e,t,r,o){c=r,l=o||{};var i=n(e,t);return h(i),function(t){for(var r=[],o=0;o<i.length;o++){var s=i[o];(u=a[s.id]).refs--,r.push(u)}t?h(i=n(e,t)):i=[];for(o=0;o<r.length;o++){var u;if(0===(u=r[o]).refs){for(var c=0;c<u.parts.length;c++)u.parts[c]();delete a[u.id]}}}}function h(e){for(var t=0;t<e.length;t++){var r=e[t],n=a[r.id];if(n){n.refs++;for(var o=0;o<n.parts.length;o++)n.parts[o](r.parts[o]);for(;o<r.parts.length;o++)n.parts.push(b(r.parts[o]));n.parts.length>r.parts.length&&(n.parts.length=r.parts.length)}else{var i=[];for(o=0;o<r.parts.length;o++)i.push(b(r.parts[o]));a[r.id]={id:r.id,refs:1,parts:i}}}}function g(){var e=document.createElement("style");return e.type="text/css",i.appendChild(e),e}function b(e){var t,r,n=document.querySelector("style["+f+'~="'+e.id+'"]');if(n){if(c)return d;n.parentNode.removeChild(n)}if(p){var o=u++;n=s||(s=g()),t=C.bind(null,n,o,!1),r=C.bind(null,n,o,!0)}else n=g(),t=_.bind(null,n),r=function(){n.parentNode.removeChild(n)};return t(e),function(n){if(n){if(n.css===e.css&&n.media===e.media&&n.sourceMap===e.sourceMap)return;t(e=n)}else r()}}var m,y=(m=[],function(e,t){return m[e]=t,m.filter(Boolean).join("\n")});function C(e,t,r,n){var o=r?"":n.css;if(e.styleSheet)e.styleSheet.cssText=y(t,o);else{var a=document.createTextNode(o),i=e.childNodes;i[t]&&e.removeChild(i[t]),i.length?e.insertBefore(a,i[t]):e.appendChild(a)}}function _(e,t){var r=t.css,n=t.media,o=t.sourceMap;if(n&&e.setAttribute("media",n),l.ssrId&&e.setAttribute(f,t.id),o&&(r+="\n/*# sourceURL="+o.sources[0]+" */",r+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(o))))+" */"),e.styleSheet)e.styleSheet.cssText=r;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(r))}}}},t={};function r(n){var o=t[n];if(void 0!==o)return o.exports;var a=t[n]={id:n,exports:{}};return e[n](a,a.exports,r),a.exports}r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},(()=>{"use strict";function e(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function t(t){for(var r=1;r<arguments.length;r++){var o=null!=arguments[r]?arguments[r]:{};r%2?e(Object(o),!0).forEach((function(e){n(t,e,o[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(o)):e(Object(o)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(o,e))}))}return t}function n(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}const o={name:"NavigationCard",props:["card","resource","resourceId","resourceName"],mounted:function(){this.$parent.$el.classList.add("w-full");var e=this.card.resources[0].slug,r=function(e,t){for(var r=e+"=",n=decodeURIComponent(document.cookie).split(";"),o=0;o<n.length;o++){for(var a=n[o];" "===a.charAt(0);)a=a.substring(1);if(0===a.indexOf(r))return a.substring(r.length,a.length)}return t}("navigation_tab","");this.$route.query.navigationTab?this.$route.query.navigationTab&&this.$route.query.navigationTab!==e&&(""!==r&&r!==this.$route.query.navigationTab&&this.$router.replace({query:t(t({},this.$route.query),{},{navigationTab:r})}),this.onNavigate()):e&&this.$router.replace({query:t(t({},this.$route.query),{},{navigationTab:e})})},beforeCreate:function(){this.$on("hook:destroyed",(function(){return e="navigation_tab",void(document.cookie=e+"=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;");var e}))},methods:{getDetailCard:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this;return e.hasOwnProperty("initializeComponent")?e:this.getDetailCard(e.$parent)},onNavigate:function(){!function(e,t,r){var n=new Date;n.setTime(n.getTime()+24*r*60*60*1e3);var o="expires="+n.toUTCString();document.cookie=e+"="+t+";"+o+";path=/"}("navigation_tab",this.$route.query.navigationTab);var e=this.getDetailCard(),t=this.$route.query.navigationTab,r=this.card.cardsToRemove[t];e.cards=e.cards.filter((function(e){return!r.includes(e.navigationTabClass)})),e.initializeComponent(),e.fetchCards()}}};r(126);const a=function(e,t,r,n,o,a,i,s){var u,c="function"==typeof e?e.options:e;if(t&&(c.render=t,c.staticRenderFns=r,c._compiled=!0),n&&(c.functional=!0),a&&(c._scopeId="data-v-"+a),i?(u=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),o&&o.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(i)},c._ssrRegister=u):o&&(u=s?function(){o.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:o),u)if(c.functional){c._injectStyles=u;var d=c.render;c.render=function(e,t){return u.call(t),d(e,t)}}else{var l=c.beforeCreate;c.beforeCreate=l?[].concat(l,u):[u]}return{exports:e,options:c}}(o,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("card",{staticClass:"resource-navigation-card whitespace-no-wrap flex flex-row items-center justify-center"},e._l(e.card.resources,(function(t,n){return r("router-link",{key:n,staticClass:"p-6 dim flex-1 text-center no-underline text-primary border-b-2 cursor-pointer border-transparent hover:border-90",attrs:{replace:"",to:{query:Object.assign({},e.$route.query,{navigationTab:t.slug}),params:Object.assign({},e.$route.params,{resourceId:t.resourceId||e.$route.params.resourceId})}},nativeOn:{click:function(t){return e.onNavigate(t)}}},[e._v("\n\n        "+e._s(t.label)+"\n\n    ")])})),1)}),[],!1,null,"10714866",null).exports;Nova.booting((function(e,t,r){e.component("resource-navigation-card",a)}))})()})();