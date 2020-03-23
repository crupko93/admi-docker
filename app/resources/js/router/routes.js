export default [
    ...applyRules(['guest'], [
        {
            path: '', component: require('$comp/auth/AuthWrapper').default, redirect: {name: 'login'}, children:
                [
                    {path: '/login', name: 'login', component: require('$comp/auth/login/Login').default},
                    {path: '/register', name: 'register', component: require('$comp/auth/register/Register').default},
                    {
                        path: '/password', component: require('$comp/auth/password/PasswordWrapper').default, children:
                            [
                                {
                                    path     : '',
                                    name     : 'forgot',
                                    component: require('$comp/auth/password/password-forgot/PasswordForgot').default
                                },
                                {
                                    path     : 'reset/:token',
                                    name     : 'reset',
                                    component: require('$comp/auth/password/password-reset/PasswordReset').default
                                }
                            ]
                    }
                ]
        }
    ]),
    ...applyRules(['auth'], [
        {
            path: '', component: require('$comp/AppWrapper').default, children:
                [
                    {path: '', name: 'index', redirect: {name: 'dashboard'}},
                    {path: 'dashboard', name: 'dashboard', component: require('$comp/dashboard/Dashboard').default},
                    {
                        path: 'profile', component: require('$comp/profile/ProfileWrapper').default, children:
                            [
                                {path: '', name: 'profile', component: require('$comp/profile/Profile').default},
                                {
                                    path     : 'edit',
                                    name     : 'profile-edit',
                                    component: require('$comp/profile/edit/ProfileEdit').default
                                }
                            ]
                    },
                    {
                        path     : 'users', name: 'users',
                        component: require('$comp/user/UsersIndex').default,
                        meta     : {permissions: ['read_administration_section']}
                    },
                    {
                        path     : 'roles', name: 'roles',
                        component: require('$comp/roles-permissions/RolesPermissionsIndex').default,
                        meta     : {permissions: ['read_administration_section']}
                    }
                ]
        }
    ]),
    {path: '*', redirect: {name: 'index'}}
];

function applyRules (rules, routes) {
    for (let i in routes) {
        routes[i].meta = routes[i].meta || {};

        if (!routes[i].meta.rules) {
            routes[i].meta.rules = [];
        }
        routes[i].meta.rules.unshift(...rules);

        if (routes[i].children) {
            routes[i].children = applyRules(rules, routes[i].children);
        }
    }

    return routes;
}
