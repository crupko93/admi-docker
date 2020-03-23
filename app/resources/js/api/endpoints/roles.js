export default remote => ({
    ////////////
    // Admins //
    ////////////
    all   : (pagination = {}) => remote.get('roles', {params: pagination}),
    get   : id => remote.get(`roles/${id}`),
    create: data => remote.post('roles', data),
    update: data => remote.put('roles', data),
    delete: ids => {
        ids = Array.isArray(ids) ? _.join(ids) : ids;
        return remote.delete(`roles/${ids}`);
    },
    list  : () => remote.get('roles/list')
});
