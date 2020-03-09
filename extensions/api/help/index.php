<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"></link>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"></link>
<link rel="stylesheet" href="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/nprogress.css"></link>
<style id="webmakerstyle">
body {
  padding: 0;
  letter-spacing: 0.01em;
  line-height: 1.6;
  font-size: 1em;
  font-weight: 400;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, Avenir, "Avenir Next",
    "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans",
    "Droid Sans", "Helvetica Neue", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition: transform 0.25s ease-in-out;
  -moz-transition: transform 0.25s ease-in-out;
  -o-transition: transform 0.25s ease-in-out;
  transition: transform 0.25s ease-in-out;
  overflow: hidden;
}

/*float*/

.left {
  float: left;
}

.right {
  float: right;
}

@media (max-width: 400px) and (orientation: portrait) {
  .right {
    float: none;
  }
}

/** Header
-------------------------------*/

#header {
  overflow: hidden;
  height: 60px;
  background: whitesmoke;
}

#header h2 {
  font-size: 15px;
  line-height: 60px;
  float: left;
  margin: 0;
  margin-left: 1em;
}

#header .right a,
#header button {
  display: block;
  float: left;
  height: 60px;
  line-height: 62px;
  margin: 0;
  padding: 0 1.5em;
  text-align: center;
  text-decoration: none;
  border: none;
  outline: 0;
  background: whitesmoke;
  color: black;
  transition: background 500ms ease;
}
#header .right a:hover,
#header button:hover,
#header .right a:focus,
#header button:focus {
  background: #e8e8e8;
  border-radius: 5px;
  transition: background 500ms ease;
}

#header .right {
  float: right;
}

a.header-title {
  transition: all 0.5s ease;
  text-decoration: none;
  color: #fff;
}

a.header-title:hover {
  transition: all 0.5s ease;
  text-decoration: none;
}

@media (max-width: 400px) and (orientation: portrait) {
  #header h2 {
    display: none !important;
  }
}

/** Menu
-----------------------------*/

#menu {
  position: fixed;
  width: 17em;
  height: 100%;
  -webkit-transform: translate(-17em, 0);
  transform: translate(-17em, 0);
}

#menu.is-opened {
  -webkit-transform: translate(0, 0) !important;
  transform: translate(0, 0) !important;
}

#menu header input {
  min-height: 45px;
}

#menu header {
  display: block;
  text-align: center;
  text-transform: uppercase;
  background: #f5f5f5;
  color: #000000;
  border-bottom: 1px solid #f5f5f5;
  height: 60px;
}

#menu header a {
  text-decoration: none;
  color: #fff;
}

#menu section {
  overflow-y: auto;
  height: calc(100% - 8.5em);
  padding: 0.2em;
  background: white;
}

#menu ul {
  margin: 0;
  padding: 0;
}

#menu ul li {
  padding: 0.5em;
  background: #fff;
  border: 1px solid #f5f5f5;
  list-style: none;
}

#menu ul li a {
  text-decoration: none;
}
#menu ul li i {
  display: inline-block;
  margin-right: 1em;
}

/** wrapper
----------------------------*/

#wrapper.menu-is-open {
  width: calc(100% - 17em);
  -webkit-transform: translate(17em, 0) !important;
}

@media (max-width: 600px) {
  #wrapper.menu-is-open {
    width: 100%;
    -webkit-transform: translate(17em, 0) !important;
    transform: translate(17em, 0) !important;
  }
}

#wrapper {
  width: 100%;
  margin: 0;
  padding: 0;
}

#content {
  display: block;
  padding: 0em;
  margin: 0;
  height: calc(100vh - 3.8em);
  overflow-y: scroll;
}

/** transition
----------------------------*/

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter,
.fade-leave-active {
  opacity: 0;
}

.is-opened header {
  animation: slide-up 200ms 1 ease-in-out;
}
.is-opened section {
  animation: slide-up 400ms 1 ease-in-out;
}
.is-opened footer {
  animation: slide-up 600ms 1 ease-in-out;
}

@keyframes slide-up {
  from {
    transform: translateY(100px);
  }
}

