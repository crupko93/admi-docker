/*
 |--------------------------------------------------------------------------
 | Laravel Spark Components
 |--------------------------------------------------------------------------
 |
 | Here we will load the Spark components which makes up the core client
 | application. This is also a convenient spot for you to load all of
 | your components that you write while building your applications.
 */

// Common Components
Vue.component('content-loader',  require('./common/ContentLoader').default);
Vue.component('profile-image',   require('./common/ProfileImage').default);
Vue.component('vue-particles',   require('./common/VueParticles').default);

