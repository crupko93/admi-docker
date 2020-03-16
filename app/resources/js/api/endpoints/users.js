export default remote => ({
    updatePassword: data => remote.put('users/password', data),
    me            : () => remote.get('profile'),
    updateProfile : data => remote.put('profile', data),

    ////////////
    // Admins //
    ////////////
    all       : (pagination = {}) => remote.get('users', {params: pagination}),
    get       : id => remote.get(`users/${id}`),
    create    : data => remote.post('users', data),
    update    : data => remote.put('users', data),
    delete    : id => remote.delete(`users/${id}`),
    updateRole: data => remote.put('users/role', data)
});