/** router
----------------------------*/

.router-link-exact-active:hover,
.router-link-exact-active {
  cursor: pointer;
  color: #777;
}
nav li a {
  color: #333;
}
.list-group-item i {
  color: #333;
}

/* progress bar
------------------------------*/
#nprogress .bar {
  background: blue !important;
}

#nprogress .peg {
  box-shadow: 0 0 10px #f55, 0 0 5px #f55 !important;
}

#nprogress .spinner-icon {
  border-top-color: #f55 !important;
  border-left-color: #f55 !important;
}

</style>
</head>
<body>
<div class="app" id="app">
  <nav id="menu" v-if="navOpen" :class="{'is-opened': navOpen}">
    <header v-if="navOpen">
      <h5 class="pt-3">Opciones</h5>
    </header>
    <section>
      <ul class="list-unstyled">
        <li class="list-item">
          <i class="fa fa-home"> </i>
          <router-link to="/" title="Inicio">
            Paginas
          </router-link>
        </li>
        <li class="list-item">
          <i class="fa fa-image"> </i>
          <router-link to="/images" title="Imagenes">
            Imagenes
          </router-link>
        </li>
        <li class="list-item">
          <i class="fa fa-book" > </i>
          <router-link to="/manifest" title="Manifiesto">
            Manifiesto
          </router-link>
        </li>
        <li class="list-item">
          <i class="fa fa-sitemap"> </i>
          <router-link to="/sitemap" title="Sitemap">
            Sitemap
          </router-link>
        </li>
      </ul>
    </section>
    <footer class="footer text-center">
      <p><small>Copyright © 2019</small></p>
    </footer>
  </nav>

  <main id="wrapper"  :class="{'menu-is-open': navOpen}">
    <header id="header">
      <button @click="toogleMenu">
        <span v-if="navOpen" class="fa fa-arrow-left"></span>
        <span v-else="navOpen" class="fa fa-bars"></span>
      </button>
      <h2 class="text-deco-none">Barrio CMS Api test</h2>
      <div class="right">
        <a href="https://monchovarela.es" class="btn">
          <i class="fa fa-external-link"></i>
        </a>
      </div>
    </header>
    <section id="content">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mt-4">
            <transition name="fade" mode="out-in">
               <router-view></router-view>
            </transition>
          </div>
        </div>
      </div>
    </section>
  </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-router/3.1.3/vue-router.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
<script src="https://cdn.rawgit.com/rikmms/progress-bar-4-axios/0a3acf92/dist/index.js"></script>
<script src="https://webmaker.app/app/lib/transpilers/babel-polyfill.min.js"></script><script>
'use strict';

function _asyncToGenerator(fn) { return function () { var gen = fn.apply(this, arguments); return new Promise(function (resolve, reject) { function step(key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { return Promise.resolve(value).then(function (value) { step("next", value); }, function (err) { step("throw", err); }); } } return step("next"); }); }; }

var url = 'https://monchovarela.es/index.php?';

var waitForTime = function waitForTime(ms) {
  return new Promise(function (r) {
    return setTimeout(r, ms);
  });
};

var Home = {
  data: function data() {
    return {
      link: 'example.php',
      output: 'example'
    };
  },


  template: '<div>\n\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=file&data=pages&name=blog\')">Get Blog</button>\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=file&data=pages&name=blog&limit=2\')">Get Blog limit 2</button>\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=file&data=pages&name=blog&filter=title\')">Get Blog titles</button>\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=file&data=pages&name=blog&filter=images\')">Get Blog images</button>\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=file&data=pages&name=blog&filter=videos\')">Get Blog Videos</button>\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=file&data=page&name=contacto\')">Get Single</button>\n\n<div class="form-group mt-3">\n<input type="text" class="form-control" disabled v-model="link"/>\n<textarea class="form-control" rows="10">{{output}}</textarea>\n</div>\n\n</div>',
  methods: {
    getBlog: function getBlog(data) {
      var _this = this;

      return _asyncToGenerator(regeneratorRuntime.mark(function _callee() {
        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return loadProgressBar();

              case 2:
                _this.link = 'loading..';
                _context.next = 5;
                return waitForTime(300);

              case 5:
                _this.output = 'loading..';
                _context.next = 8;
                return waitForTime(500);

              case 8:
                _context.next = 10;
                return axios.get(url + data).then(function (r) {
                  _this.link = data;
                  _this.output = r.data;
                });

              case 10:
              case 'end':
                return _context.stop();
            }
          }
        }, _callee, _this);
      }))();
    }
  }
};

