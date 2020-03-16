export default remote => ({
    login        : data => remote.post('login', data),
    refresh      : data => remote.post('login/refresh', data),
    logout       : data => remote.post('logout', data),
    emailPassword: data => remote.post('password/email', data),
    resetPassword: data => remote.post('password/forgot', data),
    register     : data => remote.post('register', data)
});