var Images = {
  data: function data() {
    return {
      link: 'example.php',
      output: 'https://via.placeholder.com/150'
    };
  },


  template: '<div>\n\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=image&url=public/notfound.jpg\')">Get Image</button>\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=image&url=public/notfound.jpg&w=600\')">Get Image width</button>\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=image&url=public/notfound.jpg&w=600&h=300\')">Get Image width height</button>\n\n<div class="form-group mt-3">\n<input type="text" class="form-control" disabled v-model="link"/>\n</div>\n<a target="_blank" :href="output">{{output}}</a>\n\n</div>',
  methods: {
    getBlog: function getBlog(data) {
      this.link = data;
      this.output = url + data;
    }
  }
};

var Manifest = {
  data: function data() {
    return {
      link: 'api=',
      output: '{}'
    };
  },


  template: '<div>\n\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=manifest\')">Get Manifest</button>\n\n<div class="form-group mt-3">\n<input type="text" class="form-control" disabled v-model="link"/>\n<textarea class="form-control" rows="10">{{output}}</textarea>\n</div>\n\n</div>',
  methods: {
    getBlog: function getBlog(data) {
      var _this2 = this;

      return _asyncToGenerator(regeneratorRuntime.mark(function _callee2() {
        return regeneratorRuntime.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _context2.next = 2;
                return loadProgressBar();

              case 2:
                _this2.link = 'loading..';
                _context2.next = 5;
                return waitForTime(300);

              case 5:
                _this2.output = 'loading..';
                _context2.next = 8;
                return waitForTime(500);

              case 8:
                _context2.next = 10;
                return axios.get(url + data).then(function (r) {
                  _this2.link = data;
                  _this2.output = r.data;
                });

              case 10:
              case 'end':
                return _context2.stop();
            }
          }
        }, _callee2, _this2);
      }))();
    }
  }
};

var Sitemap = {
  data: function data() {
    return {
      link: 'api=',
      output: '<?xml version="1.0" encoding="UTF-8"?>'
    };
  },


  template: '<div>\n\n<button class="btn btn-block btn-primary" @click="getBlog(\'api=sitemap\')">Get Sitemap</button>\n\n<div class="form-group mt-3">\n<input type="text" class="form-control" disabled v-model="link"/>\n<textarea class="form-control" rows="10">{{output}}</textarea>\n</div>\n\n</div>',
  methods: {
    getBlog: function getBlog(data) {
      var _this3 = this;

      return _asyncToGenerator(regeneratorRuntime.mark(function _callee3() {
        return regeneratorRuntime.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                _context3.next = 2;
                return loadProgressBar();

              case 2:
                _this3.link = 'loading..';
                _context3.next = 5;
                return waitForTime(300);

              case 5:
                _this3.output = 'loading..';
                _context3.next = 8;
                return waitForTime(500);

              case 8:
                _context3.next = 10;
                return axios.get(url + data).then(function (r) {
                  _this3.link = data;
                  _this3.output = r.data;
                });

              case 10:
              case 'end':
                return _context3.stop();
            }
          }
        }, _callee3, _this3);
      }))();
    }
  }
};

var routes = [{ path: '/', component: Home }, { path: '/images', component: Images }, { path: '/manifest', component: Manifest }, { path: '/sitemap', component: Sitemap }];

var router = new VueRouter({
  routes: routes // short for `routes: routes`
});

var app = new Vue({
  data: function data() {
    return { navOpen: false };
  },

  methods: {
    toogleMenu: function toogleMenu() {
      this.navOpen = !this.navOpen;
    }
  },
  router: router
}).$mount('#app');
//# sourceURL=userscript.js
</script>
</body>
</html>